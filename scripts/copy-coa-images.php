<?php

$src = __DIR__ . '/../temp_layout/members';
$dst = __DIR__ . '/../public/welfare/img/leadership';

$targets = [
    'coa_ya_dato_seri_vazeer_alam_mydin_meera.jpg',
    'coa_datuk_seri_haji_mohamed_iqbal.jpg',
    'coa_datuk_seri_dr_vaseehar_hassan.jpg',
    'coa_datuk_seri_dr_jahaberdeen_mohamed_yunoos.jpg',
    'coa_dato_dr_noorul_ameen_mohamed_ishack.jpg',
    'coa_dato_haji_syed_jamarulkhan_haji_m_s_kadir.jpg',
    'coa_dato_haji_jawahar_ali_taib_khan.jpg',
    'coa_datuk_haji_mohamed_sirajudeen_mohamed_salahudeen.jpg',
    'coa_dato_kadar_shah_abdul_razak.jpg',
    'coa_dato_akbar_moidunny.jpg',
    'coa_dato_shahira_ahmed_bazari.jpg',
    'coa_dato_dr_fazilah_shaik_allaudin.jpg',
    'coa_dr_jasmine_begum.jpg',
    'coa_professor_dr_ainul_jaria_maidin.jpg',
    'coa_tuan_syed_ali_shahul_hameed.jpg',
];

$files = glob($src . '/*.jpeg') ?: [];
natsort($files);
$files = array_values($files);

if (count($files) !== count($targets)) {
    fwrite(STDERR, 'Expected ' . count($targets) . ' source images, found ' . count($files) . PHP_EOL);
    exit(1);
}

if (! is_dir($dst) && ! mkdir($dst, 0755, true)) {
    fwrite(STDERR, "Failed to create {$dst}" . PHP_EOL);
    exit(1);
}

foreach ($files as $i => $file) {
    $out = $dst . DIRECTORY_SEPARATOR . $targets[$i];
    if (! copy($file, $out)) {
        fwrite(STDERR, "Failed to copy {$file} to {$out}" . PHP_EOL);
        exit(1);
    }
    echo basename($out) . PHP_EOL;
}
