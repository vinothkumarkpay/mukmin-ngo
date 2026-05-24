{{-- Matches welfare/cmsms-donations/post-type/campaign/horizontal.php --}}
@php
    $progress = $campaign['goal'] > 0 ? min(100, (int) floor(($campaign['raised'] / $campaign['goal']) * 100)) : 0;
    $togo = '$' . number_format(max(0, $campaign['goal'] - $campaign['raised'])) . ' to go';
@endphp
<article class="campaign type-campaign status-publish hentry">
    <figure class="cmsms_img_wrap">
        <div class="cmsms_img_rollover_wrap preloader">
            <img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }}" class="full-width" />
            <div class="cmsms_img_rollover">
                <a href="{{ route('welfare.campaigns.show', $campaign['slug']) }}" title="{{ $campaign['title'] }}" class="cmsms_theme_icon_money"></a>
            </div>
        </div>
    </figure>
    <header class="cmsms_campaign_header entry-header">
        <h4 class="cmsms_campaign_title entry-title">
            <a href="{{ route('welfare.campaigns.show', $campaign['slug']) }}">{{ $campaign['title'] }}</a>
        </h4>
    </header>
    <div class="cmsms_campaign_content entry-content">
        <p>{{ $campaign['excerpt'] }}</p>
    </div>
    {!! \App\Support\Welfare\Theme::campaignDonatedBar($progress, $togo) !!}
</article>
