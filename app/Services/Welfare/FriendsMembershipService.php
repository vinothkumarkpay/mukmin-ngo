<?php

namespace App\Services\Welfare;

use App\Mail\FriendsOfMukminWelcomeMail;
use App\Models\AppSetting;
use App\Models\FriendMemberSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FriendsMembershipService
{
    public const WELCOME_EMAIL_SETTING = 'friends.approval_welcome_email_enabled';

    /** CR80 card size in points (85.6mm x 54mm). */
    private const CARD_SIZE = [0, 0, 242.65, 153.07];

    public function isWelcomeEmailEnabled(): bool
    {
        return AppSetting::getBool(
            self::WELCOME_EMAIL_SETTING,
            (bool) config('welfare.friends_membership.welcome_email_default', true)
        );
    }

    public function setWelcomeEmailEnabled(bool $enabled, ?int $userId = null): void
    {
        AppSetting::setValue(self::WELCOME_EMAIL_SETTING, $enabled, $userId);
    }

    /**
     * Assigns the membership number and validity period on first approval.
     * Existing members keep their number and dates when re-approved.
     */
    public function ensureMembership(FriendMemberSubmission $member): FriendMemberSubmission
    {
        if ($member->membership_number) {
            return $member;
        }

        return DB::transaction(function () use ($member) {
            $locked = FriendMemberSubmission::query()->lockForUpdate()->findOrFail($member->id);
            if ($locked->membership_number) {
                return $locked;
            }

            $start = (int) config('welfare.friends_membership.number_start', 100);
            $max = (int) FriendMemberSubmission::query()->lockForUpdate()->max('membership_number');

            $approvedAt = Carbon::now();
            $locked->forceFill([
                'membership_number' => max($start, $max + 1),
                'membership_start_date' => $approvedAt->copy()->startOfDay(),
                'membership_end_date' => $this->membershipEndDate($approvedAt),
                'approved_at' => $approvedAt,
            ])->save();

            return $locked;
        });
    }

    public function referenceNumber(FriendMemberSubmission $member): string
    {
        $year = ($member->approved_at ?? $member->membership_start_date ?? Carbon::now())->format('Y');
        $number = str_pad((string) $member->membership_number, 4, '0', STR_PAD_LEFT);

        return config('welfare.friends_membership.reference_prefix', 'MUKMIN/SEC/FOM') . "/{$year}/{$number}";
    }

    public function letterPdf(FriendMemberSubmission $member): string
    {
        return Pdf::loadView('pdf.friends-membership-letter', [
            'member' => $member,
            'referenceNumber' => $this->referenceNumber($member),
            'issuedDate' => ($member->approved_at ?? Carbon::now())->format('d F Y'),
            'logo' => $this->imageDataUri(public_path('welfare/img/mukmin_logo.png')),
            'settings' => config('welfare.friends_membership'),
        ])->setPaper('a4')->output();
    }

    public function cardPdf(FriendMemberSubmission $member): string
    {
        return Pdf::loadView('pdf.friends-membership-card', [
            'member' => $member,
            'front' => $this->imageDataUri(resource_path('images/membership/fom-card-front.png')),
            'back' => $this->imageDataUri(resource_path('images/membership/fom-card-back.png')),
            'settings' => config('welfare.friends_membership'),
        ])->setPaper(self::CARD_SIZE)->output();
    }

    public function letterFilename(FriendMemberSubmission $member): string
    {
        return "{$member->formatted_membership_number}-Membership-Confirmation-Letter.pdf";
    }

    public function cardFilename(FriendMemberSubmission $member): string
    {
        return "{$member->formatted_membership_number}-Membership-E-Card.pdf";
    }

    /**
     * @throws \RuntimeException when the member has no email address
     * @throws \Throwable when rendering or sending fails
     */
    public function sendWelcomeEmail(FriendMemberSubmission $member): void
    {
        $member = $this->ensureMembership($member);
        $email = $member->member_email;

        if (! $email) {
            throw new \RuntimeException('No email address is available for this member.');
        }

        try {
            Mail::to($email)->send(new FriendsOfMukminWelcomeMail(
                $member,
                $this->letterPdf($member),
                $this->letterFilename($member),
                $this->cardPdf($member),
                $this->cardFilename($member)
            ));
        } catch (\Throwable $e) {
            Log::error('Friends of MUKMIN welcome email failed', [
                'submission_id' => $member->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }

        $member->forceFill(['welcome_email_sent_at' => Carbon::now()])->save();
    }

    private function membershipEndDate(Carbon $approvedAt): Carbon
    {
        if (config('welfare.friends_membership.validity') === 'one_year') {
            return $approvedAt->copy()->addYear()->subDay()->startOfDay();
        }

        return $approvedAt->copy()->endOfYear()->startOfDay();
    }

    private function imageDataUri(string $path): string
    {
        return 'data:image/png;base64,' . base64_encode((string) file_get_contents($path));
    }
}
