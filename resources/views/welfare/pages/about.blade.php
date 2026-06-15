@extends('welfare.layouts.app')

@section('title', 'About Us - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
/* About Page Specific Premium Styling */


.section-padding {
    padding: 70px 0;
}
.bg-white {
    background-color: #ffffff;
}
.bg-light {
    background-color: #f9f9f9;
}
.bg-warm {
    background-color: #fcf6f3;
}

/* Who We Are Layout */
.who-we-are-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 50px;
    align-items: stretch;
}
.who-headline {
    font-size: 30px;
    font-weight: 700;
    color: var(--color-heading);
    margin-bottom: 12px;
    line-height: 1.25;
}
.who-subheadline {
    font-size: 17px;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 25px;
    line-height: 1.5;
}
.who-body p {
    font-size: 14.5px;
    line-height: 24px;
    color: #555;
    margin-bottom: 18px;
    text-align: justify;
}
.who-image {
    height: 100%;
    min-height: 0;
}
.who-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    border-radius: 6px;
    box-shadow: var(--shadow);
}

/* President's Note Layout */
.president-grid {
    display: grid;
    grid-template-columns: 0.8fr 1.2fr;
    gap: 45px;
    align-items: flex-start;
}
.president-img-container {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: var(--shadow);
}
.president-img-container img {
    width: 100%;
    display: block;
}
.president-title-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.85));
    color: #fff;
    padding: 15px;
    text-align: center;
}
.president-title-overlay h4 {
    margin: 0 0 3px 0;
    font-size: 15px;
    color: #fff;
    font-weight: 700;
}
.president-title-overlay span {
    display: block;
    font-size: 12px;
    opacity: 0.9;
}
.president-title-overlay a {
    display: block;
    font-size: 12px;
    opacity: 0.9;
    color: #fff;
    text-decoration: none;
    margin-top: 3px;
}
.president-title-overlay a:hover {
    text-decoration: underline;
}
.president-letter {
    background: #fff;
    border: 1px solid var(--color-border);
    border-radius: 6px;
    padding: 35px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.president-letter h3 {
    font-size: 24px;
    margin-bottom: 20px;
    color: var(--color-heading);
    border-bottom: 2px solid var(--color-primary);
    padding-bottom: 8px;
    display: inline-block;
}
.president-letter > p {
    font-size: 14.5px;
    line-height: 24px;
    color: #444;
    margin-bottom: 15px;
    text-align: justify;
}
.president-signature {
    margin-top: 25px;
    border-top: 1px solid var(--color-border);
    padding-top: 15px;
}
.president-signature h5 {
    margin: 0;
    font-size: 15px;
    color: var(--color-heading);
}
.president-signature p {
    margin: 3px 0 0 0;
    font-size: 12px;
    color: #777;
}

/* Leadership Section Layout */
.leadership-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 40px;
}
.leadership-header h2 {
    font-size: 34px;
    margin-bottom: 10px;
    color: var(--color-heading);
}
.leadership-header .subtitle {
    font-size: 19px;
    color: var(--color-primary);
    font-weight: 600;
    margin-bottom: 15px;
}
.leadership-header .intro-text {
    font-size: 16px;
    line-height: 26px;
    color: #666;
}

.leadership-tabs {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 35px;
    border-bottom: 2px solid var(--color-border);
    padding-bottom: 1px;
    flex-wrap: wrap;
}
.leadership-tab-btn {
    background: none;
    border: none;
    font-family: var(--font-main);
    font-size: 17px;
    font-weight: 600;
    color: var(--color-text);
    cursor: pointer;
    padding: 12px 20px;
    position: relative;
    transition: color var(--transition);
}
.leadership-tab-btn:hover {
    color: var(--color-primary);
}
.leadership-tab-btn.active {
    color: var(--color-primary) !important;
}
.leadership-tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--color-primary);
}

.leadership-tab-content {
    display: none;
    animation: fadeInTab 0.4s ease forwards;
}
.leadership-tab-content.active {
    display: block;
}

