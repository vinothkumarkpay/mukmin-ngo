@extends('welfare.layouts.app')

@section('title', 'Pertubuhan Gabungan MUKMIN Nasional - Home')

@section('body-class', 'home-page')

@section('content')

<style>
/* Page Specific Styling */
/* Card & Modal specific styles */
.subtabs-grid, .subtabs-grid-4 {
    display: grid;
    gap: 30px;
    margin-top: 40px;
    margin-bottom: 20px;
}
.subtabs-grid {
    grid-template-columns: repeat(3, 1fr);
}
.subtabs-grid-4 {
    grid-template-columns: repeat(4, 1fr);
}
.subtab-card {
    background: #ffffff;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 50px 30px 40px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.subtab-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
}
.subtab-icon-wrap {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    background: #d43c18;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(212, 60, 24, 0.25);
    border: 3px solid #ffffff;
}
.subtab-icon-wrap i {
    color: #ffffff;
    font-size: 20px;
}
.subtab-card h3 {
    font-size: 20px;
    font-weight: 700;
    color: #222222;
    margin-bottom: 12px;
    font-family: var(--font-heading), sans-serif;
}
.aid-card-subheading {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary);
    margin-top: -6px;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.subtab-card p {
    font-size: 14px;
    line-height: 24px;
    color: #666666;
    margin-bottom: 20px;
    min-height: 72px;
}
/* Progress Bar styling */
.aid-card-progress {
    margin-top: 15px;
    margin-bottom: 25px;
    text-align: left;
}
.aid-card-progress-label {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    font-weight: 700;
    color: #555555;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.aid-card-progress-bar {
    width: 100%;
    height: 6px;
    background: #eaeaea;
    border-radius: 3px;
    overflow: hidden;
}
.aid-card-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #ff6b4a, #d43c18);
    border-radius: 3px;
}
.subtab-card .read-more-btn {
    display: inline-block;
    background: #a5a5a5;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 25px;
    border-radius: 20px;
    text-decoration: none;
    transition: background 0.3s ease, transform 0.2s ease;
    cursor: pointer;
    border: none;
    outline: none;
}
.subtab-card .read-more-btn:hover {
    background: #d43c18;
    transform: translateY(-1px);
}
.subtab-card .read-more-btn:active {
    transform: translateY(0);
}

/* Modal system */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
}
.modal-container {
    background: #ffffff;
    border-radius: 12px;
    width: 90%;
    max-width: 650px;
    max-height: 85vh;
    box-shadow: 0 15px 40px rgba(0,0,0,0.18);
    position: relative;
    overflow-y: auto;
    transform: translateY(30px);
    transition: transform 0.3s ease;
    padding: 40px;
}
.modal-overlay.active .modal-container {
    transform: translateY(0);
}
.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: none;
    border: none;
    font-size: 26px;
    color: #999999;
    cursor: pointer;
    transition: color 0.2s ease, transform 0.2s ease;
    line-height: 1;
    padding: 5px;
}
.modal-close:hover {
    color: #d43c18;
    transform: rotate(90deg);
}
.modal-header {
    text-align: center;
    margin-bottom: 25px;
    border-bottom: 2px solid #f5f5f5;
    padding-bottom: 15px;
}
.modal-header h2 {
    font-size: 26px;
    font-weight: 800;
    background: linear-gradient(135deg, #222222, #555555);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 8px;
    display: inline-block;
}
.modal-header .modal-subtitle {
    font-size: 12px;
    color: #d43c18;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.modal-body {
    font-size: 14.5px;
    line-height: 24px;
    color: #555555;
    margin-bottom: 25px;
}
.modal-body ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.modal-body ul li {
    position: relative;
    background: #fafafa;
    border-left: 3px solid #d43c18;
    padding: 12px 18px 12px 42px;
    border-radius: 4px;
    margin-bottom: 12px;
    font-size: 13.5px;
    line-height: 22px;
    color: #444;
    transition: transform 0.2s ease, background 0.2s ease;
    text-align: left;
}
.modal-body ul li:hover {
    transform: translateX(4px);
    background: #fdf6f4;
}
.modal-body ul li i {
    position: absolute;
    left: 15px;
    top: 15px;
    color: #d43c18;
    font-size: 14px;
}

/* Three Engines Layout in Modal */
.modal-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}
.engine-modal-card {
    background: #fafafa;
    border: 1px solid #eeeeee;
    border-radius: 8px;
    padding: 24px 20px;
    text-align: center;
    transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
}
.engine-modal-card:hover {
    transform: translateY(-4px);
    border-color: rgba(212, 60, 24, 0.4);
    background: #ffffff;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
}
.engine-modal-icon {
    width: 48px;
    height: 48px;
    background: rgba(212, 60, 24, 0.08);
    color: #d43c18;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 16px;
    transition: background 0.2s ease, color 0.2s ease;
}
.engine-modal-card:hover .engine-modal-icon {
    background: #d43c18;
    color: #ffffff;
}
.engine-modal-card h4 {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--color-heading);
    margin-bottom: 6px;
    margin-top: 0;
}
.engine-modal-card span {
    display: block;
    font-size: 10px;
    font-weight: 600;
    color: #d43c18;
    text-transform: uppercase;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
}
.engine-modal-card p {
    font-size: 12px;
    line-height: 18px;
    color: #666666;
    margin: 0;
}

