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
        </div>
    </div>
</footer>
