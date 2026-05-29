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

/* Custom Header Banner (Dynamic Vector Background) */
.card-header-banner {
    position: relative;
    padding: 25px 20px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 200px;
    overflow: hidden;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
.banner-title-text {
    font-size: 12.5px;
    font-weight: 800;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.95);
    margin-bottom: 12px;
    z-index: 2;
}
.vector-wrap {
    width: 100%;
    max-width: 180px;
    height: 110px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}
.vector-wrap svg {
    width: 100%;
    height: 100%;
    filter: drop-shadow(0 4px 10px rgba(0,0,0,0.15));
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
        height: 180px;
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
                    <div class="card-header-banner banner-shape">
                        <span class="banner-title-text">SHAPE</span>
                        <div class="vector-wrap">
                            <svg viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor">
                              <!-- Glowing Background Effect -->
                              <circle cx="100" cy="60" r="45" fill="rgba(255, 255, 255, 0.05)" />
                              <!-- Drafting/Strategy Grid Lines -->
                              <line x1="40" y1="60" x2="160" y2="60" stroke="rgba(255, 255, 255, 0.1)" stroke-dasharray="2,2" />
                              <line x1="100" y1="15" x2="100" y2="105" stroke="rgba(255, 255, 255, 0.1)" stroke-dasharray="2,2" />
                              <circle cx="100" cy="60" r="35" stroke="rgba(255, 255, 255, 0.15)" stroke-dasharray="4,4" />
                              
                              <!-- Stylized Compass / Diamond Point (Strategy) -->
                              <path d="M100,25 L112,60 L100,95 L88,60 Z" fill="url(#shape-diamond-grad)" stroke="#ffffff" stroke-width="1.5" />
                              <path d="M100,25 L100,95" stroke="rgba(255,255,255,0.4)" stroke-width="1" />
                              <path d="M88,60 L112,60" stroke="rgba(255,255,255,0.4)" stroke-width="1" />
                              
                              <!-- Small Gear (Execution) -->
                              <circle cx="140" cy="80" r="10" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
                              <path d="M140,67 L140,70 M140,90 L140,93 M127,80 L130,80 M150,80 L153,80 M131,71 L133,73 M149,89 L147,87 M131,89 L133,87 M149,71 L147,73" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" stroke-linecap="round" />
                              <circle cx="140" cy="80" r="4" fill="rgba(255,255,255,0.3)" />

                              <!-- Brain/Lightbulb Concept - Spark of Idea -->
                              <circle cx="60" cy="40" r="10" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
                              <path d="M57,48 L63,48 M60,40 L60,46" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
                              <path d="M54,34 Q60,26 66,34" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
                              <circle cx="60" cy="40" r="3" fill="rgba(255,255,255,0.3)" />

                              <!-- Connection Paths (dashed lines) -->
                              <path d="M70,40 Q100,30 100,25" stroke="rgba(255,255,255,0.4)" stroke-width="1" stroke-dasharray="3,3" />
                              <path d="M100,95 Q120,95 130,85" stroke="rgba(255,255,255,0.4)" stroke-width="1" stroke-dasharray="3,3" />
                              
                              <defs>
                                <linearGradient id="shape-diamond-grad" x1="0" y1="0" x2="1" y2="1">
                                  <stop offset="0%" stop-color="rgba(255,255,255,0.8)" />
                                  <stop offset="100%" stop-color="rgba(255,255,255,0.2)" />
                                </linearGradient>
                              </defs>
                            </svg>
                        </div>
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
                        <span class="banner-title-text">CONNECT</span>
                        <div class="vector-wrap">
                            <svg viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor">
                              <!-- Glowing Background Circles -->
                              <circle cx="100" cy="60" r="40" fill="rgba(255, 255, 255, 0.05)" />
                              
                              <!-- Outer Connecting Links -->
                              <path d="M60,35 L100,25 L140,35 L150,75 L100,95 L50,75 Z" stroke="rgba(255,255,255,0.15)" stroke-width="1" stroke-dasharray="2,2" />

                              <!-- Primary Network Links (Thicker) -->
                              <line x1="100" y1="25" x2="100" y2="95" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" />
                              <line x1="60" y1="35" x2="150" y2="75" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" />
                              <line x1="140" y1="35" x2="50" y2="75" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" />
                              <line x1="60" y1="35" x2="100" y2="95" stroke="rgba(255,255,255,0.3)" stroke-width="1" />
                              <line x1="140" y1="35" x2="100" y2="95" stroke="rgba(255,255,255,0.3)" stroke-width="1" />

                              <!-- Nodes (Glowing Dots) -->
                              <circle cx="100" cy="25" r="7" fill="url(#connect-node-grad)" stroke="#ffffff" stroke-width="1.5" />
                              <circle cx="60" cy="35" r="7" fill="url(#connect-node-grad)" stroke="#ffffff" stroke-width="1.5" />
                              <circle cx="140" cy="35" r="7" fill="url(#connect-node-grad)" stroke="#ffffff" stroke-width="1.5" />
                              <circle cx="50" cy="75" r="7" fill="url(#connect-node-grad)" stroke="#ffffff" stroke-width="1.5" />
                              <circle cx="150" cy="75" r="7" fill="url(#connect-node-grad)" stroke="#ffffff" stroke-width="1.5" />
                              
                              <!-- Central Coordination Hub (Larger) -->
                              <circle cx="100" cy="60" r="14" fill="#ffffff" stroke="rgba(255,255,255,0.4)" stroke-width="3" />
                              <!-- Inner symbol inside central hub -->
                              <path d="M96,60 L104,60 M100,56 L100,64" stroke="#d43c18" stroke-width="2" stroke-linecap="round" />

                              <!-- Glowing rings around central hub -->
                              <circle cx="100" cy="60" r="22" stroke="rgba(255,255,255,0.2)" stroke-width="1" />

                              <defs>
                                <linearGradient id="connect-node-grad" x1="0" y1="0" x2="1" y2="1">
                                  <stop offset="0%" stop-color="#ffffff" />
                                  <stop offset="100%" stop-color="rgba(255,255,255,0.4)" />
                                </linearGradient>
                              </defs>
                            </svg>
                        </div>
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
                        <span class="banner-title-text">DELIVER</span>
                        <div class="vector-wrap">
                            <svg viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor">
                              <!-- Glowing Background Circle -->
                              <circle cx="100" cy="60" r="40" fill="rgba(255, 255, 255, 0.05)" />

                              <!-- Delivery Paths/Grids -->
                              <path d="M40,90 Q100,80 160,90" stroke="rgba(255,255,255,0.2)" stroke-width="1.5" />
                              
                              <!-- Sprout of Hope (Growth/Delivery) -->
                              <path d="M100,90 L100,45" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                              <path d="M100,70 Q80,60 75,45 Q90,45 100,65" fill="url(#deliver-leaf-grad)" stroke="#ffffff" stroke-width="1.5" />
                              <path d="M100,60 Q120,50 125,35 Q110,35 100,55" fill="url(#deliver-leaf-grad)" stroke="#ffffff" stroke-width="1.5" />

                              <!-- Direct Target / Bullseye in Background -->
                              <circle cx="100" cy="30" r="10" stroke="rgba(255,255,255,0.3)" stroke-width="1" stroke-dasharray="2,2" />
                              <circle cx="100" cy="30" r="5" stroke="rgba(255,255,255,0.4)" stroke-dasharray="2,2" />
                              
                              <!-- Arrow heading to Target -->
                              <path d="M100,45 L100,32" stroke="rgba(255,255,255,0.6)" stroke-dasharray="2,2" />
                              <path d="M98,35 L100,31 L102,35" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />

                              <!-- Delivery Hand/Support representation -->
                              <path d="M80,95 Q100,85 120,95" stroke="rgba(255,255,255,0.7)" stroke-width="2" stroke-linecap="round" />
                              <path d="M85,95 Q100,91 115,95" stroke="rgba(255,255,255,0.4)" stroke-width="1" stroke-linecap="round" />

                              <!-- Small Sparks of Delivery/Result -->
                              <circle cx="70" cy="35" r="1.5" fill="#ffffff" />
                              <circle cx="130" cy="30" r="1.5" fill="#ffffff" />
                              <circle cx="100" cy="20" r="2" fill="#ffffff" />

                              <defs>
                                <linearGradient id="deliver-leaf-grad" x1="0" y1="0" x2="1" y2="1">
                                  <stop offset="0%" stop-color="#ffffff" stop-opacity="0.9" />
                                  <stop offset="100%" stop-color="#ffffff" stop-opacity="0.3" />
                                </linearGradient>
                              </defs>
                            </svg>
                        </div>
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



