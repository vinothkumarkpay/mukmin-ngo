<?php

namespace App\Mail;

use App\Models\CommunityAidSubmission;
use App\Models\EducationAidInterviewProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EducationAidInterviewProposalMail extends Mailable
{
    use Queueable, SerializesModels;

    public CommunityAidSubmission $submission;
    public EducationAidInterviewProposal $proposal;
    public string $schedulerName;

    public function __construct(
        CommunityAidSubmission $submission,
        EducationAidInterviewProposal $proposal,
        string $schedulerName
    ) {
        $this->submission = $submission;
        $this->proposal = $proposal;
        $this->schedulerName = $schedulerName;
    }

    public function build()
    {
        return $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject('Interview Scheduling — Education Aid #' . $this->submission->id . ' — MUKMIN')
            ->view('emails.education_aid_interview_proposal');
    }
}
