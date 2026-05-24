@php
    $scheme = $scheme ?? 'default';
    $align = $align ?? 'left';
@endphp
<div class="headline cmsms_color_scheme_{{ $scheme }}">
    <div class="headline_outer">
        <div class="headline_color"></div>
        <div class="headline_inner align_{{ $align }}">
            <div class="headline_aligner"></div>
            <div class="headline_text">
                <h1 class="entry-title">{{ $title }}</h1>
                @isset($subtitle)
                <div class="cmsms_breadcrumbs">
                    <div class="cmsms_breadcrumbs_inner">
                        <span>{{ $subtitle }}</span>
                    </div>
                </div>
                @endisset
            </div>
        </div>
    </div>
</div>
