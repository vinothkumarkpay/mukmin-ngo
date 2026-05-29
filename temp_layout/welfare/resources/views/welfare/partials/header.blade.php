@php
    $t = config('welfare.theme');
    $w = config('welfare.assets');
@endphp
<header id="header">
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
                    <a href="{{ route('welfare.home') }}" title="{{ config('welfare.name') }}" class="logo">
                        <img src="{{ asset("$w/img/logo.png") }}" alt="{{ config('welfare.name') }}" />
                        <img class="logo_retina" src="{{ asset("$w/img/logo_retina.png") }}" alt="{{ config('welfare.name') }}" width="172" height="70" />
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
