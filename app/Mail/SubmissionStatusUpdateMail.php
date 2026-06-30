<?php

namespace App\Mail;

use App\Support\SubmissionStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $formTitle;
    public ?string $recipientName;
    public string $status;
    public string $statusLabel;
    public $subject;
    public string $statusMessage;

    public function __construct(string $formTitle, ?string $recipientName, string $status, string $statusLabel)
    {
        $this->formTitle = $formTitle;
        $this->recipientName = $recipientName;
        $this->status = $status;
        $this->statusLabel = $statusLabel;
        $this->statusMessage = $this->buildStatusMessage($status);
        $this->subject = "Application Status Update: {$statusLabel} — MUKMIN";
    }

    public function build()
    {
        return $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.submission_status_update');
    }

    protected function buildStatusMessage(string $status): string
    {
        return match (SubmissionStatus::normalize($status)) {
            SubmissionStatus::REVIEWING => 'Your submission is currently under review by our team.',
            SubmissionStatus::PENDING_APPROVAL => 'Your submission is pending final approval.',
            SubmissionStatus::FURTHER_INFO_REQUIRED => 'We require further information regarding your submission. Our team will contact you if additional details are needed.',
            SubmissionStatus::APPROVED => 'We are pleased to inform you that your submission has been approved.',
            SubmissionStatus::REJECTED => 'After careful review, we regret to inform you that your submission was not approved at this time.',
            SubmissionStatus::COMPLETED => 'Your submission process has been completed.',
            default => 'We have received your submission and it is currently being processed.',
        };
    }
}
