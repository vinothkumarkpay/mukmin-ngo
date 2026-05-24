@extends('welfare.layouts.app')

@section('title', 'Contact')
@php $layout = 'fullwidth'; @endphp

@section('headline')
    @include('welfare.partials.headline', ['title' => 'Contact'])
@endsection

@section('content')
<div class="middle_content entry" role="main">
    <div class="cmsms_row cmsms_color_scheme_default">
        <div class="cmsms_row_outer_parent">
            <div class="cmsms_row_outer">
                <div class="cmsms_row_inner">
                    <div class="cmsms_row_margin">
                        <div class="cmsms_column one_half">
                            <div class="cmsms_text">
                                <h2>Contact Information</h2>
                                <p><strong>Address:</strong> {{ config('welfare.address') }}, {{ config('welfare.postal') }}, {{ config('welfare.country') }}</p>
                                <p><strong>Phone:</strong> {{ config('welfare.phone') }}</p>
                                <p><strong>Email:</strong> <a href="mailto:{{ config('welfare.email') }}">{{ config('welfare.email') }}</a></p>
                            </div>
                            @include('welfare.partials.social')
                        </div>
                        <div class="cmsms_column one_half">
                            <div class="cmsms_contact_form">
                                <form method="POST" action="#">
                                    @csrf
                                    <p><label>Name</label><input type="text" name="name" class="cmsms_input" required></p>
                                    <p><label>Email</label><input type="email" name="email" class="cmsms_input" required></p>
                                    <p><label>Message</label><textarea name="message" rows="6" class="cmsms_textarea" required></textarea></p>
                                    <p><button type="submit" class="cmsms_button"><span>Send Message</span></button></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
