@extends('welfare.layouts.app')

@section('title', 'Donate')
@php $layout = 'fullwidth'; @endphp

@section('headline')
    @include('welfare.partials.headline', ['title' => 'Donate'])
@endsection

@section('content')
<div class="middle_content entry" role="main">
    <div class="cmsms_donation_form">
        <div class="cmsms_text"><p>Your generosity fuels our programs. Choose an amount below.</p></div>
        <form method="POST" action="#">
            @csrf
            <div class="cmsms_row_margin">
                @foreach([25, 50, 100, 250] as $amount)
                <label class="cmsms_column one_fourth" style="text-align:center;">
                    <input type="radio" name="amount" value="{{ $amount }}" {{ $loop->first ? 'checked' : '' }} style="display:none;">
                    <span class="cmsms_button"><span>${{ $amount }}</span></span>
                </label>
                @endforeach
            </div>
            <p><label>Full name</label><input type="text" name="name" class="cmsms_input" required></p>
            <p><label>Email</label><input type="email" name="email" class="cmsms_input" required></p>
            <p><button type="submit" class="cmsms_button"><span>Complete Donation</span></button></p>
        </form>
    </div>
</div>
@endsection
