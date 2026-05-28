<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FormSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formName;
    public $formData;
    public $formattedData;
    public $isForSupport;
    public $subject;
    public $recipientName;

    /**
     * Label mapping for form fields to human-readable names.
     */
    public static $labels = [
        'full_name' => 'Full Name',
        'nric_number' => 'NRIC Number',
        'organisation' => 'Organisation / Company',
        'position' => 'Position',
        'state_residency' => 'State',
        'full_address' => 'Full Address',
        'email' => 'Email Address',
        'contact_number' => 'Contact Number',
        'categories' => 'Categories',
        'other_category' => 'Other Category',
        'suggestion_description' => 'Suggestion / Feedback Description',
        'benefits_description' => 'Expected Benefits Description',
        'contact_consent' => 'Consent to Contact',
        'preferred_contact_methods' => 'Preferred Contact Methods',
        'declaration_confirmed' => 'Declaration Confirmed',
        
        // Ordinary Member
        'name_of_organisation' => 'Name of Organisation',
        'org_reg_number' => 'Organisation Registration Number',
        'org_reg_date' => 'Organisation Registration Date',
        'registered_state' => 'Registered State',
        'postcode' => 'Postcode',
        'district_city' => 'District / City',
        'year_established' => 'Year Established',
        'total_members_size' => 'Total Members Size',
        'website' => 'Website URL',
        'org_type' => 'Organisation Type',
        'org_type_other' => 'Organisation Type (Other)',
        'primary_activities' => 'Primary Activities',
        'primary_activities_other' => 'Primary Activities (Other)',
        'is_registered_ros' => 'Registered under ROS?',
        'registration_certificate' => 'Registration Certificate',
        'committee_members' => 'Committee Members List',
        'key_office_bearers' => 'Key Office Bearers',
        
        // Friends Member
        'entity_type' => 'Entity Type',
        'others_specify' => 'Others Specify',
        'ind_name' => 'Individual Name',
        'ind_nric' => 'Individual NRIC',
        'ind_state' => 'Individual State',
        'ind_address' => 'Individual Address',
        'ind_email' => 'Individual Email',
        'ind_phone' => 'Individual Phone',
        'org_name' => 'Organisation Name',
        'org_state' => 'Organisation State',
        'org_address' => 'Organisation Address',
        'org_email' => 'Organisation Email',
        'org_phone' => 'Organisation Phone',
        'org_website' => 'Organisation Website',
        
        // Mentor
        'nric_passport' => 'NRIC / Passport Number',
        'gender' => 'Gender',
        'occupation' => 'Occupation',
        'experience_years' => 'Years of Experience',
        'linkedin' => 'LinkedIn Profile',
        'expertise_areas' => 'Expertise Areas',
        'expertise_other' => 'Expertise Areas (Other)',
        'preferred_format' => 'Preferred Mentoring Format',
        'preferred_commitment' => 'Preferred Commitment Level',
        'experience_description' => 'Professional Experience Summary',
        'has_served_before' => 'Has Served as Mentor Before?',
        'served_before_details' => 'Previous Mentorship Details',
        
        // Partner
        'company_name' => 'Company Name',
        'contact_person' => 'Contact Person Name',
        'office_address' => 'Office Address',
        'state_country' => 'State / Country',
        'collaboration_areas' => 'Collaboration Areas',
        'collaboration_other' => 'Collaboration Areas (Other)',
        'partnership_type' => 'Partnership Type',
        'partnership_other' => 'Partnership Type (Other)',
        'proposal_description' => 'Partnership Proposal Description',
        'expected_outcomes' => 'Expected Outcomes',
        'has_collaborated_before' => 'Has Collaborated with MUKMIN Before?',
        'collaborated_before_details' => 'Previous Collaboration Details',
        'supporting_files' => 'Supporting Files',
        'supporting_documents' => 'Supporting Documents',

        // Volunteer
        'occupation_study' => 'Occupation / Field of Study',
        'interest_areas' => 'Volunteer Interest Areas',
        'interest_other' => 'Interest Areas (Other)',
        'skills_expertise' => 'Skills / Areas of Expertise',
        'preferred_mode' => 'Preferred Volunteer Mode',
        'availability' => 'Availability Status',
        'has_volunteered_before' => 'Has Volunteered with MUKMIN/Others?',
        'volunteered_before_details' => 'Previous Volunteering Details',
        'emergency_contact_name' => 'Emergency Contact Name',
        'emergency_contact_relationship' => 'Emergency Contact Relationship',
        'emergency_contact_phone' => 'Emergency Contact Phone',
        
        // Community Aid
        'dob' => 'Date of Birth',
        'nationality' => 'Nationality',
        'monthly_income' => 'Monthly Household Income',
        'type_of_aid' => 'Type of Aid Required',
        'type_of_aid_other' => 'Other Type of Aid',
        'situation_description' => 'Current Situation & Assistance Required',
        'who_benefits' => 'Who Will Benefit',
        'number_of_beneficiaries' => 'Number of Beneficiaries',
        'received_aid_before' => 'Has Received Aid Before',
        'received_aid_before_details' => 'Previous Aid Details',
    ];

    /**
     * Create a new message instance.
     *
     * @param string $formName
     * @param array $formData
     * @param bool $isForSupport
     * @param string|null $recipientName
     */
    public function __construct(string $formName, array $formData, bool $isForSupport = false, ?string $recipientName = null)
    {
        $this->formName = $formName;
        $this->formData = $formData;
        $this->isForSupport = $isForSupport;
        $this->recipientName = $recipientName;
        
        $this->subject = $isForSupport 
            ? "New Submission: {$formName}" 
            : "Acknowledgement: {$formName}";
            
        $this->formattedData = $this->formatData($formData);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.form_submission');

        // Attach files if the email is for support
        if ($this->isForSupport) {
            // Find and attach registration_certificate
            if (!empty($this->formData['registration_certificate'])) {
                $path = $this->formData['registration_certificate'];
                if (Storage::disk('public')->exists($path)) {
                    $email->attach(Storage::disk('public')->path($path));
                }
            }
            // Find and attach committee_members
            if (!empty($this->formData['committee_members'])) {
                $path = $this->formData['committee_members'];
                if (Storage::disk('public')->exists($path)) {
                    $email->attach(Storage::disk('public')->path($path));
                }
            }
            // Find and attach supporting_documents
            if (!empty($this->formData['supporting_documents']) && is_array($this->formData['supporting_documents'])) {
                foreach ($this->formData['supporting_documents'] as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        $email->attach(Storage::disk('public')->path($path));
                    }
                }
            }
        }

        return $email;
    }

    /**
     * Format form data for highly readable email layout.
     *
     * @param array $data
     * @return array
     */
    protected function formatData(array $data)
    {
        $formatted = [];
        foreach ($data as $key => $value) {
            if ($key === 'declaration_confirmed') {
                continue;
            }
            
            // Format files as HTML link
            if ($key === 'registration_certificate' || $key === 'committee_members') {
                $url = $value ? asset('storage/' . $value) : 'Not provided';
                $formatted[] = [
                    'label' => self::$labels[$key] ?? ucwords(str_replace('_', ' ', $key)),
                    'value' => $value ? '<a href="' . $url . '" target="_blank" style="color: #0c5930; font-weight: 500;">View Uploaded File</a>' : 'Not provided',
                    'is_html' => true
                ];
                continue;
            }
            
            // Format supporting documents as list of HTML links
            if ($key === 'supporting_documents') {
                $links = [];
                if (is_array($value)) {
                    foreach ($value as $index => $path) {
                        $url = asset('storage/' . $path);
                        $links[] = '<a href="' . $url . '" target="_blank" style="color: #0c5930; font-weight: 500;">Document ' . ($index + 1) . '</a>';
                    }
                }
                $formatted[] = [
                    'label' => self::$labels[$key] ?? ucwords(str_replace('_', ' ', $key)),
                    'value' => !empty($links) ? implode(' | ', $links) : 'Not provided',
                    'is_html' => true
                ];
                continue;
            }

            // Format key office bearers cleanly
            if ($key === 'key_office_bearers' && is_array($value)) {
                foreach ($value as $role => $info) {
                    if (is_array($info)) {
                        $roleLabel = ucfirst($role);
                        $infoStr = '';
                        if (!empty($info['name'])) {
                            $infoStr .= '<strong>Name:</strong> ' . e($info['name']) . '<br>';
                        }
                        if (!empty($info['email'])) {
                            $infoStr .= '<strong>Email:</strong> ' . e($info['email']) . '<br>';
                        }
                        if (!empty($info['phone'])) {
                            $infoStr .= '<strong>Phone:</strong> ' . e($info['phone']);
                        }
                        
                        if (!empty($infoStr)) {
                            $formatted[] = [
                                'label' => "Key Office Bearer - {$roleLabel}",
                                'value' => $infoStr,
                                'is_html' => true
                            ];
                        }
                    }
                }
                continue;
            }

            $label = self::$labels[$key] ?? ucwords(str_replace('_', ' ', $key));

            if (is_array($value)) {
                $listItems = array_map(function($item) {
                    return '<li style="margin-bottom: 4px;">' . e($item) . '</li>';
                }, $value);
                $formattedValue = '<ul style="margin: 0; padding-left: 20px; color: #2d3748;">' . implode('', $listItems) . '</ul>';
                $formatted[] = [
                    'label' => $label,
                    'value' => $formattedValue,
                    'is_html' => true
                ];
            } elseif (is_bool($value)) {
                $formatted[] = [
                    'label' => $label,
                    'value' => $value ? 'Yes' : 'No',
                    'is_html' => false
                ];
            } elseif (is_numeric($value)) {
                $formatted[] = [
                    'label' => $label,
                    'value' => (string)$value,
                    'is_html' => false
                ];
            } else {
                $formattedValue = nl2br(e($value));
                $formatted[] = [
                    'label' => $label,
                    'value' => $formattedValue,
                    'is_html' => true
                ];
            }
        }
        return $formatted;
    }
}
