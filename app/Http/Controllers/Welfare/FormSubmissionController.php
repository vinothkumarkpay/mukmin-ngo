<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\FormDropdownOption;
use App\Models\FeedbackSubmission;
use App\Models\OrdinaryMemberSubmission;
use App\Models\FriendMemberSubmission;
use App\Models\MentorSubmission;
use App\Models\PartnerSubmission;
use App\Models\VolunteerSubmission;
use App\Models\ContactSubmission;
use App\Models\MflsScholarshipSubmission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormSubmissionMail;
use App\Mail\MflsRequirementsInquiryMail;
use App\Services\Welfare\MflsPartnerDocumentService;
use App\Support\SubmissionStatus;

class FormSubmissionController extends Controller
{
    /**
     * Send acknowledgement to the applicant (when email provided) and a copy to the team inbox.
     * Each recipient is mailed independently so one failure does not block the other.
     */
    private function sendFormSubmissionEmails(
        string $formName,
        array $validated,
        ?string $applicantEmail = null,
        ?string $applicantName = null,
        ?string $formKey = null
    ): void
    {
        $applicantEmail = $applicantEmail ?: $this->resolveApplicantEmail($validated);
        $applicantName = $applicantName ?: $this->resolveApplicantName($validated);

        if ($applicantEmail) {
            try {
                Mail::to($applicantEmail)->send(new FormSubmissionMail($formName, $validated, false, $applicantName));
            } catch (\Throwable $e) {
                Log::error("Mail to applicant failed for {$formName}", [
                    'email' => $applicantEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $teamEmail = $formKey
            ? config("welfare.form_submission_recipients.{$formKey}", config('welfare.email'))
            : config('welfare.email');

        try {
            Mail::to($teamEmail)
                ->send(new FormSubmissionMail($formName, $validated, true));
        } catch (\Throwable $e) {
            Log::error("Mail to team inbox failed for {$formName}", [
                'email' => $teamEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function resolveApplicantEmail(array $validated): ?string
    {
        foreach (['email', 'ind_email', 'org_email'] as $field) {
            if (!empty($validated[$field]) && is_string($validated[$field])) {
                return $validated[$field];
            }
        }

        return null;
    }

    private function resolveApplicantName(array $validated): ?string
    {
        foreach ([
            'name',
            'full_name',
            'ind_name',
            'contact_person',
            'name_of_organisation',
            'org_name',
            'company_name',
        ] as $field) {
            if (!empty($validated[$field]) && is_string($validated[$field])) {
                return $validated[$field];
            }
        }

        return null;
    }

    private function getOptions($type)
    {
        return FormDropdownOption::where('form_type', $type)
            ->orderBy('sort_order', 'asc')
            ->orderBy('option_value', 'asc')
            ->pluck('option_value')
            ->toArray();
    }

    private function requiredEmailRule(): string
    {
        return app()->environment('testing') 
            ? 'required|email|max:255' 
            : 'required|email:rfc,dns|max:255';
    }

    private function nullableEmailRule(): string
    {
        return app()->environment('testing') 
            ? 'nullable|email|max:255' 
            : 'nullable|email:rfc,dns|max:255';
    }

    private function requiredPhoneRule(): string
    {
        return 'required|regex:/^\+?[0-9][0-9\s\-()]{7,19}$/';
    }

    private function nullablePhoneRule(): string
    {
        return 'nullable|regex:/^\+?[0-9][0-9\s\-()]{7,19}$/';
    }

    private function requiredNricRule(): array
    {
        return ['required', 'regex:/^\d{1,12}$/'];
    }

    private function malaysianNricRule(): array
    {
        return ['required', 'digits:12'];
    }

    private function malaysianPhoneRule(): string
    {
        return 'required|regex:/^(\+?6?01)[0-9][0-9\s\-()]{7,11}$/';
    }

    private function wordCountBetweenRule(int $min, int $max): \Closure
    {
        return function (string $attribute, $value, \Closure $fail) use ($min, $max) {
            $count = str_word_count(strip_tags((string) $value));
            if ($count < $min || $count > $max) {
                $label = str_replace('_', ' ', $attribute);
                $fail("The {$label} must be between {$min} and {$max} words.");
            }
        };
    }

    private function requiredNricOrPassportRule(): array
    {
        return ['required', 'regex:/^(?:\d{1,12}|(?=.*[A-Za-z])[A-Za-z0-9]{6,20})$/'];
    }

    private function organisationNameRules(): array
    {
        return ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s]+$/u'];
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
            'nric_number' => $this->requiredNricRule(),
            'organisation' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'state_residency' => 'required|string|max:50',
            'full_address' => 'required|string',
            'email' => $this->requiredEmailRule(),
            'contact_number' => $this->requiredPhoneRule(),
            'categories' => 'required|array|min:1',
            'other_category' => 'nullable|string|max:255',
            'suggestion_description' => 'required|string',
            'benefits_description' => 'required|string',
            'contact_consent' => 'required|boolean',
            'preferred_contact_methods' => 'nullable|array',
            'declaration_confirmed' => 'required|accepted',
        ]);

        $this->applyDefaultSubmissionStatus($validated);
        FeedbackSubmission::create($validated);

        $this->sendFormSubmissionEmails('Feedback & Suggestion', $validated, $validated['email'], null, 'feedback-suggestion');

        return view('welfare.pages.form_success', [
            'title' => 'Thank you for sharing your feedback with MUKMIN.',
            'message' => 'Your submission has been successfully received and will be reviewed by our team accordingly.',
        ]);
    }

    public function membershipOrdinary()
    {
        $orgTypes = $this->getOptions('ordinary_org_type');
        $activities = $this->getOptions('ordinary_activity');
        $states = $this->malaysianStateOptions();
        $salutations = $this->officeBearerSalutationOptions();

        return view('welfare.pages.membership_ordinary', compact('orgTypes', 'activities', 'states', 'salutations'));
    }

    public function submitOrdinary(Request $request)
    {
        $states = $this->malaysianStateOptions();
        $salutations = $this->officeBearerSalutationOptions();

        $validated = $request->validate([
            'name_of_organisation' => $this->organisationNameRules(),
            'org_reg_number' => 'required|string|max:50',
            'org_reg_date' => 'required|date',
            'registered_state' => ['required', 'string', Rule::in($states)],
            'full_address' => 'required|string',
            'state' => ['required', 'string', Rule::in($states)],
            'postcode' => 'required|string|max:10',
            'district_city' => 'required|string|max:100',
            'year_established' => 'required|integer|min:1800|max:' . date('Y'),
            'total_members_size' => 'required|integer|min:0',
            'email' => $this->requiredEmailRule(),
            'contact_number' => $this->requiredPhoneRule(),
            'website' => 'nullable|string|max:255',
            'org_type' => 'required|string|max:255',
            'org_type_other' => 'nullable|string|max:255',
            'primary_activities' => 'required|array|min:1',
            'primary_activities_other' => 'nullable|string|max:255',
            'key_office_bearers' => 'required|array',
            'key_office_bearers.president.salutation' => ['required', 'string', Rule::in($salutations)],
            'key_office_bearers.president.name' => 'required|string|max:255',
            'key_office_bearers.president.nric' => $this->malaysianNricRule(),
            'key_office_bearers.president.email' => $this->requiredEmailRule(),
            'key_office_bearers.president.phone' => $this->requiredPhoneRule(),
            'key_office_bearers.secretary.salutation' => ['nullable', 'string', Rule::in($salutations)],
            'key_office_bearers.secretary.name' => 'nullable|string|max:255',
            'key_office_bearers.secretary.nric' => ['nullable', 'digits:12'],
            'key_office_bearers.secretary.email' => $this->nullableEmailRule(),
            'key_office_bearers.secretary.phone' => $this->nullablePhoneRule(),
            'key_office_bearers.treasurer.salutation' => ['nullable', 'string', Rule::in($salutations)],
            'key_office_bearers.treasurer.name' => 'nullable|string|max:255',
            'key_office_bearers.treasurer.nric' => ['nullable', 'digits:12'],
            'key_office_bearers.treasurer.email' => $this->nullableEmailRule(),
            'key_office_bearers.treasurer.phone' => $this->nullablePhoneRule(),
            'declaration_confirmed' => 'required|accepted',
        ], [
            'name_of_organisation.regex' => 'Organisation name may only contain letters and spaces (no numbers).',
        ]);

        $validated['is_registered_ros'] = false;
        $validated['registration_certificate'] = null;
        $validated['committee_members'] = null;
        $validated['org_type'] = [$validated['org_type']];

        $this->applyDefaultSubmissionStatus($validated);
        OrdinaryMemberSubmission::create($validated);

        $this->sendFormSubmissionEmails('Ordinary Member Registration', $validated, $validated['email'], null, 'membership-ordinary');

        return view('welfare.pages.form_success', [
            'title' => 'Application Submitted Successfully',
            'message' => "Thank you for your interest in joining the MUKMIN ecosystem as an Ordinary Member. Your registration has been successfully received and will undergo review by the Central Executive Committee.\nOur team will contact you should further information or clarification be required during the evaluation process.",
        ]);
    }

    public function membershipFriends()
    {
        $categories = $this->friendsCategoryOptions();
        $areaOfInterestOptions = $this->friendsAreaOfInterestOptions();
        $professionOptions = $this->friendsProfessionOptions();
        $states = $this->malaysianStateOptions();
        $salutations = $this->officeBearerSalutationOptions();

        return view('welfare.pages.membership_friends', compact(
            'categories',
            'areaOfInterestOptions',
            'professionOptions',
            'states',
            'salutations'
        ));
    }

    private function friendsAreaOfInterestOptions(): array
    {
        return [
            'Economic Empowerment',
            'Education and Talent Development',
            'Leadership and Representation',
            'Community Welfare',
            'Faith, Identity and Ukhwah',
        ];
    }

    private function friendsProfessionOptions(): array
    {
        return [
            'Student',
            'Academic / Educator',
            'Doctor / Medical Professional',
            'Lawyer / Legal Professional',
            'Accountant / Finance Professional',
            'Engineer / Technical Professional',
            'IT / Technology Professional',
            'Business Owner / Entrepreneur',
            'Corporate Executive / Management',
            'Civil Servant / Government Officer',
            'NGO / Community Leader',
            'Shariah / Islamic Affairs',
            'Creative / Media / Communications',
            'Self-Employed / Freelancer',
            'Retired',
            'Homemaker',
            'Other (Please Specify)',
        ];
    }

    public function submitFriends(Request $request)
    {
        $states = $this->malaysianStateOptions();
        $salutations = $this->officeBearerSalutationOptions();

        $rules = [
            'entity_type' => ['required', 'string', Rule::in($this->friendsCategoryOptions())],
            'others_specify' => 'nullable|string|max:255',
            'declaration_confirmed' => 'required|accepted',
        ];

        if ($request->input('entity_type') === 'Individual') {
            $rules = array_merge($rules, [
                'ind_salutation' => ['required', 'string', Rule::in($salutations)],
                'ind_name' => 'required|string|max:255',
                'ind_nric' => $this->malaysianNricRule(),
                'ind_state' => ['required', 'string', Rule::in($states)],
                'ind_postcode' => 'required|string|max:10',
                'ind_profession' => ['required', 'string', Rule::in($this->friendsProfessionOptions())],
                'ind_profession_other' => 'nullable|required_if:ind_profession,Other (Please Specify)|string|max:255',
                'ind_address' => 'required|string',
                'ind_email' => $this->requiredEmailRule(),
                'ind_phone' => $this->requiredPhoneRule(),
                'ind_area_of_interest' => ['required', 'string', Rule::in($this->friendsAreaOfInterestOptions())],
            ]);
        } else {
            $rules = array_merge($rules, [
                'org_name' => $this->organisationNameRules(),
                'org_state' => ['required', 'string', Rule::in($states)],
                'org_postcode' => 'required|string|max:10',
                'org_address' => 'required|string',
                'org_email' => $this->requiredEmailRule(),
                'org_phone' => $this->requiredPhoneRule(),
                'org_contact_person_salutation' => ['required', 'string', Rule::in($salutations)],
                'org_contact_person_name' => 'required|string|max:255',
                'org_contact_person_nric' => $this->malaysianNricRule(),
                'org_website' => 'nullable|string|max:255',
            ]);
        }

        $validated = $request->validate($rules, [
            'org_name.regex' => 'Organisation name may only contain letters and spaces (no numbers).',
        ]);

        $this->applyDefaultSubmissionStatus($validated);
        FriendMemberSubmission::create($validated);

        $email = $validated['entity_type'] === 'Individual' ? ($validated['ind_email'] ?? null) : ($validated['org_email'] ?? null);
        $this->sendFormSubmissionEmails('Friend of MUKMIN Registration', $validated, $email ?: null, null, 'membership-friends');

        return view('welfare.pages.form_success', [
            'title' => 'Your submission has been successfully received.',
            'message' => 'Our team will review the information provided and reach out to you.',
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
            'nric_passport' => $this->requiredNricOrPassportRule(),
            'gender' => 'required|string|in:Male,Female',
            'occupation' => 'required|string|max:255',
            'organisation' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'state_residency' => 'required|string|max:50',
            'full_address' => 'required|string',
            'email' => $this->requiredEmailRule(),
            'contact_number' => $this->requiredPhoneRule(),
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

        $this->applyDefaultSubmissionStatus($validated);
        MentorSubmission::create($validated);

        $this->sendFormSubmissionEmails('Mentor Registration', $validated, $validated['email'], null, 'mentor-registration');

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
            'email' => $this->requiredEmailRule(),
            'contact_number' => $this->requiredPhoneRule(),
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

        $this->applyDefaultSubmissionStatus($validated);
        PartnerSubmission::create($validated);

        $this->sendFormSubmissionEmails('Partnership & Collaboration Proposal', $validated, $validated['email'], null, 'partnership-collaboration');

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
            'nric_passport' => $this->requiredNricOrPassportRule(),
            'gender' => 'required|string|in:Male,Female',
            'occupation_study' => 'required|string|max:255',
            'organisation' => 'nullable|string|max:255',
            'state_residency' => 'required|string|max:50',
            'full_address' => 'required|string',
            'email' => $this->requiredEmailRule(),
            'contact_number' => $this->requiredPhoneRule(),
            'interest_areas' => 'required|array|min:1',
            'interest_other' => 'nullable|string|max:255',
            'skills_expertise' => 'required|string',
            'preferred_mode' => 'required|string|in:Physical / On-Ground,Virtual / Remote,Both',
            'availability' => 'required|array|min:1',
            'has_volunteered_before' => 'required|boolean',
            'volunteered_before_details' => 'nullable|string',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_phone' => $this->requiredPhoneRule(),
            'declaration_confirmed' => 'required|accepted',
        ]);

        $this->applyDefaultSubmissionStatus($validated);
        VolunteerSubmission::create($validated);

        $this->sendFormSubmissionEmails('Volunteer Registration', $validated, $validated['email'], null, 'volunteer-registration');

        return view('welfare.pages.form_success', [
            'title' => 'Thank You For Stepping Forward To Serve',
            'message' => 'Your willingness to volunteer reflects the spirit of compassion, unity and collective responsibility that drives the MUKMIN ecosystem. We look forward to engaging you in future initiatives and community impact programmes.',
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => $this->requiredEmailRule(),
            'phone' => $this->requiredPhoneRule(),
            'message' => 'required|string',
        ]);

        $this->applyDefaultSubmissionStatus($validated);
        ContactSubmission::create($validated);
        $this->sendFormSubmissionEmails('Contact Us', $validated, $validated['email'], $validated['name'], 'contact');

        return view('welfare.pages.form_success', [
            'title' => 'Message Sent Successfully',
            'message' => 'Thank you for reaching out to us! Your message has been received, and our team will get in touch with you shortly.',
        ]);
    }

    public function submitDonate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => $this->requiredEmailRule(),
            'amount' => 'nullable|numeric|min:1',
            'custom_amount' => 'nullable|numeric|min:1',
        ]);

        $this->sendFormSubmissionEmails('Donation Enquiry', $validated, $validated['email'], $validated['name']);

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
            'nric_passport' => $this->requiredNricOrPassportRule(),
            'gender' => 'required|string|in:Male,Female',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'monthly_income' => 'nullable|string|max:255',
            'contact_number' => $this->requiredPhoneRule(),
            'email' => $this->requiredEmailRule(),
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
            'emergency_contact_phone' => $this->requiredPhoneRule(),
            'declaration_confirmed' => 'required|accepted',
        ]);

        if ($request->hasFile('supporting_files')) {
            $filePaths = [];
            foreach ($request->file('supporting_files') as $file) {
                $filePaths[] = $file->store('documents', 'public');
            }
            $validated['supporting_documents'] = $filePaths;
        }

        $this->applyDefaultSubmissionStatus($validated);
        \App\Models\CommunityAidSubmission::create($validated);

        $this->sendFormSubmissionEmails('Community Aid & Assistance Request', $validated, $validated['email'], $validated['full_name'], 'community-aid');

        return view('welfare.pages.form_success', [
            'title' => 'Request Submitted Successfully',
            'message' => 'Your request for MUKMIN Community Aid & Assistance has been received. Our welfare department will review your details and contact you or your emergency contact if additional verification is required.',
        ]);
    }

