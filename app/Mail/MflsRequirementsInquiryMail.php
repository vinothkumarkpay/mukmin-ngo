<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MflsRequirementsInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $applicantEmail;
    public string $partnerName;
    public string $programmeName;
    public string $redirectUrl;
    public $subject;
    public bool $isForSupport;

    public function __construct(
        string $applicantEmail,
        string $partnerName,
        string $programmeName,
        string $redirectUrl,
        bool $isForSupport = false
    ) {
        $this->applicantEmail = $applicantEmail;
        $this->partnerName = $partnerName;
        $this->programmeName = $programmeName;
        $this->redirectUrl = $redirectUrl;
        $this->isForSupport = $isForSupport;
        $this->subject = $isForSupport
            ? 'MFLS Requirements Inquiry — ' . $programmeName
            : 'MFLS Programme Requirements — Next Steps';
    }

    public function build()
    {
        return $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.mfls_requirements_inquiry');
    }
}
