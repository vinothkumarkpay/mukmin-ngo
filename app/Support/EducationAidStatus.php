<?php

namespace App\Support;

class EducationAidStatus
{
    public const RECEIVED = 'received';
    public const INITIAL_SCREENING = 'initial_screening';
    public const DOCUMENTS_INCOMPLETE = 'documents_incomplete';
    public const DOCUMENTS_COMPLETE = 'documents_complete';
    public const ASSESSMENT_PENDING = 'assessment_pending';
    public const UNDER_ASSESSMENT = 'under_assessment';
    public const INTERVIEW_REQUIRED = 'interview_required';
    public const COMMITTEE_REVIEW = 'committee_review';
    public const APPROVED_FULL = 'approved_full';
    public const APPROVED_PARTIAL = 'approved_partial';
    public const DEFERRED_FURTHER_INFO = 'deferred_further_info';
    public const ALTERNATIVE_ASSISTANCE = 'alternative_assistance';
    public const NOT_APPROVED = 'not_approved';
    public const PAYMENT_PROCESSING = 'payment_processing';
    public const PAID = 'paid';
    public const CASE_CLOSED = 'case_closed';

    /** @var array<string, string> */
    private const LABELS = [
        self::RECEIVED => 'Received',
        self::INITIAL_SCREENING => 'Initial Screening',
        self::DOCUMENTS_INCOMPLETE => 'Documents Incomplete',
        self::DOCUMENTS_COMPLETE => 'Documents Complete',
        self::ASSESSMENT_PENDING => 'Assessment Pending',
        self::UNDER_ASSESSMENT => 'Under Assessment',
        self::INTERVIEW_REQUIRED => 'Interview Required',
        self::COMMITTEE_REVIEW => 'Committee Review',
        self::APPROVED_FULL => 'Approved – Full',
        self::APPROVED_PARTIAL => 'Approved – Partial',
        self::DEFERRED_FURTHER_INFO => 'Deferred – Further Information',
        self::ALTERNATIVE_ASSISTANCE => 'Alternative Assistance / Counselling',
        self::NOT_APPROVED => 'Not Approved',
        self::PAYMENT_PROCESSING => 'Payment Processing',
        self::PAID => 'Paid',
        self::CASE_CLOSED => 'Case Closed',
    ];

    /**
     * Map legacy shared statuses onto the Education Aid workflow.
     *
     * @var array<string, string>
     */
    private const LEGACY_MAP = [
        'pending' => self::RECEIVED,
        'new' => self::RECEIVED,
        'under_review' => self::UNDER_ASSESSMENT,
        'reviewing' => self::UNDER_ASSESSMENT,
        'pending_approval' => self::COMMITTEE_REVIEW,
        'further_info_required' => self::DEFERRED_FURTHER_INFO,
        'approved' => self::APPROVED_FULL,
        'rejected' => self::NOT_APPROVED,
        'completed' => self::CASE_CLOSED,
    ];

    /** @return array<string, string> */
    public static function options(): array
    {
        return self::LABELS;
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_keys(self::LABELS);
    }

    public static function validationRule(): string
    {
        return 'required|string|in:' . implode(',', self::values());
    }

    public static function default(): string
    {
        return self::RECEIVED;
    }

    public static function normalize(?string $status): string
    {
        if ($status === null || $status === '') {
            return self::RECEIVED;
        }

        $trimmed = trim($status);

        foreach (self::LABELS as $slug => $label) {
            if (strcasecmp($trimmed, $label) === 0) {
                return $slug;
            }
        }

        $status = strtolower($trimmed);

        if (isset(self::LABELS[$status])) {
            return $status;
        }

        return self::LEGACY_MAP[$status] ?? $status;
    }

    public static function label(?string $status): string
    {
        $normalized = self::normalize($status);

        return self::LABELS[$normalized] ?? ucfirst(str_replace('_', ' ', (string) $status));
    }

    public static function badgeClass(?string $status): string
    {
        return 'badge-aid-' . self::normalize($status);
    }

    /** @return list<string> */
    public static function storedValuesFor(?string $status): array
    {
        if ($status === null || $status === '') {
            return [];
        }

        $normalized = self::normalize($status);
        $values = [$normalized];

        foreach (self::LEGACY_MAP as $legacy => $mapped) {
            if ($mapped === $normalized) {
                $values[] = $legacy;
            }
        }

        if (isset(self::LABELS[$normalized])) {
            $values[] = self::LABELS[$normalized];
        }

        return array_values(array_unique($values));
    }

    public static function matchesFilter(?string $recordStatus, ?string $filterStatus): bool
    {
        if ($filterStatus === null || $filterStatus === '') {
            return true;
        }

        if ($recordStatus === null || $recordStatus === '') {
            $recordStatus = self::default();
        }

        return self::normalize($recordStatus) === self::normalize($filterStatus);
    }

    /** @return list<string> */
    public static function decisionStatuses(): array
    {
        return [
            self::APPROVED_FULL,
            self::APPROVED_PARTIAL,
            self::DEFERRED_FURTHER_INFO,
            self::ALTERNATIVE_ASSISTANCE,
            self::NOT_APPROVED,
        ];
    }

    /** @return list<string> */
    public static function approvedStatuses(): array
    {
        return [self::APPROVED_FULL, self::APPROVED_PARTIAL, self::PAYMENT_PROCESSING, self::PAID, self::CASE_CLOSED];
    }
}
