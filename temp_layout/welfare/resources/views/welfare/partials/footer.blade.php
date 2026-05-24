@php $w = config('welfare.assets'); $t = config('welfare.theme'); @endphp
<footer id="footer" role="contentinfo" class="cmsms_color_scheme_{{ $t['footer_scheme'] ?? 'footer' }} cmsms_footer_default">
    <div class="footer_bg">
        <div class="footer_inner">
            <div class="logo_wrap">
                <a href="{{ route('welfare.home') }}" title="{{ config('welfare.name') }}" class="logo">
                    <img src="{{ asset("$w/img/logo_footer.png") }}" alt="{{ config('welfare.name') }}" />
                    <img class="footer_logo_retina" src="{{ asset("$w/img/logo_footer_retina.png") }}" alt="{{ config('welfare.name') }}" width="172" height="70" />
                </a>
            </div>
            <nav>
                <ul id="footer_nav" class="footer_nav">
                    @foreach(config('welfare.footer_nav', []) as $item)
                        <li><a href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </nav>
            @include('welfare.partials.social')
            <span class="footer_copyright copyright">{{ $t['footer_copyright'] ?? '' }}</span>
        </div>
    </div>
</footer>
