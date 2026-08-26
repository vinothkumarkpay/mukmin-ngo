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
            'contact_number', 'email', 'full_address', 'state_residency', 'type_of_aid',
            'situation_description', 'who_benefits', 'received_aid_before',
            'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone',
            'declaration_confirmed'
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
        $doc1 = UploadedFile::fake()->create('medical_bill.pdf', 300);
        $doc2 = UploadedFile::fake()->create('payslip.png', 150);

        $formData = [
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
            'type_of_aid' => ['Healthcare Aid', 'Financial Assistance'],
            'situation_description' => 'Medical assistance needed for chronic illness.',
            'who_benefits' => 'Individual',
            'number_of_beneficiaries' => '1',
            'received_aid_before' => '0',
            'supporting_files' => [$doc1, $doc2],
            'emergency_contact_name' => 'John Smith',
            'emergency_contact_relationship' => 'Brother',
            'emergency_contact_phone' => '+60112223334',
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.community-aid.submit'), $formData);
        
        $response->assertStatus(200);
        $response->assertViewIs('welfare.pages.form_success');

        // Verify database entry
        $this->assertDatabaseHas('community_aid_submissions', [
            'full_name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'who_benefits' => 'Individual',
            'status' => 'received'
        ]);

        $submission = CommunityAidSubmission::first();
        $this->assertNotNull($submission->supporting_documents);
        $this->assertCount(2, $submission->supporting_documents);

        // Verify files stored in public storage
        foreach ($submission->supporting_documents as $filePath) {
            Storage::disk('public')->assertExists($filePath);
        }

        // Verify emails: Should send to the applicant (janesmith@example.com)
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('janesmith@example.com') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'Application Received : MUKMIN Community Aid & Assistance Request' &&
                   !$mail->isForSupport;
        });

        // Verify email to support: Should be sent, should contain attachments
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build(); // Build email to resolve attachments
            
            $hasAttachments = count($mail->diskAttachments) === 2 || count($mail->attachments) === 2;

            return $mail->hasTo('communitywelfare@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport &&
                   $hasAttachments;
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

        // 3. Check updateStatus API
        $response = $this->post(url("/admin/submissions/aid/{$submission->id}/status"), [
            'status' => 'approved'
        ]);
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'status' => 'approved']);
        $this->assertEquals('approved', $submission->fresh()->status);

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
        $response->assertSee('general-aid-sections', false);
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
            'type_of_aid' => ['Education Aid'],
            'university_institution' => 'Universiti Malaya',
            'programme_name' => 'Bachelor of Computer Science',
            'programme_level' => 'Degree',
            'faculty_school' => 'Faculty of Computer Science',
            'current_year_semester' => 'Currently Studying',
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
            'purpose_of_request' => 'Need assistance for tuition fees this semester.',
            'payment_not_made_consequence' => 'I may be barred from sitting examinations.',
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
            'who_benefits' => 'Individual',
            'emergency_contact_name' => 'Ahmad Education',
            'emergency_contact_relationship' => 'Applicant',
            'emergency_contact_phone' => '+60176543210',
        ]);

        $submission = CommunityAidSubmission::where('email', 'ahmad.edu@example.com')->first();
        $this->assertNotNull($submission);
        $this->assertSame(['Tuition / Programme Fees', 'Accommodation'], $submission->education_expense_types);
        $this->assertNotEmpty($submission->nric_front);
        $this->assertNotEmpty($submission->proof_of_income);
        Storage::disk('public')->assertExists($submission->nric_front);
    }

    private function actingAsAdmin()
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);
    }
}
