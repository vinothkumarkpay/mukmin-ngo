@extends('welfare.layouts.app')

@section('title', 'Changing Lives - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
/* Premium Changing Lives Page */
.changing-lives-page {
    font-family: var(--font-main);
    color: var(--color-heading);
    background-color: #f5f5f5;
}

/* Top Intro Featured Block */
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
    margin: 0 0 25px;
}
.featured-cta .btn-donate {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: var(--color-primary, #d43c18);
    color: #ffffff !important;
    border: 2px solid var(--color-primary, #d43c18);
    padding: 12px 28px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 4px;
    transition: all 0.3s ease;
    text-decoration: none;
}
.featured-cta .btn-donate:hover {
    background: var(--color-primary-dk, #b83210);
    border-color: var(--color-primary-dk, #b83210);
    transform: translateY(-2px);
}

/* Three Columns Section */
.columns-row {
    padding: 25px 0 60px;
}
.columns-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    align-items: stretch;
}

/* Full blog-style cards (Sub Tab 1 & 2) */
.column-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.column-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.column-card-image img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}
.column-card-body {
    padding: 22px 24px 24px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.column-card-title-wrap {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 10px;
}
.column-card-accent {
    width: 3px;
    min-height: 42px;
    background: var(--color-primary, #d43c18);
    flex-shrink: 0;
    margin-top: 4px;
}
.column-card-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--color-heading, #1e1e1e);
    line-height: 1.35;
    margin: 0;
}
.column-card-meta {
    font-size: 12px;
    color: #888;
    margin-bottom: 14px;
    padding-left: 15px;
}
.column-card-meta strong {
    color: var(--color-primary, #d43c18);
    font-weight: 600;
}
.column-card-body p {
    font-size: 13.5px;
    line-height: 22px;
    color: #555;
    margin: 0;
    flex-grow: 1;
}

/* Stacked column (Sub Tab 3 & 4) */
.stacked-column {
    display: flex;
    flex-direction: column;
    gap: 30px;
    height: 100%;
}

/* Quote card (Sub Tab 3) */
.quote-card {
    background: var(--color-primary, #d43c18);
    color: #ffffff;
    padding: 28px 26px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
    flex: 1;
    text-align: center;
    transition: transform 0.3s ease;
}
.quote-card:hover {
    transform: translateY(-4px);
}
.quote-card-meta {
    font-size: 11.5px;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 18px;
}
.quote-card-meta strong {
    font-weight: 600;
}
.quote-card-icon {
    font-size: 22px;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 16px;
}
.quote-card-text {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.45;
    margin: 0 0 14px;
    color: #ffffff;
}
.quote-card-desc {
    font-size: 13px;
    line-height: 21px;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
}

/* Text card (Sub Tab 4) */
.text-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    padding: 28px 26px;
    display: flex;
    flex-direction: column;
    flex: 1;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.text-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.text-card-meta {
    font-size: 11.5px;
    color: #888;
    margin-bottom: 18px;
}
.text-card-meta strong {
    color: var(--color-primary, #d43c18);
    font-weight: 600;
}
.text-card-icon {
    font-size: 22px;
    color: var(--color-primary, #d43c18);
    margin-bottom: 16px;
}
.text-card-desc {
    font-size: 13.5px;
    line-height: 22px;
    color: #666;
    font-style: italic;
    margin: 0 0 18px;
    flex-grow: 1;
}
.text-card-source {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-heading, #1e1e1e);
    margin: 0;
}

@media (max-width: 991px) {
    .featured-block {
        flex-direction: column;
    }
    .featured-left, .featured-right {
        width: 100%;
    }
    .columns-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
#funds, #giving, #ecosystem, #collab {
    scroll-margin-top: 160px;
}
@media (max-width: 991px) {
    #funds, #giving, #ecosystem, #collab {
        scroll-margin-top: 90px;
    }
}
</style>

<div class="changing-lives-page">
    <!-- Top Intro Featured Block -->
    <div class="featured-row">
        <div class="container">
            <div class="featured-block">
                <div class="featured-left">
                    <img src="{{ asset('welfare/img/changing/featured.png') }}" alt="Children celebrating together in community">
                </div>
                <div class="featured-right">
                    <span class="category-tag">Changing Lives</span>
                    <h2>Advancing Communities Through Meaningful Impact</h2>
                    <p>MUKMIN drives sustainable impact through collaborative initiatives, strategic philanthropy and community-focused programmes that strengthen lives, institutions and future generations.</p>
                    <div class="featured-cta">
                        <a href="{{ route('welfare.donate') }}" class="btn-donate">
                            Donate Now <i class="fas fa-heart"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub Tabs 1–4: Columns Layout -->
    <div class="columns-row">
        <div class="container">
            <div class="columns-grid">

                <!-- Sub Tab 1: Philanthropic Funds -->
                <article class="column-card" id="funds">
                    <div class="column-card-image">
                        <img src="{{ asset('welfare/img/changing/community-support.png') }}" alt="Volunteers united in community support">
                    </div>
                    <div class="column-card-body">
                        <div class="column-card-title-wrap">
                            <span class="column-card-accent" aria-hidden="true"></span>
                            <h3 class="column-card-title">Enabling Sustainable Community Support</h3>
                        </div>
                        <p class="column-card-meta"><strong>Philanthropic Funds</strong></p>
                        <p>Mobilising contributions and strategic resources to support long-term community development, education access and humanitarian initiatives.</p>
                    </div>
                </article>

                <!-- Sub Tab 2: Faith & Giving -->
                <article class="column-card" id="giving">
                    <div class="column-card-image">
                        <img src="{{ asset('welfare/img/changing/faith-giving.png') }}" alt="Children celebrating with the Malaysian flag">
                    </div>
                    <div class="column-card-body">
                        <div class="column-card-title-wrap">
                            <span class="column-card-accent" aria-hidden="true"></span>
                            <h3 class="column-card-title">Compassion Rooted in Values</h3>
                        </div>
                        <p class="column-card-meta"><strong>Faith &amp; Giving</strong></p>
                        <p>Strengthening the culture of giving, solidarity and social responsibility through faith-driven outreach and community care initiatives.</p>
                    </div>
                </article>

                <!-- Sub Tab 3 & 4: Stacked Column -->
                <div class="stacked-column">
                    <!-- Sub Tab 3: Ecosystem Building Initiatives -->
                    <article class="quote-card" id="ecosystem">
                        <p class="quote-card-meta"><strong>Ecosystem Building Initiatives</strong></p>
                        <div class="quote-card-icon"><i class="fas fa-microphone" aria-hidden="true"></i></div>
                        <h3 class="quote-card-text">Connecting Communities for Greater Impact</h3>
                        <p class="quote-card-desc">Building strategic collaborations between organisations, institutions and stakeholders to create a stronger and more sustainable community ecosystem.</p>
                    </article>

                    <!-- Sub Tab 4: ImpactCollab -->
                    <article class="text-card" id="collab">
                        <p class="text-card-meta"><strong>ImpactCollab</strong></p>
                        <div class="text-card-icon"><i class="fas fa-comment-dots" aria-hidden="true"></i></div>
                        <p class="text-card-desc">Advancing high-impact collaborations that transform ideas into measurable outcomes through collective action, innovation and shared responsibility.</p>
                        <p class="text-card-source">Partnerships That Drive Change</p>
                    </article>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
