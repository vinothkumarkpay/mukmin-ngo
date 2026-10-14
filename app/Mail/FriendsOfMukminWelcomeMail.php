<?php

namespace App\Mail;

use App\Models\FriendMemberSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class FriendsOfMukminWelcomeMail extends Mailable
{
    use Queueable;

    public FriendMemberSubmission $member;
    public $subject = 'Welcome to MUKMIN - Friends of MUKMIN Membership Confirmation';

    private string $letterPdf;
    private string $letterFilename;
    private string $cardPdf;
    private string $cardFilename;

    public function __construct(
        FriendMemberSubmission $member,
        string $letterPdf,
        string $letterFilename,
        string $cardPdf,
        string $cardFilename
    ) {
        $this->member = $member;
        $this->letterPdf = $letterPdf;
        $this->letterFilename = $letterFilename;
        $this->cardPdf = $cardPdf;
        $this->cardFilename = $cardFilename;
    }

    public function build()
    {
        return $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.friends_of_mukmin_welcome', [
                'greetingName' => $this->member->greeting_name,
                'settings' => config('welfare.friends_membership'),
            ])
            ->attachData($this->cardPdf, $this->cardFilename, ['mime' => 'application/pdf'])
            ->attachData($this->letterPdf, $this->letterFilename, ['mime' => 'application/pdf']);
    }
}
