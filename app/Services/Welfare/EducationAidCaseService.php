<?php

namespace App\Services\Welfare;

use App\Models\CommunityAidSubmission;
use App\Models\EducationAidAssessment;
use App\Models\EducationAidCaseEvent;
use App\Models\EducationAidCaseFile;
use App\Models\EducationAidDocumentCheck;
use App\Models\User;
use App\Support\EducationAidStatus;
use App\Support\EducationAidUrgency;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EducationAidCaseService
{
    public function ensureCaseInitialized(CommunityAidSubmission $submission): EducationAidAssessment
    {
        $assessment = $submission->assessment;

        if (! $assessment) {
            $suggestion = EducationAidUrgency::suggest($submission);

            $assessment = EducationAidAssessment::create([
                'community_aid_submission_id' => $submission->id,
                'urgency' => $suggestion['level'],
                'urgency_system_suggested' => $suggestion['level'],
                'urgency_reason' => $suggestion['reason'],
                'purpose_expense_types' => $submission->education_expense_types ?: [],
            ]);

            $this->recordEvent($submission, 'case_opened', 'Case opened for assessment.', [
                'urgency' => $suggestion['level'],
            ]);

            if (EducationAidStatus::normalize($submission->status) === EducationAidStatus::RECEIVED) {
                // Keep received until reviewer starts assessment; urgency still seeded.
            }
        }

        $this->ensureDocumentChecks($submission);
        $this->syncApplicationFiles($submission);
        $this->ensureSubmittedEvent($submission);

        return $assessment->fresh(['assignee', 'recommendationSubmitter', 'committeeSubmitter']);
    }

    public function ensureDocumentChecks(CommunityAidSubmission $submission): Collection
    {
        $existing = $submission->documentChecks()->get()->keyBy('document_key');
        $created = collect();

        foreach (EducationAidDocumentCheck::documentCatalog() as $key => $label) {
            if ($existing->has($key)) {
                $created->push($existing->get($key));
                continue;
            }

            $created->push(EducationAidDocumentCheck::create([
                'community_aid_submission_id' => $submission->id,
                'document_key' => $key,
                'verification_status' => $this->fileSubmitted($submission, $key) ? 'pending' : 'pending',
            ]));
        }

        return $created;
    }

    public function refreshUrgencySuggestion(CommunityAidSubmission $submission, EducationAidAssessment $assessment): EducationAidAssessment
    {
        $suggestion = EducationAidUrgency::suggest($submission);
        $assessment->urgency_system_suggested = $suggestion['level'];
        $assessment->urgency_reason = $suggestion['reason'];

        if (! $assessment->urgency_confirmed) {
            $assessment->urgency = $suggestion['level'];
        }

        $assessment->save();

        return $assessment;
    }

    /**
     * @return array{
     *   total_programme_fees: float,
     *   amount_already_paid: float,
     *   other_confirmed_funding: float,
     *   outstanding_balance: float,
     *   amount_requested: float,
     *   payment_deadline: ?\Carbon\Carbon,
     *   funding_requires_review: bool,
     *   reconciliation_notes: list<string>
     * }
     */
    public function caseSnapshot(CommunityAidSubmission $submission, EducationAidAssessment $assessment): array
    {
        $totalFees = (float) ($submission->total_programme_tuition_fees ?? 0);
        $alreadyPaid = (float) ($submission->total_amount_already_paid ?? 0);
        $outstanding = (float) ($submission->current_outstanding_amount ?? 0);
        $requested = (float) ($submission->amount_requested_from_mukmin ?? 0);

        $breakdown = $assessment->funding_breakdown ?? [];
        $breakdownSum = collect($breakdown)->sum(function ($value) {
            return (float) $value;
        });

        $otherConfirmed = $assessment->other_confirmed_funding !== null
            ? (float) $assessment->other_confirmed_funding
            : (float) $breakdownSum;

        $notes = [];
        $requiresReview = false;
        $tolerance = 1.0;

        if ($breakdownSum > 0 && abs($alreadyPaid - $breakdownSum) > $tolerance) {
            $requiresReview = true;
            $notes[] = sprintf(
                'Amount already paid (RM%s) does not match funding breakdown total (RM%s).',
                number_format($alreadyPaid, 2),
                number_format($breakdownSum, 2)
            );
        }

        if ($totalFees > 0 && abs(($totalFees - $alreadyPaid) - $outstanding) > $tolerance) {
            $requiresReview = true;
            $notes[] = sprintf(
                'Fees minus already paid (RM%s) does not match outstanding balance (RM%s).',
                number_format($totalFees - $alreadyPaid, 2),
                number_format($outstanding, 2)
            );
        }

        if ($assessment->funding_requires_review !== $requiresReview) {
            $assessment->funding_requires_review = $requiresReview;
            $assessment->save();
        }

        return [
            'total_programme_fees' => $totalFees,
            'amount_already_paid' => $alreadyPaid,
            'other_confirmed_funding' => $otherConfirmed,
            'outstanding_balance' => $outstanding,
            'amount_requested' => $requested,
            'payment_deadline' => $submission->payment_deadline,
            'funding_requires_review' => $requiresReview,
            'reconciliation_notes' => $notes,
        ];
    }

    public function recordEvent(
        CommunityAidSubmission $submission,
        string $eventType,
        string $description,
        array $meta = [],
        ?User $user = null
    ): EducationAidCaseEvent {
        return EducationAidCaseEvent::create([
            'community_aid_submission_id' => $submission->id,
            'event_type' => $eventType,
            'description' => $description,
            'meta' => $meta ?: null,
            'user_id' => $user ? $user->id : (Auth::id() ?: null),
            'created_at' => Carbon::now(),
        ]);
    }

    public function assignableUsers(): Collection
    {
        return User::query()
            ->with('role')
            ->where('is_active', true)
            ->whereNotNull('role_id')
            ->orderBy('name')
            ->get();
    }

    /**
     * Mirror applicant-uploaded files into case files so they can be renamed alongside admin uploads.
     */
    public function syncApplicationFiles(CommunityAidSubmission $submission): void
    {
        $existingPaths = EducationAidCaseFile::query()
            ->where('community_aid_submission_id', $submission->id)
            ->where('source', EducationAidCaseFile::SOURCE_APPLICATION)
            ->get()
            ->map(fn (EducationAidCaseFile $file) => $file->document_key . '|' . $file->path)
            ->flip();

        foreach (array_keys(EducationAidDocumentCheck::documentCatalog()) as $key) {
            foreach ($this->applicationPaths($submission, $key) as $path) {
                if ($existingPaths->has($key . '|' . $path)) {
                    continue;
                }

                EducationAidCaseFile::create([
                    'community_aid_submission_id' => $submission->id,
                    'document_key' => $key,
                    'path' => $path,
                    'display_name' => basename($path),
                    'original_name' => basename($path),
                    'source' => EducationAidCaseFile::SOURCE_APPLICATION,
                ]);
            }
        }
    }

    /** @return list<string> */
    public function applicationPaths(CommunityAidSubmission $submission, string $key): array
    {
        $value = $submission->getAttribute($key);
        $paths = is_array($value) ? $value : [$value];

        return collect($paths)
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->map(fn ($path) => ltrim($path, '/'))
            ->values()
            ->all();
    }

    public function fileSubmitted(CommunityAidSubmission $submission, string $key): bool
    {
        if (count($this->applicationPaths($submission, $key)) > 0) {
            return true;
        }

        return EducationAidCaseFile::query()
            ->where('community_aid_submission_id', $submission->id)
            ->where('document_key', $key)
            ->exists();
    }

    public function fileUrl(CommunityAidSubmission $submission, string $key): ?string
    {
        $value = $submission->getAttribute($key);
        if (is_array($value)) {
            $first = collect($value)->filter()->first();

            return $first ? asset('storage/' . ltrim($first, '/')) : null;
        }

        if (! filled($value)) {
            return null;
        }

        return asset('storage/' . ltrim((string) $value, '/'));
    }

    private function ensureSubmittedEvent(CommunityAidSubmission $submission): void
    {
        $exists = EducationAidCaseEvent::query()
            ->where('community_aid_submission_id', $submission->id)
            ->where('event_type', 'application_submitted')
            ->exists();

        if ($exists) {
            return;
        }

        EducationAidCaseEvent::create([
            'community_aid_submission_id' => $submission->id,
            'event_type' => 'application_submitted',
            'description' => 'Application submitted by applicant.',
            'meta' => null,
            'user_id' => null,
            'created_at' => $submission->created_at ?? Carbon::now(),
        ]);
    }
}
