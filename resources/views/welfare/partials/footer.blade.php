@php $w = config('welfare.assets'); $t = config('welfare.theme'); @endphp
<footer id="footer" role="contentinfo" class="cmsms_color_scheme_{{ $t['footer_scheme'] ?? 'footer' }} cmsms_footer_default">
    <div class="footer_bg">
        <div class="footer_inner">
            <div class="logo_wrap">
                <a href="{{ route('welfare.home') }}" title="{{ config('welfare.name') }}" class="logo">
                    <span style="font-size: 28px; font-weight: 800; color: #ffffff; text-transform: lowercase; font-family: 'Roboto', sans-serif;">mukmin</span>
                </a>
            </div>
            @if(count(config('welfare.footer_nav', [])) > 0)
            <nav>
                <div class="nav_wrap">
                    <ul id="footer_nav" class="footer_nav">
                        @foreach(config('welfare.footer_nav', []) as $item)
                            <li><a href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </nav>
            @endif
            @include('welfare.partials.social')
            <span class="footer_copyright copyright">{{ $t['footer_copyright'] ?? '' }}</span>
            <div class="footer_info meta_wrap" style="text-align: center; padding-top: 10px;">
                <span class="cmsms-icon-location-3" style="display:inline-block; margin: 0 10px;">
                    <span class="locality">{{ config('welfare.address') }}</span>,
                    <span class="country-name">{{ config('welfare.country') }}</span> - 
                    <span class="postal-code">{{ config('welfare.postal') }}</span>
                </span>
                <span class="cmsms-icon-phone-4" style="display:inline-block; margin: 0 10px;">{{ config('welfare.phone') }}</span>
                <span class="cmsms-icon-mail-3" style="display:inline-block; margin: 0 10px;">
                    <a href="mailto:{{ config('welfare.email') }}">{{ config('welfare.email') }}</a>
                </span>
            </div>
        </div>
    </div>
</footer>
