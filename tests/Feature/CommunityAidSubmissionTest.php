<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Mail\FormSubmissionMail;
use App\Models\CommunityAidSubmission;

class CommunityAidSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('public');
    }

    public function test_form_validation_requires_mandatory_fields()
    {
        $response = $this->post(route('welfare.community-aid.submit'), []);
        $response->assertStatus(302); // Redirects back due to validation errors
        $response->assertSessionHasErrors([
            'full_name', 'nric_passport', 'gender', 'dob', 'nationality', 'occupation',
            'contact_number', 'email', 'full_address', 'state_residency',
            'university_institution', 'programme_name', 'education_expense_types',
            'declaration_confirmed',
        ]);
        $response->assertSessionDoesntHaveErrors([
            'situation_description', 'who_benefits', 'received_aid_before',
            'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone',
        ]);
    }

    public function test_home_page_shows_community_aid_membership_gate_modal()
    {
        $response = $this->get(route('welfare.home'));

        $response->assertStatus(200);
        $response->assertSee('membership-registration-gate-modal', false);
        $response->assertSee('Are you a registered MUKMIN member?', false);
        $response->assertSee(route('welfare.community-aid'), false);
        $response->assertSee(route('welfare.membership.friends'), false);
        $response->assertSee('Talk to us', false);
    }

    public function test_successful_aid_submission_saves_to_database_and_emails_only_support_with_attachments()
    {
        $formData = [
            'full_name' => 'Jane Smith',
            'nric_passport' => '950202105432',
            'gender' => 'Female',
            'dob' => '1995-02-02',
            'nationality' => 'Malaysian',
            'occupation' => 'Student',
            'monthly_income' => '1500',
            'contact_number' => '+60176543210',
            'email' => 'janesmith@example.com',
            'full_address' => '789 Hope Avenue, Kuala Lumpur',
            'state_residency' => 'Wilayah Persekutuan Kuala Lumpur',
            'type_of_aid' => 'Education Aid',
            'university_institution' => 'Universiti Malaya',
            'programme_name' => 'Bachelor of Arts',
            'programme_level' => 'Degree',
            'faculty_school' => 'Faculty of Arts',
            'current_year_semester' => 'Year 2',
            'intake_date' => '2024-09-01',
            'expected_graduation_date' => '2027-07-31',
            'current_cgpa_result' => '3.20',
            'student_id' => 'UM99999',
            'current_student_status' => 'Full-time',
            'education_expense_types' => ['Tuition / Programme Fees'],
            'total_programme_tuition_fees' => '20000',
            'total_amount_already_paid' => '5000',
            'current_outstanding_amount' => '15000',
            'amount_due_immediately' => '3000',
            'amount_requested_from_mukmin' => '3000',
            'payment_deadline' => '2026-09-30',
            'financial_situation_explanation' => 'Family income is insufficient to cover outstanding fees.',
            'family_education_financing_efforts' => 'Parents used savings and part-time work to pay earlier semesters.',
            'family_financial_commitments' => 'Medical bills and younger siblings school fees.',
            'purpose_of_request' => 'Need tuition assistance.',
            'payment_not_made_consequence' => 'May be barred from exams.',
            'university_payment_arrangement_discussed' => 'Requested instalment plan; pending university response.',
            'remaining_balance_funding_plan' => 'Will cover remainder through part-time work.',
            'household_income' => 'Below RM 2,000',
            'father_guardian_name' => 'John Smith',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Mary Smith',
            'mother_guardian_occupation' => 'Homemaker',
            'proof_of_income' => [UploadedFile::fake()->create('income.pdf', 200)],
            'government_assistance_status' => 'Sumbangan Tunai Rahmah (STR)',
            'proof_of_government_assistance' => UploadedFile::fake()->create('gov.pdf', 200),
            'number_of_dependents' => '2',
            'other_scholarship_details' => 'None',
            'nric_front' => UploadedFile::fake()->create('nric_front.jpg', 100),
            'nric_back' => UploadedFile::fake()->create('nric_back.jpg', 100),
            'academic_result' => UploadedFile::fake()->create('spm.pdf', 100),
            'latest_academic_transcript' => UploadedFile::fake()->create('transcript.pdf', 100),
            'university_offer_letter' => UploadedFile::fake()->create('offer.pdf', 100),
            'student_id_confirmation' => UploadedFile::fake()->create('student_id.pdf', 100),
            'applicant_photo' => UploadedFile::fake()->image('applicant_photo.jpg', 400, 500)->size(100),
            'university_fee_statement' => UploadedFile::fake()->create('fees.pdf', 100),
            'official_invoice' => UploadedFile::fake()->create('invoice.pdf', 100),
            'outstanding_balance_statement' => UploadedFile::fake()->create('balance.pdf', 100),
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.community-aid.submit'), $formData);
        
        $response->assertStatus(200);
        $response->assertViewIs('welfare.pages.form_success');

        // Verify database entry
        $this->assertDatabaseHas('community_aid_submissions', [
            'full_name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'university_institution' => 'Universiti Malaya',
            'status' => 'received'
        ]);

        $submission = CommunityAidSubmission::first();
        $this->assertNotNull($submission);

        // Verify emails: Should send to the applicant (janesmith@example.com)
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('janesmith@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Application Received : Education Aid & Assistance Request' &&
                   !$mail->isForSupport;
        });

        // Verify email to support: Should be sent with education document attachments
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();

            $attachmentCount = count($mail->diskAttachments) + count($mail->attachments);

            return $mail->hasTo('communitywelfare@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport &&
                   $attachmentCount > 0;
        });
    }

    public function test_admin_dashboard_integration()
    {
        // Create an aid request
        $submission = CommunityAidSubmission::create([
            'full_name' => 'Jane Smith',
            'nric_passport' => '950202105432',
            'gender' => 'Female',
            'dob' => '1995-02-02',
            'nationality' => 'Malaysian',
            'occupation' => 'Freelancer',
            'monthly_income' => '1500',
            'contact_number' => '+60176543210',
            'email' => 'janesmith@example.com',
            'full_address' => '789 Hope Avenue, Kuala Lumpur',
            'state_residency' => 'Wilayah Persekutuan Kuala Lumpur',
            'type_of_aid' => ['Healthcare Aid'],
            'situation_description' => 'Medical assistance needed.',
            'who_benefits' => 'Individual',
            'received_aid_before' => false,
            'emergency_contact_name' => 'John Smith',
            'emergency_contact_relationship' => 'Brother',
            'emergency_contact_phone' => '+60112223334',
            'declaration_confirmed' => true,
            'status' => 'received'
        ]);

        // Simulating admin session/authentication
        $this->actingAsAdmin();

        // 1. Check stats on index page
        $response = $this->get(route('welfare.admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('aid');

        // 2. Check showSubmission JSON API
        $response = $this->get(url("/admin/submissions/aid/{$submission->id}"));
        $response->assertStatus(200);
        $response->assertJson([
            'full_name' => 'Jane Smith',
            'email' => 'janesmith@example.com'
        ]);

        // 3. Check updateStatus API (Education Aid uses aid-specific statuses)
        $response = $this->post(url("/admin/submissions/aid/{$submission->id}/status"), [
            'status' => 'approved_full'
        ]);
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'status' => 'approved_full']);
        $this->assertEquals('approved_full', $submission->fresh()->status);

        // 4. Check CSV Export
        $response = $this->get(route('welfare.admin.export', 'aid'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_dashboard_filters_submissions_by_status(): void
    {
        CommunityAidSubmission::create([
            'full_name' => 'Approved Applicant',
            'nric_passport' => '950202105433',
            'gender' => 'Female',
            'dob' => '1995-02-02',
            'nationality' => 'Malaysian',
            'occupation' => 'Freelancer',
            'contact_number' => '+60176543211',
            'email' => 'approved@example.com',
            'full_address' => '789 Hope Avenue, Kuala Lumpur',
            'state_residency' => 'Wilayah Persekutuan Kuala Lumpur',
            'type_of_aid' => ['Healthcare Aid'],
            'situation_description' => 'Medical assistance needed.',
            'who_benefits' => 'Individual',
            'received_aid_before' => false,
            'emergency_contact_name' => 'John Smith',
            'emergency_contact_relationship' => 'Brother',
            'emergency_contact_phone' => '+60112223334',
            'declaration_confirmed' => true,
            'status' => 'approved',
        ]);

        CommunityAidSubmission::create([
            'full_name' => 'Received Applicant',
            'nric_passport' => '950202105434',
            'gender' => 'Male',
            'dob' => '1994-03-03',
            'nationality' => 'Malaysian',
            'occupation' => 'Driver',
            'contact_number' => '+60176543212',
            'email' => 'received@example.com',
            'full_address' => '12 Jalan Sentosa, Kuala Lumpur',
            'state_residency' => 'Wilayah Persekutuan Kuala Lumpur',
            'type_of_aid' => ['Financial Assistance'],
            'situation_description' => 'Needs financial support.',
            'who_benefits' => 'Family',
            'received_aid_before' => false,
            'emergency_contact_name' => 'Ali Rahman',
            'emergency_contact_relationship' => 'Brother',
            'emergency_contact_phone' => '+60112223335',
            'declaration_confirmed' => true,
            'status' => 'received',
        ]);

        $this->actingAsAdmin();

        $response = $this->get(route('welfare.admin.dashboard', [
            'submission_status' => 'approved',
            'admin_tab' => 'panel-aid',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Approved Applicant', false);
        $response->assertDontSee('Received Applicant', false);
        $response->assertSee('Search &amp; Filter Submissions', false);
        $response->assertSee('1 result', false);
    }

    public function test_admin_dashboard_filters_friends_by_human_readable_status_label(): void
    {
        \App\Models\FriendMemberSubmission::create([
            'entity_type' => 'Individual',
            'ind_name' => 'Mariam Sulaiman',
            'ind_nric' => '950202105432',
            'ind_state' => 'Johor',
            'ind_address' => 'Johor Bahru',
            'ind_postcode' => '80000',
            'ind_email' => 'mariam@example.com',
            'ind_phone' => '+60123456789',
            'declaration_confirmed' => true,
            'status' => 'Received / New',
        ]);

        \App\Models\FriendMemberSubmission::create([
            'entity_type' => 'Individual',
            'ind_name' => 'Approved Friend',
            'ind_nric' => '950202105499',
            'ind_state' => 'Selangor',
            'ind_address' => 'Shah Alam',
            'ind_postcode' => '40000',
            'ind_email' => 'approvedfriend@example.com',
            'ind_phone' => '+60198765432',
            'declaration_confirmed' => true,
            'status' => 'Approved',
        ]);

        $this->actingAsAdmin();

        $response = $this->get(route('welfare.admin.dashboard', [
            'submission_status' => 'approved',
            'admin_tab' => 'panel-friends',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Approved Friend', false);
        $response->assertDontSee('Mariam Sulaiman', false);
    }

    public function test_admin_dashboard_reviewing_filter_excludes_received_friends(): void
    {
        \App\Models\FriendMemberSubmission::create([
            'entity_type' => 'Individual',
            'ind_name' => 'test',
            'ind_nric' => '950202105432',
            'ind_state' => 'Perlis',
            'ind_address' => 'Perlis',
            'ind_postcode' => '01000',
            'ind_email' => 'test@example.com',
            'ind_phone' => '+60123456789',
            'declaration_confirmed' => true,
            'status' => 'Received / New',
        ]);

        \App\Models\FriendMemberSubmission::create([
            'entity_type' => 'Individual',
            'ind_name' => 'Reviewing Friend',
            'ind_nric' => '950202105499',
            'ind_state' => 'Johor',
            'ind_address' => 'Johor Bahru',
            'ind_postcode' => '80000',
            'ind_email' => 'reviewing@example.com',
            'ind_phone' => '+60198765432',
            'declaration_confirmed' => true,
            'status' => 'Reviewing',
        ]);

        $this->actingAsAdmin();

        $response = $this->get(route('welfare.admin.dashboard', [
            'submission_status' => 'reviewing',
            'admin_tab' => 'panel-friends',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Reviewing Friend', false);
        $response->assertDontSee('test@example.com', false);
        $response->assertSee('1 result', false);
    }

    public function test_form_page_includes_education_aid_sections(): void
    {
        $response = $this->get(route('welfare.community-aid'));

        $response->assertStatus(200);
        $response->assertSee('education-aid-sections', false);
        $response->assertSee('Section 1: Education Information', false);
        $response->assertSee('Section 2: Education Cost &amp; Aid Request', false);
        $response->assertSee('Section 3: Socioeconomic Background', false);
        $response->assertSee('Section 4: Document Upload', false);
        $response->assertSee('Section 5: Financial Need Assessment', false);
        $response->assertSee('Type of Aid Required', false);
        $response->assertSee('Education Aid', false);
        $response->assertDontSee('-- Choose type of aid --', false);
        $response->assertSee('Applicant Photo', false);
    }

    public function test_successful_education_aid_submission_saves_education_fields(): void
    {
        $formData = [
            'full_name' => 'Ahmad Education',
            'nric_passport' => '980101015555',
            'gender' => 'Male',
            'dob' => '1998-01-01',
            'nationality' => 'Malaysian',
            'occupation' => 'Student',
            'contact_number' => '+60176543210',
            'email' => 'ahmad.edu@example.com',
            'full_address' => '12 Campus Road, Selangor',
            'state_residency' => 'Selangor',
            'type_of_aid' => 'Education Aid',
            'university_institution' => 'Universiti Malaya',
            'programme_name' => 'Bachelor of Computer Science',
            'programme_level' => 'Degree',
            'faculty_school' => 'Faculty of Computer Science',
            'current_year_semester' => 'Year 2',
            'intake_date' => '2024-09-01',
            'expected_graduation_date' => '2027-07-31',
            'current_cgpa_result' => '3.45',
            'student_id' => 'UM12345',
            'current_student_status' => 'Full-time',
            'education_expense_types' => ['Tuition / Programme Fees', 'Accommodation'],
            'total_programme_tuition_fees' => '25000',
            'total_amount_already_paid' => '10000',
            'current_outstanding_amount' => '15000',
            'amount_due_immediately' => '5000',
            'amount_requested_from_mukmin' => '5000',
            'payment_deadline' => '2026-09-30',
            'financial_situation_explanation' => 'Family income is insufficient to cover outstanding fees.',
            'family_education_financing_efforts' => 'Parents used savings and part-time work to pay earlier semesters.',
            'family_financial_commitments' => 'Medical bills and younger siblings school fees.',
            'purpose_of_request' => 'Need assistance for tuition fees this semester.',
            'payment_not_made_consequence' => 'I may be barred from sitting examinations.',
            'university_payment_arrangement_discussed' => 'Requested instalment plan; pending university response.',
            'remaining_balance_funding_plan' => 'Will cover remainder through part-time work.',
            'household_income' => 'Below RM 2,000',
            'father_guardian_name' => 'Ali bin Abu',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Siti binti Omar',
            'mother_guardian_occupation' => 'Homemaker',
            'proof_of_income' => [UploadedFile::fake()->create('income.pdf', 200)],
            'government_assistance_status' => 'Sumbangan Tunai Rahmah (STR)',
            'proof_of_government_assistance' => UploadedFile::fake()->create('gov.pdf', 200),
            'number_of_dependents' => '3',
            'other_scholarship_details' => 'None',
            'nric_front' => UploadedFile::fake()->create('nric_front.jpg', 100),
            'nric_back' => UploadedFile::fake()->create('nric_back.jpg', 100),
            'academic_result' => UploadedFile::fake()->create('spm.pdf', 100),
            'latest_academic_transcript' => UploadedFile::fake()->create('transcript.pdf', 100),
            'university_offer_letter' => UploadedFile::fake()->create('offer.pdf', 100),
            'student_id_confirmation' => UploadedFile::fake()->create('student_id.pdf', 100),
            'applicant_photo' => UploadedFile::fake()->image('applicant_photo.jpg', 400, 500)->size(100),
            'university_fee_statement' => UploadedFile::fake()->create('fees.pdf', 100),
            'official_invoice' => UploadedFile::fake()->create('invoice.pdf', 100),
            'outstanding_balance_statement' => UploadedFile::fake()->create('balance.pdf', 100),
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.community-aid.submit'), $formData);

        $response->assertStatus(200);
        $response->assertViewIs('welfare.pages.form_success');

        $this->assertDatabaseHas('community_aid_submissions', [
            'full_name' => 'Ahmad Education',
            'email' => 'ahmad.edu@example.com',
            'university_institution' => 'Universiti Malaya',
            'programme_name' => 'Bachelor of Computer Science',
            'programme_level' => 'Degree',
            'amount_requested_from_mukmin' => '5000.00',
            'financial_situation_explanation' => 'Family income is insufficient to cover outstanding fees.',
            'who_benefits' => 'Individual',
            'emergency_contact_name' => 'Ahmad Education',
            'emergency_contact_relationship' => 'Applicant',
            'emergency_contact_phone' => '+60176543210',
        ]);

        $submission = CommunityAidSubmission::where('email', 'ahmad.edu@example.com')->first();
        $this->assertNotNull($submission);
        $this->assertSame(['Tuition / Programme Fees', 'Accommodation'], $submission->education_expense_types);
        $this->assertNotEmpty($submission->nric_front);
        $this->assertNotEmpty($submission->applicant_photo);
        $this->assertNotEmpty($submission->proof_of_income);
        Storage::disk('public')->assertExists($submission->nric_front);
        Storage::disk('public')->assertExists($submission->applicant_photo);
    }

    public function test_education_aid_rejects_files_over_two_megabytes()
    {
        $formData = [
            'full_name' => 'Jane Smith',
            'nric_passport' => '950202105432',
            'gender' => 'Female',
            'dob' => '1995-02-02',
            'nationality' => 'Malaysian',
            'occupation' => 'Student',
            'contact_number' => '+60176543210',
            'email' => 'janesmith@example.com',
            'full_address' => '789 Hope Avenue, Kuala Lumpur',
            'state_residency' => 'Wilayah Persekutuan Kuala Lumpur',
            'type_of_aid' => 'Education Aid',
            'university_institution' => 'Universiti Malaya',
            'programme_name' => 'Bachelor of Arts',
            'programme_level' => 'Degree',
            'faculty_school' => 'Faculty of Arts',
            'current_year_semester' => 'Year 2',
            'intake_date' => '2024-09-01',
            'expected_graduation_date' => '2027-07-31',
            'current_cgpa_result' => '3.20',
            'student_id' => 'UM99999',
            'current_student_status' => 'Full-time',
            'education_expense_types' => ['Tuition / Programme Fees'],
            'total_programme_tuition_fees' => '20000',
            'total_amount_already_paid' => '5000',
            'current_outstanding_amount' => '15000',
            'amount_due_immediately' => '3000',
            'amount_requested_from_mukmin' => '3000',
            'payment_deadline' => '2026-09-30',
            'financial_situation_explanation' => 'Family income is insufficient to cover outstanding fees.',
            'family_education_financing_efforts' => 'Parents used savings and part-time work to pay earlier semesters.',
            'family_financial_commitments' => 'Medical bills and younger siblings school fees.',
            'purpose_of_request' => 'Need tuition assistance.',
            'payment_not_made_consequence' => 'May be barred from exams.',
            'university_payment_arrangement_discussed' => 'Requested instalment plan; pending university response.',
            'remaining_balance_funding_plan' => 'Will cover remainder through part-time work.',
            'household_income' => 'Below RM 2,000',
            'father_guardian_name' => 'John Smith',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Mary Smith',
            'mother_guardian_occupation' => 'Homemaker',
            'proof_of_income' => [UploadedFile::fake()->create('income.pdf', 3000)], // > 2MB
            'government_assistance_status' => 'Sumbangan Tunai Rahmah (STR)',
            'proof_of_government_assistance' => UploadedFile::fake()->create('gov.pdf', 100),
            'number_of_dependents' => '2',
            'other_scholarship_details' => 'None',
            'nric_front' => UploadedFile::fake()->create('nric_front.jpg', 100),
            'nric_back' => UploadedFile::fake()->create('nric_back.jpg', 100),
            'academic_result' => UploadedFile::fake()->create('spm.pdf', 100),
            'latest_academic_transcript' => UploadedFile::fake()->create('transcript.pdf', 100),
            'university_offer_letter' => UploadedFile::fake()->create('offer.pdf', 100),
            'student_id_confirmation' => UploadedFile::fake()->create('student_id.pdf', 100),
            'applicant_photo' => UploadedFile::fake()->image('applicant_photo.jpg', 400, 500)->size(100),
            'university_fee_statement' => UploadedFile::fake()->create('fees.pdf', 100),
            'official_invoice' => UploadedFile::fake()->create('invoice.pdf', 100),
            'outstanding_balance_statement' => UploadedFile::fake()->create('balance.pdf', 100),
            'declaration_confirmed' => '1',
        ];

        $response = $this->from(route('welfare.community-aid'))
            ->post(route('welfare.community-aid.submit'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('proof_of_income.0');
        $this->assertStringContainsString(
            '2MB',
            (string) session('errors')->first('proof_of_income.0')
        );
        $this->assertDatabaseCount('community_aid_submissions', 0);
    }

    public function test_post_too_large_shows_friendly_error_message()
    {
        $response = $this->from(route('welfare.community-aid'))
            ->call(
                'POST',
                route('welfare.community-aid.submit'),
                [],
                [],
                [],
                ['CONTENT_LENGTH' => (string) (70 * 1024 * 1024)]
            );

        $response->assertStatus(302);
        $response->assertRedirect(route('welfare.community-aid'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString(
            'too large',
            strtolower((string) session('error'))
        );
    }

    private function actingAsAdmin()
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);
    }
}
