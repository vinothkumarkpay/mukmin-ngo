@extends('welfare.layouts.app')

@section('title', 'Serve Together - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
.serve-page {
    font-family: var(--font-main);
    color: var(--color-heading);
    background-color: #f5f5f5;
}

.section-padding {
    padding: 60px 0;
}
.bg-white { background: #ffffff; }
.bg-light { background: #f9f9f9; }
.bg-dark {
    background: #1e1e1e;
    color: #ffffff;
}

.section-header {
    text-align: center;
    margin-bottom: 45px;
}
.section-header h2 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--color-heading);
}
.bg-dark .section-header h2,
.bg-dark .section-header p {
    color: #ffffff;
}
.section-header p {
    font-size: 15px;
    line-height: 26px;
    color: #666;
    max-width: 720px;
    margin: 0 auto;
}

/* SUB BLOCK 1: Featured Intro */
.featured-row { padding: 50px 0 25px; }
.featured-block {
    display: flex;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}
.featured-left {
    width: 48%;
    overflow: hidden;
}
.featured-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    min-height: 320px;
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
.featured-right .category-tag {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 12px;
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

.intro-copy {
    max-width: 900px;
    margin: 0 auto;
    padding: 10px 20px 0;
}
.intro-copy p {
    font-size: 14.5px;
    line-height: 24px;
    color: #555;
    margin-bottom: 18px;
}
.intro-copy p:last-child {
    margin-bottom: 0;
}

/* SUB BLOCK 2: Stats Circles */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 24px;
    margin-top: 10px;
}
.stat-circle {
    text-align: center;
}
.stat-circle-ring {
    width: 140px;
    height: 140px;
    margin: 0 auto 16px;
    border-radius: 50%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    transition: transform var(--transition, 0.3s ease), filter var(--transition, 0.3s ease);
}
.stat-circle-ring:hover {
    transform: scale(1.08);
    filter: drop-shadow(0 4px 10px rgba(212, 60, 24, 0.45));
}
.dial-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transform: rotate(-90deg); /* Start drawing from the top */
}
.dial-bg {
    fill: none;
    stroke: rgba(255, 255, 255, 0.08);
    stroke-width: 6px;
}
.dial-fill {
    fill: none;
    stroke: url(#dialGradient);
    stroke-width: 6px;
    stroke-linecap: round;
    stroke-dasharray: 282.74; /* 2 * PI * 45 */
    stroke-dashoffset: 282.74; /* Start fully offset */
    transition: stroke-dashoffset 2.2s cubic-bezier(0.1, 0.8, 0.2, 1);
}
.dial-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.stat-value {
    font-size: 19px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
    text-shadow: 0 2px 10px rgba(212, 60, 24, 0.35);
}
.stat-value-sm {
    font-size: 13px;
}
.stat-label {
    font-size: 12.5px;
    line-height: 18px;
    color: rgba(255, 255, 255, 0.75);
    max-width: 140px;
    margin: 0 auto;
}

/* SUB BLOCK 3: Vertical Tabs */
.vertical-tabs {
    display: flex;
    gap: 0;
    background: #ececec;
    border: 1px solid #e0e0e0;
    min-height: 420px;
}
.vertical-tabs-nav {
    width: 280px;
    flex-shrink: 0;
    background: #e8e8e8;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
}
.vtab-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 18px 20px;
    border: none;
    border-left: 3px solid transparent;
    background: transparent;
    text-align: left;
    font-size: 13.5px;
    font-weight: 500;
    line-height: 1.4;
    color: #888;
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: var(--font-main);
}
.vtab-btn i {
    width: 18px;
    text-align: center;
    font-size: 15px;
    color: #aaa;
    flex-shrink: 0;
}
.vtab-btn:hover {
    color: #444;
    background: rgba(255,255,255,0.35);
}
.vtab-btn.active {
    color: #222;
    font-weight: 600;
    background: #f5f5f5;
    border-left-color: var(--color-primary, #d43c18);
}
.vtab-btn.active i {
    color: var(--color-primary, #d43c18);
}
.vertical-tabs-content {
    flex: 1;
    background: #ffffff;
    padding: 36px 40px;
}
.vtab-panel {
    display: none;
}
.vtab-panel.active {
    display: block;
}
.vtab-panel h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--color-heading);
}
.vtab-panel .membership-subtitle {
    font-size: 13px;
    font-weight: 600;
    color: #888;
    margin-bottom: 16px;
}
.vtab-panel > p {
    font-size: 13.5px;
    line-height: 22px;
    color: #555;
    margin-bottom: 16px;
}
.vtab-panel h4 {
    font-size: 14px;
    font-weight: 700;
    margin: 12px 0 10px;
    color: var(--color-heading);
}
.vtab-panel .card-btn {
    width: auto;
    margin-top: 20px;
}

