@extends('welfare.layouts.app')

@section('title', "President's Note - Pertubuhan Gabungan MUKMIN Nasional")

@push('styles')
    @include('welfare.partials.about-styles')
@endpush

@section('content')
<!-- Sub Block 2: President's Note -->
<section class="section-padding bg-warm">
    <div class="container">
        <div class="president-grid">
            <div class="president-img-container">
                <img src="{{ asset('welfare/img/about/president-shahul-hameed.png') }}" alt="Datuk Wira Shahul Dawood">
                <div class="president-title-overlay">
                    <h4>Datuk Wira Shahul Dawood</h4>
                    <span>President, Pertubuhan Gabungan MUKMIN Nasional</span>
                </div>
            </div>
            <div class="president-letter">
                <h3>President's Note</h3>
                <p>At MUKMIN, we believe that the strength of a community is measured not by its numbers alone, but by its unity, shared purpose and ability to create lasting impact for future generations.</p>
                <p>MUKMIN was established with a clear purpose: to unite organisations, leaders and communities through a coordinated national platform that is structured, accountable, progressive and driven by meaningful action. In a rapidly changing world shaped by technological advancement, economic uncertainty and evolving social challenges, our community cannot afford to move in isolation. We must move forward together, with clarity of purpose and collective strength.</p>
                <p>MUKMIN is more than an organisation. It is an ecosystem for collaboration and community transformation — bringing together NGOs, community associations and strategic partners to align grassroots efforts with national coordination, while creating sustainable opportunities for our people.</p>
                <p>Guided by our five strategic pillars — Socio-Economic Mobility; Education &amp; Future Readiness; Entrepreneurship &amp; Innovation; Faith, Identity &amp; Ukhwah; and Leadership &amp; Capacity Building — we seek to build a resilient, empowered and future-ready community.</p>
                <p>We envision a generation that is economically resilient, intellectually prepared, socially responsible and firmly grounded in values and identity. Our mission is to create platforms that open doors to education, entrepreneurship, leadership development and strategic partnerships, while delivering initiatives that bring measurable and meaningful outcomes to society.</p>
                <p>The future belongs to communities that are organised, united and willing to evolve. MUKMIN is committed to being a catalyst for that transformation — connecting people, ideas and resources to build a stronger, more sustainable legacy for generations to come.</p>
                <p>Together, let us lead with purpose, serve with sincerity and build with vision.</p>
                <p><strong>One Identity. One Vision. One Community.</strong></p>

                <div class="president-signature">
                    <h5>Datuk Wira Shahul Dawood</h5>
                    <p>President<br>Pertubuhan Gabungan MUKMIN Nasional</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
