@extends('welfare.layouts.app')

@section('title', $campaign['title'])
@php $layout = 'fullwidth'; @endphp

@section('headline')
    @include('welfare.partials.headline', ['title' => $campaign['title'], 'subtitle' => 'Cause'])
@endsection

@section('content')
@php
    $progress = $campaign['goal'] > 0 ? min(100, (int) floor(($campaign['raised'] / $campaign['goal']) * 100)) : 0;
    $togo = '$' . number_format(max(0, $campaign['goal'] - $campaign['raised'])) . ' to go';
@endphp
<div class="middle_content entry" role="main">
    <article class="campaign type-campaign">
        <figure class="cmsms_img_wrap">
            <img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }}" />
        </figure>
        {!! \App\Support\Welfare\Theme::campaignDonatedBar($progress, $togo) !!}
        <div class="cmsms_campaign_content entry-content cmsms_text">
            {!! $campaign['content'] !!}
        </div>
        <div class="cmsms_campaign_donate_button">
            <div class="cmsms_campaign_donate_button_inner">
                <a class="button" href="{{ route('welfare.donate') }}">Donate Now</a>
            </div>
        </div>
    </article>
</div>
@endsection
