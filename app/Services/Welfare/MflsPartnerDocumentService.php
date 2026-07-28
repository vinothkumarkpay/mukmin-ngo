<?php

namespace App\Services\Welfare;

use App\Models\MflsPartnerDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MflsPartnerDocumentService
{
    public const STORAGE_DIR = 'mfls/partner-programmes';

    public function partnerIds(): array
    {
        return array_column(config('mfls_partners.institutions', []), 'id');
    }

    public function partnerName(string $partnerId): ?string
    {
        $partner = $this->findPartner($partnerId);

        return $partner['name'] ?? null;
    }

    public function findPartner(string $partnerId): ?array
    {
        foreach (config('mfls_partners.institutions', []) as $partner) {
            if ($partner['id'] === $partnerId) {
                return $partner;
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    public function partnerProgrammes(array $partner): array
    {
        if (!empty($partner['programme_groups'])) {
            $programmes = [];
            foreach ($partner['programme_groups'] as $group) {
                foreach ($group['programmes'] as $programme) {
                    $programmes[] = $programme;
                }
            }

            return $programmes;
        }

        return $partner['programmes'] ?? [];
    }

    public function isValidPartnerId(string $partnerId): bool
    {
        return in_array($partnerId, $this->partnerIds(), true);
    }

    public function findForPartner(string $partnerId): ?MflsPartnerDocument
    {
        if (!$this->isValidPartnerId($partnerId)) {
            return null;
        }

        return MflsPartnerDocument::where('partner_id', $partnerId)->first();
    }

    public function absolutePath(MflsPartnerDocument $document): string
    {
        return storage_path('app/' . ltrim($document->stored_path, '/'));
    }

    public function storeUpload(string $partnerId, UploadedFile $file): MflsPartnerDocument
    {
        if (!$this->isValidPartnerId($partnerId)) {
            abort(404);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'xlsx');
        $storedPath = self::STORAGE_DIR . '/' . $partnerId . '.' . $extension;

        File::ensureDirectoryExists(storage_path('app/' . self::STORAGE_DIR), 0775);

        $existing = $this->findForPartner($partnerId);
        if ($existing) {
            Storage::disk('local')->delete($existing->stored_path);
        }

        Storage::disk('local')->putFileAs(
            self::STORAGE_DIR,
            $file,
            $partnerId . '.' . $extension
        );

        $document = MflsPartnerDocument::updateOrCreate(
            ['partner_id' => $partnerId],
            [
                'original_filename' => $file->getClientOriginalName(),
                'stored_path' => $storedPath,
            ]
        );
        $document->touch();

        return $document->refresh();
    }

    public function documentUpdatedAt(?MflsPartnerDocument $document): ?\Illuminate\Support\Carbon
    {
        if (!$document || !is_file($this->absolutePath($document))) {
            return null;
        }

        $dbUpdatedAt = $document->updated_at;
        $fileUpdatedAt = \Illuminate\Support\Carbon::createFromTimestamp(
            filemtime($this->absolutePath($document)),
            config('app.timezone')
        );

        if ($dbUpdatedAt === null) {
            return $fileUpdatedAt->timezone('Asia/Kuala_Lumpur');
        }

        $latest = $dbUpdatedAt->gt($fileUpdatedAt) ? $dbUpdatedAt : $fileUpdatedAt;

        return $latest->timezone('Asia/Kuala_Lumpur');
    }

    public function bootstrapDocumentsIfMissing(): void
    {
        $assetDir = database_path('seeders/assets/mfls-partner-programmes');

        if (!is_dir($assetDir)) {
            return;
        }

        $originalFilenames = [
            'bac' => 'BAC - Website Copy.xlsx',
            'binary' => 'Binary - Website Copy.xlsx',
            'iact' => 'IACT - Website Copy.xlsx',
            'mahsa' => 'MAHSA - Website Copy.xlsx',
            'reliance' => 'Reliance - Website Copy.xlsx',
            'sg-academy' => 'SG Academy - Website Copy.xlsx',
            'unimy' => 'UNIMY - Website Copy.xlsx',
            'unitar' => 'UNITAR - Website Copy.xlsx',
            'uoc' => 'UOC - Website Copy.xlsx',
            'veritas' => 'VERITAS - Website Copy.xlsx',
        ];

        foreach ($originalFilenames as $partnerId => $originalFilename) {
            $existing = $this->findForPartner($partnerId);
            if ($existing && is_file($this->absolutePath($existing))) {
                continue;
            }

            $assetPath = $assetDir . DIRECTORY_SEPARATOR . $originalFilename;
            if (!is_file($assetPath)) {
                continue;
            }

            $storedPath = self::STORAGE_DIR . '/' . $partnerId . '.xlsx';
            $destinationPath = storage_path('app/' . $storedPath);

            if (!is_dir(dirname($destinationPath))) {
                mkdir(dirname($destinationPath), 0755, true);
            }

            copy($assetPath, $destinationPath);

            MflsPartnerDocument::updateOrCreate(
                ['partner_id' => $partnerId],
                [
                    'original_filename' => $originalFilename,
                    'stored_path' => $storedPath,
                ]
            );
        }
    }

    /**
     * Parse programme requirement rows from the partner Excel sheet.
     *
     * @return array<int, array<string, mixed>>
     */
    public function programmeRequirementRows(string $partnerId): array
    {
        $document = $this->findForPartner($partnerId);
        if (!$document || !is_file($this->absolutePath($document))) {
            return [];
        }

        $spreadsheet = IOFactory::load($this->absolutePath($document));
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $headerMap = null;
        $programmes = [];

        foreach ($rows as $row) {
            $cells = [];
            foreach ($row as $col => $value) {
                $cells[$col] = $this->normalizeCellValue($value);
            }

            if ($headerMap === null) {
                $detected = $this->detectRequirementHeaderMap($cells);
                if ($detected !== null) {
                    $headerMap = $detected;
                }
                continue;
            }

            $programmeName = $cells[$headerMap['programme']] ?? '';
            if ($programmeName === '' || $this->looksLikeHeaderLabel($programmeName)) {
                continue;
            }

            $entry = [
                'programme' => $programmeName,
                'venue' => $this->cellFromMap($cells, $headerMap, 'venue'),
                'course_fee' => $this->cellFromMap($cells, $headerMap, 'course_fee'),
                'scholarship_coverage' => $this->cellFromMap($cells, $headerMap, 'scholarship_coverage'),
                'waived_amount' => $this->cellFromMap($cells, $headerMap, 'waived_amount'),
                'exclusions' => $this->cellFromMap($cells, $headerMap, 'exclusions'),
                'academic_requirements' => $this->cellFromMap($cells, $headerMap, 'academic_requirements'),
                'financial_requirement' => $this->cellFromMap($cells, $headerMap, 'financial_requirement'),
                'academic_requirements_b40' => $this->cellFromMap($cells, $headerMap, 'academic_requirements_b40'),
                'academic_requirements_merit' => $this->cellFromMap($cells, $headerMap, 'academic_requirements_merit'),
            ];

            if ($entry['academic_requirements'] === ''
                && ($entry['academic_requirements_b40'] !== '' || $entry['academic_requirements_merit'] !== '')
            ) {
                $parts = [];
                if ($entry['academic_requirements_b40'] !== '') {
                    $parts[] = 'B40 Household Income Category: ' . $entry['academic_requirements_b40'];
                }
                if ($entry['academic_requirements_merit'] !== '') {
                    $parts[] = 'Excellent Academic Merit Category: ' . $entry['academic_requirements_merit'];
                }
                $entry['academic_requirements'] = implode("\n\n", $parts);
            }

            $programmes[] = $entry;
        }

        return $programmes;
    }

    /**
     * Find requirement details for a selected programme name.
     *
     * @return array<string, mixed>|null
     */
    public function findProgrammeRequirements(string $partnerId, string $programmeName): ?array
    {
        $rows = $this->programmeRequirementRows($partnerId);
        if ($rows === []) {
            return null;
        }

        $needle = $this->normalizeProgrammeKey($programmeName);
        $best = null;
        $bestScore = 0.0;

        foreach ($rows as $row) {
            $candidate = $this->normalizeProgrammeKey($row['programme']);
            if ($candidate === '' || $needle === '') {
                continue;
            }

            if ($candidate === $needle) {
                return $row;
            }

            similar_text($candidate, $needle, $percent);
            if ($percent > $bestScore) {
                $bestScore = $percent;
                $best = $row;
            }
        }

        // Allow near-matches for sheet typos (e.g. "Foudation" vs "Foundation").
        if ($best !== null && $bestScore >= 82.0) {
            return $best;
        }

        return null;
    }

    private function normalizeCellValue($value): string
    {
        if ($value === null) {
            return '';
        }

        $text = trim(preg_replace('/\s+/u', ' ', (string) $value) ?? '');

        return $text;
    }

    private function looksLikeHeaderLabel(string $value): bool
    {
        $normalized = strtolower($value);

        return in_array($normalized, [
            'no.',
            'no',
            'programmes offered',
            'programme offered',
            'programs offered',
        ], true);
    }

    /**
     * @param  array<string, string>  $cells
     * @return array<string, string>|null
     */
    private function detectRequirementHeaderMap(array $cells): ?array
    {
        $map = [];

        foreach ($cells as $col => $label) {
            if ($label === '') {
                continue;
            }

            $normalized = strtolower($label);

            if (str_contains($normalized, 'programmes offered')
                || str_contains($normalized, 'programme offered')
                || str_contains($normalized, 'programs offered')
            ) {
                $map['programme'] = $col;
            } elseif ($normalized === 'venue' || str_starts_with($normalized, 'venue')) {
                $map['venue'] = $col;
            } elseif (str_contains($normalized, 'course fee')) {
                $map['course_fee'] = $col;
            } elseif (str_contains($normalized, 'scholarship coverage')) {
                $map['scholarship_coverage'] = $col;
            } elseif (str_contains($normalized, 'waived amount')) {
                $map['waived_amount'] = $col;
            } elseif ($normalized === 'exclusions' || str_starts_with($normalized, 'exclusion')) {
                $map['exclusions'] = $col;
            } elseif (str_contains($normalized, 'b40') && str_contains($normalized, 'academic') === false
                && (str_contains($normalized, 'household') || str_contains($normalized, 'income category'))
                && !str_contains($normalized, 'financial')
            ) {
                $map['academic_requirements_b40'] = $col;
            } elseif (str_contains($normalized, 'excellent academic merit')
                || (str_contains($normalized, 'merit') && str_contains($normalized, 'academic'))
            ) {
                $map['academic_requirements_merit'] = $col;
            } elseif (str_contains($normalized, 'academic requirement')) {
                $map['academic_requirements'] = $col;
            } elseif (str_contains($normalized, 'financial requirement')
                || ($normalized === 'financial' || str_starts_with($normalized, 'financial '))
            ) {
                $map['financial_requirement'] = $col;
            }
        }

        // UNITAR-style sheets label B40 / Merit columns without the word "Academic" in-row.
        if (!isset($map['academic_requirements_b40']) || !isset($map['academic_requirements_merit'])) {
            foreach ($cells as $col => $label) {
                $normalized = strtolower($label);
                if (!isset($map['academic_requirements_b40'])
                    && str_contains($normalized, 'b40')
                    && str_contains($normalized, 'household')
                ) {
                    $map['academic_requirements_b40'] = $col;
                }
                if (!isset($map['academic_requirements_merit'])
                    && str_contains($normalized, 'excellent academic merit')
                ) {
                    $map['academic_requirements_merit'] = $col;
                }
            }
        }

        return isset($map['programme']) ? $map : null;
    }

    /**
     * @param  array<string, string>  $cells
     * @param  array<string, string>  $headerMap
     */
    private function cellFromMap(array $cells, array $headerMap, string $key): string
    {
        if (!isset($headerMap[$key])) {
            return '';
        }

        return $cells[$headerMap[$key]] ?? '';
    }

    private function normalizeProgrammeKey(string $value): string
    {
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '', $value) ?? '';

        return $value;
    }

    public function buildPreviewPayload(MflsPartnerDocument $document): array
    {
        $path = $this->absolutePath($document);

        if (!is_file($path)) {
            abort(404, 'Programme information document not found.');
        }

        $spreadsheet = IOFactory::load($path);
        $writer = new Html($spreadsheet);
        $writer->setEmbedImages(true);

        $html = $writer->generateHTMLAll();

        $styles = '';
        if (preg_match_all('/<style[^>]*>(.*?)<\/style>/is', $html, $styleMatches)) {
            $styles = implode("\n", $styleMatches[1]);
        }

        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $bodyMatch)) {
            $body = $bodyMatch[1];
        } else {
            $body = preg_replace('/<\!DOCTYPE[^>]*>/i', '', $html);
            $body = preg_replace('/<\/?html[^>]*>/i', '', $body);
            $body = preg_replace('/<\/?head[^>]*>.*?<\/head>/is', '', $body);
            $body = preg_replace('/<\/?body[^>]*>/i', '', $body);
        }

        return [
            'styles' => $styles . "\n" . $this->previewEnhancementCss(),
            'html' => trim($body),
        ];
    }

    public function renderHtmlPreview(MflsPartnerDocument $document): string
    {
        $payload = $this->buildPreviewPayload($document);

        return '<style>' . $payload['styles'] . '</style>' . $payload['html'];
    }

    private function previewEnhancementCss(): string
    {
        return <<<'CSS'
body {
    margin: 0;
    padding: 0;
    background: #ffffff;
    font-family: Calibri, Arial, Helvetica, sans-serif;
}
table {
    border-collapse: collapse;
    width: max-content;
    min-width: 100%;
}
td, th {
    padding: 10px 14px !important;
    line-height: 1.5;
    vertical-align: top !important;
}
tbody tr:nth-child(even) td:not(.style1):not(.style6):not(.style7):not(.style8) {
    background-color: #f5f9f5 !important;
}
tbody tr:hover td:not(.style1):not(.style6):not(.style7):not(.style8) {
    background-color: #eef6ee !important;
}
CSS;
    }

    public function downloadResponse(MflsPartnerDocument $document): StreamedResponse
    {
        $path = $this->absolutePath($document);

        if (!is_file($path)) {
            abort(404, 'Programme information document not found.');
        }

        return response()->streamDownload(function () use ($path) {
            $stream = fopen($path, 'rb');
            if ($stream !== false) {
                fpassthru($stream);
                fclose($stream);
            }
        }, $document->original_filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    public function documentsForAdmin(): array
    {
        $documents = MflsPartnerDocument::query()
            ->whereIn('partner_id', $this->partnerIds())
            ->get()
            ->keyBy('partner_id');

        $rows = [];

        foreach (config('mfls_partners.institutions', []) as $partner) {
            $document = $documents->get($partner['id']);

            $rows[] = [
                'id' => $partner['id'],
                'name' => $partner['name'],
                'document' => $document,
                'has_document' => $document !== null && is_file($this->absolutePath($document)),
                'updated_at' => $this->documentUpdatedAt($document),
            ];
        }

        return $rows;
    }
}
