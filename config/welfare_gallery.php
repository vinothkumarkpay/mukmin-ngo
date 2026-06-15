<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Moments of MUKMIN gallery (News page)
    |--------------------------------------------------------------------------
    |
    | Place images in public/welfare/img/moments/{folder-name}/
    | Each subfolder is auto-discovered and used as a filter category.
    |
    */
    'moments_path' => 'welfare/img/moments',

    /*
    |--------------------------------------------------------------------------
    | Moments gallery folders to hide
    |--------------------------------------------------------------------------
    |
    | Subfolder names under moments_path that should not appear in the
    | "Moments of MUKMIN" filter bar or image grid (files may remain on disk).
    |
    */
    'moments_hidden_folders' => [
        'Sirat Leaders',
    ],

    /*
    |--------------------------------------------------------------------------
    | Impact Insights news tab → Moments gallery folder
    |--------------------------------------------------------------------------
    |
    | Maps each news tab (event-tab-{id}) to the matching moments subfolder name.
    |
    */
    'news_folders' => [
        1 => 'MUKMIN Hari Raya Aidilfitri Open House 2025',
        2 => 'SIRAT Leaders Forum 2025',
        3 => 'SIRAT Youth Summit 2026',
        4 => 'FIKRAH Launch',
        5 => 'FIKRAH Global Roundtable',
        6 => 'MUKMIN Future Leaders Scholarship Pledge',
        7 => 'SIRAT Global Forum 2026',
        8 => 'The KL Declaration',
        9 => 'MUKMIN Ramadan Food Basket Initiative',
        10 => 'MUKMIN Majlis Berbuka Puasa Penang',
        11 => 'Ramadhan Assistance for Religious Scholars & Ustaz',
        12 => 'MUKMIN Majlis Berbuka Puasa Kuala Lumpur',
        13 => 'MUKMIN Takbir Raya',
        14 => 'MUKMIN Youth Icon Awards',
        15 => "MUKMIN's 1st Inaugural AGM",
        16 => 'India High Commissioner to Malaysia Felicitation Ceremony',
        17 => 'Golden Dinar Awards',
        18 => 'FIKRAH Chai & Chat',
        19 => 'MUKMIN Shark Tank Pitching',
        20 => 'MUKMIN Football Friendly： KL vs Penang',
        21 => 'MUKMIN Official Jersey Launch',
    ],
];
