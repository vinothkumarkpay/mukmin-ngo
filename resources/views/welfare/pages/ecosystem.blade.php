@extends('welfare.layouts.app')

@section('title', 'Our Ecosystem - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
/* Premium Ecosystem Page Redesign */
.ecosystem-page {
    font-family: var(--font-main);
    color: var(--color-heading);
    background-color: #f5f5f5;
}

/* 1. Page Title Banner */
.page-title-banner {
    background-color: var(--color-primary, #d43c18);
    padding: 22px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.banner-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.banner-title {
    color: #ffffff;
    font-size: 22px;
    font-weight: 500;
    margin: 0;
    line-height: 1;
}
.banner-breadcrumbs {
    color: rgba(255, 255, 255, 0.75);
    font-size: 12px;
    font-weight: 400;
}

/* 2. Top Intro Featured Block */
.featured-row {
    padding: 50px 0 25px;
}
.featured-block {
    display: flex;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}
.featured-left {
    width: 48%;
    position: relative;
    overflow: hidden;
}
.featured-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    min-height: 300px;
}
.featured-right {
    width: 52%;
    background: #1e1e1e;
    color: #ffffff;
    padding: 40px 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.featured-right h2 {
    color: #ffffff;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 18px;
    line-height: 1.3;
}
.featured-right p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
    line-height: 22px;
    margin: 0;
}

/* 3. Three Columns Section */
.cards-row {
    padding: 25px 0 60px;
}
.cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}
.custom-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    scroll-margin-top: 160px;
}
@media (max-width: 768px) {
    .custom-card {
        scroll-margin-top: 90px;
    }
}
.custom-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* Reverted Brand Logo Headers */
.card-logo-header {
    position: relative;
    padding: 30px 20px 25px;
    text-align: center;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
.card-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 4px 12px;
    border-radius: 20px;
    text-transform: uppercase;
}
.fikrah-badge { background: #e2e8f0; color: #1e293b; }
.mukmin-badge { background: #fee2e2; color: #b91c1c; }
.yayasan-badge { background: #dcfce7; color: #15803d; }

.brand-mark-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 120px;
}

/* Brand Logo Images */
.brand-mark-logo img {
    height: 120px;
    width: auto;
    max-width: 100%;
    object-fit: contain;
}

@media (max-width: 768px) {
    .brand-mark-logo {
        height: 90px;
    }
    .brand-mark-logo img {
        height: 90px;
    }
}

.card-image-wrap img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}
.card-body-wrap {
    padding: 25px 22px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.card-subtitle-tag {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.card-body-wrap p {
    font-size: 13.5px;
    line-height: 22px;
    color: #555;
    margin-bottom: 15px;
}
.card-body-wrap p:last-of-type {
    margin-bottom: 0;
}
.card-footer-wrap {
    margin-top: auto;
    padding-top: 20px;
}

/* Card CTA Buttons */
.card-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    background: var(--color-primary, #d43c18);
    color: #ffffff !important;
    text-align: center;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 4px;
    transition: background 0.3s ease;
    border: none;
    line-height: 1.5;
    cursor: pointer;
}
.card-btn:hover {
    background: var(--color-primary-dk, #b83210);
    color: #ffffff !important;
}
.card-btn-outline {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    background: transparent;
    color: var(--color-heading, #1e1e1e) !important;
    border: 2px solid var(--color-border, #e1e1e1);
    text-align: center;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 4px;
    transition: all 0.3s ease;
    line-height: 1.5;
    cursor: pointer;
}
.card-btn-outline:hover {
    background: var(--color-primary, #d43c18);
    border-color: var(--color-primary, #d43c18);
    color: #ffffff !important;
}

@media (max-width: 991px) {
    .banner-inner {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    .featured-block {
        flex-direction: column;
    }
    .featured-left, .featured-right {
        width: 100%;
    }
    .cards-grid {
        grid-template-columns: 1fr;
        gap: 35px;
    }
}
</style>

<div class="ecosystem-page">
    <!-- 2. Top Intro Featured Block -->
    <div class="featured-row">
        <div class="container">
            <div class="featured-block">
                <div class="featured-left">
                    <img src="https://images.unsplash.com/photo-1503919545889-aef636e10ad4?auto=format&fit=crop&w=600&h=450&q=80" alt="Featured Boy Portrait">
                </div>
                <div class="featured-right">
                    <h2>One Ecosystem. Three Engines of Impact.</h2>
                    <p>A structured and integrated ecosystem that transforms ideas into action through strategy, coordination, and implementation.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Three Vertical Cards Section -->
    <div class="cards-row">
        <div class="container">
            <div class="cards-grid">
                
                <!-- Card 1: FIKRAH -->
                <div class="custom-card" id="fikrah">
                    <div class="card-logo-header">
                        <span class="card-badge fikrah-badge">Shape</span>
                        <div class="brand-mark-logo">
                            <img src="{{ asset('welfare/img/fikrah_logo.png') }}" alt="FIKRAH Logo">
                        </div>
                    </div>
                    <div class="card-image-wrap">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=600&h=400&q=80" alt="Education and Policy">
                    </div>
                    <div class="card-body-wrap">
                        <div class="card-subtitle-tag">Strategic Think Tank</div>
                        <p>FIKRAH shapes direction through policy thinking, research, strategic frameworks, and thought leadership — helping communities, institutions, and stakeholders move forward with clarity and purpose.</p>
                        <p>As the strategic think tank within the ecosystem, FIKRAH develops future-ready frameworks, actionable insights, and collaborative pathways that guide programmes, partnerships, and long-term impact.</p>
                        <p>FIKRAH also serves as a catalyst for dialogue and collective action, translating research and engagement into strategic recommendations that strengthen inclusive community development.</p>
                        <div class="card-footer-wrap">
                            <a href="https://www.fikrah.org" target="_blank" class="card-btn">
                                Learn more about FIKRAH <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: MUKMIN -->
                <div class="custom-card" id="gabungan">
                    <div class="card-logo-header">
                        <span class="card-badge mukmin-badge">Connect</span>
                        <div class="brand-mark-logo">
                            <img src="{{ asset('welfare/img/mukmin_ecosystem_logo.png') }}" alt="MUKMIN Logo">
                        </div>
                    </div>
                    <div class="card-image-wrap">
                        <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=600&h=400&q=80" alt="Partnership and Networks">
                    </div>
                    <div class="card-body-wrap">
                        <div class="card-subtitle-tag">National Coordinating Ecosystem</div>
                        <p>Pertubuhan Gabungan Mukmin Nasional (MUKMIN) serves as the national coordinating platform that convenes NGOs, civil society organisations, institutions, chambers of commerce, and grassroots networks across Malaysia.</p>
                        <p>By aligning stakeholders and mobilising collaboration, MUKMIN transforms individual efforts into coordinated collective action — strengthening partnerships, accelerating initiatives, and creating scalable impact.</p>
                        <p>Through strategic coordination, MUKMIN bridges communities with opportunities, resources, expertise, and ecosystem partners.</p>
                        <div class="card-footer-wrap">
                            <a href="{{ route('welfare.about') }}" class="card-btn-outline">
                                Learn more about MUKMIN <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Yayasan MUKMIN -->
                <div class="custom-card" id="yayasan">
                    <div class="card-logo-header">
                        <span class="card-badge yayasan-badge">Deliver</span>
                        <div class="brand-mark-logo">
                            <img src="{{ asset('welfare/img/yayasan_logo.png') }}" alt="Yayasan MUKMIN Logo">
                        </div>
                    </div>
                    <div class="card-image-wrap">
                        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&h=400&q=80" alt="Outreach and Care">
                    </div>
                    <div class="card-body-wrap">
                        <div class="card-subtitle-tag">Implementation & Community Impact</div>
                        <p>Yayasan MUKMIN serves as the implementation arm that translates ideas, partnerships, and strategy into measurable outcomes at the community level.</p>
                        <p>Focused on education access, talent development, socio-economic empowerment, and community initiatives, Yayasan MUKMIN delivers impactful programmes that create opportunities and strengthen long-term resilience.</p>
                        <p>Its role ensures that initiatives move beyond planning into meaningful and sustainable impact on the ground.</p>
                        <div class="card-footer-wrap">
                            <a href="{{ route('welfare.impact') }}" class="card-btn-outline">
                                Explore Our Impact <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection



