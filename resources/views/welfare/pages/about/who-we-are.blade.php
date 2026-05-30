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
                <h2 class="who-headline">A National Ecosystem for Community Transformation</h2>
                <h3 class="who-subheadline">Uniting communities, organisations, and partners to drive inclusive and sustainable socio-economic development across Malaysia.</h3>
                
                <p>MUKMIN is a national coordinating ecosystem that brings together NGOs, civil society organisations, chambers of commerce, institutions, global think tanks and impact organisations, alongside grassroots community networks including surau, madrasah and mosques across Malaysia under a shared mission to advance inclusive and sustainable development.</p>
                <p>Anchored in collaboration and collective action, MUKMIN serves as a connector between communities, institutions, industry, and ecosystem partners — aligning ideas, mobilising partnerships, and transforming community-driven efforts into scalable initiatives with long-term impact.</p>
                <p>Through a structured ecosystem approach, MUKMIN strengthens pathways for socio-economic mobility, education and talent development, leadership, entrepreneurship, innovation, and community cohesion.</p>
                <p>More than a movement, MUKMIN represents a coordinated national effort to build resilient communities, unlock opportunities, and create sustainable progress for future generations.</p>
            </div>
            <div class="who-image">
                <img src="{{ asset('welfare/img/about/who-we-are.jpg') }}" alt="Federal Territory Mosque, Kuala Lumpur — MUKMIN community transformation ecosystem">
            </div>
        </div>
    </div>
</section>
@endsection
