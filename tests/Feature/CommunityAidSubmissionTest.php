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

    public function test_successful_aid_submission_saves_to_database_and_emails_only_support_with_attachments()
    {
        $doc1 = UploadedFile::fake()->create('medical_bill.pdf', 300);
        $doc2 = UploadedFile::fake()->create('payslip.png', 150);

        $formData = [
            'full_name' => 'Jane Smith',
            'nric_passport' => '950202-10-5432',
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
            'status' => 'pending'
        ]);

        $submission = CommunityAidSubmission::first();
        $this->assertNotNull($submission->supporting_documents);
        $this->assertCount(2, $submission->supporting_documents);

        // Verify files stored in public storage
        foreach ($submission->supporting_documents as $filePath) {
            Storage::disk('public')->assertExists($filePath);
        }

        // Verify emails: Should NOT send to the applicant (janesmith@example.com)
        Mail::assertNotSent(FormSubmissionMail::class, function ($mail) {
            return $mail->hasTo('janesmith@example.com');
        });

        // Verify email to support: Should be sent, should contain attachments
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build(); // Build email to resolve attachments
            
            $hasAttachments = count($mail->diskAttachments) === 2 || count($mail->attachments) === 2;

            return $mail->hasTo('support@mukmin.org') &&
                   $mail->hasFrom('no-reply@mukmin.org') &&
                   $mail->isForSupport &&
                   $hasAttachments;
        });
    }

    public function test_admin_dashboard_integration()
    {
        // Create an aid request
        $submission = CommunityAidSubmission::create([
            'full_name' => 'Jane Smith',
            'nric_passport' => '950202-10-5432',
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
            'status' => 'pending'
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

    private function actingAsAdmin()
    {
        // Bind an admin auth middleware bypass or configure session as authenticated
        // In this Laravel project, middleware is 'admin.auth'. Let's verify how it handles authentication.
        // Let's check session variables or use session mocks if needed.
        $this->withSession(['admin_logged_in' => true]);
    }
}
