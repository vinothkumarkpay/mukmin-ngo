@php $w = config('welfare.assets', 'welfare'); @endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="{{ asset("$w/js/jsLibraries.min.js") }}"></script>
<script src="{{ asset("$w/js/jqueryLibraries.min.js") }}"></script>
<script src="{{ asset("$w/js/jquery.iLightBox.min.js") }}"></script>
<script src="{{ asset("$w/js/jquery.isotope.min.js") }}"></script>
<script src="{{ asset("$w/js/jquery.isotope.mode.js") }}"></script>
<script src="{{ asset("$w/js/jquery.script.js") }}"></script>
<script src="{{ asset("$w/js/scrollspy.js") }}"></script>
<script>
window.cmsms_script = window.cmsms_script || {
    primary_color: '{{ config('welfare.theme.primary_color', '#d43c18') }}',
    ilightbox_skin: '{{ config('welfare.theme.ilightbox_skin', 'dark') }}'
};
</script>
@stack('page-scripts')
