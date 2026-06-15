@extends('welfare.layouts.app')

@section('title', 'Who We Are - Pertubuhan Gabungan MUKMIN Nasional')

@push('styles')
    @include('welfare.partials.about-styles')
@endpush

@section('content')
<!-- Sub Block 1: Who We Are -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="who-we-are-grid">
            <div class="who-body">
                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--color-primary); font-weight: 700; display: block; margin-bottom: 6px;">Who We Are</span>
                <h2 class="who-headline">The Strength Behind MUKMIN</h2>
                <h3 class="who-subheadline">A Collective Ecosystem of Communities, Institutions and Leaders</h3>
                
                <p>MUKMIN is strengthened by a growing ecosystem of NGOs, community organisations, chambers of commerce, institutions, professional networks, mosques, surau, madrasah, tahfiz centres and strategic partners across Malaysia.</p>
                <p>Together, these organisations represent a diverse network of community leaders, professionals, educators, entrepreneurs, religious institutions and changemakers united by a shared commitment towards community advancement and sustainable development.</p>
                <p>By bringing together expertise, resources, networks and grassroots reach under one coordinated platform, MUKMIN transforms individual efforts into collective impact, creating stronger opportunities for collaboration, empowerment and long-term community transformation.</p>
                <p>Today, MUKMIN's strength lies not in any single organisation, but in the collective power of its ecosystem and the communities it serves.</p>
            </div>
            <div class="who-image">
                <img src="{{ asset('welfare/img/about/strength-behind-mukmin.png') }}" alt="MUKMIN Annual General Meeting 2026 group photo">
            </div>
        </div>
    </div>
</section>
@endsection
