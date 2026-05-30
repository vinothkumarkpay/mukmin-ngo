<?php

$root = dirname(__DIR__);
$logoPath = $root . '/public/coming-soon-logo.png';

if (! is_file($logoPath)) {
    $logoPath = $root . '/public/welfare/img/mukmin_logo.png';
}

if (! is_file($logoPath)) {
    fwrite(STDERR, "Logo file not found.\n");
    exit(1);
}

$dataUri = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
$htmlPath = $root . '/public/coming-soon.html';
$html = file_get_contents($htmlPath);

$updated = preg_replace(
    '/src="[^"]*" alt="MUKMIN Logo"/',
    'src="' . $dataUri . '" alt="MUKMIN Logo"',
    $html,
    1,
    $count
);

if ($count !== 1) {
    fwrite(STDERR, "Could not update logo src in coming-soon.html.\n");
    exit(1);
}

file_put_contents($htmlPath, $updated);
echo "Embedded logo in coming-soon.html\n";
