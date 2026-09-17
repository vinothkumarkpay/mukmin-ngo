<?php

namespace App\Support;

use App\Models\CommunityAidSubmission;
use Carbon\Carbon;

class EducationAidUrgency
{
    public const CRITICAL = 'critical';
    public const HIGH = 'high';
    public const MODERATE = 'moderate';
    public const LOW = 'low';

    /** @var array<string, string> */
    private const LABELS = [
        self::CRITICAL => 'Critical',
        self::HIGH => 'High',
        self::MODERATE => 'Moderate',
        self::LOW => 'Low',
    ];

    /** @return array<string, string> */
    public static function options(): array
    {
        return self::LABELS;
    }

    public static function label(?string $urgency): string
    {
        return self::LABELS[$urgency] ?? 'Moderate';
    }

    /**
     * @return array{level: string, reason: string}
     */
    public static function suggest(CommunityAidSubmission $submission): array
    {
        $deadline = $submission->payment_deadline;
        $daysUntil = null;

        if ($deadline) {
            $daysUntil = Carbon::today()->diffInDays(Carbon::parse($deadline)->startOfDay(), false);
        }

        $consequence = strtolower((string) ($submission->payment_not_made_consequence ?? ''));
        $examOrSuspension = self::hasExamOrSuspensionImpact($consequence);
        $academicImpact = self::hasAcademicImpact($consequence);

        if ($daysUntil !== null && $daysUntil <= 7 && $examOrSuspension) {
            return [
                'level' => self::CRITICAL,
                'reason' => sprintf(
                    'Payment deadline in %s day(s) and examination eligibility / suspension risk indicated.',
                    max(0, (int) $daysUntil)
                ),
            ];
        }

        if ($daysUntil !== null && $daysUntil <= 14 && $academicImpact) {
            return [
                'level' => self::HIGH,
                'reason' => sprintf(
                    'Payment deadline in %s day(s) with academic impact indicated.',
                    max(0, (int) $daysUntil)
                ),
            ];
        }

        if ($daysUntil !== null && $daysUntil <= 7) {
            return [
                'level' => self::HIGH,
                'reason' => sprintf('Payment deadline in %s day(s).', max(0, (int) $daysUntil)),
            ];
        }

        if ($daysUntil !== null && $daysUntil < 0) {
            return [
                'level' => self::CRITICAL,
                'reason' => 'Payment deadline has already passed.',
            ];
        }

        return [
            'level' => self::MODERATE,
            'reason' => 'No critical deadline or examination-impact criteria matched.',
        ];
    }

    private static function hasExamOrSuspensionImpact(string $consequence): bool
    {
        $needles = [
            'cannot sit',
            'exam',
            'examination',
            'suspension',
            'suspended',
            'barred',
            'not allowed to sit',
            'professional examination',
            'pe1',
            'pe 1',
        ];

        foreach ($needles as $needle) {
            if (str_contains($consequence, $needle)) {
                return true;
            }
        }

        return false;
    }

    private static function hasAcademicImpact(string $consequence): bool
    {
        if (self::hasExamOrSuspensionImpact($consequence)) {
            return true;
        }

        $needles = [
            'academic',
            'enrol',
            'enroll',
            'registration',
            'defer',
            'progress',
            'graduate',
            'study',
            'semester',
        ];

        foreach ($needles as $needle) {
            if (str_contains($consequence, $needle)) {
                return true;
            }
        }

        return $consequence !== '';
    }
}
