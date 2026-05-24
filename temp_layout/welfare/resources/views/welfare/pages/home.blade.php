@extends('welfare.layouts.app')

@section('title', 'Home')

@section('headline')
<div class="headline">
    <div class="headline_outer cmsms_headline_disabled"></div>
</div>
@endsection

@section('content')
<div class="middle_content entry" role="main">
    @include('welfare.pages.home-content')
</div>
@endsection

@push('page-scripts')
<script>
jQuery(document).ready(function ($) {
    if ($.fn.owlCarousel && $('.cmsms_home_campaigns_slider').length) {
        $('.cmsms_home_campaigns_slider').owlCarousel({
            items: 4,
            itemsDesktopSmall: [1024, 3],
            itemsTablet: [768, 2],
            itemsMobile: [480, 1],
            navigation: true,
            navigationText: [
                '<span class="cmsms_prev_arrow"><span></span></span>',
                '<span class="cmsms_next_arrow"><span></span></span>'
            ],
            pagination: false,
            autoPlay: 5000,
            stopOnHover: true
        });
    }
});
</script>
@endpush