/* Strategic Initiatives Layout in Modal */
.modal-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 20px;
}
.initiative-modal-card {
    background: #fafafa;
    border: 1px solid #eeeeee;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    gap: 15px;
    align-items: flex-start;
    transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    text-align: left;
}
.initiative-modal-card:hover {
    transform: translateY(-4px);
    border-color: rgba(212, 60, 24, 0.4);
    background: #ffffff;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
}
.initiative-modal-icon {
    width: 44px;
    height: 44px;
    background: rgba(212, 60, 24, 0.08);
    color: #d43c18;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    transition: background 0.2s ease, color 0.2s ease;
}
.initiative-modal-card:hover .initiative-modal-icon {
    background: #d43c18;
    color: #ffffff;
}
.initiative-modal-card h4 {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--color-heading);
    margin-bottom: 4px;
    margin-top: 0;
}
.initiative-modal-card span {
    display: block;
    font-size: 10px;
    font-weight: 600;
    color: #d43c18;
    text-transform: uppercase;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
}
.initiative-modal-card p {
    font-size: 12px;
    line-height: 18px;
    color: #666666;
    margin: 0;
}
.initiative-modal-card.full-width {
    grid-column: span 2;
}

/* Wide Modal Container */
.modal-container.modal-wide {
    max-width: 850px;
}

@media (max-width: 768px) {
    .modal-grid-3, .modal-grid-2 {
        grid-template-columns: 1fr !important;
    }
    .initiative-modal-card.full-width {
        grid-column: span 1;
    }
}

.modal-footer {
    text-align: center;
    border-top: 1px solid #f5f5f5;
    padding-top: 20px;
}
.modal-footer .btn {
    padding: 10px 30px;
    font-size: 13.5px;
    font-weight: 600;
}


/* Three Engines Layout */
.engines-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-top: 30px;
}
.engine-card {
    background: #fdfdfd;
    border: 1px solid var(--color-border);
    border-radius: 6px;
    padding: 30px 24px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    transition: transform var(--transition), box-shadow var(--transition);
}
.engine-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}
.engine-icon {
    font-size: 32px;
    color: var(--color-primary);
    margin-bottom: 20px;
}
.engine-card h4 {
    font-size: 18px;
    margin-bottom: 12px;
    color: var(--color-heading);
}

/* Strategic Initiatives Layout */
.initiatives-grid {
    display: grid;
    grid-template-columns: repeat(3, 2fr);
    gap: 25px;
    margin-top: 30px;
}
.initiative-card {
    background: #fcfcfc;
    border: 1px solid var(--color-border);
    border-left: 4px solid var(--color-primary);
    border-radius: 4px;
    padding: 24px;
    transition: box-shadow var(--transition);
}
.initiative-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
}
.initiative-card h4 {
    font-size: 16px;
    margin-bottom: 8px;
    color: var(--color-heading);
}
.initiative-card h5 {
    font-size: 13px;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 10px;
}

/* Adopt A Graduate Styling */
.adopt-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 50px;
    align-items: center;
}
.adopt-highlight-box {
    background: #fdf6f4;
    border-left: 4px solid var(--color-primary);
    padding: 20px;
    border-radius: 4px;
    margin: 20px 0;
    font-size: 14px;
    line-height: 22px;
    color: #444;
}

/* Key Engagements Layout */
.engagements-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 50px;
}
.engagement-item {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    align-items: center;
    background: #ffffff;
    border: 1px solid var(--color-border);
    padding: 15px;
    border-radius: 6px;
}
.engagement-date {
    background: var(--color-primary);
    color: white;
    padding: 10px 15px;
    text-align: center;
    border-radius: 4px;
    min-width: 100px;
}
.engagement-date span.day {
    display: block;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.2;
}
.engagement-date span.month-year {
    display: block;
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 500;
}
.engagement-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--color-heading);
}

.update-list {
    list-style: none;
    padding: 0;
}
.update-list li {
    padding: 15px 0;
    border-bottom: 1px solid var(--color-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.update-list li:last-child {
    border-bottom: none;
}
.update-list a {
    color: var(--color-heading);
    font-weight: 500;
    font-size: 14px;
}
.update-list a:hover {
    color: var(--color-primary);
}

/* MUKMIN Updates Tabs Styling */
.updates-tab-nav {
    display: flex;
    gap: 5px;
    border-bottom: 2px solid #eaeaea;
    padding-bottom: 0;
    margin-bottom: 25px;
}
.updates-tab-btn {
    background: none;
    border: none;
    font-size: 13.5px;
    font-weight: 700;
    color: #888888;
    padding: 12px 14px;
    cursor: pointer;
    transition: color 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    outline: none;
}
.updates-tab-btn:hover {
    color: var(--color-primary);
}
.updates-tab-btn.active {
    color: var(--color-primary);
}
.updates-tab-btn::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--color-primary);
    transform: scaleX(0);
    transition: transform 0.3s ease;
    border-radius: 2px;
}
.updates-tab-btn.active::after {
    transform: scaleX(1);
}
.updates-pane-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.updates-pane-list li {
    background: #ffffff;
    border: 1px solid var(--color-border);
    border-left: 3px solid #d43c18;
    border-radius: 6px;
    padding: 15px 20px;
    margin-bottom: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.updates-pane-list li:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.update-date {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}
.update-item-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 6px 0;
}
.update-item-desc {
    font-size: 12.5px;
    line-height: 18px;
    color: #666666;
    margin: 0;
}
@keyframes tabFadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.updates-tab-pane {
    animation: tabFadeIn 0.4s ease-out forwards;
}