    public function mflsScholarship(Request $request, MflsPartnerDocumentService $mflsPartners)
    {
        $partnerId = $request->query('partner');
        $selectedPartner = $partnerId && $mflsPartners->isValidPartnerId($partnerId)
            ? $mflsPartners->findPartner($partnerId)
            : null;
        $states = $this->malaysianStateOptions();

        return view('welfare.pages.mfls_scholarship', compact('selectedPartner', 'states'));
    }

    public function mflsProgrammeRequirements(Request $request, MflsPartnerDocumentService $mflsPartners)
    {
        $validated = $request->validate([
            'partner' => ['required', 'string', function (string $attribute, $value, \Closure $fail) use ($mflsPartners) {
                if (!$mflsPartners->isValidPartnerId((string) $value)) {
                    $fail('Please select a valid partner institution.');
                }
            }],
            'programme' => 'required|string|min:2|max:255',
        ]);

        $partner = $mflsPartners->findPartner($validated['partner']);
        $partnerProgrammes = $mflsPartners->partnerProgrammes($partner);
        if (!in_array($validated['programme'], $partnerProgrammes, true)) {
            return response()->json([
                'message' => 'Please select a valid programme for this institution.',
            ], 422);
        }

        $requirements = $mflsPartners->findProgrammeRequirements(
            $validated['partner'],
            $validated['programme']
        );

        if ($requirements === null) {
            return response()->json([
                'found' => false,
                'partner_name' => $partner['name'],
                'programme' => $validated['programme'],
                'message' => 'Detailed requirements for this programme are not available right now. You may continue with your application.',
            ]);
        }

        return response()->json([
            'found' => true,
            'partner_name' => $partner['name'],
            'programme' => $validated['programme'],
            'matched_programme' => $requirements['programme'],
            'venue' => $requirements['venue'],
            'course_fee' => $requirements['course_fee'],
            'scholarship_coverage' => $requirements['scholarship_coverage'],
            'waived_amount' => $requirements['waived_amount'],
            'exclusions' => $requirements['exclusions'],
            'academic_requirements' => $requirements['academic_requirements'],
            'financial_requirement' => $requirements['financial_requirement'],
            'academic_requirements_b40' => $requirements['academic_requirements_b40'],
            'academic_requirements_merit' => $requirements['academic_requirements_merit'],
        ]);
    }

