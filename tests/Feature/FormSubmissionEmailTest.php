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
            return $mail->hasTo('support@mukmin.org') &&
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
            'postcode' => '47300',
            'district_city' => 'Petaling Jaya',
            'year_established' => 2020,
            'total_members_size' => 50,
            'email' => 'ordinary@example.com',
            'contact_number' => '+60123456789',
            'org_type' => ['NGO'],
            'primary_activities' => ['Welfare / Charity'],
            'key_office_bearers' => [
                'president' => ['name' => 'President Name', 'email' => 'pres@example.com', 'phone' => '+60123456789'],
                'secretary' => ['name' => 'Sec Name', 'email' => 'sec@example.com', 'phone' => '+60123456780'],
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

        // Check support email
        Mail::assertSent(FormSubmissionMail::class, function ($mail) {
            $mail->build();
            return $mail->hasTo('support@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->subject === 'New Submission: Ordinary Member Registration' &&
                   $mail->isForSupport;
        });
    }

    public function test_friend_member_individual_submission_sends_emails()
    {
        $formData = [
            'entity_type' => 'Individual',
            'ind_name' => 'Friend Ind',
            'ind_nric' => '900202141111',
            'ind_state' => 'Johor',
            'ind_address' => 'Friend Address',
            'ind_email' => 'friend_ind@example.com',
            'ind_phone' => '+60111222333',
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
            return $mail->hasTo('support@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport;
        });
    }

    public function test_friend_member_organisation_submission_sends_emails()
    {
        $formData = [
            'entity_type' => 'Organisation',
            'org_name' => 'Friend Org',
            'org_state' => 'Johor',
            'org_address' => 'Org Address',
            'org_email' => 'friend_org@example.com',
            'org_phone' => '+60111222334',
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
            return $mail->hasTo('support@mukmin.org') &&
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
            return $mail->hasTo('support@mukmin.org') &&
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
            
            return $mail->hasTo('support@mukmin.org') &&
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
            return $mail->hasTo('support@mukmin.org') &&
                   $mail->hasFrom('noreply@mukmin.org') &&
                   $mail->isForSupport;
        });
    }
}
