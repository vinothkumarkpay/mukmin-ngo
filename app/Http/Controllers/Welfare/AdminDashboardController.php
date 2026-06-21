<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormDropdownOption;
use App\Models\FeedbackSubmission;
use App\Models\OrdinaryMemberSubmission;
use App\Models\FriendMemberSubmission;
use App\Models\MentorSubmission;
use App\Models\PartnerSubmission;
use App\Models\VolunteerSubmission;
use App\Models\ContactSubmission;
use App\Models\CommunityAidSubmission;
use App\Models\MflsScholarshipSubmission;
use App\Services\Welfare\MflsPartnerDocumentService;
use App\Services\Welfare\SubmissionImportRegistry;
use App\Services\Welfare\SubmissionImporter;
use Response;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Gather stats
        $stats = [
            'feedback' => FeedbackSubmission::count(),
            'ordinary' => OrdinaryMemberSubmission::count(),
            'friends' => FriendMemberSubmission::count(),
            'mentor' => MentorSubmission::count(),
            'partner' => PartnerSubmission::count(),
            'volunteer' => VolunteerSubmission::count(),
            'contact' => ContactSubmission::count(),
            'aid' => CommunityAidSubmission::count(),
            'mfls' => MflsScholarshipSubmission::count(),
        ];

        // 2. Fetch all submissions
        $feedback = FeedbackSubmission::orderBy('created_at', 'desc')->get();
        $ordinary = OrdinaryMemberSubmission::orderBy('created_at', 'desc')->get();
        $friends = FriendMemberSubmission::orderBy('created_at', 'desc')->get();
        $mentor = MentorSubmission::orderBy('created_at', 'desc')->get();
        $partner = PartnerSubmission::orderBy('created_at', 'desc')->get();
        $volunteer = VolunteerSubmission::orderBy('created_at', 'desc')->get();
        $contact = ContactSubmission::orderBy('created_at', 'desc')->get();
        $aid = CommunityAidSubmission::orderBy('created_at', 'desc')->get();
        $mfls = MflsScholarshipSubmission::orderBy('created_at', 'desc')->get();

        app(MflsPartnerDocumentService::class)->bootstrapDocumentsIfMissing();
        $mflsPartnerDocuments = app(MflsPartnerDocumentService::class)->documentsForAdmin();
        $options = FormDropdownOption::orderBy('form_type')
            ->orderBy('sort_order')
            ->orderBy('option_value')
            ->get()
            ->groupBy('form_type');

        // Form labels map
        $formTypesMap = [
            'feedback_category' => 'Feedback Categories',
            'ordinary_org_type' => 'Ordinary Member Organisation Types',
            'ordinary_activity' => 'Ordinary Member Primary Activities',
            'friends_category' => 'Friends of MUKMIN Entity Types',
            'mentor_expertise' => 'Mentor Areas of Expertise',
            'mentor_format' => 'Mentor Preferred Formats',
            'mentor_commitment' => 'Mentor Commitments',
            'partner_org_type' => 'Partner Organisation Types',
            'partner_collaboration' => 'Partner Collaboration Areas',
            'partner_type' => 'Partner Partnership Types',
            'volunteer_interest' => 'Volunteer Areas of Interest',
            'volunteer_mode' => 'Volunteer Preferred Modes',
            'volunteer_availability' => 'Volunteer Availabilities',
        ];

        return view('welfare.admin.dashboard', compact(
            'stats', 'feedback', 'ordinary', 'friends', 'mentor', 'partner', 'volunteer', 'contact', 'aid', 'mfls', 'options', 'formTypesMap', 'mflsPartnerDocuments'
        ));
    }

    public function showSubmission($type, $id)
    {
        $submission = null;
        switch ($type) {
            case 'feedback':
                $submission = FeedbackSubmission::find($id);
                break;
            case 'ordinary':
                $submission = OrdinaryMemberSubmission::find($id);
                break;
            case 'friends':
                $submission = FriendMemberSubmission::find($id);
                break;
            case 'mentor':
                $submission = MentorSubmission::find($id);
                break;
            case 'partner':
                $submission = PartnerSubmission::find($id);
                break;
            case 'volunteer':
                $submission = VolunteerSubmission::find($id);
                break;
            case 'contact':
                $submission = ContactSubmission::find($id);
                break;
            case 'aid':
                $submission = CommunityAidSubmission::find($id);
                break;
            case 'mfls':
                $submission = MflsScholarshipSubmission::find($id);
                break;
        }

        if (!$submission) {
            return response()->json(['error' => 'Submission not found.'], 404);
        }

        return response()->json($submission);
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected,under_review',
        ]);

        $submission = null;
        switch ($type) {
            case 'ordinary':
                $submission = OrdinaryMemberSubmission::findOrFail($id);
                break;
            case 'friends':
                $submission = FriendMemberSubmission::findOrFail($id);
                break;
            case 'partner':
                $submission = PartnerSubmission::findOrFail($id);
                break;
            case 'aid':
                $submission = CommunityAidSubmission::findOrFail($id);
                break;
            case 'mfls':
                $submission = MflsScholarshipSubmission::findOrFail($id);
                break;
        }

        if ($submission) {
            $submission->update(['status' => $validated['status']]);
            return response()->json(['success' => true, 'status' => $validated['status']]);
        }

        return response()->json(['error' => 'Invalid submission type for status update.'], 400);
    }

    // Dynamic dropdown option management
    public function addOption(Request $request)
    {
        $validated = $request->validate([
            'form_type' => 'required|string|max:100',
            'option_value' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        FormDropdownOption::create($validated);

        return redirect()->back()->with('success', 'Dropdown option added successfully!');
    }

    public function editOption(Request $request, $id)
    {
        $validated = $request->validate([
            'option_value' => 'required|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $option = FormDropdownOption::findOrFail($id);
        $option->update($validated);

        return redirect()->back()->with('success', 'Dropdown option updated successfully!');
    }

    public function deleteOption($id)
    {
        $option = FormDropdownOption::findOrFail($id);
        $option->delete();

        return redirect()->back()->with('success', 'Dropdown option deleted successfully!');
    }

    // Export submissions to CSV format
    public function exportCsv($type)
    {
        $filename = "submissions_{$type}_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($type) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            switch ($type) {
                case 'feedback':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC', 'Organisation', 'Position', 'State', 'Address', 'Email', 'Phone', 'Categories', 'Other Category', 'Suggestion', 'Benefits', 'Contact Consent', 'Preferred Methods']);
                    foreach (FeedbackSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_number,
                            $item->organisation,
                            $item->position,
                            $item->state_residency,
                            $item->full_address,
                            $item->email,
                            $item->contact_number,
                            is_array($item->categories) ? implode(', ', $item->categories) : $item->categories,
                            $item->other_category,
                            $item->suggestion_description,
                            $item->benefits_description,
                            $item->contact_consent ? 'Yes' : 'No',
                            is_array($item->preferred_contact_methods) ? implode(', ', $item->preferred_contact_methods) : $item->preferred_contact_methods
                        ]);
                    }
                    break;

                case 'ordinary':
                    fputcsv($file, ['ID', 'Date', 'Organisation Name', 'Reg Number', 'Reg Date', 'State', 'Address', 'Postcode', 'City', 'Established', 'Congregation Size', 'Email', 'Phone', 'Website', 'Org Types', 'Org Types Other', 'Activities', 'Activities Other', 'Registered ROS', 'President Name', 'President Email', 'President Phone', 'Secretary Name', 'Secretary Email', 'Secretary Phone', 'Status']);
                    foreach (OrdinaryMemberSubmission::all() as $item) {
                        $bearers = $item->key_office_bearers;
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->name_of_organisation,
                            $item->org_reg_number,
                            $item->org_reg_date ? $item->org_reg_date->format('Y-m-d') : '',
                            $item->registered_state,
                            $item->full_address,
                            $item->postcode,
                            $item->district_city,
                            $item->year_established,
                            $item->total_members_size,
                            $item->email,
                            $item->contact_number,
                            $item->website,
                            is_array($item->org_type) ? implode(', ', $item->org_type) : $item->org_type,
                            $item->org_type_other,
                            is_array($item->primary_activities) ? implode(', ', $item->primary_activities) : $item->primary_activities,
                            $item->primary_activities_other,
                            $item->is_registered_ros ? 'Yes' : 'No',
                            $bearers['president']['name'] ?? '',
                            $bearers['president']['email'] ?? '',
                            $bearers['president']['phone'] ?? '',
                            $bearers['secretary']['name'] ?? '',
                            $bearers['secretary']['email'] ?? '',
                            $bearers['secretary']['phone'] ?? '',
                            ucfirst($item->status)
                        ]);
                    }
                    break;

                case 'friends':
                    fputcsv($file, ['ID', 'Date', 'Type', 'Others Specify', 'Org Name', 'Org State', 'Org Address', 'Org Email', 'Org Phone', 'Official Contact Person Name', 'Org Website', 'Ind Name', 'Ind NRIC', 'Ind State', 'Ind Profession', 'Ind Profession Other', 'Ind Address', 'Ind Email', 'Ind Phone', 'Ind Area of Interest', 'Status']);
                    foreach (FriendMemberSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->entity_type,
                            $item->others_specify,
                            $item->org_name,
                            $item->org_state,
                            $item->org_address,
                            $item->org_email,
                            $item->org_phone,
                            $item->org_contact_person_name,
                            $item->org_website,
                            $item->ind_name,
                            $item->ind_nric,
                            $item->ind_state,
                            $item->ind_profession,
                            $item->ind_profession_other,
                            $item->ind_address,
                            $item->ind_email,
                            $item->ind_phone,
                            $item->ind_area_of_interest,
                            ucfirst($item->status)
                        ]);
                    }
                    break;

                case 'mentor':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC/Passport', 'Gender', 'Occupation', 'Organisation', 'Position', 'Years Experience', 'State', 'Address', 'Email', 'Phone', 'LinkedIn', 'Expertise Areas', 'Expertise Other', 'Formats', 'Commitments', 'Experience Description', 'Has Served Before', 'Served Before Details']);
                    foreach (MentorSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_passport,
                            $item->gender,
                            $item->occupation,
                            $item->organisation,
                            $item->position,
                            $item->experience_years,
                            $item->state_residency,
                            $item->full_address,
                            $item->email,
                            $item->contact_number,
                            $item->linkedin,
                            is_array($item->expertise_areas) ? implode(', ', $item->expertise_areas) : $item->expertise_areas,
                            $item->expertise_other,
                            is_array($item->preferred_format) ? implode(', ', $item->preferred_format) : $item->preferred_format,
                            is_array($item->preferred_commitment) ? implode(', ', $item->preferred_commitment) : $item->preferred_commitment,
                            $item->experience_description,
                            $item->has_served_before ? 'Yes' : 'No',
                            $item->served_before_details
                        ]);
                    }
                    break;

                case 'partner':
                    fputcsv($file, ['ID', 'Date', 'Company Name', 'Contact Person', 'Position', 'Reg Number', 'Email', 'Phone', 'Office Address', 'State/Country', 'Org Types', 'Org Types Other', 'Collaboration Areas', 'Collaboration Other', 'Partnership Types', 'Partnership Other', 'Proposal Details', 'Expected Outcomes', 'Has Collaborated', 'Previous Collab Details', 'Supporting Documents', 'Status']);
                    foreach (PartnerSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->company_name,
                            $item->contact_person,
                            $item->position,
                            $item->org_reg_number,
                            $item->email,
                            $item->contact_number,
                            $item->office_address,
                            $item->state_country,
                            is_array($item->org_type) ? implode(', ', $item->org_type) : $item->org_type,
                            $item->org_type_other,
                            is_array($item->collaboration_areas) ? implode(', ', $item->collaboration_areas) : $item->collaboration_areas,
                            $item->collaboration_other,
                            is_array($item->partnership_type) ? implode(', ', $item->partnership_type) : $item->partnership_type,
                            $item->partnership_other,
                            $item->proposal_description,
                            $item->expected_outcomes,
                            $item->has_collaborated_before ? 'Yes' : 'No',
                            $item->collaborated_before_details,
                            is_array($item->supporting_documents) ? implode(', ', $item->supporting_documents) : $item->supporting_documents,
                            ucfirst($item->status)
                        ]);
                    }
                    break;

                case 'volunteer':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC/Passport', 'Gender', 'Occupation/Study', 'Organisation', 'State', 'Address', 'Email', 'Phone', 'Interest Areas', 'Interest Other', 'Skills/Expertise', 'Preferred Mode', 'Availability', 'Has Volunteered Before', 'Volunteered Details', 'Emergency Name', 'Emergency Relationship', 'Emergency Phone']);
                    foreach (VolunteerSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_passport,
                            $item->gender,
                            $item->occupation_study,
                            $item->organisation,
                            $item->state_residency,
                            $item->full_address,
                            $item->email,
                            $item->contact_number,
                            is_array($item->interest_areas) ? implode(', ', $item->interest_areas) : $item->interest_areas,
                            $item->interest_other,
                            $item->skills_expertise,
                            $item->preferred_mode,
                            is_array($item->availability) ? implode(', ', $item->availability) : $item->availability,
                            $item->has_volunteered_before ? 'Yes' : 'No',
                            $item->volunteered_before_details,
                            $item->emergency_contact_name,
                            $item->emergency_contact_relationship,
                            $item->emergency_contact_phone
                        ]);
                    }
                    break;

                case 'contact':
                    fputcsv($file, ['ID', 'Date', 'Name', 'Email', 'Phone', 'Message']);
                    foreach (ContactSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->name,
                            $item->email,
                            $item->phone,
                            $item->message
                        ]);
                    }
                    break;

                case 'aid':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC/Passport', 'Gender', 'DOB', 'Nationality', 'Occupation', 'Monthly Income', 'Phone', 'Email', 'Address', 'State', 'Type of Aid', 'Type of Aid Other', 'Situation', 'Who Benefits', 'Beneficiaries Count', 'Received Aid Before', 'Previous Aid Details', 'Status']);
                    foreach (CommunityAidSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_passport,
                            $item->gender,
                            $item->dob ? $item->dob->format('Y-m-d') : '',
                            $item->nationality,
                            $item->occupation,
                            $item->monthly_income,
                            $item->contact_number,
                            $item->email,
                            $item->full_address,
                            $item->state_residency,
                            is_array($item->type_of_aid) ? implode(', ', $item->type_of_aid) : $item->type_of_aid,
                            $item->type_of_aid_other,
                            $item->situation_description,
                            $item->who_benefits,
                            $item->number_of_beneficiaries,
                            $item->received_aid_before ? 'Yes' : 'No',
                            $item->received_aid_before_details,
                            ucfirst($item->status)
                        ]);
                    }
                    break;

                case 'mfls':
                    fputcsv($file, ['ID', 'Date', 'Email', 'Full Name', 'NRIC', 'DOB', 'Gender', 'Marital Status', 'Phone', 'Address', 'Partner Institution', 'Selected Programme', 'Current Qualification', 'Institution', 'CGPA/Result', 'Applied to University', 'Offer Letter Received', 'Household Income', 'Father Name', 'Father Occupation', 'Mother Name', 'Mother Occupation', 'Dependents', 'Other Scholarship', 'Leadership Roles', 'Involvement Level', 'Community Service', 'Status']);
                    foreach (MflsScholarshipSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->email,
                            $item->full_name,
                            $item->nric_passport,
                            $item->dob ? $item->dob->format('Y-m-d') : '',
                            $item->gender,
                            $item->marital_status === 'Other' ? 'Other: ' . $item->marital_status_other : $item->marital_status,
                            $item->contact_number,
                            $item->full_address,
                            $item->partner_institution_name,
                            $item->programme_course_applied,
                            $item->current_qualification,
                            $item->institution_name,
                            $item->current_cgpa_result,
                            $item->applied_to_university ? 'Yes' : 'No',
                            $item->received_offer_letter === null ? '' : ($item->received_offer_letter ? 'Yes' : 'No'),
                            $item->household_income,
                            $item->father_guardian_name,
                            $item->father_guardian_occupation,
                            $item->mother_guardian_name,
                            $item->mother_guardian_occupation,
                            $item->number_of_dependents,
                            $item->other_scholarship_details,
                            $item->leadership_roles,
                            $item->involvement_level,
                            $item->community_service_involvement,
                            ucfirst($item->status)
                        ]);
                    }
                    break;
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function downloadImportTemplate($type, SubmissionImporter $importer)
    {
        if (!in_array($type, SubmissionImportRegistry::TYPES, true)) {
            abort(404);
        }

        return $importer->downloadTemplate($type);
    }

    public function importSubmissions(Request $request, $type, SubmissionImporter $importer)
    {
        if (!in_array($type, SubmissionImportRegistry::TYPES, true)) {
            abort(404);
        }

        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $result = $importer->import($type, $request->file('import_file'));

        if ($result['imported'] === 0 && !empty($result['errors'])) {
            return redirect()
                ->route('welfare.admin.dashboard')
                ->with('import_tab', $request->input('import_tab', 'panel-' . $type))
                ->with('import_errors', $result['errors'])
                ->with('error', 'Import failed. No records were imported.');
        }

        $message = "Successfully imported {$result['imported']} record(s).";
        if ($this->hasStatus($type)) {
            $message .= ' All imported records are set to pending status.';
        }

        if (!empty($result['errors'])) {
            return redirect()
                ->route('welfare.admin.dashboard')
                ->with('import_tab', $request->input('import_tab', 'panel-' . $type))
                ->with('success', $message)
                ->with('import_errors', $result['errors']);
        }

        return redirect()
            ->route('welfare.admin.dashboard')
            ->with('import_tab', $request->input('import_tab', 'panel-' . $type))
            ->with('success', $message);
    }

    private function hasStatus(string $type): bool
    {
        return in_array($type, ['ordinary', 'friends', 'partner', 'aid', 'mfls'], true);
    }
}
