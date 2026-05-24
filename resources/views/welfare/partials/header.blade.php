@php
    $t = config('welfare.theme');
    $w = config('welfare.assets');
@endphp
<header id="header">
    @if($t['header_top_line'] ?? true)
    <div class="header_top" data-height="{{ $t['header_top_height'] ?? 35 }}">
        <div class="header_top_outer">
            <div class="header_top_inner">
                <div class="header_top_left">
                    <div class="header_top_aligner"></div>
                    <div class="meta_wrap">
                        <div class="adr cmsms-icon-location-3">
                            <span class="locality">{{ config('welfare.address') }}</span>,
                            <span class="postal-code">{{ config('welfare.postal') }}</span>,
                            <span class="country-name">{{ config('welfare.country') }}</span>
                        </div>
                        <div class="tel cmsms-icon-phone-4">{{ config('welfare.phone') }}</div>
                        <div class="email cmsms-icon-mail-3">
                            <a href="mailto:{{ config('welfare.email') }}">{{ config('welfare.email') }}</a>
                        </div>
                    </div>
                </div>
                <div class="header_top_right">
                    <div class="header_top_aligner"></div>
                    @include('welfare.partials.social')
                    @if($t['header_top_donations_but'] ?? true)
                    <a href="{{ route('welfare.donate') }}" class="header_top_donation_but">
                        <span>{{ $t['header_top_donations_text'] ?? 'Donate Now!' }}</span>
                    </a>
                    @endif
                </div>
                <div class="cl"></div>
            </div>
        </div>
        <div class="header_top_but closed">
            <span class="cmsms_bot_arrow_pixel"><span></span></span>
        </div>
    </div>
    @endif

    <div class="header_mid" data-height="{{ $t['header_mid_height'] ?? 110 }}">
        <div class="header_mid_outer">
            <div class="header_mid_inner">
                @if($t['header_donations_but'] ?? true)
                <div class="header_donation_but_wrap">
                    <div class="header_donation_but_wrap_inner">
                        <div class="header_donation_but">
                            <a href="{{ route('welfare.donate') }}" class="cmsms_button">
                                <span>{{ $t['header_donations_text'] ?? 'Donate Now!' }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($t['header_search'] ?? true)
                <div class="search_wrap">
                    <div class="search_wrap_inner">
                        <div class="search_wrap_inner_left">
                            <form method="get" action="{{ route('welfare.blog.index') }}" class="search_bar_wrap">
                                <p>
                                    <input name="s" placeholder="Enter keywords" value="" type="search" />
                                    <button type="submit" class="cmsms_search_button cmsms_theme_icon_search"></button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <div class="logo_wrap">
                    <a href="{{ route('welfare.home') }}" title="{{ config('welfare.name') }}" class="logo" style="display: flex; align-items: center; height: 100%; text-decoration: none;">
                        <span style="font-size: 42px; font-weight: 800; color: #008751; text-transform: lowercase; font-family: 'Roboto', sans-serif;">mukmin</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="header_bot" data-height="{{ $t['header_bot_height'] ?? 50 }}">
        <div class="header_bot_outer">
            <div class="header_bot_inner">
                <div class="resp_nav_wrap">
                    <div class="resp_nav_wrap_inner">
                        <div class="resp_nav_content">
                            <a class="responsive_nav cmsms_theme_icon_resp_nav" href="javascript:void(0);"></a>
                        </div>
                    </div>
                </div>
                <nav role="navigation">
                    @include('welfare.partials.navigation')
                    <div class="cl"></div>
                </nav>
            </div>
        </div>
    </div>
</header>
