<?php

return [

    'name' => env('WELFARE_SITE_NAME', 'Welfare'),
    'tagline' => env('WELFARE_TAGLINE', 'Nonprofit Organization & Charity'),
    'org_name' => env('WELFARE_ORG_NAME', 'PERTUBUHAN GABUNGAN MUKMIN NASIONAL (PPM-019-10-15042026)'),
    'email' => env('WELFARE_EMAIL', 'support@mukmin.org'),
    'form_submission_recipients' => [
        'mfls-scholarship' => 'scholarship@mukmin.org',
        'membership-ordinary' => 'membership@mukmin.org',
        'membership-friends' => 'membership@mukmin.org',
        'community-aid' => 'communitywelfare@mukmin.org',
        'feedback-suggestion' => 'info@mukmin.org',
        'volunteer-registration' => 'membership@mukmin.org',
        'mentor-registration' => 'membership@mukmin.org',
        'partnership-collaboration' => 'membership@mukmin.org',
        'contact' => 'info@mukmin.org',
    ],
    'phone' => env('WELFARE_PHONE', '+6014 302 1800'),
    'whatsapp_url' => env('WELFARE_WHATSAPP_URL', 'https://wa.me/60143021800?text=Salam.%20I%20would%20like%20to%20inquire%20about%20MUKMIN.%20Kindly%20assist%20me.'),
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
        'footer_copyright' => '© 2026 Pertubuhan Gabungan MUKMIN Nasional (PPM-019-10-15042026) | Legal Disclaimer',
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
                ['label' => 'Who We Are', 'route' => 'welfare.about', 'hash' => 'who-we-are'],
                ['label' => "President's Note", 'route' => 'welfare.about', 'hash' => 'president-note'],
                ['label' => 'Leadership & Governance', 'route' => 'welfare.about', 'hash' => 'leadership'],
            ]
        ],
        [
            'label' => 'Our Ecosystem',
            'route' => 'welfare.ecosystem',
            'children' => [
                ['label' => 'FIKRAH', 'route' => 'welfare.ecosystem', 'hash' => 'fikrah'],
                ['label' => 'Gabungan MUKMIN Nasional', 'route' => 'welfare.ecosystem', 'hash' => 'gabungan'],
                ['label' => 'Yayasan MUKMIN', 'route' => 'welfare.ecosystem', 'hash' => 'yayasan'],
            ]
        ],
        [
            'label' => 'Serve Together',
            'route' => 'welfare.serve',
            'children' => [
                ['label' => 'Be Affiliate Member', 'route' => 'welfare.serve', 'hash' => 'affiliate'],
                ['label' => 'Be Friends of MUKMIN', 'route' => 'welfare.serve', 'hash' => 'friends'],
                ['label' => 'Share Your Ideas', 'route' => 'welfare.serve', 'hash' => 'ideas'],
                ['label' => 'Let’s Volunteer', 'route' => 'welfare.serve', 'hash' => 'volunteer'],
                ['label' => 'Be A Mentor', 'route' => 'welfare.serve', 'hash' => 'mentor'],
                ['label' => 'Partner With Us', 'route' => 'welfare.serve', 'hash' => 'partner'],
            ]
        ],
        [
            'label' => 'Impact Areas',
            'route' => 'welfare.impact',
            'children' => [
                ['label' => 'Economic Empowerment', 'route' => 'welfare.impact', 'hash' => 'socio-economic'],
                ['label' => 'Education and Talent Development', 'route' => 'welfare.impact', 'hash' => 'education'],
                ['label' => 'Leadership and Representation', 'route' => 'welfare.impact', 'hash' => 'leadership'],
                ['label' => 'Community Welfare', 'route' => 'welfare.impact', 'hash' => 'entrepreneurship'],
                ['label' => 'Faith, Identity and Ukhwah', 'route' => 'welfare.impact', 'hash' => 'faith'],
            ]
        ],
        [
            'label' => 'News & Gallery',
            'route' => 'welfare.news',
            'children' => [
                ['label' => 'Impact Insights', 'route' => 'welfare.news', 'hash' => 'insights'],
                ['label' => 'Moments of MUKMIN', 'route' => 'welfare.news', 'hash' => 'moments'],
            ]
        ],
        [
            'label' => 'Changing Lives',
            'route' => 'welfare.changing',
            'children' => [
                ['label' => 'Philanthropic Funds', 'route' => 'welfare.changing', 'hash' => 'funds'],
                ['label' => 'Faith & Giving', 'route' => 'welfare.changing', 'hash' => 'giving'],
                ['label' => 'Ecosystem Building Initiatives', 'route' => 'welfare.changing', 'hash' => 'ecosystem'],
                ['label' => 'ImpactCollab', 'route' => 'welfare.changing', 'hash' => 'collab'],
            ]
        ],
        ['label' => 'Contact Us', 'route' => 'welfare.contact'],
    ],

    'footer_nav' => [
    ],

    'social' => [
        ['icon' => 'cmsms-icon-facebook', 'url' => 'https://web.facebook.com/profile.php?id=61590435118262'],
        ['icon' => 'cmsms-icon-instagram', 'url' => 'https://www.instagram.com/mukmin.malaysia'],
        ['icon' => 'cmsms-icon-linkedin', 'url' => 'https://www.linkedin.com/in/mukminofficial/'],
        ['icon' => 'cmsms-icon-tiktok', 'url' => 'https://www.tiktok.com/@mukminnasional?is_from_webapp=1&sender_device=pc'],
        ['icon' => 'cmsms-icon-youtube', 'url' => 'https://youtube.com/@mukmin-i7l?si=ZDB9eyr679HET6Ew'],
        ['icon' => 'cmsms-icon-twitter', 'url' => 'https://x.com/Mukminmy'],
    ],

];