@media (max-width: 991px) {
    .subtabs-grid, .engines-grid, .initiatives-grid, .adopt-grid, .engagements-grid {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
    }
    .subtabs-grid-4 {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 30px !important;
    }
}
@media (max-width: 600px) {
    .subtabs-grid-4 {
        grid-template-columns: 1fr !important;
    }
}

/* News & Activities — single column on mobile/tablet */
.section-blog .news-activities-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}
@media (max-width: 991px) {
    .section-blog .news-activities-grid {
        grid-template-columns: 1fr;
        gap: 24px;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
    }
    .section-blog .news-activities-grid .blog-card {
        width: 100%;
    }
    .section-blog .news-activities-grid .blog-img {
        height: 200px !important;
    }
}
</style>

    <!-- HERO SLIDER SECTION -->
    <section class="hero-slider">
        <div class="slider-container">
            <!-- Slide 1 -->
            <div class="slide active" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1600');">
                <div class="slide-content">
                    <div class="container">
                        <div class="slide-text">
                            <h1>One Identity.<br>One Vision.<br>One Community.</h1>
                            <p>Advancing inclusive communities through collaboration, opportunity, and shared purpose. MUKMIN brings together communities, organisations, institutions, and stakeholders to unlock opportunities, strengthen leadership, and drive sustainable socio-economic impact across Malaysia.</p>
                            <div class="slide-buttons">
                                <a href="{{ route('welfare.about') }}" class="btn btn-primary">Learn more about MUKMIN</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=1600');">
                <div class="slide-content">
                    <div class="container">
                        <div class="slide-text">
                            <h1>Join Us</h1>
                            <p>Be part of a national movement connecting communities, empowering people, and shaping a stronger, more inclusive future.</p>
                            <div class="slide-buttons">
                                <a href="{{ route('welfare.serve') }}" class="btn btn-primary">Register as Member</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?w=1600');">
                <div class="slide-content">
                    <div class="container">
                        <div class="slide-text">
                            <h1>Our Ecosystem</h1>
                            <p>Shape. Connect. Deliver. Developing Solutions. Connecting Communities. Driving Impact. A structured and integrated approach that transforms ideas into action through three complementary roles.</p>
                            <div class="slide-buttons">
                                <a href="{{ route('welfare.ecosystem') }}" class="btn btn-primary">Find out more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 4 -->
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1600');">
                <div class="slide-content">
                    <div class="container">
                        <div class="slide-text">
                            <h1>We Are Here For You</h1>
                            <p>Compassion in Action. Unity in Giving. Strengthening communities through service, dignity, and shared blessings.</p>
                            <div class="slide-buttons">
                                <a href="{{ route('welfare.contact') }}" class="btn btn-primary">Find out more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Controls -->
            <button class="slider-prev" id="slider-prev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-next" id="slider-next"><i class="fas fa-chevron-right"></i></button>
            <div class="slider-dots" id="slider-dots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
                <span class="dot" data-index="3"></span>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 1 (CARDS & POPUPS) -->
    <section class="section-welcome bg-white" style="padding-top: 80px; padding-bottom: 80px;">
        <div class="container">
            <div class="subtabs-grid">
                <!-- Card 1: Vision -->
                <div class="subtab-card">
                    <div class="subtab-icon-wrap">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Vision</h3>
                    <p>To become a national community development ecosystem that advances societal well-being, shared prosperity, and inclusive, sustainable progress.</p>
                    <button class="read-more-btn" onclick="openSubtabModal('vision')">Read more</button>
                </div>

                <!-- Card 2: Three Engines of Impact -->
                <div class="subtab-card">
                    <div class="subtab-icon-wrap">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>Three Engines of Impact</h3>
                    <p>MUKMIN operates through three complementary engines to strengthen collaboration, align stakeholders, and drive meaningful community impact at scale.</p>
                    <button class="read-more-btn" onclick="openSubtabModal('engines')">Read more</button>
                </div>

                <!-- Card 3: Strategic Initiatives -->
                <div class="subtab-card">
                    <div class="subtab-icon-wrap">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>Strategic Initiatives</h3>
                    <p>MUKMIN focuses on strengthening communities through strategic focus areas that advance inclusive development, future-ready leadership and sustainable national progress.</p>
                    <button class="read-more-btn" onclick="openSubtabModal('initiatives')">Read more</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Modals / Popups -->
    <div class="modal-overlay" id="subtab-modal-overlay">
        <!-- Vision Modal -->
        <div class="modal-container" id="modal-vision" style="display: none;">
            <button class="modal-close" onclick="closeSubtabModal()">&times;</button>
            <div class="modal-header">
                <!-- Premium SVG Target Vector Illustration -->
                <svg width="100" height="100" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 15px; display: block;">
                    <circle cx="60" cy="60" r="50" fill="url(#gradient-target-bg)" opacity="0.1"/>
                    <circle cx="60" cy="60" r="40" stroke="url(#gradient-target)" stroke-width="3" stroke-dasharray="6 4"/>
                    <circle cx="60" cy="60" r="28" stroke="url(#gradient-target)" stroke-width="3"/>
                    <circle cx="60" cy="60" r="16" fill="url(#gradient-target)"/>
                    <path d="M60 10V25" stroke="url(#gradient-target)" stroke-width="3" stroke-linecap="round"/>
                    <path d="M60 95V110" stroke="url(#gradient-target)" stroke-width="3" stroke-linecap="round"/>
                    <path d="M10 60H25" stroke="url(#gradient-target)" stroke-width="3" stroke-linecap="round"/>
                    <path d="M95 60H110" stroke="url(#gradient-target)" stroke-width="3" stroke-linecap="round"/>
                    <path d="M25 95L52 68" stroke="#222222" stroke-width="4" stroke-linecap="round"/>
                    <path d="M52 68L44 64M52 68L56 76" stroke="#222222" stroke-width="4" stroke-linecap="round"/>
                    <path d="M25 95L18 102M22 92L15 99" stroke="#999999" stroke-width="2" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="gradient-target" x1="20" y1="20" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ff6b4a"/>
                            <stop offset="100%" stop-color="#d43c18"/>
                        </linearGradient>
                        <linearGradient id="gradient-target-bg" x1="10" y1="10" x2="110" y2="110" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ff6b4a"/>
                            <stop offset="100%" stop-color="#d43c18"/>
                        </linearGradient>
                    </defs>
                </svg>
                <h2>Vision</h2>
                <div class="modal-subtitle">Our Aims & Mission</div>
            </div>
            <div class="modal-body">
                <p style="font-size: 15px; font-weight: 600; text-align: center; margin-bottom: 25px; color: #222222; line-height: 24px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    To become a national community development ecosystem that advances societal well-being, shared prosperity, and inclusive, sustainable progress.
                </p>
                <div style="border-top: 1px solid #f0f0f0; padding-top: 25px;">
                    <h3 style="font-size: 16px; color: var(--color-heading); margin-bottom: 20px; font-weight: 700; text-align: center;">Our Mission</h3>
                    <ul>
                        <li><i class="fas fa-check"></i>To unify and empower community networks through strategic and structured national coordination</li>
                        <li><i class="fas fa-check"></i>To drive cross-sector collaboration between communities, government, industry, and stakeholders</li>
                        <li><i class="fas fa-check"></i>To develop human capital and leadership grounded in values, skills, and future-ready competitiveness</li>
                        <li><i class="fas fa-check"></i>To strengthen socio-economic development through innovative, inclusive, and sustainable approaches</li>
                        <li><i class="fas fa-check"></i>To translate policy, research, and dialogue into high-impact community-based initiatives</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('welfare.about') }}" class="btn btn-primary">Find out more</a>
            </div>
        </div>

        <!-- Engines Modal (Wide Layout) -->
        <div class="modal-container modal-wide" id="modal-engines" style="display: none;">
            <button class="modal-close" onclick="closeSubtabModal()">&times;</button>
            <div class="modal-header">
                <!-- Premium SVG Interlocking Gears Vector Illustration -->
                <svg width="100" height="100" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 15px; display: block;">
                    <g transform="translate(48, 48)">
                        <circle cx="0" cy="0" r="20" fill="url(#gradient-gear1)" opacity="0.85"/>
                        <circle cx="0" cy="0" r="7" fill="#ffffff"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(0)"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(45)"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(90)"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(135)"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(180)"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(225)"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(270)"/>
                        <path d="M-3 -24H3L5 -18H-5L-3 -24Z" fill="url(#gradient-gear1)" transform="rotate(315)"/>
                    </g>
                    <g transform="translate(80, 72)">
                        <circle cx="0" cy="0" r="16" fill="url(#gradient-gear2)" opacity="0.9"/>
                        <circle cx="0" cy="0" r="5" fill="#ffffff"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(22.5)"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(67.5)"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(112.5)"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(157.5)"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(202.5)"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(247.5)"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(292.5)"/>
                        <path d="M-2.5 -20H2.5L4 -15H-4L-2.5 -20Z" fill="url(#gradient-gear2)" transform="rotate(337.5)"/>
                    </g>
                    <g transform="translate(42, 82)">
                        <circle cx="0" cy="0" r="14" fill="url(#gradient-gear3)" opacity="0.8"/>
                        <circle cx="0" cy="0" r="4" fill="#ffffff"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(10)"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(55)"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(100)"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(145)"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(190)"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(235)"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(280)"/>
                        <path d="M-2 -17H2L3 -13H-3L-2 -17Z" fill="url(#gradient-gear3)" transform="rotate(325)"/>
                    </g>
                    <defs>
                        <linearGradient id="gradient-gear1" x1="-20" y1="-20" x2="20" y2="20" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ff6b4a"/>
                            <stop offset="100%" stop-color="#d43c18"/>
                        </linearGradient>
                        <linearGradient id="gradient-gear2" x1="-15" y1="-15" x2="15" y2="15" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ff9900"/>
                            <stop offset="100%" stop-color="#ff5500"/>
                        </linearGradient>
                        <linearGradient id="gradient-gear3" x1="-15" y1="-15" x2="15" y2="15" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#4a90e2"/>
                            <stop offset="100%" stop-color="#0055b8"/>
                        </linearGradient>
                    </defs>
                </svg>
                <h2>Three Engines of Impact</h2>
                <div class="modal-subtitle">Our Coordination Ecosystem</div>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px; text-align: center; margin-bottom: 20px; color: #555555;">
                    MUKMIN operates through three complementary engines to strengthen collaboration, align stakeholders, and drive meaningful community impact at scale.
                </p>
                <div class="modal-grid-3">
                    <!-- Engine 1 -->
                    <div class="engine-modal-card">
                        <div class="engine-modal-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h4>FIKRAH</h4>
                        <span>Think Tank</span>
                        <p>Driving policy, research and long-term strategy to shape stronger communities and future-ready institutions.</p>
                    </div>

                    <!-- Engine 2 -->
                    <div class="engine-modal-card">
                        <div class="engine-modal-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4>MUKMIN</h4>
                        <span>Coordination Platform</span>
                        <p>Uniting organisations, aligning stakeholders and coordinating collective action for nationwide impact.</p>
                    </div>

                    <!-- Engine 3 -->
                    <div class="engine-modal-card">
                        <div class="engine-modal-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h4>Yayasan MUKMIN</h4>
                        <span>Impact Foundation</span>
                        <p>Transforming ideas into measurable outcomes through education, outreach and community-driven initiatives.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('welfare.ecosystem') }}" class="btn btn-primary">Find out more</a>
            </div>
        </div>

        <!-- Initiatives Modal (Wide Layout) -->
        <div class="modal-container modal-wide" id="modal-initiatives" style="display: none;">
            <button class="modal-close" onclick="closeSubtabModal()">&times;</button>
            <div class="modal-header">
                <!-- Premium SVG Rocket Vector Illustration -->
                <svg width="100" height="100" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin: 0 auto 15px; display: block;">
                    <circle cx="60" cy="60" r="50" fill="url(#gradient-rocket-bg)" opacity="0.1"/>
                    <path d="M30 40L32 44L36 45.5L32 47L30 51L28 47L24 45.5L28 44L30 40Z" fill="#ffb700" opacity="0.8"/>
                    <path d="M85 30L86.5 33L89.5 34.5L86.5 36L85 39L83.5 36L80.5 34.5L83.5 33L85 30Z" fill="#ffb700" opacity="0.6"/>
                    <path d="M50 95C42 95 38 90 42 84C38 80 44 72 52 76C56 70 68 70 70 76C78 72 82 80 78 84C82 90 78 95 70 95H50Z" fill="#e0e0e0" opacity="0.9"/>
                    <g transform="translate(60, 64) rotate(45)">
                        <path d="M-6 -45C-10 -35 0 -20 0 -20C0 -20 10 -35 6 -45H-6Z" fill="url(#gradient-flame)" transform="rotate(180)"/>
                        <path d="M-15 -10L-6 -30V-5H-15Z" fill="#990000"/>
                        <path d="M15 -10L6 -30V-5H15Z" fill="#990000"/>
                        <path d="M-6 -50C-6 -50 -10 -25 -6 -5H6C10 -25 6 -50 6 -50L0 -65L-6 -50Z" fill="url(#gradient-rocket)"/>
                        <path d="M-6 -50L0 -65L6 -50H-6Z" fill="#990000"/>
                        <circle cx="0" cy="-30" r="4" fill="#a4d3ee" stroke="#ffffff" stroke-width="2"/>
                    </g>
                    <defs>
                        <linearGradient id="gradient-rocket-bg" x1="10" y1="10" x2="110" y2="110" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ff6b4a"/>
                            <stop offset="100%" stop-color="#d43c18"/>
                        </linearGradient>
                        <linearGradient id="gradient-rocket" x1="-10" y1="-65" x2="10" y2="-5" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ffffff"/>
                            <stop offset="100%" stop-color="#dddddd"/>
                        </linearGradient>
                        <linearGradient id="gradient-flame" x1="-6" y1="-45" x2="6" y2="-20" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ffcc00"/>
                            <stop offset="100%" stop-color="#d43c18"/>
                        </linearGradient>
                    </defs>
                </svg>
                <h2>Strategic Initiatives</h2>
                <div class="modal-subtitle">Focus Areas of Impact</div>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px; text-align: center; margin-bottom: 20px; color: #555555;">
                    MUKMIN focuses on strengthening communities through strategic focus areas that advance inclusive development, future-ready leadership and sustainable national progress.
                </p>
                <div class="modal-grid-2">
                    <!-- Card 1 -->
                    <div class="initiative-modal-card">
                        <div class="initiative-modal-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h4>Socio-Economic Mobility</h4>
                            <span>From Assistance to Sustainable Participation</span>
                            <p>Empowering communities to move beyond dependency through economic participation, enterprise development and sustainable growth opportunities.</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="initiative-modal-card">
                        <div class="initiative-modal-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h4>Education & Future Readiness</h4>
                            <span>Building Competitive Human Capital</span>
                            <p>Developing talent through integrated academic, leadership, and skills-based pathways aligned with industry needs and global relevance.</p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="initiative-modal-card">
                        <div class="initiative-modal-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div>
                            <h4>Leadership & Capacity Building</h4>
                            <span>Values-Driven Leaders for Impact</span>
                            <p>Nurturing principled leaders equipped to contribute at community, national and global levels through leadership development and institutional capacity building.</p>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="initiative-modal-card">
                        <div class="initiative-modal-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h4>Entrepreneurship & Innovation</h4>
                            <span>Enabling Participation in the Future Economy</span>
                            <p>Strengthening innovation, digital capabilities and entrepreneurial growth to increase participation in the evolving regional and global economy.</p>
                        </div>
                    </div>

                    <!-- Card 5 (Full Width Spanning both columns on Desktop) -->
                    <div class="initiative-modal-card full-width">
                        <div class="initiative-modal-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h4>Faith, Identity & Social Ukhwah</h4>
                            <span>Strengthening Communities Through Shared Values</span>
                            <p>Anchoring development in values, identity and unity to build resilient, connected and socially cohesive communities.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('welfare.impact') }}" class="btn btn-primary">Find out more</a>
            </div>
        </div>
    </div>

    <!-- SUB BLOCK 2 (ADOPT A GRADUATE) -->
    <section class="section-welcome bg-light" style="border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
        <div class="container">
            <div class="adopt-grid">
                <div>
                    <h2 style="font-size: 26px; color: var(--color-heading); margin-bottom: 8px;">Adopt A Graduate Programme</h2>
                    <h3 style="font-size: 16px; color: var(--color-primary); font-weight: 600; margin-bottom: 25px;">Building Pathways From Education to Employment</h3>
                    <p style="font-size: 14px; line-height: 24px; color: #555; margin-bottom: 15px;">The Adopt A Graduate Programme connects graduates with industry opportunities through strategic partnerships, mentorship and workforce placement initiatives aimed at strengthening future talent development and employability.</p>
                    <p style="font-size: 14px; line-height: 24px; color: #555; margin-bottom: 15px;">Through collaboration with corporate partners, institutions and employers, the programme bridges the gap between education and career readiness while creating sustainable pathways for young professionals to thrive in the workforce.</p>
                    
                    <div class="adopt-highlight-box">
                        <i class="fas fa-info-circle" style="color: var(--color-primary); margin-right: 8px;"></i>
                        More than 25 companies and strategic partners are currently being engaged to support graduate adoption opportunities across multiple industries and sectors — reflecting MUKMIN’s long-term commitment towards human capital development, talent mobility and inclusive socio-economic advancement.
                    </div>
                    
                    <a href="{{ route('welfare.contact') }}" class="btn btn-primary" style="margin-top: 15px;">Partner with Us</a>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800" alt="Graduation Career Pathways" style="border-radius: 6px; box-shadow: var(--shadow); width: 100%;">
                </div>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 3 (CAUSES LIST / TABBED OR CARDS) -->
    <section class="section-campaigns bg-white" style="padding-top: 80px; padding-bottom: 80px;">
        <div class="container">
            <div class="section-header text-center" style="margin-bottom: 50px;">
                <h2>Our Aid Programs</h2>
                <div class="section-divider"><span></span></div>
                <p class="section-subtitle">MUKMIN distributes aid systematically to support and uplift communities across Malaysia.</p>
            </div>
            
            <div class="subtabs-grid-4">
                <!-- Card 1: Education Aid -->
                <div class="subtab-card">
                    <div class="subtab-icon-wrap">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Education Aid</h3>
                    <div class="aid-card-subheading"></div>
                    <p>Providing educational support, learning access and development opportunities to empower future generations through knowledge and skills.</p>
                    <!-- Progress Bar -->
                    <div style="margin-top: auto;">
                        <div class="aid-card-progress" style="margin-bottom: 0;">
                            <div class="aid-card-progress-label">
                                <span>Aid Target Reached</span>
                                <span>82%</span>
                            </div>
                            <div class="aid-card-progress-bar">
                                <div class="aid-card-progress-fill" style="width: 82%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Social Aid -->
                <div class="subtab-card">
                    <div class="subtab-icon-wrap">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Social Aid</h3>
                    <div class="aid-card-subheading">Strengthening Communities Through Support</div>
                    <p>Delivering community assistance and outreach programmes that uplift vulnerable groups and strengthen social well-being.</p>
                    <!-- Progress Bar -->
                    <div style="margin-top: auto;">
                        <div class="aid-card-progress" style="margin-bottom: 0;">
                            <div class="aid-card-progress-label">
                                <span>Aid Target Reached</span>
                                <span>65%</span>
                            </div>
                            <div class="aid-card-progress-bar">
                                <div class="aid-card-progress-fill" style="width: 65%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Healthcare Aid -->
                <div class="subtab-card">
                    <div class="subtab-icon-wrap">
                        <i class="fas fa-first-aid"></i>
                    </div>
                    <h3>Healthcare Aid</h3>
                    <div class="aid-card-subheading">Advancing Community Well-Being</div>
                    <p>Improving access to healthcare support, wellness initiatives and essential assistance for healthier and more resilient communities.</p>
                    <!-- Progress Bar -->
                    <div style="margin-top: auto;">
                        <div class="aid-card-progress" style="margin-bottom: 0;">
                            <div class="aid-card-progress-label">
                                <span>Aid Target Reached</span>
                                <span>74%</span>
                            </div>
                            <div class="aid-card-progress-bar">
                                <div class="aid-card-progress-fill" style="width: 74%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Others -->
                <div class="subtab-card">
                    <div class="subtab-icon-wrap">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                    <h3>Others</h3>
                    <div class="aid-card-subheading">Responding to Emerging Community Needs</div>
                    <p>Supporting strategic and community-driven initiatives that address evolving challenges and areas requiring immediate impact.</p>
                    <!-- Progress Bar and Link -->
                    <div style="margin-top: auto;">
                        <a href="{{ route('welfare.contact') }}" class="read-more-btn">Talk To Us</a>
                        <div class="aid-card-progress" style="margin-top: 20px; margin-bottom: 0;">
                            <div class="aid-card-progress-label">
                                <span>Aid Target Reached</span>
                                <span>90%</span>
                            </div>
                            <div class="aid-card-progress-bar">
                                <div class="aid-card-progress-fill" style="width: 90%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 4 (KEY ENGAGEMENTS & UPDATES) -->
    <section class="section-welcome bg-light" style="border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
        <div class="container">
            <div class="engagements-grid">
                <!-- Left: Key Engagements -->
                <div>
                    <h2 style="font-size: 24px; color: var(--color-heading); margin-bottom: 30px; position: relative; padding-bottom: 10px;">
                        Key Engagements
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background: var(--color-primary);"></span>
                    </h2>
                    
                    <div class="engagement-item">
                        <div class="engagement-date">
                            <span class="day">11</span>
                            <span class="month-year">May 2026</span>
                        </div>
                        <div class="engagement-title">
                            MUKMIN Extraordinary General Meeting (EGM)
                        </div>
                    </div>

                    <div class="engagement-item">
                        <div class="engagement-date">
                            <span class="day">31</span>
                            <span class="month-year">May 2026</span>
                        </div>
                        <div class="engagement-title">
                            MUKMIN Annual General Meeting (AGM)
                        </div>
                    </div>
                </div>

                <!-- Right: MUKMIN Updates (Horizontal Tabs) -->
                <div>
                    <h2 style="font-size: 24px; color: var(--color-heading); margin-bottom: 30px; position: relative; padding-bottom: 10px;">
                        MUKMIN Updates
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background: var(--color-primary);"></span>
                    </h2>
                    
                    <!-- Tabs Navigation -->
                    <div class="updates-tab-nav">
                        <button class="updates-tab-btn active" onclick="switchUpdatesTab(event, 'programme-updates')">
                            <i class="fas fa-bullhorn"></i> Programme
                        </button>
                        <button class="updates-tab-btn" onclick="switchUpdatesTab(event, 'official-notices')">
                            <i class="fas fa-file-alt"></i> Notices
                        </button>
                        <button class="updates-tab-btn" onclick="switchUpdatesTab(event, 'media-highlights')">
                            <i class="fas fa-photo-video"></i> Media
                        </button>
                    </div>
                    
                    <!-- Tab Panes -->
                    <div class="updates-tab-content">
                        <!-- Pane 1: Programme Updates -->
                        <div class="updates-tab-pane" id="programme-updates">
                            <ul class="updates-pane-list">
                                <li>
                                    <span class="update-date">17 Mar 2026</span>
                                    <h4 class="update-item-title">Ramadan Food Basket Completed</h4>
                                    <p class="update-item-desc">MUKMIN successfully distributed 5,000 food baskets nationwide through mosques and local NGOs.</p>
                                </li>
                                <li>
                                    <span class="update-date">01 May 2026</span>
                                    <h4 class="update-item-title">Adopt A Graduate Programme Onboarding</h4>
                                    <p class="update-item-desc">Over 25 corporate partners are currently being engaged to onboard our next Q3 cohort of graduates.</p>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Pane 2: Official Notices -->
                        <div class="updates-tab-pane" id="official-notices" style="display: none;">
                            <ul class="updates-pane-list">
                                <li>
                                    <span class="update-date">15 May 2026</span>
                                    <h4 class="update-item-title">MUKMIN AGM Registration Open</h4>
                                    <p class="update-item-desc">Members are requested to register for the Annual General Meeting by 25 May 2026 via the online portal.</p>
                                </li>
                                <li>
                                    <span class="update-date">11 May 2026</span>
                                    <h4 class="update-item-title">EGM Minutes Published</h4>
                                    <p class="update-item-desc">The official minutes for the Extraordinary General Meeting held on 11 May 2026 are now available for download.</p>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Pane 3: Media & Highlights -->
                        <div class="updates-tab-pane" id="media-highlights" style="display: none;">
                            <ul class="updates-pane-list">
                                <li>
                                    <span class="update-date">22 Nov 2025</span>
                                    <h4 class="update-item-title">SIRAT Youth Summit Highlights Video</h4>
                                    <p class="update-item-desc">Watch the recap video from Malaysia's largest community youth gathering and the Youth Icon Awards ceremony.</p>
                                </li>
                                <li>
                                    <span class="update-date">12 Apr 2025</span>
                                    <h4 class="update-item-title">Hari Raya Open House Photo Gallery</h4>
                                    <p class="update-item-desc">Browse through the photos from the Hari Raya Aidilfitri Open House with over 2,000 guests in Shah Alam.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 5 (QUOTE PARALLAX / CTA BANNER) -->
    <section class="section-donate-cta" style="background-image: linear-gradient(rgba(212,60,24,0.9), rgba(212,60,24,0.9)), url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=1600'); background-size: cover; background-position: center; background-attachment: fixed; text-align: center; padding: 80px 0;">
        <div class="container" style="max-width: 800px;">
            <i class="fas fa-quote-left" style="font-size: 40px; color: white; opacity: 0.3; margin-bottom: 20px;"></i>
            <h2 style="color: white; font-size: 32px; line-height: 42px; font-weight: 700; margin-bottom: 20px; font-style: italic;">"No one has ever become poor by giving"</h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 15px; line-height: 26px; margin-bottom: 30px;">
                Your contribution supports more than a single programme — it strengthens a wider ecosystem dedicated to community development, leadership growth, education access and long-term social impact. Through strategic collaboration and community-driven initiatives, every contribution helps MUKMIN and its ecosystem partners create meaningful change across communities, institutions and future generations.
            </p>
            <div style="margin-bottom: 15px;">
                <a href="{{ route('welfare.donate') }}" class="btn btn-white" style="font-size: 16px; padding: 12px 35px; border-radius: 25px; font-weight: 700;">Donate Now</a>
            </div>
            <span style="color: rgba(255,255,255,0.85); font-size: 13px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Building Sustainable Impact Together</span>
        </div>
    </section>

    <!-- SUB BLOCK 6 (FEATURED NEWS SNIPPETS) -->
    <section class="section-blog bg-white">
        <div class="container">
            <div class="section-header text-center">
                <h2>News & Activities</h2>
                <div class="section-divider"><span></span></div>
                <p class="section-subtitle">Stay updated with the latest events and highlights from Pertubuhan Gabungan MUKMIN Nasional.</p>
            </div>
            
            <div class="blog-grid news-activities-grid">
                @php
                $activities = [
                    [
                        'title' => 'MUKMIN Hari Raya Aidilfitri Open House 2025',
                        'meta' => '12 April 2025 | IDCC, Shah Alam',
                        'desc' => 'MUKMIN’s Hari Raya Aidilfitri Open House gathered NGOs, mosques, madrasahs, suraus and tahfiz institutions from across Malaysia in a celebration of unity and community spirit, welcoming approximately 2,000 guests including more than 200 religious scholars.',
                        'image' => 'https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=500'
                    ],
                    [
                        'title' => 'SIRAT Leaders Forum',
                        'meta' => '29 – 31 August 2025 | Pahang',
                        'desc' => 'The SIRAT Leaders Forum convened 250 NGO leaders, policymakers, professionals and religious institutions in a strategic platform focused on collaboration, leadership and sustainable community development.',
                        'image' => 'https://images.unsplash.com/photo-1540317580384-e5d43616b9aa?w=500'
                    ],
                    [
                        'title' => 'Official Launch of FIKRAH',
                        'meta' => '22 November 2025 | PICCA Convention Centre',
                        'desc' => 'FIKRAH was officially launched as MUKMIN’s strategic think tank focused on innovation, policy thinking, research and long-term community development.',
                        'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=500'
                    ],
                    [
                        'title' => 'SIRAT Youth Summit & Youth Icon Awards',
                        'meta' => '22 November 2025 | PICCA Convention Centre',
                        'desc' => 'Malaysia’s largest community youth gatherings, the programme brought together over 1,000 changemakers through leadership sessions, panel discussions, pitching sessions and the Youth Icon Awards.',
                        'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=500'
                    ],
                    [
                        'title' => 'Kembara Ramadan MUKMIN – Food Basket Initiative',
                        'meta' => '28 February – 17 March 2026',
                        'desc' => 'MUKMIN successfully distributed 5,000 food baskets nationwide through mosque, madrasah, surau and NGO networks to support communities in need during Ramadan.',
                        'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=500'
                    ],
                    [
                        'title' => 'SIRAT Global Forum 2026',
                        'meta' => '23 – 25 January 2026 | Kuala Lumpur',
                        'desc' => 'An international platform bringing together business leaders, professionals, innovators and youths from over 20 countries to strengthen global collaboration and future community development.',
                        'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=500'
                    ],
                    [
                        'title' => 'Ramadan Assistance for Religious Scholars & Ustaz',
                        'meta' => '11 March 2026 | Kuala Lumpur',
                        'desc' => 'Ramadan assistance initiatives were carried out to support religious scholars and ustaz through food aid, financial assistance and complimentary medical protection coverage.',
                        'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=500'
                    ]
                ];
                @endphp

                @foreach($activities as $act)
                <div class="blog-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="blog-img" style="position: relative; overflow: hidden; height: 180px;">
                            <img src="{{ $act['image'] }}" alt="{{ $act['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="blog-body" style="padding: 20px;">
                            <div class="blog-meta" style="font-size: 11px; color: var(--color-primary); margin-bottom: 8px; font-weight: 600;">
                                <i class="fas fa-calendar-alt"></i> {{ $act['meta'] }}
                            </div>
                            <h3 style="font-size: 15px; line-height: 22px; margin-bottom: 10px; color: var(--color-heading); font-weight: 600;">
                                {{ $act['title'] }}
                            </h3>
                            <p style="font-size: 13px; line-height: 18px; color: #666; margin-bottom: 0;">
                                {{ \Illuminate\Support\Str::limit($act['desc'], 140) }}
                            </p>
                        </div>
                    </div>
                    <div style="padding: 0 20px 20px 20px;">
                        <a href="{{ route('welfare.news') }}" class="read-more" style="font-size: 12px; font-weight: 600;">Read More / View Gallery <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
