<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="cmsms_html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', config('welfare.name'))</title>
    @include('welfare.partials.styles')
    @stack('styles')
</head>
<body @class(array_filter([request()->routeIs('welfare.home') ? 'home' : null, 'page', 'page-template-default']))>
    <section id="page" class="{{ \App\Support\Welfare\Theme::pageClasses() }} hfeed site">
        <div id="main">
            @include('welfare.partials.header')

            <section id="middle">
                @yield('headline')

                <div class="middle_inner">
                    <section @class([
                        'content_wrap',
                        'fullwidth' => request()->routeIs('welfare.home'),
                        'r_sidebar' => !request()->routeIs('welfare.home') && ($layout ?? 'r_sidebar') === 'r_sidebar',
                        'l_sidebar' => ($layout ?? '') === 'l_sidebar',
                    ])>
                        @yield('content')
                    </section>
                </div>
            </section>

            @include('welfare.partials.footer')
        </div>
    </section>

    <a href="javascript:void(0);" id="slide_top" class="cmsms_theme_icon_slide_top"></a>
    <span class="cmsms_responsive_width"></span>

    @include('welfare.partials.scripts')
    @stack('scripts')
</body>
</html>
