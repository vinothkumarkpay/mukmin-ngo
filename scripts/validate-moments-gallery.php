<?php

$root = $argv[1] ?? dirname(__DIR__);
$root = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $root), DIRECTORY_SEPARATOR);

$config = require $root . '/config/welfare_gallery.php';
$base = $root . '/public/welfare/img/moments';

echo "Root: {$root}\n";

$folders = array_values(array_filter(scandir($base) ?: [], function ($f) use ($base) {
    return $f !== '.' && $f !== '..' && is_dir($base . DIRECTORY_SEPARATOR . $f);
}));

$hidden = $config['moments_hidden_folders'] ?? [];
$mapped = $config['news_folders'] ?? [];

$errors = [];

echo "=== Mapping validation ({$root}) ===\n";
foreach ($mapped as $tab => $name) {
    $ok = in_array($name, $folders, true);
    $status = $ok ? 'OK' : 'MISSING';
    echo "tab {$tab}: [{$status}] {$name}\n";
    if (! $ok) {
        $errors[] = "Tab {$tab} maps to missing folder: {$name}";
    }
}

echo "\n=== Unmapped folders (not hidden) ===\n";
$unmapped = 0;
foreach ($folders as $folder) {
    if (! in_array($folder, $mapped, true) && ! in_array($folder, $hidden, true)) {
        echo "- {$folder}\n";
        $unmapped++;
        $errors[] = "Folder on disk is not mapped or hidden: {$folder}";
    }
}
if ($unmapped === 0) {
    echo "(none)\n";
}

echo "\n=== Hidden folders on disk ===\n";
foreach ($hidden as $hiddenFolder) {
    $ok = in_array($hiddenFolder, $folders, true);
    echo ($ok ? 'OK' : 'MISSING') . ": {$hiddenFolder}\n";
    if (! $ok) {
        $errors[] = "Hidden folder missing from disk: {$hiddenFolder}";
    }
}

echo "\n";
if ($errors) {
    echo "FAILED with " . count($errors) . " issue(s):\n";
    foreach ($errors as $error) {
        echo "- {$error}\n";
    }
    exit(1);
}

echo "PASSED: all mappings match folders on disk.\n";
exit(0);
