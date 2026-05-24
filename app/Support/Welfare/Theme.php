<?php

namespace App\Support\Welfare;

class Theme
{
    public static function pageClasses(string $extra = ''): string
    {
        $t = config('welfare.theme', []);

        $classes = [
            'csstransition',
            'cmsms_' . ($t['layout'] ?? 'liquid'),
            'cmsms_responsive',
            'fixed_header',
            'enable_header_top',
            'enable_header_bottom',
            'cmsms_heading_after_header',
        ];

        if ($extra) {
            $classes[] = $extra;
        }

        return implode(' ', $classes);
    }

    public static function asset(string $path): string
    {
        return asset(config('welfare.assets', 'welfare') . '/' . ltrim($path, '/'));
    }

    public static function campaignDonatedBar(int $progress, string $togo = '$2,500 to go'): string
    {
        $id = 'cmsms_stat_' . uniqid();

        return '<div class="cmsms_campaign_donated_percent">' .
            '<style type="text/css">.cmsms_stats.shortcode_animated #' . $id . '.cmsms_stat { width:' . $progress . '%; }</style>' .
            '<div class="cmsms_stats stats_mode_bars stats_type_horizontal">' .
            '<div class="cmsms_stat_wrap">' .
            '<div class="cmsms_stat_title_wrap">' .
            '<span class="cmsms_stat_counter_wrap"><span class="cmsms_stat_counter">' . $progress . '</span><span class="cmsms_stat_units">%</span></span>' .
            '<span class="cmsms_stat_title">Donated</span>' .
            '</div>' .
            '<div id="' . $id . '" class="cmsms_stat" data-percent="' . $progress . '"><div class="cmsms_stat_inner"></div></div>' .
            '<span class="cmsms_stat_subtitle">' . e($togo) . '</span>' .
            '</div></div></div>';
    }
}
