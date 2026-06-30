<?php

namespace App\Services\Welfare;

use App\Mail\SubmissionStatusUpdateMail;
use App\Support\SubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubmissionStatusNotifier
{
    /** @var array<string, string> */
    private const FORM_TITLES = [
        'feedback' => 'Feedback & Suggestion',
        'ordinary' => 'Ordinary Member Registration',
        'friends' => 'Friend of MUKMIN Registration',
        'mentor' => 'Mentor Registration',
        'partner' => 'Partnership & Collaboration Proposal',
        'volunteer' => 'Volunteer Registration',
        'contact' => 'Contact Us',
        'aid' => 'Community Aid & Assistance Request',
        'mfls' => 'MFLS Scholarship Application',
    ];

    public function formTitle(string $type): string
    {
        return self::FORM_TITLES[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }

    public function notifyApplicant(Model $submission, string $type): bool
    {
        $email = $this->resolveApplicantEmail($submission);
        if (! $email) {
            return false;
        }

        $name = $this->resolveApplicantName($submission);
        $status = SubmissionStatus::normalize($submission->status);
        $formTitle = $this->formTitle($type);

        try {
            Mail::to($email)->send(new SubmissionStatusUpdateMail(
                $formTitle,
                $name,
                $status,
                SubmissionStatus::label($status)
            ));

            return true;
        } catch (\Throwable $e) {
            Log::error('Submission status notification email failed', [
                'type' => $type,
                'submission_id' => $submission->id,
                'email' => $email,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function resolveApplicantEmail(Model $submission): ?string
    {
        foreach (['email', 'ind_email', 'org_email'] as $field) {
            $value = $submission->getAttribute($field);
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    public function resolveApplicantName(Model $submission): ?string
    {
        foreach ([
            'full_name',
            'name',
            'ind_name',
            'contact_person',
            'name_of_organisation',
            'org_name',
            'company_name',
        ] as $field) {
            $value = $submission->getAttribute($field);
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