/* SUB BLOCK 4: Horizontal Tabs */
.horizontal-tabs {
    background: #ececec;
    border: 1px solid #e0e0e0;
}
.htab-nav {
    display: flex;
    gap: 0;
    padding: 0;
    background: #ececec;
}
.htab-btn {
    flex: 1;
    padding: 16px 24px;
    border: none;
    border-top: 3px solid transparent;
    background: transparent;
    font-size: 14px;
    font-weight: 500;
    color: #999;
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: var(--font-main);
    text-align: left;
}
.htab-btn:hover {
    color: #555;
}
.htab-btn.active {
    color: #222;
    font-weight: 700;
    background: #ffffff;
    border-top-color: var(--color-primary, #d43c18);
}
.htab-content {
    background: #ffffff;
    padding: 36px 40px;
    min-height: 280px;
}
.htab-panel {
    display: none;
}
.htab-panel.active {
    display: block;
}
.htab-panel h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--color-heading);
}
.htab-panel .panel-subtitle {
    font-size: 13px;
    color: var(--color-primary, #d43c18);
    font-weight: 600;
    margin-bottom: 22px;
}
.membership-list {
    list-style: none;
    padding: 0;
    margin: 0 0 16px;
}
.membership-list li {
    position: relative;
    padding-left: 18px;
    font-size: 13px;
    line-height: 22px;
    color: #555;
    margin-bottom: 8px;
}
.membership-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 9px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--color-primary, #d43c18);
}
.membership-note {
    background: #fdf6f4;
    border-left: 3px solid var(--color-primary, #d43c18);
    padding: 12px 14px;
    font-size: 12.5px;
    line-height: 20px;
    color: #666;
    margin-bottom: 18px;
}
.card-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: auto;
    background: var(--color-primary, #d43c18);
    color: #ffffff !important;
    padding: 12px 22px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.3s ease;
    width: 100%;
    text-align: center;
}
.card-btn:hover {
    background: var(--color-primary-dk, #b83210);
    color: #ffffff !important;
}
button.card-btn {
    border: none;
    cursor: pointer;
    font-family: inherit;
}

/* Membership registration modal */
.membership-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    z-index: 9999;
}
.membership-modal-overlay[hidden] {
    display: none;
}
.membership-modal {
    background: #ffffff;
    border-radius: 10px;
    width: 100%;
    max-width: 520px;
    padding: 32px 28px 28px;
    position: relative;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
}
.membership-modal-close {
    position: absolute;
    top: 14px;
    right: 16px;
    border: none;
    background: transparent;
    font-size: 24px;
    line-height: 1;
    color: #888;
    cursor: pointer;
}
.membership-modal-close:hover {
    color: #333;
}
.membership-modal h3 {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 18px;
    color: var(--color-heading);
    padding-right: 24px;
}
.membership-modal-question {
    font-size: 15px;
    line-height: 24px;
    color: #444;
    margin: 0 0 18px;
    font-weight: 600;
}
.membership-modal-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 24px;
}
.membership-modal-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    color: #333;
    transition: border-color 0.2s ease, background 0.2s ease;
}
.membership-modal-option:hover {
    border-color: var(--color-primary, #d43c18);
    background: #fff9f7;
}
.membership-modal-option input[type="radio"] {
    accent-color: var(--color-primary, #d43c18);
    flex-shrink: 0;
}
.membership-modal-submit {
    margin-top: 0;
}

/* SUB BLOCK 4 shared lists */
.process-steps {
    list-style: none;
    padding: 0;
    margin: 0;
    counter-reset: step;
}
.process-steps li {
    position: relative;
    padding-left: 48px;
    margin-bottom: 18px;
    font-size: 13.5px;
    line-height: 22px;
    color: #555;
}
.process-steps li::before {
    counter-increment: step;
    content: counter(step);
    position: absolute;
    left: 0;
    top: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--color-primary, #d43c18);
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* SUB BLOCK 5: Engagement Cards */
.engage-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}
#membership-vertical-tabs {
    scroll-margin-top: 160px;
}
.engage-card {
    background: #ffffff;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 50px 24px 32px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    scroll-margin-top: 190px;
}
.engage-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}
.engage-icon {
    position: absolute;
    top: -28px;
    left: 50%;
    transform: translateX(-50%);
    width: 56px;
    height: 56px;
    background: var(--color-primary, #d43c18);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #ffffff;
    box-shadow: 0 4px 10px rgba(212, 60, 24, 0.25);
}
.engage-icon i {
    color: #ffffff;
    font-size: 20px;
}
.engage-card .engage-time {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}
.engage-card h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 12px;
    line-height: 1.35;
}
.engage-card p {
    font-size: 13.5px;
    line-height: 22px;
    color: #666;
    margin-bottom: 22px;
    flex-grow: 1;
}
.card-btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    color: var(--color-heading) !important;
    border: 2px solid var(--color-border, #e1e1e1);
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 700;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
    width: 100%;
}
.card-btn-outline:hover {
    background: var(--color-primary, #d43c18);
    border-color: var(--color-primary, #d43c18);
    color: #ffffff !important;
}

@media (max-width: 991px) {
    #membership-vertical-tabs {
        scroll-margin-top: 90px;
    }
    .engage-card {
        scroll-margin-top: 120px;
    }
    .featured-block { flex-direction: column; }
    .featured-left, .featured-right { width: 100%; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .engage-grid { grid-template-columns: 1fr; }
    .vertical-tabs { flex-direction: column; min-height: auto; }
    .vertical-tabs-nav { width: 100%; flex-direction: row; border-right: none; border-bottom: 1px solid #ddd; }
    .vtab-btn { flex: 1; justify-content: center; border-left: none; border-bottom: 3px solid transparent; padding: 14px 12px; font-size: 12px; }
    .vtab-btn.active { border-left-color: transparent; border-bottom-color: var(--color-primary, #d43c18); }
    .vertical-tabs-content { padding: 28px 24px; }
    .htab-btn { font-size: 13px; padding: 14px 16px; }
    .htab-content { padding: 28px 24px; }
}
@media (max-width: 575px) {
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

<div class="serve-page">
    <!-- SUB BLOCK 1: Join Our Ecosystem -->
    <div class="featured-row">
        <div class="container">
            <div class="featured-block">
                <div class="featured-left">
                    <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=600&h=450&q=80" alt="Community collaboration and collective action">
                </div>
                <div class="featured-right">
                    <span class="category-tag">Join Our Ecosystem</span>
                    <h2>Strengthening Communities Through Collective Action</h2>
                    <p>Building a connected ecosystem of organisations, institutions, and changemakers committed to inclusive and sustainable community development.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="section-padding bg-white" style="padding-top: 35px;">
        <div class="container">
            <div class="intro-copy">
                <p>MUKMIN's membership ecosystem is designed to unite organisations, community networks, institutions, and individuals under a shared commitment towards collaboration, empowerment, and long-term impact.</p>
                <p>As a national coordinating ecosystem, MUKMIN brings together NGOs, civil society organisations, chambers of commerce, grassroots networks, and strategic stakeholders to strengthen collective action and community transformation across Malaysia.</p>
                <p>Through participation in the ecosystem, members gain opportunities to collaborate, contribute, participate in strategic initiatives, and be part of a growing national movement shaping inclusive and sustainable development.</p>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 2: Impact Stats -->
    <section class="section-padding bg-dark" id="serve-impact-section">
        <!-- SVG Gradient Definition for Dials -->
        <svg style="width: 0; height: 0; position: absolute;" aria-hidden="true">
            <defs>
                <linearGradient id="dialGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ff7b54" />
                    <stop offset="100%" stop-color="#d43c18" />
                </linearGradient>
            </defs>
        </svg>

        <div class="container">
            <div class="section-header">
                <h2>Measuring Progress. Empowering Communities.</h2>
                <p>A growing movement driven by collaboration, leadership and meaningful community impact across Malaysia and beyond.</p>
            </div>
            <div class="stats-grid">
                <!-- Dial 1 -->
                <div class="stat-circle">
                    <div class="stat-circle-ring">
                        <svg class="dial-svg" viewBox="0 0 100 100">
                            <circle class="dial-bg" cx="50" cy="50" r="45"></circle>
                            <circle class="dial-fill" cx="50" cy="50" r="45" data-percentage="90"></circle>
                        </svg>
                        <div class="dial-content">
                            <span class="stat-value" data-target="720" data-suffix="+">0+</span>
                        </div>
                    </div>
                    <p class="stat-label">Days<br>Driving Change</p>
                </div>
                <!-- Dial 2 -->
                <div class="stat-circle">
                    <div class="stat-circle-ring">
                        <svg class="dial-svg" viewBox="0 0 100 100">
                            <circle class="dial-bg" cx="50" cy="50" r="45"></circle>
                            <circle class="dial-fill" cx="50" cy="50" r="45" data-percentage="65"></circle>
                        </svg>
                        <div class="dial-content">
                            <span class="stat-value" data-target="20" data-suffix="+">0+</span>
                        </div>
                    </div>
                    <p class="stat-label">Initiatives<br>Groundbreaking Programmes</p>
                </div>
                <!-- Dial 3 -->
                <div class="stat-circle">
                    <div class="stat-circle-ring">
                        <svg class="dial-svg" viewBox="0 0 100 100">
                            <circle class="dial-bg" cx="50" cy="50" r="45"></circle>
                            <circle class="dial-fill" cx="50" cy="50" r="45" data-percentage="85"></circle>
                        </svg>
                        <div class="dial-content">
                            <span class="stat-value stat-value-sm" data-target="5000000" data-prefix="RM" data-suffix="+">RM0+</span>
                        </div>
                    </div>
                    <p class="stat-label">Raised for<br>Education</p>
                </div>
                <!-- Dial 4 -->
                <div class="stat-circle">
                    <div class="stat-circle-ring">
                        <svg class="dial-svg" viewBox="0 0 100 100">
                            <circle class="dial-bg" cx="50" cy="50" r="45"></circle>
                            <circle class="dial-fill" cx="50" cy="50" r="45" data-percentage="95"></circle>
                        </svg>
                        <div class="dial-content">
                            <span class="stat-value" data-target="10000" data-suffix="+">0+</span>
                        </div>
                    </div>
                    <p class="stat-label">Lives Empowered<br>Through Programmes</p>
                </div>
                <!-- Dial 5 -->
                <div class="stat-circle">
                    <div class="stat-circle-ring">
                        <svg class="dial-svg" viewBox="0 0 100 100">
                            <circle class="dial-bg" cx="50" cy="50" r="45"></circle>
                            <circle class="dial-fill" cx="50" cy="50" r="45" data-percentage="80"></circle>
                        </svg>
                        <div class="dial-content">
                            <span class="stat-value" data-target="5000" data-suffix="+">0+</span>
                        </div>
                    </div>
                    <p class="stat-label">Families Supported<br>During Ramadan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 3: Membership Categories (Vertical Tabs) -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="section-header">
                <h2>Membership Categories</h2>
            </div>
            <div class="vertical-tabs" id="membership-vertical-tabs">
                <div class="vertical-tabs-nav" role="tablist" aria-label="Membership categories">
                    <button type="button" class="vtab-btn active" role="tab" aria-selected="true" aria-controls="vtab-ordinary" id="vtab-btn-ordinary" data-target="vtab-ordinary">
                        <i class="fas fa-building" aria-hidden="true"></i>
                        <span>Ordinary Members</span>
                    </button>
                    <button type="button" class="vtab-btn" role="tab" aria-selected="false" aria-controls="vtab-friends" id="vtab-btn-friends" data-target="vtab-friends">
                        <i class="fas fa-users" aria-hidden="true"></i>
                        <span>Friends of MUKMIN</span>
                    </button>
                </div>
                <div class="vertical-tabs-content">
                    <div class="vtab-panel active" id="vtab-ordinary" role="tabpanel" aria-labelledby="vtab-btn-ordinary">
                        <h3>Organisational Membership</h3>
                        <p class="membership-subtitle">Open to legally registered organisations and entities that share MUKMIN's vision and mission.</p>
                        <p>Ordinary Members form the core membership structure of MUKMIN and play an active role in contributing towards the organisation's direction, initiatives, and ecosystem development.</p>
                        <h4>Eligibility</h4>
                        <ul class="membership-list">
                            <li>Registered NGOs and Civil Society Organisations</li>
                            <li>Chambers of Commerce and Industry Associations</li>
                        </ul>
                        <h4>Member Rights &amp; Participation</h4>
                        <ul class="membership-list">
                            <li>Eligible to vote at General Meetings</li>
                            <li>Eligible to hold positions within the organisation</li>
                            <li>Participation in programmes, platforms, and ecosystem initiatives</li>
                            <li>Opportunity to nominate organisational representatives subject to constitutional provisions and approvals</li>
                        </ul>
                        <button
                            type="button"
                            class="card-btn"
                            id="mukmin-member-trigger"
                            data-ordinary-url="{{ route('welfare.membership.ordinary') }}"
                            data-friends-url="{{ route('welfare.membership.friends') }}"
                        >Be a MUKMIN Member</button>
                    </div>
                    <div class="vtab-panel" id="vtab-friends" role="tabpanel" aria-labelledby="vtab-btn-friends" hidden>
                        <h3>Community &amp; Supporter Network</h3>
                        <p class="membership-subtitle">Open to individuals, informal groups, and organisations that are not formally registered but wish to contribute towards MUKMIN's mission and community initiatives.</p>
                        <p>Friends of MUKMIN serve as an important support ecosystem that strengthens volunteerism, collaboration, and grassroots engagement.</p>
                        <h4>Participation Opportunities</h4>
                        <ul class="membership-list">
                            <li>Contribute to community engagement initiatives</li>
                            <li>Participate in selected programmes and platforms</li>
                            <li>Be involved in working groups and sub-committees</li>
                            <li>Collaborate in community-driven activities and ecosystem initiatives</li>
                        </ul>
                        <div class="membership-note">
                            <strong>Important Note:</strong> Friends of MUKMIN do not possess voting rights and are not eligible to hold positions within the Executive Committee of the organisation.
                        </div>
                        <a href="{{ route('welfare.membership.friends') }}" class="card-btn">Be Friends of MUKMIN</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 4: Process & Why Join (Horizontal Tabs) -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="horizontal-tabs" id="process-horizontal-tabs">
                <div class="htab-nav" role="tablist" aria-label="Membership information">
                    <button type="button" class="htab-btn active" role="tab" aria-selected="true" aria-controls="htab-process" id="htab-btn-process" data-target="htab-process">
                        Membership Process
                    </button>
                    <button type="button" class="htab-btn" role="tab" aria-selected="false" aria-controls="htab-why-join" id="htab-btn-why-join" data-target="htab-why-join">
                        Why Join MUKMIN?
                    </button>
                </div>
                <div class="htab-content">
                    <div class="htab-panel active" id="htab-process" role="tabpanel" aria-labelledby="htab-btn-process">
                        <h3>Membership Process</h3>
                        <p class="panel-subtitle">How to Apply</p>
                        <ol class="process-steps">
                            <li>Submit membership application</li>
                            <li>Application review and endorsement process</li>
                            <li>Evaluation by the Central Executive Committee</li>
                            <li>Approval and confirmation of membership</li>
                            <li>Membership activation upon completion of required registration and fees</li>
                        </ol>
                    </div>
                    <div class="htab-panel" id="htab-why-join" role="tabpanel" aria-labelledby="htab-btn-why-join" hidden>
                        <h3>Why Join MUKMIN?</h3>
                        <p class="panel-subtitle">Be Part of a National Ecosystem That:</p>
                        <ul class="membership-list">
                            <li>Connects organisations, communities, and stakeholders</li>
                            <li>Strengthens collaboration and collective action</li>
                            <li>Expands opportunities for partnerships and initiatives</li>
                            <li>Drives sustainable socio-economic impact</li>
                            <li>Builds stronger, more resilient communities together</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SUB BLOCK 5: Get Involved -->
    <section class="section-padding bg-light" style="padding-bottom: 70px;">
        <div class="container">
            <div class="engage-grid">
                <article class="engage-card" id="ideas">
                    <div class="engage-icon"><i class="fas fa-lightbulb" aria-hidden="true"></i></div>
                    <p class="engage-time">Have a Moment?</p>
                    <h3>Share Your Ideas</h3>
                    <p>Contribute perspectives, proposals and solutions that can shape meaningful community impact.</p>
                    <a href="{{ route('welfare.feedback') }}" class="card-btn-outline">Listen To Me</a>
                </article>
                <article class="engage-card" id="volunteer">
                    <div class="engage-icon"><i class="fas fa-hands-helping" aria-hidden="true"></i></div>
                    <p class="engage-time">Have a Few Hours?</p>
                    <h3>Let's Volunteer</h3>
                    <p>Support impactful initiatives, develop meaningful experiences and contribute directly to community-driven programmes.</p>
                    <a href="{{ route('welfare.volunteer') }}" class="card-btn-outline">Volunteer Now</a>
                </article>
                <article class="engage-card" id="mentor">
                    <div class="engage-icon"><i class="fas fa-user-graduate" aria-hidden="true"></i></div>
                    <p class="engage-time">Ready to Guide Others?</p>
                    <h3>Be A Mentor</h3>
                    <p>Empower future leaders through mentorship, professional guidance and shared experience.</p>
                    <a href="{{ route('welfare.mentor') }}" class="card-btn-outline">Contribute Now</a>
                </article>
                <article class="engage-card" id="partner">
                    <div class="engage-icon"><i class="fas fa-handshake" aria-hidden="true"></i></div>
                    <p class="engage-time">Looking to Create Greater Impact?</p>
                    <h3>Partner With Us</h3>
                    <p>Collaborate with MUKMIN through strategic partnerships, initiatives and long-term community development efforts.</p>
                    <a href="{{ route('welfare.partner') }}" class="card-btn-outline">Partner Now</a>
                </article>
            </div>
        </div>
    </section>
</div>

<div class="membership-modal-overlay" id="membership-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="membership-modal-title" hidden>
    <div class="membership-modal">
        <button type="button" class="membership-modal-close" id="membership-modal-close" aria-label="Close">&times;</button>
        <h3 id="membership-modal-title">Membership Registration</h3>
        <form id="membership-ros-form">
            <p class="membership-modal-question">Is your organisation registered with ROS / Religious Authority?</p>
            <div class="membership-modal-options">
                <label class="membership-modal-option">
                    <input type="radio" name="ros_registered" value="yes" required>
                    <span>Yes</span>
                </label>
                <label class="membership-modal-option">
                    <input type="radio" name="ros_registered" value="no">
                    <span>No</span>
                </label>
            </div>
            <button type="submit" class="card-btn membership-modal-submit">Continue</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    function initTabs(containerSelector, btnSelector, panelSelector) {
        var container = document.querySelector(containerSelector);
        if (!container) return;
        var buttons = container.querySelectorAll(btnSelector);
        var panels = container.querySelectorAll(panelSelector);
        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var targetId = btn.getAttribute('data-target');
                buttons.forEach(function (b) {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                panels.forEach(function (panel) {
                    var isActive = panel.id === targetId;
                    panel.classList.toggle('active', isActive);
                    panel.hidden = !isActive;
                });
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
            });
        });
    }
    initTabs('#membership-vertical-tabs', '.vtab-btn', '.vtab-panel');
    initTabs('#process-horizontal-tabs', '.htab-btn', '.htab-panel');

    var memberTrigger = document.getElementById('mukmin-member-trigger');
    var membershipModal = document.getElementById('membership-modal-overlay');
    var membershipModalClose = document.getElementById('membership-modal-close');
    var membershipRosForm = document.getElementById('membership-ros-form');

    function openMembershipModal() {
        if (!membershipModal) return;
        membershipModal.hidden = false;
        document.body.style.overflow = 'hidden';
        if (membershipRosForm) {
            membershipRosForm.reset();
        }
    }

    function closeMembershipModal() {
        if (!membershipModal) return;
        membershipModal.hidden = true;
        document.body.style.overflow = '';
    }

    if (memberTrigger && membershipModal) {
        memberTrigger.addEventListener('click', openMembershipModal);
    }

    if (membershipModalClose) {
        membershipModalClose.addEventListener('click', closeMembershipModal);
    }

    if (membershipModal) {
        membershipModal.addEventListener('click', function (event) {
            if (event.target === membershipModal) {
                closeMembershipModal();
            }
        });
    }

    if (membershipRosForm && memberTrigger) {
        membershipRosForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var selected = membershipRosForm.querySelector('input[name="ros_registered"]:checked');
            if (!selected) return;

            var destination = selected.value === 'yes'
                ? memberTrigger.getAttribute('data-ordinary-url')
                : memberTrigger.getAttribute('data-friends-url');

            if (destination) {
                window.location.href = destination;
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && membershipModal && !membershipModal.hidden) {
            closeMembershipModal();
        }
    });

    function handleServeHashes(hash) {
        if (!hash) return;
        var targetBtn = null;
        if (hash === '#affiliate') {
            targetBtn = document.getElementById('vtab-btn-ordinary');
        } else if (hash === '#friends') {
            targetBtn = document.getElementById('vtab-btn-friends');
        }
        
        if (targetBtn) {
            targetBtn.click();
            var container = document.getElementById('membership-vertical-tabs');
            if (container) {
                setTimeout(function () {
                    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 50);
            }
        }
    }
    
    if (window.location.hash) {
        handleServeHashes(window.location.hash);
    }
    window.addEventListener('hashchange', function () {
        handleServeHashes(window.location.hash);
    });

    // Dials Animation logic when scrolled into view
    var statsSection = document.getElementById('serve-impact-section');
    if (statsSection && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateDials();
                    observer.unobserve(statsSection);
                }
            });
        }, { threshold: 0.15 });
        observer.observe(statsSection);
    } else {
        // Fallback for older browsers
        setTimeout(animateDials, 800);
    }

    function animateDials() {
        var dials = document.querySelectorAll('.dial-fill');
        dials.forEach(function (dial) {
            var percentage = parseFloat(dial.getAttribute('data-percentage'));
            var radius = 45;
            var circumference = 2 * Math.PI * radius; // 282.74
            var offset = circumference - (percentage / 100) * circumference;
            dial.style.strokeDashoffset = offset;
        });

        var counters = document.querySelectorAll('.stat-value');
        counters.forEach(function (counter) {
            var target = parseInt(counter.getAttribute('data-target'));
            var prefix = counter.getAttribute('data-prefix') || '';
            var suffix = counter.getAttribute('data-suffix') || '';
            var start = 0;
            var duration = 2000; // 2 seconds
            var startTime = null;

            function updateCounter(currentTime) {
                if (!startTime) startTime = currentTime;
                var progress = currentTime - startTime;
                var currentVal = Math.min(Math.floor((progress / duration) * target), target);
                
                counter.textContent = prefix + currentVal.toLocaleString() + suffix;
                
                if (progress < duration) {
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = prefix + target.toLocaleString() + suffix;
                }
            }
            requestAnimationFrame(updateCounter);
        });
    }
})();
</script>
@endpush
@endsection
