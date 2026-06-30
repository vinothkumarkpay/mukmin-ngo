<?php

namespace Tests\Feature;

use App\Mail\SubmissionStatusUpdateMail;
use App\Models\CommunityAidSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubmissionStatusNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_notify_status_update_sends_email_to_applicant(): void
    {
        $this->actingAs(User::factory()->create());

        $submission = $this->createAidSubmission([
            'email' => 'jane@example.com',
            'status' => 'approved',
        ]);

        $response = $this->postJson(url("/admin/submissions/aid/{$submission->id}/status/notify"));

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        Mail::assertSent(SubmissionStatusUpdateMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('jane@example.com')
                && $mail->formTitle === 'Community Aid & Assistance Request'
                && $mail->status === 'approved'
                && $mail->statusLabel === 'Approved'
                && str_contains($mail->subject, 'Approved');
        });
    }

    public function test_notify_status_update_returns_error_when_applicant_email_missing(): void
    {
        $this->actingAs(User::factory()->create());

        $submission = $this->createAidSubmission([
            'email' => 'jane@example.com',
            'status' => 'approved',
        ]);
        $submission->update(['email' => '']);

        $response = $this->postJson(url("/admin/submissions/aid/{$submission->id}/status/notify"));

        $response->assertStatus(422);
        Mail::assertNothingSent();
    }

    private function createAidSubmission(array $overrides = []): CommunityAidSubmission
    {
        return CommunityAidSubmission::create(array_merge([
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
            'status' => 'received',
        ], $overrides));
    }
}
