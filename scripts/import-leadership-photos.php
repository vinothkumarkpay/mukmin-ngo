<?php

/**
 * One-off import: copy leadership photos from temp_layout into public/welfare/img/leadership
 * using filenames that PageController::getMemberImage() resolves.
 */

function nameSlug(string $name): string
{
    return trim(preg_replace('/[^a-z0-9]+/i', '_', strtolower($name)), '_');
}

function normalize(string $text): string
{
    $text = preg_replace('/^\d+\./', '', $text);
    $text = pathinfo($text, PATHINFO_FILENAME);
    $text = str_replace(['_', "'", '’', '.'], ' ', $text);
    $text = preg_replace('/\b(bin|binti|hj|haji|dr|dato|datuk|puan|tuan|cik|prof|madya|seri|wira|ya|is)\b/i', '', $text);

    return preg_replace('/[^a-z0-9]/i', '', strtolower($text));
}

$root = dirname(__DIR__);
$dest = $root . '/public/welfare/img/leadership';

if (! is_dir($dest)) {
    mkdir($dest, 0755, true);
}

$team = require $root . '/config/welfare_team.php';

$sources = [
    'cec' => $root . '/temp_layout/CEC Pictures',
    'exco' => $root . '/temp_layout/Exco Pictures',
    'bureau' => $root . '/temp_layout/Bureau Chairs',
];

$copied = 0;
$unmatched = [];

foreach ($sources as $category => $srcDir) {
    if (! is_dir($srcDir)) {
        fwrite(STDERR, "Missing source directory: {$srcDir}\n");
        continue;
    }

    $files = array_values(array_filter(
        scandir($srcDir) ?: [],
        fn ($file) => ! in_array($file, ['.', '..'], true) && is_file("{$srcDir}/{$file}")
    ));
    usort($files, fn ($a, $b) => strnatcasecmp($a, $b));

    $members = $team[$category] ?? [];

    foreach ($members as $index => $member) {
        $slug = nameSlug($member['name']);
        $matched = null;

        if (preg_match('/^\d+\./', $files[0] ?? '')) {
            $position = $index + 1;
            foreach ($files as $file) {
                if ((int) preg_replace('/\..*$/', '', $file) === $position) {
                    $matched = $file;
                    break;
                }
            }
        }

        if (! $matched) {
            $want = normalize($member['name']);
            foreach ($files as $file) {
                $candidate = normalize($file);
                if ($candidate === $want || strpos($candidate, $want) !== false || strpos($want, $candidate) !== false) {
                    $matched = $file;
                    break;
                }
            }
        }

        if (! $matched) {
            $unmatched[] = "[{$category}] {$member['name']}";
            continue;
        }

        $sourcePath = "{$srcDir}/{$matched}";
        $targets = [
            "{$dest}/{$slug}.jpg",
            "{$dest}/{$category}_{$slug}.jpg",
        ];

        foreach ($targets as $target) {
            if (! copy($sourcePath, $target)) {
                fwrite(STDERR, "Failed to copy to {$target}\n");
                continue;
            }
            $copied++;
        }

        echo "OK [{$category}] {$member['name']} <- {$matched}\n";
    }
}

echo "\nCopied {$copied} files. Unmatched: " . count($unmatched) . "\n";
foreach ($unmatched as $item) {
    echo "  MISSING: {$item}\n";
}

exit(count($unmatched) > 0 ? 1 : 0);
