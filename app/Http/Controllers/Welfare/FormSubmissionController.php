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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormSubmissionMail;

class FormSubmissionController extends Controller
{
    /**
     * Send acknowledgement to the applicant (when email provided) and a copy to support.
     * Each recipient is mailed independently so one failure does not block the other.
     */
    private function sendFormSubmissionEmails(string $formName, array $validated, ?string $applicantEmail = null): void
    {
        if ($applicantEmail) {
            try {
                Mail::to($applicantEmail)->send(new FormSubmissionMail($formName, $validated));
            } catch (\Throwable $e) {
                Log::error("Mail to applicant failed for {$formName}", [
                    'email' => $applicantEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        try {
            Mail::to('support@mukmin.org')->send(new FormSubmissionMail($formName, $validated, true));
        } catch (\Throwable $e) {
            Log::error("Mail to support failed for {$formName}", [
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getOptions($type)
    {
        return FormDropdownOption::where('form_type', $type)
            ->orderBy('sort_order', 'asc')
            ->orderBy('option_value', 'asc')
            ->pluck('option_value')
            ->toArray();
    }

    public function feedback()
    {
        $categories = $this->getOptions('feedback_category');
        return view('welfare.pages.feedback', compact('categories'));
    }

    public function submitFeedback(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nric_number' => 'required|string|max:20',
            'organisation' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'state_residency' => 'required|string|max:50',
            'full_address' => 'required|string',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'categories' => 'required|array|min:1',
            'other_category' => 'nullable|string|max:255',
            'suggestion_description' => 'required|string',
            'benefits_description' => 'required|string',
            'contact_consent' => 'required|boolean',
            'preferred_contact_methods' => 'nullable|array',
            'declaration_confirmed' => 'required|accepted',
        ]);

        FeedbackSubmission::create($validated);

        $this->sendFormSubmissionEmails('Feedback & Suggestion', $validated, $validated['email']);

        return view('welfare.pages.form_success', [
            'title' => 'Thank you for sharing your feedback with MUKMIN.',
            'message' => 'Your submission has been successfully received and will be reviewed by our team accordingly.',
        ]);
    }

    public function membershipOrdinary()
    {
        $orgTypes = $this->getOptions('ordinary_org_type');
        $activities = $this->getOptions('ordinary_activity');
        return view('welfare.pages.membership_ordinary', compact('orgTypes', 'activities'));
    }

    public function submitOrdinary(Request $request)
    {
        $validated = $request->validate([
            'name_of_organisation' => 'required|string|max:255',
            'org_reg_number' => 'required|string|max:50',
            'org_reg_date' => 'required|date',
            'registered_state' => 'required|string|max:50',
            'full_address' => 'required|string',
            'postcode' => 'required|string|max:10',
            'district_city' => 'required|string|max:100',
            'year_established' => 'required|integer|min:1800|max:' . date('Y'),
            'total_members_size' => 'required|integer|min:0',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'website' => 'nullable|string|max:255',
            'org_type' => 'required|array|min:1',
            'org_type_other' => 'nullable|string|max:255',
            'primary_activities' => 'required|array|min:1',
            'primary_activities_other' => 'nullable|string|max:255',
            'is_registered_ros' => 'required|boolean',
            'registration_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'committee_members' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'key_office_bearers' => 'required|array',
            'key_office_bearers.president.name' => 'required|string|max:255',
            'key_office_bearers.president.email' => 'required|email|max:255',
            'key_office_bearers.president.phone' => 'required|string|max:20',
            'key_office_bearers.secretary.name' => 'nullable|string|max:255',
            'key_office_bearers.secretary.email' => 'nullable|email|max:255',
            'key_office_bearers.secretary.phone' => 'nullable|string|max:20',
            'key_office_bearers.treasurer.name' => 'nullable|string|max:255',
            'key_office_bearers.treasurer.email' => 'nullable|email|max:255',
            'key_office_bearers.treasurer.phone' => 'nullable|string|max:20',
            'declaration_confirmed' => 'required|accepted',
        ]);

        if ($request->hasFile('registration_certificate')) {
            $validated['registration_certificate'] = $request->file('registration_certificate')->store('documents', 'public');
        }
        if ($request->hasFile('committee_members')) {
            $validated['committee_members'] = $request->file('committee_members')->store('documents', 'public');
        }

        OrdinaryMemberSubmission::create($validated);

        $this->sendFormSubmissionEmails('Ordinary Member Registration', $validated, $validated['email']);

        return view('welfare.pages.form_success', [
            'title' => 'Application Submitted',
            'message' => 'Your Ordinary Member registration has been successfully submitted. Our Central Executive Committee will review your application and contact you shortly.',
        ]);
    }

    public function membershipFriends()
    {
        $categories = $this->getOptions('friends_category');
        return view('welfare.pages.membership_friends', compact('categories'));
    }

    public function submitFriends(Request $request)
    {
        $rules = [
            'entity_type' => 'required|string|max:50',
            'others_specify' => 'nullable|string|max:255',
            'declaration_confirmed' => 'required|accepted',
        ];

        // Add conditional rules depending on whether Individual or Organisation
        if ($request->input('entity_type') === 'Individual') {
            $rules = array_merge($rules, [
                'ind_name' => 'required|string|max:255',
                'ind_nric' => 'required|string|max:20',
                'ind_state' => 'required|string|max:50',
                'ind_address' => 'required|string',
                'ind_email' => 'required|email|max:255',
                'ind_phone' => 'required|string|max:20',
            ]);
        } else {
            $rules = array_merge($rules, [
                'org_name' => 'required|string|max:255',
                'org_state' => 'required|string|max:50',
                'org_address' => 'required|string',
                'org_email' => 'required|email|max:255',
                'org_phone' => 'required|string|max:20',
                'org_website' => 'nullable|string|max:255',
            ]);
        }

        $validated = $request->validate($rules);

        FriendMemberSubmission::create($validated);

        $email = $validated['entity_type'] === 'Individual' ? ($validated['ind_email'] ?? null) : ($validated['org_email'] ?? null);
        $this->sendFormSubmissionEmails('Friend of MUKMIN Registration', $validated, $email ?: null);

        return view('welfare.pages.form_success', [
            'title' => 'Your submission has been successfully received.',
            'message' => 'Our team will review the information provided and reach out to you within 3–7 working days where applicable. We appreciate your patience and engagement with MUKMIN.',
        ]);
    }

    public function mentor()
    {
        $expertises = $this->getOptions('mentor_expertise');
        $formats = $this->getOptions('mentor_format');
        $commitments = $this->getOptions('mentor_commitment');
        return view('welfare.pages.mentor', compact('expertises', 'formats', 'commitments'));
    }

    public function submitMentor(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nric_passport' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'occupation' => 'required|string|max:255',
            'organisation' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'state_residency' => 'required|string|max:50',
            'full_address' => 'required|string',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'linkedin' => 'nullable|string|max:255',
            'expertise_areas' => 'required|array|min:1',
            'expertise_other' => 'nullable|string|max:255',
            'preferred_format' => 'required|array|min:1',
            'preferred_commitment' => 'required|array|min:1',
            'experience_description' => 'required|string',
            'has_served_before' => 'required|boolean',
            'served_before_details' => 'nullable|string',
            'declaration_confirmed' => 'required|accepted',
        ]);

        MentorSubmission::create($validated);

        $this->sendFormSubmissionEmails('Mentor Registration', $validated, $validated['email']);

        return view('welfare.pages.form_success', [
            'title' => 'Thank you for registering as a MUKMIN Mentor.',
            'message' => 'We appreciate your willingness to contribute your experience, leadership and expertise towards empowering communities and future changemakers. Our team will review your professional background and reach out regarding the next steps for onboarding and engagement.',
        ]);
    }

    public function partner()
    {
        $orgTypes = $this->getOptions('partner_org_type');
        $collabs = $this->getOptions('partner_collaboration');
        $partnerTypes = $this->getOptions('partner_type');
        return view('welfare.pages.partner', compact('orgTypes', 'collabs', 'partnerTypes'));
    }

    public function submitPartner(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'org_reg_number' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'office_address' => 'required|string',
            'state_country' => 'required|string|max:50',
            'org_type' => 'required|array|min:1',
            'org_type_other' => 'nullable|string|max:255',
            'collaboration_areas' => 'required|array|min:1',
            'collaboration_other' => 'nullable|string|max:255',
            'partnership_type' => 'required|array|min:1',
            'partnership_other' => 'nullable|string|max:255',
            'proposal_description' => 'required|string',
            'expected_outcomes' => 'required|string',
            'has_collaborated_before' => 'required|boolean',
            'collaborated_before_details' => 'nullable|string',
            'supporting_files' => 'nullable|array',
            'supporting_files.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,zip,ppt,pptx|max:20480',
            'declaration_confirmed' => 'required|accepted',
        ]);

        if ($request->hasFile('supporting_files')) {
            $filePaths = [];
            foreach ($request->file('supporting_files') as $file) {
                $filePaths[] = $file->store('documents', 'public');
            }
            $validated['supporting_documents'] = $filePaths;
        }

        PartnerSubmission::create($validated);

        $this->sendFormSubmissionEmails('Partnership & Collaboration Proposal', $validated, $validated['email']);

        return view('welfare.pages.form_success', [
            'title' => 'Thank you for engaging with MUKMIN.',
            'message' => 'We appreciate your interest in building strategic collaborations that strengthen communities and drive collective impact. Our leadership team will review your proposal and initiate further engagement accordingly.',
        ]);
    }

    public function volunteer()
    {
        $interests = $this->getOptions('volunteer_interest');
        $modes = $this->getOptions('volunteer_mode');
        $availabilities = $this->getOptions('volunteer_availability');
        return view('welfare.pages.volunteer', compact('interests', 'modes', 'availabilities'));
    }

    public function submitVolunteer(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nric_passport' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'occupation_study' => 'required|string|max:255',
            'organisation' => 'nullable|string|max:255',
            'state_residency' => 'required|string|max:50',
            'full_address' => 'required|string',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'interest_areas' => 'required|array|min:1',
            'interest_other' => 'nullable|string|max:255',
            'skills_expertise' => 'required|string',
            'preferred_mode' => 'required|string|in:Physical / On-Ground,Virtual / Remote,Both',
            'availability' => 'required|array|min:1',
            'has_volunteered_before' => 'required|boolean',
            'volunteered_before_details' => 'nullable|string',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'declaration_confirmed' => 'required|accepted',
        ]);

        VolunteerSubmission::create($validated);

        $this->sendFormSubmissionEmails('Volunteer Registration', $validated, $validated['email']);

        return view('welfare.pages.form_success', [
            'title' => 'Thank You For Stepping Forward To Serve',
            'message' => 'Your willingness to volunteer reflects the spirit of compassion, unity and collective responsibility that drives the MUKMIN ecosystem. We look forward to engaging you in future initiatives and community impact programmes.',
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        ContactSubmission::create($validated);

        return view('welfare.pages.form_success', [
            'title' => 'Message Sent Successfully',
            'message' => 'Thank you for reaching out to us! Your message has been received, and our team will get in touch with you shortly.',
        ]);
    }

    public function submitDonate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'nullable|numeric|min:1',
            'custom_amount' => 'nullable|numeric|min:1',
        ]);