.tab-intro {
    text-align: center;
    max-width: 750px;
    margin: 0 auto 35px;
    font-size: 16px;
    line-height: 26px;
    color: #555;
    font-style: italic;
    background: #fcfcfc;
    padding: 14px 22px;
    border-left: 3px solid var(--color-primary);
    border-radius: 0 4px 4px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

/* Members Grid Layout */
.members-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    align-items: stretch;
}
.members-grid.exco-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* Member Card Layout */
.member-card {
    background: #ffffff;
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 26px 20px;
    text-align: center;
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    font-family: inherit;
    appearance: none;
    -webkit-appearance: none;
}
.member-card:hover,
.member-card:focus-visible {
    box-shadow: var(--shadow-hover);
    border-color: var(--color-primary);
    outline: none;
}
.member-avatar {
    width: 105px;
    height: 105px;
    border-radius: 50%;
    margin: 0 auto 14px;
    overflow: hidden;
    border: 3px solid #f3f3f3;
    transition: border-color var(--transition);
    flex-shrink: 0;
}
.member-card:hover .member-avatar,
.member-card:focus-visible .member-avatar {
    border-color: var(--color-primary);
}
.member-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.member-card-body {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1 1 auto;
}
.member-name {
    width: 100%;
    font-size: 17px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 6px;
    line-height: 1.4;
    min-height: calc(1.4em * 2);
}
.member-role {
    width: 100%;
    font-size: 15px;
    font-weight: 600;
    color: var(--color-primary);
    margin: 0 0 5px;
    line-height: 1.4;
    min-height: calc(1.4em * 2);
    display: block;
}
.member-org {
    width: 100%;
    font-size: 14px;
    color: #666;
    line-height: 1.4;
    min-height: calc(1.4em * 2);
    margin: 0;
    display: block;
}
.member-tag {
    display: inline-block;
    width: 100%;
    max-width: 100%;
    background: #f1f5f9;
    color: #475569;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 11px;
    border-radius: 14px;
    margin: 0;
    letter-spacing: 0.2px;
    line-height: 1.35;
    box-sizing: border-box;
}
.member-card:hover .member-tag,
.member-card:focus-visible .member-tag {
    background: #fdeee9;
    color: var(--color-primary);
}
.member-card-hint {
    display: block;
    margin-top: auto;
    padding-top: 12px;
    font-size: 13px;
    font-weight: 600;
    color: var(--color-primary);
    opacity: 0;
    transform: translateY(4px);
    transition: opacity var(--transition), transform var(--transition);
    flex-shrink: 0;
}
.member-card:hover .member-card-hint,
.member-card:focus-visible .member-card-hint {
    opacity: 1;
    transform: translateY(0);
}

/* Leadership hover preview */
.member-hover-preview {
    position: fixed;
    z-index: 1200;
    width: min(320px, calc(100vw - 24px));
    background: #fff;
    border: 1px solid var(--color-border);
    border-radius: 10px;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
    overflow: hidden;
    pointer-events: none;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}
.member-hover-preview.visible {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}
.member-hover-preview-photo {
    width: 100%;
    aspect-ratio: 4 / 5;
    background: #f3f4f6;
}
.member-hover-preview-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.member-hover-preview-body {
    padding: 16px 18px 18px;
}
.member-hover-preview-committee,
.leadership-member-modal-committee {
    display: inline-block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    color: var(--color-primary);
    margin-bottom: 8px;
}
.member-hover-preview-name {
    margin: 0 0 8px;
    font-size: 18px;
    line-height: 1.35;
    color: var(--color-heading);
}
.member-hover-preview-role,
.leadership-member-modal-role {
    margin: 0 0 8px;
    font-size: 15px;
    font-weight: 600;
    color: var(--color-primary);
}
.member-hover-preview-meta,
.leadership-member-modal-meta {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
    color: #555;
}

