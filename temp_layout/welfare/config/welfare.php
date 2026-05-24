<?php

return [

    'name' => env('WELFARE_SITE_NAME', 'Welfare'),
    'tagline' => env('WELFARE_TAGLINE', 'Nonprofit Organization & Charity'),
    'email' => env('WELFARE_EMAIL', 'example@welfare_ngo.com'),
    'phone' => env('WELFARE_PHONE', '1-800-123-1234'),
    'address' => env('WELFARE_ADDRESS', 'Brooklyn'),
    'postal' => env('WELFARE_POSTAL', 'NY 10036'),
    'country' => env('WELFARE_COUNTRY', 'United States'),

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
        'header_donations_text' => 'Donate Now!',
        'header_top_donations_but' => true,
        'header_top_donations_text' => 'Donate Now!',
        'header_top_info' => true,
        'footer_scheme' => 'footer',
        'footer_copyright' => 'This is a sample website - cmsmasters © 2019 / All Rights Reserved',
        'primary_color' => '#d43c18',
        'ilightbox_skin' => 'dark',
    ],

    'google_fonts' => 'Roboto:300,300italic,400,400italic,500,500italic,700,700italic',

    'nav' => [
        ['label' => 'Home', 'route' => 'welfare.home'],
        ['label' => 'Causes', 'route' => 'welfare.campaigns.index'],
        ['label' => 'Events', 'route' => 'welfare.about'],
        ['label' => 'Blog', 'route' => 'welfare.blog.index'],
        ['label' => 'About', 'route' => 'welfare.about'],
        ['label' => 'Contact', 'route' => 'welfare.contact'],
    ],

    'footer_nav' => [
        ['label' => 'About Us', 'route' => 'welfare.about'],
        ['label' => 'Our Team', 'route' => 'welfare.about'],
        ['label' => 'Volunteer', 'route' => 'welfare.contact'],
        ['label' => 'Donate', 'route' => 'welfare.donate'],
    ],

    'social' => [
        ['icon' => 'cmsms-icon-facebook', 'url' => '#'],
        ['icon' => 'cmsms-icon-twitter', 'url' => '#'],
        ['icon' => 'cmsms-icon-googleplus', 'url' => '#'],
        ['icon' => 'cmsms-icon-vimeo', 'url' => '#'],
        ['icon' => 'cmsms-icon-skype', 'url' => '#'],
    ],

];
