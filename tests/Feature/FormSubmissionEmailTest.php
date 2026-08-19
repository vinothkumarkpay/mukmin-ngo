<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Mail\FormSubmissionMail;

class FormSubmissionEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('public');
    }

    public function test_feedback_submission_sends_emails()
    {
        $formData = [
            'full_name' => 'John Doe',
            'nric_number' => '900101141234',
            'organisation' => 'Tech Corp',
            'position' => 'Developer',
            'state_residency' => 'Selangor',
            'full_address' => '456 feedback street',
            'email' => 'johndoe@example.com',
            'contact_number' => '+60123456789',
            'categories' => ['Ecosystem'],
            'suggestion_description' => 'Great initiative!',
            'benefits_description' => 'Better community',
            'contact_consent' => '1',
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.feedback.submit'), $formData);
        $response->assertStatus(200);

        // Assert 2 emails were sent: 1 to applicant, 1 to support
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('johndoe@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Feedback Received : MUKMIN Community Feedback & Suggestion' &&
                   !$mail->isForSupport;
        });

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('info@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport;
        });
    }

    public function test_ordinary_member_submission_sends_emails()
    {
        $formData = [
            'name_of_organisation' => 'Ordinary Org',
            'org_reg_number' => 'REG-11111',
            'org_reg_date' => '2025-01-01',
            'registered_state' => 'Selangor',
            'full_address' => '123 Address',
            'state' => 'Selangor',
            'postcode' => '47300',
            'district_city' => 'Petaling Jaya',
            'year_established' => 2020,
            'total_members_size' => 50,
            'email' => 'ordinary@example.com',
            'contact_number' => '+60123456789',
            'org_type' => 'NGO',
            'primary_activities' => ['Welfare / Charity'],
            'key_office_bearers' => [
                'president' => ['salutation' => 'Dato\'', 'name' => 'President Name', 'nric' => '900101145555', 'email' => 'pres@example.com', 'phone' => '+60123456789'],
                'secretary' => ['salutation' => 'Mr.', 'name' => 'Sec Name', 'nric' => '900101145556', 'email' => 'sec@example.com', 'phone' => '+60123456780'],
            ],
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.membership.ordinary.submit'), $formData);
        $response->assertStatus(200);

        // Check applicant email
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('ordinary@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Application Received : MUKMIN Ordinary Member Registration' &&
                   !$mail->isForSupport;
        });

        // Check team inbox email
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('membership@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'New Submission: Ordinary Member Registration' &&
                   $mail->isForSupport;
        });
    }

    public function test_friend_member_individual_submission_sends_emails()
    {
        $formData = [
            'entity_type' => 'Individual',
            'ind_salutation' => 'Mrs.',
            'ind_name' => 'Friend Ind',
            'ind_nric' => '900202141111',
            'ind_state' => 'Johor',
            'ind_postcode' => '84000',
            'ind_profession' => 'NGO / Community Leader',
            'ind_address' => 'Friend Address',
            'ind_email' => 'friend_ind@example.com',
            'ind_phone' => '+60111222333',
            'ind_area_of_interest' => 'Education and Talent Development',
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.membership.friends.submit'), $formData);
        $response->assertStatus(200);

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('friend_ind@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   !$mail->isForSupport;
        });

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('membership@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport;
        });
    }

    public function test_friend_member_organisation_submission_sends_emails()
    {
        $formData = [
            'entity_type' => 'Non-registered NGO',
            'org_name' => 'Friend Org',
            'org_state' => 'Johor',
            'org_postcode' => '84000',
            'org_address' => 'Org Address',
            'org_email' => 'friend_org@example.com',
            'org_phone' => '+60111222334',
            'org_contact_person_salutation' => 'Mr.',
            'org_contact_person_name' => 'Ali bin Abu',
            'org_contact_person_nric' => '900101145555',
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.membership.friends.submit'), $formData);
        $response->assertStatus(200);

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('friend_org@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   !$mail->isForSupport;
        });

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('membership@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport;
        });
    }

    public function test_mentor_submission_sends_emails()
    {
        $formData = [
            'full_name' => 'Mentor Jane',
            'nric_passport' => '850505101234',
            'gender' => 'Female',
            'occupation' => 'Professor',
            'organisation' => 'University',
            'position' => 'Lecturer',
            'experience_years' => 10,
            'state_residency' => 'Perak',
            'full_address' => 'Mentor Road',
            'email' => 'mentor@example.com',
            'contact_number' => '+60129997777',
            'expertise_areas' => ['Education & Training'],
            'preferred_format' => ['One-on-One Mentoring'],
            'preferred_commitment' => ['Monthly (1-2 hours)'],
            'experience_description' => 'Taught for 10 years.',
            'has_served_before' => '0',
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.mentor.submit'), $formData);
        $response->assertStatus(200);

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('mentor@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Application Received : MUKMIN Mentor Registration' &&
                   !$mail->isForSupport;
        });

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('membership@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport;
        });
    }

    public function test_partner_submission_sends_emails_with_attachments()
    {
        $proposal = UploadedFile::fake()->create('proposal.pdf', 500);

        $formData = [
            'company_name' => 'Partner Corp Ltd',
            'contact_person' => 'John Doe',
            'position' => 'Director',
            'email' => 'partner@corp.com',
            'contact_number' => '+60129998888',
            'office_address' => 'Corporate Tower',
            'state_country' => 'Kuala Lumpur',
            'org_type' => ['Corporate / Private Sector'],
            'collaboration_areas' => ['Technology & Digital Innovation'],
            'partnership_type' => ['Sponsorship'],
            'proposal_description' => 'Proposal details.',
            'expected_outcomes' => 'Outcome details.',
            'has_collaborated_before' => '0',
            'supporting_files' => [$proposal],
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.partner.submit'), $formData);
        $response->assertStatus(200);

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('partner@corp.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Application Received : MUKMIN Partnership & Collaboration' &&
                   !$mail->isForSupport;
        });

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            
            $hasAttachments = count($mail->diskAttachments) === 1 || count($mail->attachments) === 1;
            
            return $mail->hasTo('membership@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport &&
                   $hasAttachments;
        });
    }

    public function test_volunteer_submission_sends_emails()
    {
        $formData = [
            'full_name' => 'Vol Jack',
            'nric_passport' => '950606149999',
            'gender' => 'Male',
            'occupation_study' => 'Student',
            'state_residency' => 'Melaka',
            'full_address' => 'Student house',
            'email' => 'vol@example.com',
            'contact_number' => '+60177778888',
            'interest_areas' => ['Event Management & Logistics'],
            'skills_expertise' => 'Hard worker',
            'preferred_mode' => 'Both',
            'availability' => ['Weekends'],
            'has_volunteered_before' => '0',
            'emergency_contact_name' => 'Dad',
            'emergency_contact_relationship' => 'Father',
            'emergency_contact_phone' => '+60122223333',
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.volunteer.submit'), $formData);
        $response->assertStatus(200);

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('vol@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Application Received : MUKMIN Volunteer Registration' &&
                   !$mail->isForSupport;
        });

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('membership@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport;
        });
    }

    public function test_mfls_scholarship_submission_sends_emails()
    {
        $wordParagraph = implode(' ', array_fill(0, 160, 'leadership'));

        $formData = [
            'partner_id' => 'bac',
            'email' => 'mfls@example.com',
            'full_name' => 'Ahmad Student',
            'nric_passport' => '010101011234',
            'dob' => '2001-01-01',
            'gender' => 'Male',
            'age' => '22',
            'citizenship' => 'Malaysian',
            'marital_status' => 'Single',
            'contact_number' => '+60123456789',
            'full_address' => '123 Jalan Pendidikan',
            'state' => 'Selangor',
            'postcode' => '43000',
            'current_qualification' => 'SPM',
            'institution_name' => 'SMK Contoh',
            'year_of_completion' => '2024',
            'current_cgpa_result' => '7A 2B',
            'academic_transcript' => UploadedFile::fake()->create('transcript.pdf', 100, 'application/pdf'),
            'programme_course_applied' => 'FIL (Foundation in Law)',
            'household_income' => 'Below RM 2,000',
            'father_guardian_name' => 'Father Name',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Mother Name',
            'mother_guardian_occupation' => 'Homemaker',
            'proof_of_income' => [
                UploadedFile::fake()->create('income-father.pdf', 100, 'application/pdf'),
                UploadedFile::fake()->create('income-mother.pdf', 100, 'application/pdf'),
            ],
            'government_assistance_status' => 'Sumbangan Tunai Rahmah (STR)',
            'proof_of_government_assistance' => UploadedFile::fake()->create('str-proof.pdf', 100, 'application/pdf'),
            'number_of_dependents' => '4',
            'sibling_information' => [
                [
                    'name' => 'Aisyah Student',
                    'age' => '19',
                    'status' => 'Studying',
                    'program' => 'Foundation in Arts',
                    'university' => 'Example University',
                ],
                [
                    'name' => 'Hakim Student',
                    'age' => '24',
                    'status' => 'Not Working',
                    'reason' => 'Searching for a job',
                ],
            ],
            'other_scholarship_details' => 'None',
            'leadership_experience_statement' => $wordParagraph,
            'scholar_selection_statement' => $wordParagraph,
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.mfls-scholarship.submit'), $formData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('mfls_scholarship_submissions', [
            'email' => 'mfls@example.com',
            'partner_institution_id' => 'bac',
            'partner_institution_name' => 'BAC',
            'programme_course_applied' => 'FIL (Foundation in Law)',
        ]);

        $submission = \App\Models\MflsScholarshipSubmission::where('email', 'mfls@example.com')->firstOrFail();
        $this->assertIsArray($submission->sibling_information);
        $this->assertSame('Studying', $submission->sibling_information[0]['status']);
        $this->assertSame('Foundation in Arts', $submission->sibling_information[0]['program']);
        $this->assertSame('Not Working', $submission->sibling_information[1]['status']);
        $this->assertSame('Searching for a job', $submission->sibling_information[1]['reason']);

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            $labels = array_column($mail->formattedData, 'label');
            $rows = collect($mail->formattedData)->keyBy('label');
            $hasAttachments = count($mail->diskAttachments) >= 1 || count($mail->attachments) >= 1 || count($mail->rawAttachments) >= 1;
            return $mail->hasTo('mfls@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Application Received : MUKMIN Future Leaders Scholarship (MFLS)' &&
                   !$mail->isForSupport &&
                   in_array('Partner Institution', $labels, true) &&
                   in_array('Selected Programme', $labels, true) &&
                   in_array('Age', $labels, true) &&
                   in_array('Citizenship', $labels, true) &&
                   isset($rows['Sibling 1'], $rows['Sibling 2']) &&
                   str_contains(strip_tags($rows['Sibling 1']['value']), 'Foundation in Arts') &&
                   str_contains(strip_tags($rows['Sibling 2']['value']), 'Searching for a job') &&
                   $hasAttachments;
        });

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            $labels = array_column($mail->formattedData, 'label');
            $programmeIndex = array_search('Selected Programme', $labels, true);
            $partnerIndex = array_search('Partner Institution', $labels, true);
            $hasAttachments = count($mail->diskAttachments) >= 1 || count($mail->attachments) >= 1 || count($mail->rawAttachments) >= 1;
            return $mail->hasTo('scholarship@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport &&
                   $partnerIndex !== false &&
                   $programmeIndex !== false &&
                   $partnerIndex < $programmeIndex &&
                   $hasAttachments;
        });
    }

    public function test_mfls_scholarship_email_formats_partner_and_programme_fields()
    {
        $mail = new FormSubmissionMail('MFLS Scholarship Application', [
            'partner_institution_name' => 'Sunway University',
            'programme_course_applied' => 'Diploma in Culinary Arts',
            'full_name' => 'Ahmad Student',
        ], true);

        $rows = collect($mail->formattedData)->keyBy('label');

        $this->assertSame('Sunway University', strip_tags($rows['Partner Institution']['value']));
        $this->assertSame('Diploma in Culinary Arts', strip_tags($rows['Selected Programme']['value']));
    }

    public function test_mfls_scholarship_rejects_non_malaysian_citizenship()
    {
        $wordParagraph = implode(' ', array_fill(0, 160, 'leadership'));

        $formData = [
            'partner_id' => 'bac',
            'email' => 'mfls@example.com',
            'full_name' => 'Ahmad Student',
            'nric_passport' => '010101011234',
            'dob' => '2001-01-01',
            'gender' => 'Male',
            'age' => '22',
            'citizenship' => 'Non-Malaysian',
            'marital_status' => 'Single',
            'contact_number' => '+60123456789',
            'full_address' => '123 Jalan Pendidikan',
            'state' => 'Selangor',
            'postcode' => '43000',
            'current_qualification' => 'SPM',
            'institution_name' => 'SMK Contoh',
            'year_of_completion' => '2024',
            'current_cgpa_result' => '7A 2B',
            'academic_transcript' => UploadedFile::fake()->create('transcript.pdf', 100, 'application/pdf'),
            'programme_course_applied' => 'FIL (Foundation in Law)',
            'household_income' => 'Below RM 2,000',
            'father_guardian_name' => 'Father Name',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Mother Name',
            'mother_guardian_occupation' => 'Homemaker',
            'proof_of_income' => [
                UploadedFile::fake()->create('income-father.pdf', 100, 'application/pdf'),
                UploadedFile::fake()->create('income-mother.pdf', 100, 'application/pdf'),
            ],
            'government_assistance_status' => 'Sumbangan Tunai Rahmah (STR)',
            'proof_of_government_assistance' => UploadedFile::fake()->create('str-proof.pdf', 100, 'application/pdf'),
            'number_of_dependents' => '4',
            'other_scholarship_details' => 'None',
            'leadership_experience_statement' => $wordParagraph,
            'scholar_selection_statement' => $wordParagraph,
            'declaration_confirmed' => '1',
        ];

        $this->from(route('welfare.mfls-scholarship', ['partner' => 'bac']))
            ->post(route('welfare.mfls-scholarship.submit'), $formData)
            ->assertSessionHasErrors(['citizenship']);
    }

    public function test_mfls_scholarship_rejects_invalid_nric_phone_and_email()
    {
        $wordParagraph = implode(' ', array_fill(0, 160, 'leadership'));

        $baseData = [
            'partner_id' => 'bac',
            'full_name' => 'Ahmad Student',
            'dob' => '2001-01-01',
            'gender' => 'Male',
            'age' => '22',
            'citizenship' => 'Malaysian',
            'marital_status' => 'Single',
            'full_address' => '123 Jalan Pendidikan, Kuala Lumpur',
            'state' => 'Selangor',
            'postcode' => '43000',
            'current_qualification' => 'SPM',
            'institution_name' => 'SMK Contoh',
            'year_of_completion' => '2024',
            'current_cgpa_result' => '7A 2B',
            'academic_transcript' => UploadedFile::fake()->create('transcript.pdf', 100, 'application/pdf'),
            'programme_course_applied' => 'FIL (Foundation in Law)',
            'household_income' => 'Below RM 2,000',
            'father_guardian_name' => 'Father Name',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Mother Name',
            'mother_guardian_occupation' => 'Homemaker',
            'proof_of_income' => [
                UploadedFile::fake()->create('income-father.pdf', 100, 'application/pdf'),
                UploadedFile::fake()->create('income-mother.pdf', 100, 'application/pdf'),
            ],
            'government_assistance_status' => 'Sumbangan Tunai Rahmah (STR)',
            'proof_of_government_assistance' => UploadedFile::fake()->create('str-proof.pdf', 100, 'application/pdf'),
            'number_of_dependents' => '4',
            'other_scholarship_details' => 'None',
            'leadership_experience_statement' => $wordParagraph,
            'scholar_selection_statement' => $wordParagraph,
            'declaration_confirmed' => '1',
        ];

        $this->from(route('welfare.mfls-scholarship', ['partner' => 'bac']))
            ->post(route('welfare.mfls-scholarship.submit'), array_merge($baseData, [
                'email' => 'mfls@example.com',
                'nric_passport' => '12345',
                'contact_number' => '+60123456789',
            ]))
            ->assertSessionHasErrors(['nric_passport']);

        $this->from(route('welfare.mfls-scholarship', ['partner' => 'bac']))
            ->post(route('welfare.mfls-scholarship.submit'), array_merge($baseData, [
                'email' => 'not-an-email',
                'nric_passport' => '010101011234',
                'contact_number' => '+60123456789',
            ]))
            ->assertSessionHasErrors(['email']);

        $this->from(route('welfare.mfls-scholarship', ['partner' => 'bac']))
            ->post(route('welfare.mfls-scholarship.submit'), array_merge($baseData, [
                'email' => 'mfls@example.com',
                'nric_passport' => '010101011234',
                'contact_number' => '999999',
            ]))
            ->assertSessionHasErrors(['contact_number']);

        Mail::assertNothingSent();
    }
}
