@php $w = config('welfare.assets', 'welfare'); @endphp
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family={{ config('welfare.google_fonts') }}&subset=latin,latin-ext">
<link rel="stylesheet" href="{{ asset("$w/style.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/style.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/adaptive.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/retina.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/fontello.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/animate.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/styles/welfare.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/styles/welfare_colors_primary.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/styles/welfare_colors_secondary.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/styles/welfare_fonts.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/donations/cmsms-donations-style.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/donations/cmsms-donations-adaptive.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/ilightbox.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/ilightbox-skins/".config('welfare.theme.ilightbox_skin', 'dark')."-skin.css") }}">
<link rel="stylesheet" href="{{ asset("$w/css/jquery.isotope.css") }}">
<link rel="icon" href="{{ asset("$w/img/favicon.ico") }}" type="image/x-icon">

<style type="text/css">
    .header_mid .header_mid_inner .logo_wrap { width: 172px; }
    .header_mid_inner .logo img.logo_retina { max-width: 172px; }
</style>

@stack('inline-styles')
