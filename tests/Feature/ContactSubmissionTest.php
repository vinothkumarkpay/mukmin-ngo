<?php

namespace Tests\Feature;

use App\Mail\FormSubmissionMail;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_saves_and_attempts_email(): void
    {
        Mail::fake();

        $response = $this->post(route('welfare.contact.submit'), [
            'name' => 'Jane Doe',
            'email' => 'jane@gmail.com',
            'phone' => '+60123456789',
            'message' => 'Hello from test',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contact_submissions', [
            'email' => 'jane@gmail.com',
            'name' => 'Jane Doe',
        ]);

        Mail::assertSent(FormSubmissionMail::class, 2);

        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('info@mukmin.org') &&
                   $mail->isForSupport;
        });
    }
}
