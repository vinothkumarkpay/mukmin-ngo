{{-- Matches welfare/cmsms-donations/post-type/campaign/vertical.php (featured) --}}
@php
    $progress = $campaign['goal'] > 0 ? min(100, (int) floor(($campaign['raised'] / $campaign['goal']) * 100)) : 0;
    $togo = '$' . number_format(max(0, $campaign['goal'] - $campaign['raised'])) . ' to go';
@endphp
<div class="cmsms_featured_campaign">
    <article class="campaign type-campaign status-publish hentry">
        <div class="cmsms_campaign_wrap_img">
            <figure class="cmsms_img_wrap">
                <div class="cmsms_img_rollover_wrap preloader">
                    <img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }}" class="full-width" />
                    <div class="cmsms_img_rollover">
                        <a href="{{ route('welfare.campaigns.show', $campaign['slug']) }}" title="{{ $campaign['title'] }}" class="cmsms_theme_icon_money"></a>
                    </div>
                </div>
            </figure>
        </div>
        {!! \App\Support\Welfare\Theme::campaignDonatedBar($progress, $togo) !!}
        <div class="cmsms_campaign_cont">
            <div class="cmsms_campaign_wrap_heading">
                <div class="cmsms_campaign_rest_amount">
                    <span class="cmsms_campaign_rest_amount_currency">$</span>
                    <span class="cmsms_campaign_rest_amount_number">{{ number_format(max(0, $campaign['goal'] - $campaign['raised'])) }}</span>
                    <span class="cmsms_campaign_rest_amount_title">to go</span>
                </div>
                <header class="cmsms_campaign_header entry-header">
                    <h2 class="cmsms_campaign_title entry-title">
                        <a href="{{ route('welfare.campaigns.show', $campaign['slug']) }}">{{ $campaign['title'] }}</a>
                    </h2>
                </header>
            </div>
            <div class="cmsms_campaign_content entry-content">
                <p>{{ $campaign['excerpt'] }}</p>
            </div>
            <div class="cmsms_campaign_donate_button">
                <div class="cmsms_campaign_donate_button_inner">
                    <a class="button" href="{{ route('welfare.donate') }}">Donate Now</a>
                </div>
            </div>
        </div>
    </article>
</div>
