<?php

namespace App\Mail;

use App\Support\EducationAidStatus;
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
    public string $submissionType;

    public function __construct(
        string $formTitle,
        ?string $recipientName,
        string $status,
        string $statusLabel,
        string $submissionType = ''
    ) {
        $this->formTitle = $formTitle;
        $this->recipientName = $recipientName;
        $this->status = $status;
        $this->statusLabel = $statusLabel;
        $this->submissionType = $submissionType;
        $this->statusMessage = $this->buildStatusMessage($status, $submissionType);
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

    protected function buildStatusMessage(string $status, string $submissionType = ''): string
    {
        if ($submissionType === 'aid') {
            return match (EducationAidStatus::normalize($status)) {
                EducationAidStatus::UNDER_ASSESSMENT,
                EducationAidStatus::ASSESSMENT_PENDING,
                EducationAidStatus::INITIAL_SCREENING => 'Your education aid application is currently under assessment by our team.',
                EducationAidStatus::DOCUMENTS_INCOMPLETE,
                EducationAidStatus::DEFERRED_FURTHER_INFO => 'We require further information or documents regarding your education aid application.',
                EducationAidStatus::INTERVIEW_REQUIRED => 'An interview is required as part of your education aid assessment. Our team will contact you with scheduling details.',
                EducationAidStatus::COMMITTEE_REVIEW => 'Your application is pending committee review.',
                EducationAidStatus::APPROVED_FULL,
                EducationAidStatus::APPROVED_PARTIAL => 'We are pleased to inform you that your education aid application has been approved.',
                EducationAidStatus::ALTERNATIVE_ASSISTANCE => 'We would like to offer alternative assistance or counselling regarding your application.',
                EducationAidStatus::NOT_APPROVED => 'After careful review, we regret to inform you that your education aid application was not approved at this time.',
                EducationAidStatus::PAYMENT_PROCESSING => 'Your approved assistance is currently being processed for payment.',
                EducationAidStatus::PAID,
                EducationAidStatus::CASE_CLOSED => 'Your education aid case has been completed.',
                default => 'We have received your education aid application and it is currently being processed.',
            };
        }

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
