<?php

namespace App\Services\Welfare;

use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionImporter
{
    public function downloadTemplate(string $type): StreamedResponse
    {
        $registry = SubmissionImportRegistry::for($type);
        $spreadsheet = new Spreadsheet();

        $dataSheet = $spreadsheet->getActiveSheet();
        $dataSheet->setTitle('Data');

        $headers = array_map(static function ($column) {
            return $column['label'];
        }, $registry->columns());

        $dataSheet->fromArray($headers, null, 'A1');

        $example = $registry->exampleRow();
        if ($example) {
            $dataSheet->fromArray($example, null, 'A2');
        }

        $instructionsSheet = $spreadsheet->createSheet();
        $instructionsSheet->setTitle('Instructions');

        $row = 1;
        foreach ($registry->instructions() as $line) {
            $instructionsSheet->setCellValue('A' . $row, $line);
            $row++;
        }

        $instructionsSheet->getColumnDimension('A')->setWidth(120);
        $spreadsheet->setActiveSheetIndex(0);

        $filename = "mukmin_import_template_{$type}.xlsx";

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * @return array{imported: int, skipped: int, errors: list<string>}
     */
    public function import(string $type, UploadedFile $file): array
    {
        $registry = SubmissionImportRegistry::for($type);
        $rows = $this->parseFile($file);
        $labelMap = [];

        foreach ($registry->columns() as $key => $column) {
            $labelMap[$this->normalizeHeader($column['label'])] = $key;
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $modelClass = $registry->modelClass();

        foreach ($rows as $index => $rawRow) {
            $lineNumber = $index + 2;

            if ($this->isEmptyRow($rawRow)) {
                $skipped++;
                continue;
            }

            $mapped = [];
            foreach ($rawRow as $header => $value) {
                $normalizedHeader = $this->normalizeHeader((string) $header);
                if (!isset($labelMap[$normalizedHeader])) {
                    continue;
                }

                $mapped[$labelMap[$normalizedHeader]] = $value;
            }

            if ($this->isEmptyRow($mapped)) {
                $skipped++;
                continue;
            }

            try {
                $data = $registry->mapRow($mapped);
                $modelClass::create($data);
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$lineNumber}: " . $e->getMessage();
            }
        }

        return compact('imported', 'skipped', 'errors');
    }

    /** @return list<array<string, string|null>> */
    private function parseFile(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['csv', 'txt'], true)) {
            return $this->parseCsv($path);
        }

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getSheetByName('Data') ?? $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (empty($rows)) {
            return [];
        }

        $headerRow = array_shift($rows);
        $headers = $this->normalizeSheetHeaders($headerRow);
        $parsed = [];

        foreach ($rows as $row) {
            $assoc = [];
            foreach ($headers as $columnKey => $headerLabel) {
                $assoc[$headerLabel] = isset($row[$columnKey]) ? trim((string) $row[$columnKey]) : '';
            }
            $parsed[] = $assoc;
        }

        return $parsed;
    }

    /** @return list<array<string, string|null>> */
    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }

        $headerRow = fgetcsv($handle);
        if (!$headerRow) {
            fclose($handle);
            return [];
        }

        if (isset($headerRow[0])) {
            $headerRow[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headerRow[0]);
        }

        $headers = array_map(static function ($header) {
            return trim((string) $header);
        }, $headerRow);

        $parsed = [];
        while (($row = fgetcsv($handle)) !== false) {
            $assoc = [];
            foreach ($headers as $i => $header) {
                $assoc[$header] = isset($row[$i]) ? trim((string) $row[$i]) : '';
            }
            $parsed[] = $assoc;
        }

        fclose($handle);

        return $parsed;
    }

    /** @param array<int|string, mixed> $headerRow */
    private function normalizeSheetHeaders(array $headerRow): array
    {
        $headers = [];

        foreach ($headerRow as $columnKey => $header) {
            $label = trim((string) $header);
            if ($label !== '') {
                $headers[$columnKey] = $label;
            }
        }

        return $headers;
    }

    /** @param array<string, mixed> $row */
    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function normalizeHeader(string $header): string
    {
        return strtolower(trim($header));
    }
}
