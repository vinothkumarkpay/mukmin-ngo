@extends('welfare.layouts.app')

@section('title', 'Causes')
@php $layout = 'fullwidth'; @endphp

@section('headline')
    @include('welfare.partials.headline', ['title' => 'Our Causes', 'subtitle' => 'Active campaigns'])
@endsection

@section('content')
<div class="middle_content entry" role="main">
    <div class="cmsms_campaigns">
        <div class="cmsms_row_margin">
            @foreach($campaigns as $campaign)
            <div class="cmsms_column one_fourth">
                @include('welfare.partials.campaign-horizontal', ['campaign' => $campaign])
            </div>
            @endforeach
        </div>
        <div class="cl"></div>
    </div>
</div>
@endsection