/* Leadership profile modal */
.leadership-member-modal {
    position: fixed;
    inset: 0;
    z-index: 1300;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s ease, visibility 0.25s ease;
}
.leadership-member-modal.open {
    opacity: 1;
    visibility: visible;
}
.leadership-member-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.72);
}
.leadership-member-modal-panel {
    position: relative;
    width: min(760px, 100%);
    max-height: calc(100vh - 40px);
    overflow: auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
    transform: translateY(16px) scale(0.98);
    transition: transform 0.25s ease;
}
.leadership-member-modal.open .leadership-member-modal-panel {
    transform: translateY(0) scale(1);
}
.leadership-member-modal-close {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 2;
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.92);
    color: #334155;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
}
.leadership-member-modal-close:hover {
    background: #fff;
    color: var(--color-primary);
}
.leadership-member-modal-content {
    display: grid;
    grid-template-columns: 0.95fr 1.05fr;
}
.leadership-member-modal-photo {
    min-height: 320px;
    background: #f3f4f6;
}
.leadership-member-modal-photo img {
    width: 100%;
    height: 100%;
    min-height: 320px;
    object-fit: cover;
    display: block;
}
.leadership-member-modal-details {
    padding: 34px 30px;
}
.leadership-member-modal-details h3 {
    margin: 0 0 12px;
    font-size: 28px;
    line-height: 1.3;
    color: var(--color-heading);
}
body.leadership-modal-open {
    overflow: hidden;
}

@keyframes fadeInTab {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 1199px) {
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 991px) {
    .who-we-are-grid, .president-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .who-image {
        height: auto;
    }
    .who-image img {
        height: auto;
        max-height: 480px;
    }
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 575px) {
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: 1fr;
    }
    .leadership-member-modal-content {
        grid-template-columns: 1fr;
    }
    .leadership-member-modal-details {
        padding: 24px 20px 28px;
    }
    .leadership-member-modal-details h3 {
        font-size: 22px;
    }
}
</style>


<!-- Sub Block 1: Who We Are -->
<section id="who-we-are" class="section-padding bg-white">
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

<!-- Sub Block 2: President's Note -->
<section id="president-note" class="section-padding bg-warm" style="border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <div class="president-grid">
            <div class="president-img-container">
                <img src="{{ asset('welfare/img/about/president-shahul-hameed.png') }}" alt="Datuk Wira Shahul Dawood">
                <div class="president-title-overlay">
                    <h4>Datuk Wira Shahul Dawood</h4>
                    <span>President, Pertubuhan Gabungan MUKMIN Nasional</span>
                    <a href="mailto:president@mukmin.org">president@mukmin.org</a>
                </div>
            </div>
            <div class="president-letter">
                <h3>President's Note</h3>
                <p>At MUKMIN, we believe that the strength of a community is measured not by its numbers alone, but by its unity, shared purpose and ability to create lasting impact for future generations.</p>
                <p>MUKMIN was established with a clear purpose: to unite organisations, leaders and communities through a coordinated national platform that is structured, accountable, progressive and driven by meaningful action. In a rapidly changing world shaped by technological advancement, economic uncertainty and evolving social challenges, our community cannot afford to move in isolation. We must move forward together, with clarity of purpose and collective strength.</p>
                <p>MUKMIN is more than an organisation. It is an ecosystem for collaboration and community transformation which brings together NGOs, community associations and strategic partners to align grassroots efforts with national coordination, while creating sustainable opportunities for our people.</p>
                <p>Guided by our five strategic pillars, namely Economic Empowerment, Education and Talent Development, Leadership and Representation, Community Welfare and Faith, Identity and Ukhwah, we seek to build a resilient, empowered and future-ready community.</p>
                <p>We envision a generation that is economically resilient, intellectually prepared, socially responsible and firmly grounded in values and identity. Our mission is to create platforms that open doors to education, entrepreneurship, leadership development and strategic partnerships, while delivering initiatives that bring measurable and meaningful outcomes to society.</p>
                <p>The future belongs to communities that are organised, united and willing to evolve. MUKMIN is committed to being a catalyst for that transformation of connecting people, ideas and resources to build a stronger, more sustainable legacy for generations to come.</p>
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

@include('welfare.partials.leadership-section')
@endsection

@push('scripts')
    @include('welfare.partials.leadership-scripts')
@endpush
