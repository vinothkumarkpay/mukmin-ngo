<?php

namespace App\Support;

class SubmissionStatus
{
    public const RECEIVED = 'received';
    public const REVIEWING = 'reviewing';
    public const PENDING_APPROVAL = 'pending_approval';
    public const FURTHER_INFO_REQUIRED = 'further_info_required';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const COMPLETED = 'completed';

    /** @var array<string, string> */
    private const LABELS = [
        self::RECEIVED => 'Received / New',
        self::REVIEWING => 'Reviewing',
        self::PENDING_APPROVAL => 'Pending for Approval',
        self::FURTHER_INFO_REQUIRED => 'Further Information Required',
        self::APPROVED => 'Approved',
        self::REJECTED => 'Rejected',
        self::COMPLETED => 'Completed',
    ];

    /** @var array<string, string> */
    private const LEGACY_MAP = [
        'pending' => self::RECEIVED,
        'under_review' => self::REVIEWING,
        'new' => self::RECEIVED,
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

    public static function label(?string $status): string
    {
        if ($status === null || $status === '') {
            return self::LABELS[self::RECEIVED];
        }

        $normalized = self::normalize($status);

        return self::LABELS[$normalized] ?? ucfirst(str_replace('_', ' ', $status));
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

        return self::LEGACY_MAP[$status] ?? $status;
    }

    public static function badgeClass(?string $status): string
    {
        return 'badge-' . self::normalize($status);
    }

    /** @return list<string> */
    public static function matchingValues(?string $status): array
    {
        return self::storedValuesFor($status);
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

        $normalizedRecord = self::normalize($recordStatus);
        $normalizedFilter = self::normalize($filterStatus);

        return $normalizedRecord === $normalizedFilter;
    }
}
