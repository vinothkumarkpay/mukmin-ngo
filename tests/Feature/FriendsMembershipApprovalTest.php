<?php

namespace Tests\Feature;

use App\Mail\FriendsOfMukminWelcomeMail;
use App\Models\FriendMemberSubmission;
use App\Models\User;
use App\Services\Welfare\FriendsMembershipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FriendsMembershipApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->actingAs(User::factory()->create());
    }

    public function test_approval_assigns_membership_and_sends_welcome_email_with_attachments(): void
    {
        $member = $this->createFriend(['ind_email' => 'friend@example.com']);

        $response = $this->postJson(url("/admin/submissions/friends/{$member->id}/status"), ['status' => 'approved']);

        $response->assertOk()
            ->assertJsonPath('welcome_email.sent', true)
            ->assertJsonPath('welcome_email.membership_number', 'MUKMIN-0100');

        $member->refresh();
        $this->assertSame(100, $member->membership_number);
        $this->assertNotNull($member->welcome_email_sent_at);
        $this->assertSame(now()->endOfYear()->toDateString(), $member->membership_end_date->toDateString());

        Mail::assertSent(FriendsOfMukminWelcomeMail::class, function ($mail) {
            $mail->build();
            $attachments = collect($mail->rawAttachments)->pluck('name')->all();

            return $mail->hasTo('friend@example.com')
                && in_array('MUKMIN-0100-Membership-E-Card.pdf', $attachments, true)
                && in_array('MUKMIN-0100-Membership-Confirmation-Letter.pdf', $attachments, true);
        });
    }

    public function test_approval_does_not_send_welcome_email_when_disabled(): void
    {
        app(FriendsMembershipService::class)->setWelcomeEmailEnabled(false);
        $member = $this->createFriend();

        $response = $this->postJson(url("/admin/submissions/friends/{$member->id}/status"), ['status' => 'approved']);

        $response->assertOk()
            ->assertJsonPath('welcome_email.enabled', false)
            ->assertJsonPath('welcome_email.sent', false)
            ->assertJsonPath('welcome_email.membership_number', 'MUKMIN-0100');

        Mail::assertNotSent(FriendsOfMukminWelcomeMail::class);
    }

    public function test_reapproval_keeps_number_and_does_not_resend(): void
    {
        $member = $this->createFriend();

        $this->postJson(url("/admin/submissions/friends/{$member->id}/status"), ['status' => 'approved'])->assertOk();
        $this->postJson(url("/admin/submissions/friends/{$member->id}/status"), ['status' => 'reviewing'])->assertOk();
        $this->postJson(url("/admin/submissions/friends/{$member->id}/status"), ['status' => 'approved'])
            ->assertOk()
            ->assertJsonPath('welcome_email.already_sent', true)
            ->assertJsonPath('welcome_email.membership_number', 'MUKMIN-0100');

        Mail::assertSent(FriendsOfMukminWelcomeMail::class, 1);
    }

    public function test_membership_numbers_increment(): void
    {
        $first = $this->createFriend();
        $second = $this->createFriend(['ind_email' => 'second@example.com']);

        $this->postJson(url("/admin/submissions/friends/{$first->id}/status"), ['status' => 'approved']);
        $this->postJson(url("/admin/submissions/friends/{$second->id}/status"), ['status' => 'approved'])
            ->assertJsonPath('welcome_email.membership_number', 'MUKMIN-0101');
    }

    public function test_toggle_endpoint_updates_setting(): void
    {
        $this->postJson(url('/admin/friends/welcome-email-setting'), ['enabled' => false])
            ->assertOk()
            ->assertJson(['success' => true, 'enabled' => false]);

        $this->assertFalse(app(FriendsMembershipService::class)->isWelcomeEmailEnabled());
    }

    public function test_documents_are_available_for_approved_members_only(): void
    {
        $member = $this->createFriend();

        $this->get(url("/admin/friends/{$member->id}/membership-card"))->assertStatus(422);

        $member->update(['status' => 'approved']);

        $this->get(url("/admin/friends/{$member->id}/membership-card"))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
        $this->get(url("/admin/friends/{$member->id}/membership-letter"))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    private function createFriend(array $overrides = []): FriendMemberSubmission
    {
        return FriendMemberSubmission::create(array_merge([
            'entity_type' => 'Individual',
            'ind_salutation' => 'Encik',
            'ind_name' => 'Ahmad Faizal',
            'ind_nric' => '900101105555',
            'ind_state' => 'Selangor',
            'ind_email' => 'ahmad@example.com',
            'ind_phone' => '+60123456789',
            'declaration_confirmed' => true,
            'status' => 'received',
        ], $overrides));
    }
}
