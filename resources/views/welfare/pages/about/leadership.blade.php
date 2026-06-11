@extends('welfare.layouts.app')

@section('title', 'Leadership & Governance - Pertubuhan Gabungan MUKMIN Nasional')

@push('styles')
    @include('welfare.partials.about-styles')
@endpush

@section('content')
    @include('welfare.partials.leadership-section')
@endsection

@push('scripts')
    @include('welfare.partials.leadership-scripts')
@endpush
