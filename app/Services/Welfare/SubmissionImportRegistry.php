<?php

namespace App\Services\Welfare;

use App\Models\CommunityAidSubmission;
use App\Models\ContactSubmission;
use App\Models\FeedbackSubmission;
use App\Models\FriendMemberSubmission;
use App\Models\MentorSubmission;
use App\Models\MflsScholarshipSubmission;
use App\Models\OrdinaryMemberSubmission;
use App\Models\PartnerSubmission;
use App\Models\VolunteerSubmission;
use App\Support\SubmissionStatus;
use InvalidArgumentException;

class SubmissionImportRegistry
{
    public const TYPES = [
        'feedback',
        'ordinary',
        'friends',
        'mentor',
        'partner',
        'volunteer',
        'contact',
        'aid',
        'mfls',
    ];

    private string $type;

    public function __construct(string $type)
    {
        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException("Unsupported import type: {$type}");
        }

        $this->type = $type;
    }

    public static function for(string $type): self
    {
        return new self($type);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function modelClass(): string
    {
        return [
            'feedback' => FeedbackSubmission::class,
            'ordinary' => OrdinaryMemberSubmission::class,
            'friends' => FriendMemberSubmission::class,
            'mentor' => MentorSubmission::class,
            'partner' => PartnerSubmission::class,
            'volunteer' => VolunteerSubmission::class,
            'contact' => ContactSubmission::class,
            'aid' => CommunityAidSubmission::class,
            'mfls' => MflsScholarshipSubmission::class,
        ][$this->type];
    }

    public function hasStatus(): bool
    {
        return true;
    }

    /** @return array<string, array{label: string, required?: bool, hint?: string}> */
    public function columns(): array
    {
        return $this->definitions()[$this->type]['columns'];
    }

    /** @return list<string> */
    public function instructions(): array
    {
        $lines = [
            'MUKMIN Admin Import Template',
            'Form: ' . $this->definitions()[$this->type]['title'],
            '',
            '1. Fill in one row per record on the Data sheet.',
            '2. Do not change the header row.',
            '3. Delete the sample row before uploading (or replace it with real data).',
            '4. For multi-select fields, separate values with commas.',
            '5. For Yes/No fields use Yes or No.',
            '6. Dates must use YYYY-MM-DD format.',
        ];

        if ($this->hasStatus()) {
            $lines[] = '7. All imported records are saved with status: Received / New.';
        }

        $lines[] = '';
        $lines[] = 'Column reference:';

        foreach ($this->columns() as $key => $column) {
            $required = !empty($column['required']) ? ' (required)' : ' (optional)';
            $hint = !empty($column['hint']) ? ' — ' . $column['hint'] : '';
            $lines[] = $column['label'] . $required . $hint;
        }

        return $lines;
    }

    /** @return list<string|null> */
    public function exampleRow(): array
    {
        $examples = [
            'feedback' => [
                'Ahmad bin Ali', '900101011234', 'ABC Organisation', 'Manager', 'Selangor',
                'No 1, Jalan Example, Puchong', 'ahmad@example.com', '+60123456789',
                'Programme Improvement', '', 'Sample suggestion text', 'Sample benefits text',
                'Yes', 'Email, WhatsApp',
            ],
            'ordinary' => [
                'Persatuan Contoh', 'PPM-000-00-0000000', '2020-01-15', 'Selangor',
                'No 1, Jalan Example', '47100', 'Puchong', '2020', '150',
                'org@example.com', '+60123456789', 'https://example.org',
                'NGO / Non-Profit Organisation', '', 'Community Development, Education',
                '', 'No',
                'Ali bin Abu', 'ali@example.com', '+60111111111',
                'Siti binti Omar', 'siti@example.com', '+60222222222',
                'Hassan bin Yusof', 'hassan@example.com', '+60333333333',
            ],
            'friends' => [
                'Individual', '', '', '', '', '', '', '', '',
                'Ahmad bin Ali', '900101011234', 'Selangor', 'Civil Servant / Government Officer', '',
                'No 1, Jalan Example', 'ahmad@example.com', '+60123456789', 'Education and Talent Development',
            ],
            'mentor' => [
                'Dr. Fatimah', '800101011234', 'Female', 'Consultant', 'Example Sdn Bhd',
                'Director', '15', 'Selangor', 'No 1, Jalan Example', 'fatimah@example.com',
                '+60123456789', 'https://linkedin.com/in/example',
                'Leadership Development, Entrepreneurship', '', 'Virtual Mentoring, Workshop / Talk',
                'Monthly (1–2 hours)', 'Sample experience description', 'No', '',
            ],
            'partner' => [
                'Example Corp', 'Ali bin Abu', 'CEO', '123456-A', 'partner@example.com',
                '+60123456789', 'No 1, Jalan Example', 'Selangor, Malaysia',
                'Corporate / Private Sector', '', 'Community Programmes, Education & Youth',
                '', 'Strategic Partner', '', 'Sample proposal', 'Sample expected outcomes',
                'No', '',
            ],
            'volunteer' => [
                'Ahmad bin Ali', '900101011234', 'Male', 'Student', 'University Example',
                'Selangor', 'No 1, Jalan Example', 'ahmad@example.com', '+60123456789',
                'Community Outreach, Event Support', '', 'Communication, event planning',
                'Both', 'Weekends, Public Holidays', 'No', '',
                'Siti binti Omar', 'Mother', '+60198765432',
            ],
            'contact' => [
                'Ahmad bin Ali', 'ahmad@example.com', '+60123456789', 'Sample enquiry message',
            ],
            'aid' => [
                'Ahmad bin Ali', '900101011234', 'Male', '1990-01-01', 'Malaysian',
                'Driver', 'RM 2000', '+60123456789', 'ahmad@example.com',
                'No 1, Jalan Example', 'Selangor', 'Financial Assistance, Food Aid', '',
                // Education fields (optional — leave blank for non-education aid)
                '', '', '', '', '', '', '', '', '', '', '',
                '', '', '', '', '', '', '', '', '', '',
                '', '', '', '', '', '', '', '', '',
                'Sample situation description', 'Family', '4', 'No', '',
                'Siti binti Omar', 'Spouse', '+60198765432',
            ],
            'mfls' => [
                'student@example.com', 'Ahmad bin Ali', '900101011234', '2000-01-01', 'Male',
                'Single', '', '+60123456789', 'No 1, Jalan Example',
                'SPM', 'SMK Example', '3.50', 'Foundation in Science',
                'Below RM 2,000', 'Ali bin Abu', 'Driver', 'Siti binti Omar', 'Housewife',
                '4', '',
                'Sample leadership statement', 'Sample scholar selection statement',
            ],
        ];

        return $examples[$this->type] ?? [];
    }

    /**
     * @param array<string, string|null> $row
     * @return array<string, mixed>
     */
    public function mapRow(array $row): array
    {
        $data = [];

        foreach ($this->columns() as $key => $column) {
            $value = isset($row[$key]) ? trim((string) $row[$key]) : '';
            if ($value === '') {
                continue;
            }

            $data[$key] = $this->castValue($key, $value);
        }

        $this->validate($data);

        return $this->transform($data);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function validate(array $data): array
    {
        $errors = [];

        foreach ($this->columns() as $key => $column) {
            if (!empty($column['required']) && empty($data[$key]) && $data[$key] !== 0 && $data[$key] !== false) {
                $errors[] = $column['label'] . ' is required';
            }
        }

        if ($errors) {
            throw new InvalidArgumentException(implode('; ', $errors));
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function transform(array $data): array
    {
        switch ($this->type) {
            case 'ordinary':
                $data['key_office_bearers'] = [
                    'president' => [
                        'salutation' => $data['president_salutation'] ?? '',
                        'name' => $data['president_name'] ?? '',
                        'nric' => $data['president_nric'] ?? '',
                        'email' => $data['president_email'] ?? '',
                        'phone' => $data['president_phone'] ?? '',
                    ],
                    'secretary' => [
                        'salutation' => $data['secretary_salutation'] ?? '',
                        'name' => $data['secretary_name'] ?? '',
                        'nric' => $data['secretary_nric'] ?? '',
                        'email' => $data['secretary_email'] ?? '',
                        'phone' => $data['secretary_phone'] ?? '',
                    ],
                    'treasurer' => [
                        'salutation' => $data['treasurer_salutation'] ?? '',
                        'name' => $data['treasurer_name'] ?? '',
                        'nric' => $data['treasurer_nric'] ?? '',
                        'email' => $data['treasurer_email'] ?? '',
                        'phone' => $data['treasurer_phone'] ?? '',
                    ],
                ];
                unset(
                    $data['president_salutation'], $data['president_name'], $data['president_nric'], $data['president_email'], $data['president_phone'],
                    $data['secretary_salutation'], $data['secretary_name'], $data['secretary_nric'], $data['secretary_email'], $data['secretary_phone'],
                    $data['treasurer_salutation'], $data['treasurer_name'], $data['treasurer_nric'], $data['treasurer_email'], $data['treasurer_phone']
                );
                $data['is_registered_ros'] = $data['is_registered_ros'] ?? false;
                $data['declaration_confirmed'] = true;
                $data['status'] = SubmissionStatus::default();
                break;

            case 'friends':
                $data['declaration_confirmed'] = true;
                $data['status'] = SubmissionStatus::default();
                break;

            case 'feedback':
                $data['contact_consent'] = $data['contact_consent'] ?? true;
                $data['declaration_confirmed'] = true;
                $data['status'] = SubmissionStatus::default();
                break;

            case 'mentor':
            case 'volunteer':
                $data['declaration_confirmed'] = true;
                $data['status'] = SubmissionStatus::default();
                break;

            case 'contact':
                $data['status'] = SubmissionStatus::default();
                break;

            case 'partner':
                $data['declaration_confirmed'] = true;
                $data['status'] = SubmissionStatus::default();
                break;

            case 'aid':
                $data['declaration_confirmed'] = true;
                $data['status'] = SubmissionStatus::default();
                if (!empty($data['type_of_aid']) && !is_array($data['type_of_aid'])) {
                    $data['type_of_aid'] = [$data['type_of_aid']];
                }
                if (empty($data['situation_description']) && !empty($data['purpose_of_request'])) {
                    $data['situation_description'] = $data['purpose_of_request'];
                }
                if (empty($data['who_benefits'])) {
                    $data['who_benefits'] = 'Individual';
                }
                if (!array_key_exists('received_aid_before', $data)) {
                    $data['received_aid_before'] = false;
                }
                break;

            case 'mfls':
                if (($data['marital_status'] ?? '') === 'Other' && !empty($data['marital_status_other'])) {
                    // keep both fields
                }
                $data['declaration_confirmed'] = true;
                $data['status'] = SubmissionStatus::default();
                break;
        }

        return $data;
    }

    private function castValue(string $key, string $value)
    {
        $column = $this->columns()[$key] ?? [];
        $type = $column['type'] ?? 'string';

        switch ($type) {
            case 'boolean':
                return $this->parseBoolean($value);
            case 'integer':
                return (int) $value;
            case 'list':
                return $this->parseList($value);
            case 'date':
                return $value;
            default:
                return $value;
        }
    }

    private function parseBoolean(string $value): bool
    {
        $normalized = strtolower(trim($value));

        return in_array($normalized, ['1', 'yes', 'true', 'y'], true);
    }

    /** @return list<string> */
    private function parseList(string $value): array
    {
        $parts = preg_split('/\s*,\s*/', $value) ?: [];

        return array_values(array_filter(array_map('trim', $parts), static function ($item) {
            return $item !== '';
        }));
    }

    /** @return array<string, array{title: string, columns: array<string, array{label: string, required?: bool, hint?: string, type?: string}>}> */
    private function definitions(): array
    {
        return [
            'feedback' => [
                'title' => 'Feedback & Suggestion',
                'columns' => [
                    'full_name' => ['label' => 'Full Name', 'required' => true],
                    'nric_number' => ['label' => 'NRIC', 'required' => true],
                    'organisation' => ['label' => 'Organisation'],
                    'position' => ['label' => 'Position'],
                    'state_residency' => ['label' => 'State', 'required' => true],
                    'full_address' => ['label' => 'Address', 'required' => true],
                    'email' => ['label' => 'Email', 'required' => true],
                    'contact_number' => ['label' => 'Phone', 'required' => true],
                    'categories' => ['label' => 'Categories', 'required' => true, 'type' => 'list', 'hint' => 'Comma-separated'],
                    'other_category' => ['label' => 'Other Category'],
                    'suggestion_description' => ['label' => 'Suggestion', 'required' => true],
                    'benefits_description' => ['label' => 'Benefits', 'required' => true],
                    'contact_consent' => ['label' => 'Contact Consent', 'type' => 'boolean', 'hint' => 'Yes or No'],
                    'preferred_contact_methods' => ['label' => 'Preferred Methods', 'type' => 'list', 'hint' => 'Email, Phone Call, WhatsApp'],
                ],
            ],
            'ordinary' => [
                'title' => 'Ordinary Member',
                'columns' => [
                    'name_of_organisation' => ['label' => 'Organisation Name', 'required' => true],
                    'org_reg_number' => ['label' => 'Reg Number', 'required' => true],
                    'org_reg_date' => ['label' => 'Reg Date', 'required' => true, 'type' => 'date'],
                    'registered_state' => ['label' => 'Registered State', 'required' => true],
                    'full_address' => ['label' => 'Address', 'required' => true],
                    'state' => ['label' => 'State', 'required' => true],
                    'postcode' => ['label' => 'Postcode', 'required' => true],
                    'district_city' => ['label' => 'City', 'required' => true],
                    'year_established' => ['label' => 'Established', 'required' => true, 'type' => 'integer'],
                    'total_members_size' => ['label' => 'Congregation Size', 'required' => true, 'type' => 'integer'],
                    'email' => ['label' => 'Email', 'required' => true],
                    'contact_number' => ['label' => 'Phone', 'required' => true],
                    'website' => ['label' => 'Website'],
                    'org_type' => ['label' => 'Org Types', 'required' => true, 'type' => 'list'],
                    'org_type_other' => ['label' => 'Org Types Other'],
                    'primary_activities' => ['label' => 'Activities', 'required' => true, 'type' => 'list'],
                    'primary_activities_other' => ['label' => 'Activities Other'],
                    'is_registered_ros' => ['label' => 'Registered ROS', 'type' => 'boolean'],
                    'president_salutation' => ['label' => 'President Salutation', 'required' => true],
                    'president_name' => ['label' => 'President Name', 'required' => true],
                    'president_nric' => ['label' => 'President NRIC', 'required' => true],
                    'president_email' => ['label' => 'President Email', 'required' => true],
                    'president_phone' => ['label' => 'President Phone', 'required' => true],
                    'secretary_salutation' => ['label' => 'Secretary Salutation', 'required' => true],
                    'secretary_name' => ['label' => 'Secretary Name', 'required' => true],
                    'secretary_nric' => ['label' => 'Secretary NRIC', 'required' => true],
                    'secretary_email' => ['label' => 'Secretary Email', 'required' => true],
                    'secretary_phone' => ['label' => 'Secretary Phone', 'required' => true],
                    'treasurer_salutation' => ['label' => 'Treasurer Salutation'],
                    'treasurer_name' => ['label' => 'Treasurer Name'],
                    'treasurer_nric' => ['label' => 'Treasurer NRIC'],
                    'treasurer_email' => ['label' => 'Treasurer Email'],
                    'treasurer_phone' => ['label' => 'Treasurer Phone'],
                ],
            ],
            'friends' => [
                'title' => 'Friends of MUKMIN',
                'columns' => [
                    'entity_type' => ['label' => 'Type', 'required' => true, 'hint' => 'Individual or organisation category from form options'],
                    'others_specify' => ['label' => 'Others Specify'],
                    'org_name' => ['label' => 'Org Name'],
                    'org_state' => ['label' => 'Org State'],
                    'org_postcode' => ['label' => 'Org Postcode'],
                    'org_address' => ['label' => 'Org Address'],
                    'org_email' => ['label' => 'Org Email'],
                    'org_phone' => ['label' => 'Org Phone'],
                    'org_contact_person_salutation' => ['label' => 'Contact Person Salutation'],
                    'org_contact_person_name' => ['label' => 'Official Contact Person Name'],
                    'org_contact_person_nric' => ['label' => 'Contact Person NRIC'],
                    'org_website' => ['label' => 'Org Website'],
                    'ind_salutation' => ['label' => 'Ind Salutation'],
                    'ind_name' => ['label' => 'Ind Name'],
                    'ind_nric' => ['label' => 'Ind NRIC'],
                    'ind_state' => ['label' => 'Ind State'],
                    'ind_postcode' => ['label' => 'Ind Postcode'],
                    'ind_profession' => ['label' => 'Ind Profession'],
                    'ind_profession_other' => ['label' => 'Ind Profession Other'],
                    'ind_address' => ['label' => 'Ind Address'],
                    'ind_email' => ['label' => 'Ind Email'],
                    'ind_phone' => ['label' => 'Ind Phone'],
                    'ind_area_of_interest' => ['label' => 'Ind Area of Interest'],
                ],
            ],
            'mentor' => [
                'title' => 'Mentor Registration',
                'columns' => [
                    'full_name' => ['label' => 'Full Name', 'required' => true],
                    'nric_passport' => ['label' => 'NRIC/Passport', 'required' => true],
                    'gender' => ['label' => 'Gender', 'required' => true, 'hint' => 'Male or Female'],
                    'occupation' => ['label' => 'Occupation', 'required' => true],
                    'organisation' => ['label' => 'Organisation'],
                    'position' => ['label' => 'Position', 'required' => true],
                    'experience_years' => ['label' => 'Years Experience', 'required' => true, 'type' => 'integer'],
                    'state_residency' => ['label' => 'State', 'required' => true],
                    'full_address' => ['label' => 'Address', 'required' => true],
                    'email' => ['label' => 'Email', 'required' => true],
                    'contact_number' => ['label' => 'Phone', 'required' => true],
                    'linkedin' => ['label' => 'LinkedIn'],
                    'expertise_areas' => ['label' => 'Expertise Areas', 'required' => true, 'type' => 'list'],
                    'expertise_other' => ['label' => 'Expertise Other'],
                    'preferred_format' => ['label' => 'Formats', 'required' => true, 'type' => 'list'],
                    'preferred_commitment' => ['label' => 'Commitments', 'required' => true, 'type' => 'list'],
                    'experience_description' => ['label' => 'Experience Description', 'required' => true],
                    'has_served_before' => ['label' => 'Has Served Before', 'required' => true, 'type' => 'boolean'],
                    'served_before_details' => ['label' => 'Served Before Details'],
                ],
            ],
            'partner' => [
                'title' => 'Partnership & Collaboration',
                'columns' => [
                    'company_name' => ['label' => 'Company Name', 'required' => true],
                    'contact_person' => ['label' => 'Contact Person', 'required' => true],
                    'position' => ['label' => 'Position', 'required' => true],
                    'org_reg_number' => ['label' => 'Reg Number'],
                    'email' => ['label' => 'Email', 'required' => true],
                    'contact_number' => ['label' => 'Phone', 'required' => true],
                    'office_address' => ['label' => 'Office Address', 'required' => true],
                    'state_country' => ['label' => 'State/Country', 'required' => true],
                    'org_type' => ['label' => 'Org Types', 'required' => true, 'type' => 'list'],
                    'org_type_other' => ['label' => 'Org Types Other'],
                    'collaboration_areas' => ['label' => 'Collaboration Areas', 'required' => true, 'type' => 'list'],
                    'collaboration_other' => ['label' => 'Collaboration Other'],
                    'partnership_type' => ['label' => 'Partnership Types', 'required' => true, 'type' => 'list'],
                    'partnership_other' => ['label' => 'Partnership Other'],
                    'proposal_description' => ['label' => 'Proposal Details', 'required' => true],
                    'expected_outcomes' => ['label' => 'Expected Outcomes', 'required' => true],
                    'has_collaborated_before' => ['label' => 'Has Collaborated', 'required' => true, 'type' => 'boolean'],
                    'collaborated_before_details' => ['label' => 'Previous Collab Details'],
                ],
            ],
            'volunteer' => [
                'title' => 'Volunteer Registration',
                'columns' => [
                    'full_name' => ['label' => 'Full Name', 'required' => true],
                    'nric_passport' => ['label' => 'NRIC/Passport', 'required' => true],
                    'gender' => ['label' => 'Gender', 'required' => true],
                    'occupation_study' => ['label' => 'Occupation/Study', 'required' => true],
                    'organisation' => ['label' => 'Organisation'],
                    'state_residency' => ['label' => 'State', 'required' => true],
                    'full_address' => ['label' => 'Address', 'required' => true],
                    'email' => ['label' => 'Email', 'required' => true],
                    'contact_number' => ['label' => 'Phone', 'required' => true],
                    'interest_areas' => ['label' => 'Interest Areas', 'required' => true, 'type' => 'list'],
                    'interest_other' => ['label' => 'Interest Other'],
                    'skills_expertise' => ['label' => 'Skills/Expertise', 'required' => true],
                    'preferred_mode' => ['label' => 'Preferred Mode', 'required' => true],
                    'availability' => ['label' => 'Availability', 'required' => true, 'type' => 'list'],
                    'has_volunteered_before' => ['label' => 'Has Volunteered Before', 'required' => true, 'type' => 'boolean'],
                    'volunteered_before_details' => ['label' => 'Volunteered Details'],
                    'emergency_contact_name' => ['label' => 'Emergency Name', 'required' => true],
                    'emergency_contact_relationship' => ['label' => 'Emergency Relationship', 'required' => true],
                    'emergency_contact_phone' => ['label' => 'Emergency Phone', 'required' => true],
                ],
            ],
            'contact' => [
                'title' => 'Contact Message',
                'columns' => [
                    'name' => ['label' => 'Name', 'required' => true],
                    'email' => ['label' => 'Email', 'required' => true],
                    'phone' => ['label' => 'Phone', 'required' => true],
                    'message' => ['label' => 'Message', 'required' => true],
                ],
            ],
            'aid' => [
                'title' => 'Community Aid Request',
                'columns' => [
                    'full_name' => ['label' => 'Full Name', 'required' => true],
                    'nric_passport' => ['label' => 'NRIC/Passport', 'required' => true],
                    'gender' => ['label' => 'Gender', 'required' => true],
                    'dob' => ['label' => 'DOB', 'required' => true, 'type' => 'date'],
                    'nationality' => ['label' => 'Nationality', 'required' => true],
                    'occupation' => ['label' => 'Occupation', 'required' => true],
                    'monthly_income' => ['label' => 'Monthly Income'],
                    'contact_number' => ['label' => 'Phone', 'required' => true],
                    'email' => ['label' => 'Email', 'required' => true],
                    'full_address' => ['label' => 'Address', 'required' => true],
                    'state_residency' => ['label' => 'State', 'required' => true],
                    'type_of_aid' => ['label' => 'Type of Aid', 'required' => true, 'type' => 'string'],
                    'type_of_aid_other' => ['label' => 'Type of Aid Other'],
                    // Education Aid — Section 1
                    'university_institution' => ['label' => 'University/Institution'],
                    'programme_name' => ['label' => 'Programme Name'],
                    'programme_level' => ['label' => 'Programme Level'],
                    'faculty_school' => ['label' => 'Faculty/School'],
                    'current_year_semester' => ['label' => 'Current Year/Semester'],
                    'intake_date' => ['label' => 'Intake Date', 'type' => 'date'],
                    'expected_graduation_date' => ['label' => 'Expected Graduation Date', 'type' => 'date'],
                    'current_cgpa_result' => ['label' => 'CGPA/Result'],
                    'student_id' => ['label' => 'Student ID'],
                    'current_student_status' => ['label' => 'Student Status'],
                    'current_student_status_other' => ['label' => 'Student Status Other'],
                    // Education Aid — Section 2
                    'education_expense_types' => ['label' => 'Education Expense Types', 'type' => 'list'],
                    'education_expense_other' => ['label' => 'Education Expense Other'],
                    'total_programme_tuition_fees' => ['label' => 'Total Programme/Tuition Fees'],
                    'total_amount_already_paid' => ['label' => 'Total Amount Already Paid'],
                    'current_outstanding_amount' => ['label' => 'Current Outstanding Amount'],
                    'amount_due_immediately' => ['label' => 'Amount Due Immediately'],
                    'amount_requested_from_mukmin' => ['label' => 'Amount Requested from MUKMIN'],
                    'payment_deadline' => ['label' => 'Payment Deadline', 'type' => 'date'],
                    'purpose_of_request' => ['label' => 'Purpose of Request'],
                    'payment_not_made_consequence' => ['label' => 'Consequence if Payment Not Made'],
                    // Education Aid — Section 3
                    'household_income' => ['label' => 'Household Income'],
                    'father_guardian_name' => ['label' => 'Father/Guardian Name'],
                    'father_guardian_occupation' => ['label' => 'Father/Guardian Occupation'],
                    'mother_guardian_name' => ['label' => 'Mother/Guardian Name'],
                    'mother_guardian_occupation' => ['label' => 'Mother/Guardian Occupation'],
                    'government_assistance_status' => ['label' => 'Government Assistance Status'],
                    'number_of_dependents' => ['label' => 'Number of Dependents', 'type' => 'integer'],
                    'sibling_information' => ['label' => 'Sibling Information', 'type' => 'json'],
                    'other_scholarship_details' => ['label' => 'Other Scholarship Details'],
                    // General III–IV
                    'situation_description' => ['label' => 'Situation'],
                    'who_benefits' => ['label' => 'Who Benefits'],
                    'number_of_beneficiaries' => ['label' => 'Beneficiaries Count'],
                    'received_aid_before' => ['label' => 'Received Aid Before', 'type' => 'boolean'],
                    'received_aid_before_details' => ['label' => 'Previous Aid Details'],
                    'emergency_contact_name' => ['label' => 'Emergency Name', 'required' => true],
                    'emergency_contact_relationship' => ['label' => 'Emergency Relationship', 'required' => true],
                    'emergency_contact_phone' => ['label' => 'Emergency Phone', 'required' => true],
                ],
            ],
            'mfls' => [
                'title' => 'MFLS Scholarship Application',
                'columns' => [
                    'email' => ['label' => 'Email', 'required' => true],
                    'full_name' => ['label' => 'Full Name', 'required' => true],
                    'nric_passport' => ['label' => 'NRIC', 'required' => true],
                    'dob' => ['label' => 'DOB', 'required' => true, 'type' => 'date'],
                    'gender' => ['label' => 'Gender', 'required' => true],
                    'marital_status' => ['label' => 'Marital Status', 'required' => true],
                    'marital_status_other' => ['label' => 'Marital Status Other'],
                    'contact_number' => ['label' => 'Phone', 'required' => true],
                    'full_address' => ['label' => 'Address', 'required' => true],
                    'partner_institution_id' => ['label' => 'Partner Institution ID'],
                    'partner_institution_name' => ['label' => 'Partner Institution', 'required' => true],
                    'current_qualification' => ['label' => 'Current Qualification', 'required' => true],
                    'institution_name' => ['label' => 'Institution', 'required' => true],
                    'year_of_completion' => ['label' => 'Year of Completion', 'required' => true, 'type' => 'integer'],
                    'current_cgpa_result' => ['label' => 'CGPA/Result', 'required' => true],
                    'programme_course_applied' => ['label' => 'Selected Programme', 'required' => true],
                    'household_income' => ['label' => 'Household Income', 'required' => true],
                    'father_guardian_name' => ['label' => 'Father Name', 'required' => true],
                    'father_guardian_occupation' => ['label' => 'Father Occupation', 'required' => true],
                    'mother_guardian_name' => ['label' => 'Mother Name', 'required' => true],
                    'mother_guardian_occupation' => ['label' => 'Mother Occupation', 'required' => true],
                    'number_of_dependents' => ['label' => 'Dependents', 'required' => true, 'type' => 'integer'],
                    'sibling_information' => ['label' => 'Sibling Information', 'type' => 'json'],
                    'other_scholarship_details' => ['label' => 'Other Scholarship'],
                    'leadership_experience_statement' => ['label' => 'Leadership Statement', 'required' => true],
                    'scholar_selection_statement' => ['label' => 'Scholar Selection Statement', 'required' => true],
                ],
            ],
        ];
    }
}