        return view('welfare.pages.form_success', [
            'title' => 'Donation Portal - Coming Soon',
            'message' => 'Thank you for your generosity and willingness to support MUKMIN. Our online donation payment gateway is currently under integration. Please check back soon or contact our administration directly for offline donation instructions.',
        ]);
    }

    public function communityAid()
    {
        return view('welfare.pages.community_aid');
    }

    public function submitCommunityAid(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nric_passport' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'monthly_income' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'full_address' => 'required|string',
            'state_residency' => 'required|string|max:50',
            'type_of_aid' => 'required|array|min:1',
            'type_of_aid_other' => 'nullable|string|max:255',
            'situation_description' => 'required|string',
            'who_benefits' => 'required|string|in:Individual,Family,Community / Group,Organisation / Institution',
            'number_of_beneficiaries' => 'nullable|integer|min:1',
            'received_aid_before' => 'required|boolean',
            'received_aid_before_details' => 'nullable|string',
            'supporting_files' => 'nullable|array',
            'supporting_files.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,zip,ppt,pptx|max:20480',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'declaration_confirmed' => 'required|accepted',
        ]);

        if ($request->hasFile('supporting_files')) {
            $filePaths = [];
            foreach ($request->file('supporting_files') as $file) {
                $filePaths[] = $file->store('documents', 'public');
            }
            $validated['supporting_documents'] = $filePaths;
        }

        \App\Models\CommunityAidSubmission::create($validated);

        $this->sendFormSubmissionEmails('Community Aid & Assistance Request', $validated);

        return view('welfare.pages.form_success', [
            'title' => 'Request Submitted Successfully',
            'message' => 'Your request for MUKMIN Community Aid & Assistance has been received. Our welfare department will review your details and contact you or your emergency contact if additional verification is required.',
        ]);
    }
}