    public function submitMflsRequirementsInquiry(Request $request, MflsPartnerDocumentService $mflsPartners)
    {
        $partner = $mflsPartners->findPartner((string) $request->input('partner_id', ''));
        $partnerProgrammes = $partner ? $mflsPartners->partnerProgrammes($partner) : [];

        $validated = $request->validate([
            'partner_id' => ['required', 'string', function (string $attribute, $value, \Closure $fail) use ($mflsPartners) {
                if (!$mflsPartners->isValidPartnerId((string) $value)) {
                    $fail('Please select a valid partner institution.');
                }
            }],
            'programme' => ['required', 'string', 'min:2', 'max:255', Rule::in($partnerProgrammes)],
            'email' => $this->requiredEmailRule(),
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'programme.in' => 'Please select a valid programme for the chosen institution.',
        ]);

        $redirectUrl = route('welfare.impact.mfls');
        $partnerName = $partner['name'];
        $programmeName = $validated['programme'];
        $applicantEmail = $validated['email'];

        try {
            Mail::to($applicantEmail)->send(new MflsRequirementsInquiryMail(
                $applicantEmail,
                $partnerName,
                $programmeName,
                $redirectUrl,
                false
            ));
        } catch (\Throwable $e) {
            Log::error('Mail to applicant failed for MFLS requirements inquiry', [
                'email' => $applicantEmail,
                'error' => $e->getMessage(),
            ]);
        }

        $teamEmail = config('welfare.form_submission_recipients.mfls-scholarship', config('welfare.email'));
        try {
            Mail::to($teamEmail)->send(new MflsRequirementsInquiryMail(
                $applicantEmail,
                $partnerName,
                $programmeName,
                $redirectUrl,
                true
            ));
        } catch (\Throwable $e) {
            Log::error('Mail to team inbox failed for MFLS requirements inquiry', [
                'email' => $teamEmail,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Thank you. We have emailed you next steps. You will now be redirected to the scholarship page.',
            'redirect_url' => $redirectUrl,
        ]);
    }

    public function submitMflsScholarship(Request $request, MflsPartnerDocumentService $mflsPartners)
    {
        $partner = $mflsPartners->findPartner((string) $request->input('partner_id', ''));
        $partnerProgrammes = $partner ? $mflsPartners->partnerProgrammes($partner) : [];

        $states = $this->malaysianStateOptions();

        $validated = $request->validate([
            'partner_id' => ['required', 'string', function (string $attribute, $value, \Closure $fail) use ($mflsPartners) {
                if (!$mflsPartners->isValidPartnerId((string) $value)) {
                    $fail('Please select a valid partner institution from the MFLS page.');
                }
            }],
            'email' => $this->requiredEmailRule(),
            'full_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\'\-\.@]+$/u'],
            'nric_passport' => $this->malaysianNricRule(),
            'dob' => 'required|date|before_or_equal:today|after:1950-01-01',
            'gender' => 'required|string|in:Male,Female',
            'age' => 'required|integer|min:15|max:60',
            'citizenship' => 'required|string|in:Malaysian,Permanent Resident',
            'marital_status' => 'required|string|in:Single,Married',
            'contact_number' => $this->malaysianPhoneRule(),
            'full_address' => 'required|string|min:10|max:1000',
            'state' => ['required', 'string', Rule::in($states)],
            'postcode' => 'required|string|max:10',
            'current_qualification' => 'required|string|in:SPM,STPM,IGCSE,Foundation,Diploma,Degree',
            'institution_name' => 'required|string|min:2|max:255',
            'current_cgpa_result' => 'required|string|min:1|max:255',
            'academic_transcript' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
            'programme_course_applied' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::in($partnerProgrammes),
            ],
            'household_income' => ['required', 'string', Rule::in(['Below RM 2,000', 'RM 2,001 to RM 5,000'])],
            'father_guardian_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\'\-\.@]+$/u'],
            'father_guardian_occupation' => 'required|string|min:2|max:255',
            'mother_guardian_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\'\-\.@]+$/u'],
            'mother_guardian_occupation' => 'required|string|min:2|max:255',
            'proof_of_income' => 'required|array|min:1|max:10',
            'proof_of_income.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
            'government_assistance_status' => [
                'required',
                'string',
                Rule::in([
                    'Sumbangan Tunai Rahmah (STR)',
                    'Bantuan Sara Hidup (BSH)',
                    'Sumbangan Asas Rahmah (SARA)',
                    'Zakat / Baitulmal Assistance Recipient',
                ]),
            ],
            'proof_of_government_assistance' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
            'number_of_dependents' => 'required|integer|min:0|max:20',
            'other_scholarship_details' => 'required|string|min:2|max:2000',
            'leadership_experience_statement' => ['required', 'string', 'max:5000', $this->wordCountBetweenRule(150, 200)],
            'scholar_selection_statement' => ['required', 'string', 'max:5000', $this->wordCountBetweenRule(150, 200)],
            'declaration_confirmed' => 'required|accepted',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'full_name.required' => 'Please enter your full name.',
            'full_name.regex' => 'Full name may only contain letters, spaces, and common punctuation.',
            'nric_passport.required' => 'Please enter your NRIC number.',
            'nric_passport.digits' => 'NRIC must be exactly 12 digits without dashes (e.g. 900101145555).',
            'dob.required' => 'Please enter your date of birth.',
            'dob.before_or_equal' => 'Date of birth cannot be in the future.',
            'dob.after' => 'Please enter a valid date of birth.',
            'contact_number.required' => 'Please enter your phone number.',
            'contact_number.regex' => 'Please enter a valid Malaysian mobile number.',
            'citizenship.in' => 'The MFLS Scholarship is open to Malaysian citizens and Permanent Residents only.',
            'full_address.min' => 'Please enter your complete residential address.',
            'academic_transcript.required' => 'Please upload your academic certificate/transcript.',
            'academic_transcript.mimes' => 'Academic certificate/transcript must be a PDF, JPG, PNG, DOC, or DOCX file.',
            'academic_transcript.max' => 'Academic certificate/transcript must not exceed 20MB.',
            'proof_of_income.required' => 'Please upload proof of income.',
            'proof_of_income.min' => 'Please upload at least one proof of income file.',
            'proof_of_income.*.mimes' => 'Proof of income must be a PDF, JPG, PNG, DOC, or DOCX file.',
            'proof_of_income.*.max' => 'Each proof of income file must not exceed 20MB.',
            'government_assistance_status.required' => 'Please select your government assistance / welfare status.',
            'government_assistance_status.in' => 'Please select a valid government assistance / welfare status.',
            'proof_of_government_assistance.required' => 'Please upload proof of government assistance / welfare.',
            'proof_of_government_assistance.mimes' => 'Proof of government assistance must be a PDF, JPG, PNG, DOC, or DOCX file.',
            'proof_of_government_assistance.max' => 'Proof of government assistance must not exceed 20MB.',
            'programme_course_applied.in' => 'Please select a valid programme for the chosen institution.',
            'declaration_confirmed.accepted' => 'You must agree to the declaration before submitting.',
        ]);

        $fileFields = [
            'academic_transcript',
            'proof_of_government_assistance',
        ];

        unset($validated['partner_id']);

        $validated['partner_institution_id'] = $partner['id'];
        $validated['partner_institution_name'] = $partner['name'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('documents', 'public');
            }
        }

        if ($request->hasFile('proof_of_income')) {
            $incomePaths = [];
            foreach ($request->file('proof_of_income') as $file) {
                $incomePaths[] = $file->store('documents', 'public');
            }
            $validated['proof_of_income'] = $incomePaths;
        }

        $this->applyDefaultSubmissionStatus($validated);
        MflsScholarshipSubmission::create($validated);

        $this->sendFormSubmissionEmails(
            'MFLS Scholarship Application',
            $validated,
            $validated['email'],
            $validated['full_name'],
            'mfls-scholarship'
        );

        return view('welfare.pages.form_success', [
            'title' => 'Application Submitted Successfully',
            'message' => 'Your MFLS Scholarship Application has been received. The MFLS Secretariat will review your application and supporting documents within 3–5 working days. You will be contacted via email or phone regarding the next steps.',
        ]);
    }

    private function friendsCategoryOptions(): array
    {
        return [
            'Individual',
            'Non-registered NGO',
            'Non-registered Surau',
            'Non-registered Madrasah',
            'Others',
        ];
    }

    private function malaysianStateOptions(): array
    {
        return [
            'Johor',
            'Kedah',
            'Kelantan',
            'Melaka',
            'Negeri Sembilan',
            'Pahang',
            'Perak',
            'Perlis',
            'Pulau Pinang',
            'Sabah',
            'Sarawak',
            'Selangor',
            'Terengganu',
            'Wilayah Persekutuan Kuala Lumpur',
            'Wilayah Persekutuan Labuan',
            'Wilayah Persekutuan Putrajaya',
        ];
    }

    private function officeBearerSalutationOptions(): array
    {
        return [
            'Tan Sri',
            'Puan Sri',
            'Datuk Seri',
            "Dato' Seri",
            'Datin Seri',
            "Dato' Sri",
            'Datin Sri',
            'Datuk',
            'Datin',
            "Dato'",
            'Dr.',
            'Mr.',
            'Mrs.',
            'Ms.',
        ];
    }

    private function applyDefaultSubmissionStatus(array &$validated): void
    {
        $validated['status'] = SubmissionStatus::default();
    }
}
