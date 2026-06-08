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

/* 2. Full-width Hero */
.ecosystem-hero {
    position: relative;
    overflow: hidden;
    padding: 0;
}

.ecosystem-hero-image {
    width: 100%;
    height: auto;
    display: block;
}

.ecosystem-hero-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
}

.ecosystem-hero .container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 32px;
}

.ecosystem-hero-content {
    max-width: 520px;
    text-align: left;
}

.ecosystem-hero-content h2 {
    color: #1e1e1e;
    font-size: 30px;
    font-weight: 700;
    margin: 0 0 18px;
    line-height: 1.35;
    white-space: nowrap;
}

.ecosystem-hero-content p {
    color: #1e1e1e;
    font-size: 15px;
    line-height: 1.7;
    margin: 0;
    max-width: 520px;
}

/* 3. Three Columns Section */
.cards-row {
    padding: 24px 0 40px;
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

/* Custom Header Banner (Dynamic Vector Background) */
.card-header-banner {
    position: relative;
    padding: 25px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 80px;
    overflow: hidden;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
.banner-title-text {
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 2px;
    color: rgba(255, 255, 255, 0.95);
    z-index: 2;
}
.banner-shape {
    background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
}
.banner-connect {
    background: linear-gradient(135deg, #7f1d1d 0%, #f97316 100%);
}
.banner-deliver {
    background: linear-gradient(135deg, #064e3b 0%, #10b981 100%);
}

.card-logo-header {
    padding: 20px 20px;
    text-align: center;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
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
    .card-header-banner {
        min-height: 70px;
    }
    .brand-mark-logo {
        height: 100px;
    }
    .brand-mark-logo img {
        height: 100px;
    }
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

    .ecosystem-hero {
        display: flex;
        flex-direction: column;
    }

    .ecosystem-hero-image {
        order: 1;
        width: 100%;
        height: auto;
    }

    .ecosystem-hero-overlay {
        position: static;
        order: 2;
        background: #1e1e1e;
    }

    .ecosystem-hero .container {
        padding: 32px 24px;
    }

    .ecosystem-hero-content {
        max-width: none;
    }

    .ecosystem-hero-content h2 {
        color: #ffffff;
        font-size: 22px;
        white-space: normal;
        margin-bottom: 14px;
    }

    .ecosystem-hero-content p {
        color: rgba(255, 255, 255, 0.8);
        max-width: none;
        font-size: 14px;
        line-height: 1.65;
    }

    .cards-grid {
        grid-template-columns: 1fr;
        gap: 35px;
    }
}

@media (max-width: 575px) {
    .ecosystem-hero .container {
        padding: 28px 20px;
    }

    .ecosystem-hero-content h2 {
        font-size: 20px;
    }

    .ecosystem-hero-content p {
        font-size: 13px;
    }
}
</style>

<div class="ecosystem-page">
    <!-- Full-width Hero -->
    <section class="ecosystem-hero">
        <img
            class="ecosystem-hero-image"
            src="{{ asset('welfare/img/ecosystem/hero.jpg') }}"
            alt="MUKMIN Ecosystem — FIKRAH, Gabungan MUKMIN Nasional, and Yayasan MUKMIN"
        >
        <div class="ecosystem-hero-overlay">
            <div class="container">
                <div class="ecosystem-hero-content">
                    <h2>One Ecosystem. Three Engines of Impact.</h2>
                    <p>A structured and integrated ecosystem that transforms ideas into action through strategy, coordination, and implementation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Three Vertical Cards Section -->
    <div class="cards-row">
        <div class="container">
            <div class="cards-grid">
                
                <!-- Card 1: FIKRAH -->
                <div class="custom-card" id="fikrah">
                    <div class="card-header-banner banner-shape">
                        <span class="banner-title-text">Shape</span>
                    </div>
                    <div class="card-logo-header">
                        <div class="brand-mark-logo">
                            <img src="{{ asset('welfare/img/fikrah_logo.jpg') }}" alt="FIKRAH Logo">
                        </div>
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
                    <div class="card-header-banner banner-connect">
                        <span class="banner-title-text">Connect</span>
                    </div>
                    <div class="card-logo-header">
                        <div class="brand-mark-logo">
                            <img src="{{ asset('welfare/img/mukmin_ecosystem_logo.jpg') }}" alt="MUKMIN Logo">
                        </div>
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
                    <div class="card-header-banner banner-deliver">
                        <span class="banner-title-text">Deliver</span>
                    </div>
                    <div class="card-logo-header">
                        <div class="brand-mark-logo">
                            <img src="{{ asset('welfare/img/yayasan_logo.jpg') }}" alt="Yayasan MUKMIN Logo">
                        </div>
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



