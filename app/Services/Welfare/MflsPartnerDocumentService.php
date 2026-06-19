<?php

namespace App\Services\Welfare;

use App\Models\MflsPartnerDocument;
use Illuminate\Http\UploadedFile;
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
        foreach (config('mfls_partners.institutions', []) as $partner) {
            if ($partner['id'] === $partnerId) {
                return $partner['name'];
            }
        }

        return null;
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

        $existing = $this->findForPartner($partnerId);
        if ($existing && $existing->stored_path !== $storedPath) {
            Storage::disk('local')->delete($existing->stored_path);
        }

        Storage::disk('local')->putFileAs(
            self::STORAGE_DIR,
            $file,
            $partnerId . '.' . $extension
        );

        return MflsPartnerDocument::updateOrCreate(
            ['partner_id' => $partnerId],
            [
                'original_filename' => $file->getClientOriginalName(),
                'stored_path' => $storedPath,
            ]
        );
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
                'updated_at' => $document ? $document->updated_at : null,
            ];
        }

        return $rows;
    }
}
