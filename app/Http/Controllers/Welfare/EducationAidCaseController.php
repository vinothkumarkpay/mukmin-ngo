<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Welfare\Concerns\ChecksAdminAccess;
use App\Mail\EducationAidInterviewProposalMail;
use App\Models\CommunityAidSubmission;
use App\Models\EducationAidAssessment;
use App\Models\EducationAidDocumentCheck;
use App\Models\EducationAidInterviewProposal;
use App\Models\EducationAidSectionComment;
use App\Models\User;
use App\Services\Welfare\EducationAidCaseService;
use App\Support\EducationAidCompleteness;
use App\Support\EducationAidStatus;
use App\Support\EducationAidUrgency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class EducationAidCaseController extends Controller
{
    use ChecksAdminAccess;

    private EducationAidCaseService $cases;

    public function __construct(EducationAidCaseService $cases)
    {
        $this->cases = $cases;
    }

    public function review($id)
    {
        $this->authorizePermission('submissions.aid.view');

        $submission = CommunityAidSubmission::with([
            'assessment.assignee',
            'assessment.recommendationSubmitter',
            'documentChecks.verifier',
            'sectionComments.user',
            'caseEvents.user',
            'interviewProposals.creator',
        ])->findOrFail($id);

        $assessment = $this->cases->ensureCaseInitialized($submission);
        $assessment = $this->cases->refreshUrgencySuggestion($submission, $assessment);
        $submission->load(['documentChecks.verifier', 'caseEvents.user']);

        $snapshot = $this->cases->caseSnapshot($submission, $assessment);
        $completeness = EducationAidCompleteness::evaluate($submission->fresh(['documentChecks']), $assessment);
        $assignees = $this->cases->assignableUsers();
        $canManage = $this->adminUser()->hasPermission('submissions.aid.status');

        $documentRows = collect(EducationAidDocumentCheck::documentCatalog())->map(function ($label, $key) use ($submission) {
            $check = $submission->documentChecks->firstWhere('document_key', $key);

            return [
                'key' => $key,
                'label' => $label,
                'submitted' => $this->cases->fileSubmitted($submission, $key),
                'url' => $this->cases->fileUrl($submission, $key),
                'check' => $check,
            ];
        })->values();

        $commentsBySection = $submission->sectionComments->groupBy('section');

        return view('welfare.admin.education-aid.review', compact(
            'submission',
            'assessment',
            'snapshot',
            'completeness',
            'assignees',
            'canManage',
            'documentRows',
            'commentsBySection'
        ));
    }

    public function startAssessment($id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $assessment = $this->cases->ensureCaseInitialized($submission);

        $oldStatus = EducationAidStatus::normalize($submission->status);
        $submission->update(['status' => EducationAidStatus::UNDER_ASSESSMENT]);

        $this->cases->recordEvent(
            $submission,
            'assessment_started',
            'Assessment started.',
            ['from_status' => $oldStatus, 'to_status' => EducationAidStatus::UNDER_ASSESSMENT]
        );

        return redirect()
            ->route('welfare.admin.education-aid.review', $submission->id)
            ->with('success', 'Assessment started. Application data has been loaded into the case workspace.');
    }

    public function updateAssignment(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $assessment = $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'assigned_to' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'assessment_due_at' => ['nullable', 'date'],
            'urgency' => ['required', Rule::in(array_keys(EducationAidUrgency::options()))],
        ]);

        $assignee = ! empty($validated['assigned_to'])
            ? User::find($validated['assigned_to'])
            : null;

        $assessment->fill([
            'assigned_to' => $assignee ? $assignee->id : null,
            'assessment_due_at' => $validated['assessment_due_at'] ?? null,
            'urgency' => $validated['urgency'],
            'urgency_confirmed' => true,
        ])->save();

        $this->cases->recordEvent(
            $submission,
            'assignment_updated',
            $assignee
                ? 'Assigned to: ' . $assignee->name . '.'
                : 'Assignee cleared.',
            [
                'assigned_to' => $assessment->assigned_to,
                'assessment_due_at' => $assessment->assessment_due_at,
                'urgency' => $assessment->urgency,
            ]
        );

        return back()->with('success', 'Assignment and urgency updated.');
    }

    public function updateStatus(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'status' => EducationAidStatus::validationRule(),
        ]);

        $from = EducationAidStatus::normalize($submission->status);
        $to = $validated['status'];
        $submission->update(['status' => $to]);

        $this->cases->recordEvent(
            $submission,
            'status_changed',
            'Status changed from ' . EducationAidStatus::label($from) . ' to ' . EducationAidStatus::label($to) . '.',
            ['from' => $from, 'to' => $to]
        );

        return back()->with('success', 'Case status updated.');
    }

    public function updateAssessment(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $assessment = $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'aid_categories' => ['nullable', 'array'],
            'aid_categories.*' => ['string', Rule::in(EducationAidAssessment::aidCategoryOptions())],
            'purpose_expense_types' => ['nullable', 'array'],
            'purpose_expense_types.*' => ['string', 'max:255'],
            'documents_verified' => ['nullable', 'boolean'],
            'university_balance_verified' => ['nullable', 'boolean'],
            'ptptn_funding_verified' => ['nullable', 'boolean'],
            'other_scholarship_verified' => ['nullable', 'boolean'],
            'interview_conducted' => ['nullable', 'boolean'],
            'financial_need' => ['nullable', Rule::in(['high', 'moderate', 'low'])],
            'funding_breakdown' => ['nullable', 'array'],
            'funding_breakdown.ptptn' => ['nullable', 'numeric', 'min:0'],
            'funding_breakdown.mied' => ['nullable', 'numeric', 'min:0'],
            'funding_breakdown.family' => ['nullable', 'numeric', 'min:0'],
            'funding_breakdown.other' => ['nullable', 'numeric', 'min:0'],
            'other_confirmed_funding' => ['nullable', 'numeric', 'min:0'],
            'recommended_amount' => ['nullable', 'numeric', 'min:0'],
            'recommendation' => ['nullable', Rule::in(array_keys(EducationAidAssessment::recommendationOptions()))],
            'assessment_remarks' => ['nullable', 'string'],
            'interview_date' => ['nullable', 'date'],
            'interview_conducted_by' => ['nullable', 'string', 'max:255'],
            'interview_notes' => ['nullable', 'string'],
        ]);

        $boolFields = [
            'documents_verified',
            'university_balance_verified',
            'ptptn_funding_verified',
            'other_scholarship_verified',
            'interview_conducted',
        ];
        foreach ($boolFields as $field) {
            $validated[$field] = $request->boolean($field);
        }

        $breakdown = collect($validated['funding_breakdown'] ?? [])
            ->map(fn ($v) => $v === null || $v === '' ? 0 : (float) $v)
            ->all();
        $validated['funding_breakdown'] = $breakdown;
        $validated['other_confirmed_funding'] = array_key_exists('other_confirmed_funding', $validated) && $validated['other_confirmed_funding'] !== null
            ? $validated['other_confirmed_funding']
            : array_sum($breakdown);
        $validated['aid_categories'] = $validated['aid_categories'] ?? [];
        $validated['purpose_expense_types'] = $validated['purpose_expense_types'] ?? [];

        $assessment->fill($validated)->save();
        $this->cases->caseSnapshot($submission, $assessment);

        $this->cases->recordEvent($submission, 'assessment_updated', 'Assessment data updated.');

        return back()->with('success', 'Assessment saved.');
    }

    public function submitRecommendation(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $assessment = $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'recommended_amount' => ['required', 'numeric', 'min:0'],
            'recommendation' => ['required', Rule::in(array_keys(EducationAidAssessment::recommendationOptions()))],
            'assessment_remarks' => ['nullable', 'string'],
        ]);

        $assessment->fill($validated);
        $assessment->recommendation_submitted_at = Carbon::now();
        $assessment->recommendation_submitted_by = $this->adminUser()->id;
        $assessment->save();

        $label = EducationAidAssessment::recommendationOptions()[$validated['recommendation']];
        $this->cases->recordEvent(
            $submission,
            'recommendation_submitted',
            sprintf(
                'Assessment recommended: %s – RM%s.',
                $label,
                number_format((float) $validated['recommended_amount'], 2)
            ),
            $validated
        );

        return back()->with('success', 'Reviewer recommendation submitted.');
    }

    public function submitToCommittee($id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::with(['documentChecks'])->findOrFail($id);
        $assessment = $this->cases->ensureCaseInitialized($submission);
        $completeness = EducationAidCompleteness::evaluate($submission, $assessment);

        if (! $completeness['can_submit_to_committee']) {
            return back()->with('error', 'Assessment is incomplete. Complete all mandatory items before submitting to committee.');
        }

        $assessment->submitted_to_committee_at = Carbon::now();
        $assessment->submitted_to_committee_by = $this->adminUser()->id;
        $assessment->save();

        $from = EducationAidStatus::normalize($submission->status);
        $submission->update(['status' => EducationAidStatus::COMMITTEE_REVIEW]);

        $this->cases->recordEvent(
            $submission,
            'submitted_to_committee',
            'Submitted to MFLS Selection Committee.',
            ['from' => $from]
        );

        return back()->with('success', 'Case submitted to committee review.');
    }

    public function saveCommitteeDecision(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $assessment = $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'committee_decision' => ['required', Rule::in(array_keys(EducationAidAssessment::committeeDecisionOptions()))],
            'approved_amount' => ['nullable', 'numeric', 'min:0'],
            'committee_remarks' => ['nullable', 'string'],
            'committee_decision_date' => ['required', 'date'],
            'committee_approved_by' => ['required', 'string', 'max:255'],
        ]);

        if (in_array($validated['committee_decision'], ['approve_full', 'approve_partial'], true)
            && ($validated['approved_amount'] === null || $validated['approved_amount'] === '')) {
            return back()->with('error', 'Approved amount is required for approval decisions.')->withInput();
        }

        $assessment->fill($validated)->save();

        $statusMap = [
            'approve_full' => EducationAidStatus::APPROVED_FULL,
            'approve_partial' => EducationAidStatus::APPROVED_PARTIAL,
            'defer' => EducationAidStatus::DEFERRED_FURTHER_INFO,
            'do_not_approve' => EducationAidStatus::NOT_APPROVED,
        ];
        $newStatus = $statusMap[$validated['committee_decision']];
        $submission->update(['status' => $newStatus]);

        $this->cases->recordEvent(
            $submission,
            'committee_decision',
            'Committee decision: ' . EducationAidAssessment::committeeDecisionOptions()[$validated['committee_decision']] . '.',
            $validated
        );

        return back()->with('success', 'Committee decision recorded.');
    }

    public function updateDocumentCheck(Request $request, $id, $documentKey)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $this->cases->ensureCaseInitialized($submission);

        if (! array_key_exists($documentKey, EducationAidDocumentCheck::documentCatalog())) {
            abort(404);
        }

        $validated = $request->validate([
            'verification_status' => ['required', Rule::in(array_keys(EducationAidDocumentCheck::statusOptions()))],
            'remarks' => ['nullable', 'string'],
        ]);

        $check = EducationAidDocumentCheck::updateOrCreate(
            [
                'community_aid_submission_id' => $submission->id,
                'document_key' => $documentKey,
            ],
            [
                'verification_status' => $validated['verification_status'],
                'remarks' => $validated['remarks'] ?? null,
                'verified_by' => $this->adminUser()->id,
            ]
        );
        $check->load('verifier');

        $label = EducationAidDocumentCheck::documentCatalog()[$documentKey];
        $statusLabel = EducationAidDocumentCheck::statusOptions()[$validated['verification_status']];
        $this->cases->recordEvent(
            $submission,
            'document_verified',
            $label . ' marked as ' . $statusLabel . '.',
            ['document_key' => $documentKey, 'status' => $validated['verification_status']]
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $label . ' verification updated.',
                'document' => $this->documentCheckPayload($check, $documentKey),
                'completeness' => $this->completenessPayload($submission),
            ]);
        }

        return back()->with('success', $label . ' verification updated.');
    }

    public function bulkUpdateDocumentChecks(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $this->cases->ensureCaseInitialized($submission);

        $catalogKeys = array_keys(EducationAidDocumentCheck::documentCatalog());
        $validated = $request->validate([
            'document_keys' => ['required', 'array', 'min:1'],
            'document_keys.*' => ['required', 'string', Rule::in($catalogKeys)],
            'verification_status' => ['required', Rule::in(array_keys(EducationAidDocumentCheck::statusOptions()))],
            'remarks' => ['nullable', 'string'],
            'apply_remarks' => ['nullable', 'boolean'],
        ]);

        $status = $validated['verification_status'];
        $statusLabel = EducationAidDocumentCheck::statusOptions()[$status];
        $applyRemarks = $request->boolean('apply_remarks');
        $remarks = $validated['remarks'] ?? null;
        $updated = [];

        foreach ($validated['document_keys'] as $documentKey) {
            $payload = [
                'verification_status' => $status,
                'verified_by' => $this->adminUser()->id,
            ];
            if ($applyRemarks) {
                $payload['remarks'] = $remarks;
            }

            $check = EducationAidDocumentCheck::updateOrCreate(
                [
                    'community_aid_submission_id' => $submission->id,
                    'document_key' => $documentKey,
                ],
                $payload
            );
            $check->load('verifier');
            $updated[] = $this->documentCheckPayload($check, $documentKey);
        }

        $count = count($updated);
        $this->cases->recordEvent(
            $submission,
            'documents_bulk_verified',
            $count . ' document(s) marked as ' . $statusLabel . '.',
            [
                'document_keys' => $validated['document_keys'],
                'status' => $status,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $count . ' document(s) marked as ' . $statusLabel . '.',
            'documents' => $updated,
            'completeness' => $this->completenessPayload($submission),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function documentCheckPayload(EducationAidDocumentCheck $check, string $documentKey): array
    {
        return [
            'key' => $documentKey,
            'label' => EducationAidDocumentCheck::documentCatalog()[$documentKey] ?? $documentKey,
            'verification_status' => $check->verification_status,
            'status_label' => EducationAidDocumentCheck::statusOptions()[$check->verification_status]
                ?? $check->verification_status,
            'remarks' => $check->remarks,
            'verified_by' => optional($check->verifier)->name,
        ];
    }

    /**
     * @return array{percent: int, items: array<string, array{label: string, done: bool, mandatory: bool}>, can_submit_to_committee: bool}
     */
    private function completenessPayload(CommunityAidSubmission $submission): array
    {
        $submission->load(['documentChecks', 'assessment']);

        return EducationAidCompleteness::evaluate($submission, $submission->assessment);
    }

    public function addSectionComment(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'section' => ['required', Rule::in(array_keys(EducationAidSectionComment::sectionOptions()))],
            'comment' => ['required', 'string', 'min:2'],
        ]);

        EducationAidSectionComment::create([
            'community_aid_submission_id' => $submission->id,
            'section' => $validated['section'],
            'comment' => $validated['comment'],
            'user_id' => $this->adminUser()->id,
        ]);

        $sectionLabel = EducationAidSectionComment::sectionOptions()[$validated['section']];
        $this->cases->recordEvent(
            $submission,
            'section_comment_added',
            'Comment added to ' . $sectionLabel . '.',
            ['section' => $validated['section']]
        );

        return back()->with('success', 'Comment added.');
    }

    public function updateApplicant(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nric_passport' => ['required', 'string', 'max:50'],
            'dob' => ['nullable', 'date'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'university_institution' => ['nullable', 'string', 'max:255'],
            'programme_name' => ['nullable', 'string', 'max:255'],
            'programme_level' => ['nullable', 'string', 'max:255'],
            'current_year_semester' => ['nullable', 'string', 'max:255'],
            'intake_date' => ['nullable', 'date'],
            'expected_graduation_date' => ['nullable', 'date'],
            'household_income' => ['nullable', 'string', 'max:255'],
            'father_guardian_occupation' => ['nullable', 'string', 'max:255'],
            'mother_guardian_occupation' => ['nullable', 'string', 'max:255'],
            'number_of_dependents' => ['nullable', 'integer', 'min:0'],
            'amount_requested_from_mukmin' => ['nullable', 'numeric', 'min:0'],
            'current_outstanding_amount' => ['nullable', 'numeric', 'min:0'],
            'total_programme_tuition_fees' => ['nullable', 'numeric', 'min:0'],
            'total_amount_already_paid' => ['nullable', 'numeric', 'min:0'],
            'payment_deadline' => ['nullable', 'date'],
            'payment_not_made_consequence' => ['nullable', 'string'],
        ]);

        $before = $submission->only(array_keys($validated));
        $submission->update($validated);

        $this->cases->recordEvent(
            $submission,
            'applicant_info_edited',
            'Applicant information edited by aid admin.',
            ['before' => $before, 'after' => $validated]
        );

        return back()->with('success', 'Applicant information updated. Change recorded in audit trail.');
    }

    public function scheduleInterview(Request $request, $id)
    {
        $this->authorizePermission('submissions.aid.status');
        $submission = CommunityAidSubmission::findOrFail($id);
        $assessment = $this->cases->ensureCaseInitialized($submission);

        $validated = $request->validate([
            'proposed_dates' => ['required', 'array', 'size:3'],
            'proposed_dates.*' => ['required', 'date', 'after_or_equal:today'],
            'committee_emails' => ['required', 'string'],
        ]);

        $emails = collect(preg_split('/[\s,;]+/', $validated['committee_emails']))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return back()->with('error', 'Please provide at least one valid committee email.')->withInput();
        }

        $proposal = EducationAidInterviewProposal::create([
            'community_aid_submission_id' => $submission->id,
            'proposed_dates' => $validated['proposed_dates'],
            'status' => 'pending',
            'created_by' => $this->adminUser()->id,
        ]);

        $recipients = collect();
        if ($submission->email) {
            $recipients->push($submission->email);
        }
        $recipients = $recipients->merge($emails)->unique()->values();

        foreach ($recipients as $email) {
            Mail::to($email)->send(new EducationAidInterviewProposalMail(
                $submission,
                $proposal,
                $this->adminUser()->name
            ));
        }

        $proposal->update(['email_sent_at' => Carbon::now()]);

        $from = EducationAidStatus::normalize($submission->status);
        if (! in_array($from, [EducationAidStatus::COMMITTEE_REVIEW, EducationAidStatus::INTERVIEW_REQUIRED], true)) {
            $submission->update(['status' => EducationAidStatus::INTERVIEW_REQUIRED]);
        }

        $assessment->interview_conducted = false;
        $assessment->save();

        $this->cases->recordEvent(
            $submission,
            'interview_scheduled',
            'Applicant interview scheduled. Proposed dates emailed to applicant and committee.',
            [
                'proposed_dates' => $validated['proposed_dates'],
                'committee_emails' => $emails->all(),
            ]
        );

        return back()->with('success', 'Interview scheduling email sent to applicant and committee.');
    }

    public function report($id)
    {
        $this->authorizePermission('submissions.aid.view');

        $submission = CommunityAidSubmission::with([
            'assessment.assignee',
            'assessment.recommendationSubmitter',
            'documentChecks.verifier',
            'sectionComments.user',
            'caseEvents.user',
        ])->findOrFail($id);

        $assessment = $this->cases->ensureCaseInitialized($submission);
        $snapshot = $this->cases->caseSnapshot($submission, $assessment);
        $completeness = EducationAidCompleteness::evaluate($submission, $assessment);

        $this->cases->recordEvent($submission, 'report_generated', 'Assessment report generated.');

        return view('welfare.admin.education-aid.report', compact(
            'submission',
            'assessment',
            'snapshot',
            'completeness'
        ));
    }
}
