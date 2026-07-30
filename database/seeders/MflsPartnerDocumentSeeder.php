<?php

namespace Database\Seeders;

use App\Models\MflsPartnerDocument;
use App\Services\Welfare\MflsPartnerDocumentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MflsPartnerDocumentSeeder extends Seeder
{
    private const ORIGINAL_FILENAMES = [
        'bac' => 'BAC - Website Copy.xlsx',
        'binary' => 'Binary - Website Copy.xlsx',
        'iact' => 'IACT - Website Copy.xlsx',
        'mahsa' => 'MAHSA - Website Copy.xlsx',
        'reliance' => 'Reliance - Website Copy.xlsx',
        'asia-drone' => 'Asia Drone Technical Academy - Website Copy.xlsx',
        'autotronics' => 'Autotronics Center of Excellence - Website Copy.xlsx',
        'unimy' => 'UNIMY - Website Copy.xlsx',
        'unitar' => 'UNITAR - Website Copy.xlsx',
        'uoc' => 'UOC - Website Copy.xlsx',
        'veritas' => 'VERITAS - Website Copy.xlsx',
    ];

    public function run()
    {
        $assetDir = database_path('seeders/assets/mfls-partner-programmes');

        foreach (self::ORIGINAL_FILENAMES as $partnerId => $originalFilename) {
            $assetPath = $assetDir . DIRECTORY_SEPARATOR . $originalFilename;
            $storedPath = MflsPartnerDocumentService::STORAGE_DIR . '/' . $partnerId . '.xlsx';
            $destinationPath = storage_path('app/' . $storedPath);

            if (!is_file($assetPath)) {
                continue;
            }

            File::ensureDirectoryExists(dirname($destinationPath));

            if (!is_file($destinationPath) || filemtime($assetPath) > filemtime($destinationPath)) {
                File::copy($assetPath, $destinationPath);
            }

            MflsPartnerDocument::updateOrCreate(
                ['partner_id' => $partnerId],
                [
                    'original_filename' => $originalFilename,
                    'stored_path' => $storedPath,
                ]
            );
        }
    }
}
