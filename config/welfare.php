<?php

return [

    'name' => env('WELFARE_SITE_NAME', 'Welfare'),
    'tagline' => env('WELFARE_TAGLINE', 'Nonprofit Organization & Charity'),
    'org_name' => env('WELFARE_ORG_NAME', 'PERTUBUHAN GABUNGAN MUKMIN NASIONAL (PPM-019-10-15042026)'),
    'email' => env('WELFARE_EMAIL', 'support@mukmin.org'),
    'phone' => env('WELFARE_PHONE', '+6014 302 1800'),
    'address' => env('WELFARE_ADDRESS', 'No 73, Jalan TPK 2/8, Taman Perindustrian Kinrara, Puchong'),
    'address_short' => env('WELFARE_ADDRESS_SHORT', 'Petaling, Selangor, Malaysia - 47180'),
    'postal' => env('WELFARE_POSTAL', '47180 Petaling, Selangor'),
    'country' => env('WELFARE_COUNTRY', 'Malaysia'),

    'assets' => 'welfare',

    /* Matches demo theme settings (theme-settings.txt) */
    'theme' => [
        'layout' => 'liquid',
        'fixed_header' => true,
        'fixed_footer' => true,
        'header_style' => 'l_nav',
        'header_top_line' => true,
        'header_top_height' => 35,
        'header_mid_height' => 110,
        'header_bot_height' => 50,
        'header_overlaps' => false,
        'header_search' => true,
        'header_donations_but' => true,
        'header_donations_text' => 'Donate Now',
        'header_top_donations_but' => true,
        'header_top_donations_text' => 'Donate Now',
        'header_top_info' => true,
        'footer_scheme' => 'footer',
        'footer_copyright' => '© 2026 Pertubuhan Gabungan MUKMIN Nasional (PPM-019-10-15042026) | Integrity | Legal Disclaimer',
        'primary_color' => '#d43c18',
        'ilightbox_skin' => 'dark',
    ],

    'google_fonts' => 'Roboto:300,300italic,400,400italic,500,500italic,700,700italic',

    'nav' => [
        ['label' => 'Home', 'route' => 'welfare.home'],
        [
            'label' => 'About Us',
            'route' => 'welfare.about',
            'children' => [
                ['label' => 'Who We Are', 'route' => 'welfare.about.who-we-are'],
                ['label' => "President's Note", 'route' => 'welfare.about.president-note'],
                ['label' => 'Leadership & Governance', 'route' => 'welfare.about.leadership'],
            ]
        ],
        ['label' => 'Our Ecosystem', 'route' => 'welfare.ecosystem'],
        ['label' => 'Serve Together', 'route' => 'welfare.serve'],
        ['label' => 'Impact Areas', 'route' => 'welfare.impact'],
        ['label' => 'News & Gallery', 'route' => 'welfare.news'],
        ['label' => 'Changing Lives', 'route' => 'welfare.changing'],
        ['label' => 'Contact Us', 'route' => 'welfare.contact'],
    ],

    'footer_nav' => [
    ],

    'social' => [
        ['icon' => 'cmsms-icon-facebook', 'url' => 'https://web.facebook.com/profile.php?id=61590435118262'],
        ['icon' => 'cmsms-icon-instagram', 'url' => 'https://youtube.com/@mukmin-i7l?si=ZDB9eyr679HET6Ew'],
        ['icon' => 'cmsms-icon-linkedin', 'url' => 'https://www.linkedin.com/in/mukminofficial/'],
        ['icon' => 'cmsms-icon-tiktok', 'url' => 'https://www.tiktok.com/@mukminnasional?is_from_webapp=1&sender_device=pc'],
        ['icon' => 'cmsms-icon-youtube', 'url' => 'https://youtube.com/@mukmin-i7l?si=ZDB9eyr679HET6Ew'],
        ['icon' => 'cmsms-icon-twitter', 'url' => 'https://x.com/Mukminmy'],
    ],

];
