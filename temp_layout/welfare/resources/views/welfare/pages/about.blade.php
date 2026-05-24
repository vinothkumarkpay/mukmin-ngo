@extends('welfare.layouts.app')

@section('title', 'About Us')
@php $layout = 'r_sidebar'; @endphp

@section('headline')
    @include('welfare.partials.headline', ['title' => 'About Us'])
@endsection

@section('content')
<div class="content entry" role="main">
    <div class="cmsms_text">
        <h2>Our Mission</h2>
        <p>Welfare NGO is dedicated to improving lives through education, healthcare, clean water, and emergency relief.</p>
    </div>
    <div class="cmsms_profiles">
        @foreach($team as $member)
        <article class="cmsms_profile one_third">
            <figure class="cmsms_img_wrap"><img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" /></figure>
            <header class="cmsms_profile_header">
                <h3 class="cmsms_profile_title">{{ $member['name'] }}</h3>
                <span class="cmsms_profile_subtitle">{{ $member['role'] }}</span>
            </header>
        </article>
        @endforeach
        <div class="cl"></div>
    </div>
</div>
<aside class="sidebar" role="complementary">
    <aside class="widget widget_text">
        <h3 class="widgettitle">Quick Facts</h3>
        <div class="textwidget"><ul><li>Founded: 2010</li><li>Countries: 24</li></ul></div>
    </aside>
</aside>
@endsection
