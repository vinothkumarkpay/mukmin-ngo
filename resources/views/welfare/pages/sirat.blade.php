@extends('welfare.layouts.app')

@section('title', 'SIRAT Series - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
.sirat-page {
    font-family: var(--font-main);
    color: var(--color-heading);
    background-color: #f5f5f5;
}
.sirat-page .section-padding { padding: 60px 0; }
.sirat-page .bg-white { background: #ffffff; }
.sirat-page .bg-light { background: #f9f9f9; }
.sirat-page .section-header {
    text-align: center;
    margin-bottom: 35px;
}
.sirat-page .section-header .section-tag {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
    display: block;
}
.sirat-page .section-header h1 {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--color-heading);
}
.sirat-page .section-header .headline {
    font-size: 22px;
    font-weight: 600;
    color: var(--color-primary, #d43c18);
    margin-bottom: 14px;
    line-height: 1.35;
}
.sirat-page .section-header .subheadline {
    font-size: 15px;
    line-height: 26px;
    color: #666;
    max-width: 780px;
    margin: 0 auto;
}
.sirat-page .intro-copy {
    max-width: 900px;
    margin: 0 auto;
}
.sirat-page .intro-copy p {
    font-size: 14.5px;
    line-height: 24px;
    color: #555;
    margin-bottom: 18px;
}
.sirat-page .intro-copy p:last-child {
    margin-bottom: 0;
}
.sirat-page .content-block {
    max-width: 960px;
    margin: 0 auto;
}
.sirat-page .content-block h2 {
    font-size: 20px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 20px;
}
.sirat-platforms {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.sirat-platform {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 22px 24px;
    border-left: 4px solid var(--color-primary, #d43c18);
}
.sirat-platform h3 {
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 6px;
    color: var(--color-heading);
}
.sirat-platform .platform-tagline {
    font-size: 13px;
    font-weight: 600;
    color: var(--color-primary, #d43c18);
    margin: 0 0 12px;
    line-height: 1.4;
}
.sirat-platform p {
    font-size: 14px;
    line-height: 22px;
    color: #555;
    margin: 0;
}
.sirat-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 28px;
    font-size: 13px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-decoration: none;
}
.sirat-back:hover {
    text-decoration: underline;
}
</style>

<div class="sirat-page">
    <section class="section-padding bg-white" style="padding-bottom: 40px;">
        <div class="container">
            <header class="section-header">
                <span class="section-tag">Impact Areas &rsaquo; Leadership &amp; Capacity Building</span>
                <h1>SIRAT Series</h1>
                <p class="headline">Building leadership, connection and collaboration across youth, leaders and diaspora.</p>
                <p class="subheadline">The SIRAT Series serves as MUKMIN&rsquo;s flagship capacity-building platform, designed to develop leadership, strengthen networks and catalyse collaboration across national, regional and global levels.</p>
            </header>
            <div class="intro-copy">
                <p>Bringing together youth, leaders and diaspora communities, the series creates a dynamic ecosystem for dialogue, knowledge exchange and collective action, driving both community impact and cross-border collaboration.</p>
            </div>
        </div>
    </section>

    <section class="section-padding bg-light" style="padding-top: 0;">
        <div class="container">
            <div class="content-block">
                <h2>The SIRAT Platforms</h2>
                <div class="sirat-platforms">
                    <article class="sirat-platform">
                        <h3>SIRAT Leaders Forum</h3>
                        <p class="platform-tagline">Strengthening India Muslim Roots &amp; Aspiration Together</p>
                        <p>A strategic leadership platform bringing together community leaders, institutions, professionals and stakeholders to discuss critical issues, strengthen collaboration and advance sustainable socio-economic development within the ecosystem.</p>
                    </article>
                    <article class="sirat-platform">
                        <h3>SIRAT Youth Summit</h3>
                        <p>A future-focused youth platform dedicated to empowering the next generation through leadership development, innovation, entrepreneurship, capacity-building and meaningful civic engagement.</p>
                    </article>
                    <article class="sirat-platform">
                        <h3>SIRAT Global Forum 2026</h3>
                        <p>An international platform connecting diaspora communities, global leaders, strategic partners and institutions to foster cross-border collaboration, investment opportunities, knowledge exchange and global community engagement.</p>
                    </article>
                </div>
                <a href="{{ route('welfare.impact') }}#leadership" class="sirat-back">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to Impact Areas
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