function switchUpdatesTab(evt, tabId) {
    const panes = document.querySelectorAll('.updates-tab-pane');
    panes.forEach(pane => {
        pane.style.display = 'none';
        pane.classList.remove('active');
    });
    
    const buttons = document.querySelectorAll('.updates-tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });
    
    const activePane = document.getElementById(tabId);
    if (activePane) {
        activePane.style.display = 'block';
        activePane.offsetHeight; // Force reflow
        activePane.classList.add('active');
    }
    
    evt.currentTarget.classList.add('active');
}

function openSubtabModal(type) {
    const overlay = document.getElementById('subtab-modal-overlay');
    const modals = document.querySelectorAll('.modal-overlay .modal-container');
    
    // Hide all modals first
    modals.forEach(function(modal) {
        modal.style.display = 'none';
    });
    
    // Show target modal
    const targetModal = document.getElementById('modal-' + type);
    if (targetModal) {
        targetModal.style.display = 'block';
    }
    
    // Show overlay
    if (overlay) {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Disable background scrolling
    }
}

function closeSubtabModal() {
    const overlay = document.getElementById('subtab-modal-overlay');
    if (overlay) {
        overlay.classList.remove('active');
    }
    document.body.style.overflow = ''; // Enable background scrolling
}

// Close modal when clicking outside the container or pressing ESC
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('subtab-modal-overlay');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeSubtabModal();
            }
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSubtabModal();
        }
    });
});
</script>
@endpush
