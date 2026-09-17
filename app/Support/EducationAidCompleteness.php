<?php

namespace App\Support;

use App\Models\CommunityAidSubmission;
use App\Models\EducationAidAssessment;
use App\Models\EducationAidDocumentCheck;

class EducationAidCompleteness
{
    /** @var list<string> */
    public const MANDATORY_FOR_COMMITTEE = [
        'applicant_information',
        'academic_information',
        'financial_information',
        'education_cost_documents',
        'income_documents',
        'nric',
        'university_verification',
        'reviewer_recommendation',
    ];

    /**
     * @return array{
     *   percent: int,
     *   items: array<string, array{label: string, done: bool, mandatory: bool}>,
     *   can_submit_to_committee: bool
     * }
     */
    public static function evaluate(CommunityAidSubmission $submission, ?EducationAidAssessment $assessment = null): array
    {
        $assessment = $assessment ?? $submission->assessment;
        $docChecks = $submission->documentChecks ?? collect();

        $items = [
            'applicant_information' => [
                'label' => 'Applicant information',
                'done' => filled($submission->full_name) && filled($submission->nric_passport),
                'mandatory' => true,
            ],
            'academic_information' => [
                'label' => 'Academic information',
                'done' => filled($submission->university_institution) && filled($submission->programme_name),
                'mandatory' => true,
            ],
            'financial_information' => [
                'label' => 'Financial information',
                'done' => filled($submission->household_income) && filled($submission->amount_requested_from_mukmin),
                'mandatory' => true,
            ],
            'education_cost_documents' => [
                'label' => 'Education cost documents',
                'done' => self::docsVerified($docChecks, [
                    'university_fee_statement',
                    'official_invoice',
                    'outstanding_balance_statement',
                ]),
                'mandatory' => true,
            ],
            'income_documents' => [
                'label' => 'Income documents',
                'done' => self::docsVerified($docChecks, ['proof_of_income'], false)
                    || self::docsStatus($docChecks, 'proof_of_income', 'not_applicable'),
                'mandatory' => true,
            ],
            'nric' => [
                'label' => 'NRIC',
                'done' => self::docsVerified($docChecks, ['nric_front', 'nric_back']),
                'mandatory' => true,
            ],
            'university_verification' => [
                'label' => 'University verification',
                'done' => (bool) ($assessment->university_balance_verified ?? false),
                'mandatory' => true,
            ],
            'interview' => [
                'label' => 'Interview',
                'done' => (bool) ($assessment->interview_conducted ?? false),
                'mandatory' => false,
            ],
            'reviewer_recommendation' => [
                'label' => 'Reviewer recommendation',
                'done' => filled($assessment->recommendation ?? null)
                    && $assessment->recommended_amount !== null
                    && filled($assessment->recommendation_submitted_at ?? null),
                'mandatory' => true,
            ],
            'committee_decision' => [
                'label' => 'Committee decision',
                'done' => filled($assessment->committee_decision ?? null),
                'mandatory' => false,
            ],
        ];

        $doneCount = collect($items)->where('done', true)->count();
        $percent = (int) round(($doneCount / max(1, count($items))) * 100);

        $canSubmit = collect($items)
            ->filter(fn ($item) => $item['mandatory'])
            ->every(fn ($item) => $item['done']);

        return [
            'percent' => $percent,
            'items' => $items,
            'can_submit_to_committee' => $canSubmit,
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, EducationAidDocumentCheck>  $checks
     * @param  list<string>  $keys
     */
    private static function docsVerified($checks, array $keys, bool $requireAll = true): bool
    {
        if ($checks->isEmpty()) {
            return false;
        }

        $relevant = $checks->whereIn('document_key', $keys);
        if ($relevant->isEmpty()) {
            return false;
        }

        $verified = $relevant->filter(function (EducationAidDocumentCheck $check) {
            return in_array($check->verification_status, ['verified', 'not_applicable'], true);
        });

        if ($requireAll) {
            return $verified->count() >= count($keys);
        }

        return $verified->isNotEmpty();
    }

    private static function hasSubmittedFile(CommunityAidSubmission $submission, string $field): bool
    {
        $value = $submission->getAttribute($field);
        if (is_array($value)) {
            return count(array_filter($value)) > 0;
        }

        return filled($value);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, EducationAidDocumentCheck>  $checks
     */
    private static function docsStatus($checks, string $key, string $status): bool
    {
        $check = $checks->firstWhere('document_key', $key);

        return $check && $check->verification_status === $status;
    }
}
