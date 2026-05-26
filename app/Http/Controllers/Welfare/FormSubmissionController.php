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
use Illuminate\Support\Facades\Mail;
use App\Mail\FormSubmissionMail;

class FormSubmissionController extends Controller
{
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

        try {
            Mail::to($validated['email'])->send(new FormSubmissionMail('Feedback & Suggestion', $validated));
            Mail::to('support@mukmin.org')->send(new FormSubmissionMail('Feedback & Suggestion', $validated, true));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed for Feedback & Suggestion: ' . $e->getMessage());
        }

        return view('welfare.pages.form_success', [
            'title' => 'Feedback Received',
            'message' => 'Thank you for your valuable feedback. Your suggestion has been recorded and will help strengthen the MUKMIN ecosystem.',
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
            'committee_members' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'key_office_bearers' => 'required|array',
            'key_office_bearers.president.name' => 'required|string|max:255',
            'key_office_bearers.president.email' => 'required|email|max:255',
            'key_office_bearers.president.phone' => 'required|string|max:20',
            'key_office_bearers.secretary.name' => 'required|string|max:255',
            'key_office_bearers.secretary.email' => 'required|email|max:255',
            'key_office_bearers.secretary.phone' => 'required|string|max:20',
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

        try {
            Mail::to($validated['email'])->send(new FormSubmissionMail('Ordinary Member Registration', $validated));
            Mail::to('support@mukmin.org')->send(new FormSubmissionMail('Ordinary Member Registration', $validated, true));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed for Ordinary Member Registration: ' . $e->getMessage());
        }

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
        if ($email) {
            try {
                Mail::to($email)->send(new FormSubmissionMail('Friend of MUKMIN Registration', $validated));
                Mail::to('support@mukmin.org')->send(new FormSubmissionMail('Friend of MUKMIN Registration', $validated, true));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mail sending failed for Friend of MUKMIN: ' . $e->getMessage());
            }
        }

        return view('welfare.pages.form_success', [
            'title' => 'Registration Completed',
            'message' => 'Thank you for registering as a Friend of MUKMIN! Your request is received and you are now part of our community network.',
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

        try {
            Mail::to($validated['email'])->send(new FormSubmissionMail('Mentor Registration', $validated));
            Mail::to('support@mukmin.org')->send(new FormSubmissionMail('Mentor Registration', $validated, true));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed for Mentor Registration: ' . $e->getMessage());
        }

        return view('welfare.pages.form_success', [
            'title' => 'Registration Submitted',
            'message' => 'Your application to become a MUKMIN Mentor has been received. Our team will review your professional background and get in touch to discuss onboarding.',
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

        try {
            Mail::to($validated['email'])->send(new FormSubmissionMail('Partnership & Collaboration Proposal', $validated));
            Mail::to('support@mukmin.org')->send(new FormSubmissionMail('Partnership & Collaboration Proposal', $validated, true));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed for Partnership & Collaboration: ' . $e->getMessage());
        }

        return view('welfare.pages.form_success', [
            'title' => 'Proposal Submitted',
            'message' => 'Thank you for your partnership proposal. MUKMIN values strategic collaborations, and our leadership team will review your proposal and initiate a discussion soon.',
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

        try {
            Mail::to($validated['email'])->send(new FormSubmissionMail('Volunteer Registration', $validated));
            Mail::to('support@mukmin.org')->send(new FormSubmissionMail('Volunteer Registration', $validated, true));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed for Volunteer Registration: ' . $e->getMessage());
        }

        return view('welfare.pages.form_success', [
            'title' => 'Volunteer Registered',
            'message' => 'Thank you for registering to volunteer! We appreciate your willingness to serve. We will notify you when volunteer opportunities matching your interests arise.',
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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

        try {
            Mail::to('support@mukmin.org')->send(new FormSubmissionMail('Community Aid & Assistance Request', $validated, true));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed for Community Aid Request: ' . $e->getMessage());
        }

        return view('welfare.pages.form_success', [
            'title' => 'Request Submitted Successfully',
            'message' => 'Your request for MUKMIN Community Aid & Assistance has been received. Our welfare department will review your details and contact you or your emergency contact if additional verification is required.',
        ]);
    }
}
