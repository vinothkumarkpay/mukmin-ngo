<?php

namespace Tests\Unit;

use App\Support\SubmissionStatus;
use PHPUnit\Framework\TestCase;

class SubmissionStatusTest extends TestCase
{
    public function test_stored_values_include_slug_legacy_and_label(): void
    {
        $values = SubmissionStatus::storedValuesFor('received');

        $this->assertContains('received', $values);
        $this->assertContains('pending', $values);
        $this->assertContains('new', $values);
        $this->assertContains('Received / New', $values);
    }

    public function test_matches_filter_accepts_label_and_legacy_values(): void
    {
        $this->assertTrue(SubmissionStatus::matchesFilter('pending', 'received'));
        $this->assertTrue(SubmissionStatus::matchesFilter('Received / New', 'received'));
        $this->assertFalse(SubmissionStatus::matchesFilter('Received / New', 'reviewing'));
        $this->assertFalse(SubmissionStatus::matchesFilter('received', 'approved'));
    }

    public function test_normalize_maps_human_readable_labels_to_slugs(): void
    {
        $this->assertSame('received', SubmissionStatus::normalize('Received / New'));
        $this->assertSame('reviewing', SubmissionStatus::normalize('Reviewing'));
        $this->assertSame('approved', SubmissionStatus::normalize('Approved'));
    }
}
