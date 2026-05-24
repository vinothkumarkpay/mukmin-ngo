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
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=600&q=80" alt="Datuk Dr. Mohd Gazali Haji Mohd Abas">
                <div class="president-title-overlay">
                    <h4>Datuk Dr. Mohd Gazali Haji Mohd Abas</h4>
                    <span>President, Pertubuhan Gabungan MUKMIN Nasional</span>
                </div>
            </div>
            <div class="president-letter">
                <h3>President's Note</h3>
                <p>Assalamu'alaikum Warahmatullahi Wabarakatuh and Warm Greetings,</p>
                <p>Welcome to the Pertubuhan Gabungan MUKMIN Nasional.</p>
                <p>As we stand at the threshold of a new era of community transformation in Malaysia, our mission is clearer and more urgent than ever. MUKMIN was established as a national coordinating ecosystem to address the socio-economic disparities and developmental gaps that exist in our society by uniting grassroots communities, civil society, chambers of commerce, and institutions.</p>
                <p>Our structured ecosystem approach enables us to channel aid, build capacity, and cultivate values-driven leadership systematically. We believe that true, lasting community transformation cannot happen in isolation; it requires a collective, coordinated commitment from every sector of society.</p>
                <p>By bridging the gap between national-level strategic vision and grassroots execution, we are shaping an ecosystem that is not just reactive to immediate needs, but proactive in building resilient, future-ready communities. We invite you to join us on this journey of purpose, collaboration, and shared impact.</p>
                <p>Thank you and Wassalam.</p>
                
                <div class="president-signature">
                    <h5>Datuk Dr. Mohd Gazali Haji Mohd Abas</h5>
                    <p>President, Pertubuhan Gabungan MUKMIN Nasional</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
