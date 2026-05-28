@extends('welfare.layouts.app')

@section('title', 'MUKMIN Future Leaders Scholarship (MFLS) - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
.mfls-page {
    font-family: var(--font-main);
    color: var(--color-heading);
    background-color: #f5f5f5;
}
.mfls-page .section-padding { padding: 60px 0; }
.mfls-page .bg-white { background: #ffffff; }
.mfls-page .bg-light { background: #f9f9f9; }
.mfls-page .section-header {
    text-align: center;
    margin-bottom: 35px;
}
.mfls-page .section-header .section-tag {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
    display: block;
}
.mfls-page .section-header h1 {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--color-heading);
}
.mfls-page .section-header .headline {
    font-size: 22px;
    font-weight: 600;
    color: var(--color-primary, #d43c18);
    margin-bottom: 14px;
    line-height: 1.35;
}
.mfls-page .section-header .subheadline {
    font-size: 15px;
    line-height: 26px;
    color: #666;
    max-width: 780px;
    margin: 0 auto;
}
.mfls-page .intro-copy {
    max-width: 900px;
    margin: 0 auto;
}
.mfls-page .intro-copy p {
    font-size: 14.5px;
    line-height: 24px;
    color: #555;
    margin-bottom: 18px;
}
.mfls-page .content-block {
    max-width: 960px;
    margin: 0 auto;
}
.mfls-page .content-block h2 {
    font-size: 20px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 16px;
}
.mfls-page .content-block > p {
    font-size: 14.5px;
    line-height: 24px;
    color: #555;
    margin-bottom: 16px;
}
.mfls-steps {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.mfls-steps li {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 16px 18px;
    font-size: 14px;
    line-height: 22px;
    color: #444;
}
.mfls-step-num {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(212, 60, 24, 0.12);
    color: var(--color-primary, #d43c18);
    font-weight: 700;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mfls-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 8px;
}
.mfls-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 24px 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}
.mfls-stat-card strong {
    display: block;
    font-size: 18px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    margin-bottom: 8px;
    line-height: 1.3;
}
.mfls-stat-card span {
    font-size: 13px;
    line-height: 20px;
    color: #666;
}
.mfls-pathways {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.mfls-pathway {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 20px 22px;
    border-left: 4px solid var(--color-primary, #d43c18);
}
.mfls-pathway h3 {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 8px;
    color: var(--color-heading);
}
.mfls-pathway p {
    font-size: 14px;
    line-height: 22px;
    color: #555;
    margin: 0;
}
.mfls-notice {
    background: #fdf8f6;
    border: 1px solid rgba(212, 60, 24, 0.2);
    border-radius: 6px;
    padding: 18px 22px;
    font-size: 14px;
    line-height: 22px;
    color: #555;
    text-align: center;
}
.mfls-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 28px;
    font-size: 13px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-decoration: none;
}
.mfls-back:hover {
    text-decoration: underline;
}
@media (max-width: 767px) {
    .mfls-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="mfls-page">
    <section class="section-padding bg-white" style="padding-bottom: 40px;">
        <div class="container">
            <header class="section-header">
                <span class="section-tag">Impact Areas &rsaquo; Education &amp; Future Readiness</span>
                <h1>MUKMIN Future Leaders Scholarship (MFLS)</h1>
                <p class="headline">A National Scholarship &amp; Talent Development Programme</p>
                <p class="subheadline">MFLS serves as a structured talent pipeline, connecting education, leadership development and long-term community impact.</p>
            </header>
            <div class="intro-copy">
                <p>MFLS provides scholarship opportunities across multiple education levels in collaboration with leading universities and colleges.</p>
            </div>
        </div>
    </section>

    <section class="section-padding bg-light" style="padding-top: 0;">
        <div class="container">
            <div class="content-block" style="margin-bottom: 48px;">
                <h2>2025/2026 Intake Update</h2>
                <p>Applications are supported through a coordinated selection process with partner institutions, aligning academic excellence, skills development, and values-driven leadership.</p>
            </div>

            <div class="content-block" style="margin-bottom: 48px;">
                <h2>How It Works</h2>
                <ol class="mfls-steps">
                    <li>
                        <span class="mfls-step-num">1</span>
                        <span>Apply for the MFLS scholarship</span>
                    </li>
                    <li>
                        <span class="mfls-step-num">2</span>
                        <span>Shortlisting and joint selection with university partners</span>
                    </li>
                    <li>
                        <span class="mfls-step-num">3</span>
                        <span>Receive an offer letter</span>
                    </li>
                </ol>
            </div>

            <div class="content-block" style="margin-bottom: 48px;">
                <h2>Programme Snapshot (2025/2026 Intake)</h2>
                <div class="mfls-stats">
                    <div class="mfls-stat-card">
                        <strong>Over RM5,000,000++</strong>
                        <span>confirmed scholarship pool</span>
                    </div>
                    <div class="mfls-stat-card">
                        <strong>5+ partner institutions</strong>
                        <span>universities and colleges</span>
                    </div>
                    <div class="mfls-stat-card">
                        <strong>20+ confirmed</strong>
                        <span>scholarship placements</span>
                    </div>
                </div>
            </div>

            <div class="content-block" style="margin-bottom: 32px;">
                <h2>Available Pathways</h2>
                <p>MFLS offers both academic and skills-based pathways, ensuring multiple routes to success based on each student&rsquo;s strengths and aspirations.</p>
                <div class="mfls-pathways" style="margin-top: 20px;">
                    <article class="mfls-pathway">
                        <h3>1. TVET (Skills &amp; Technical Pathways)</h3>
                        <p>Industry-relevant, hands-on programmes designed for immediate employability.</p>
                    </article>
                    <article class="mfls-pathway">
                        <h3>2. Foundation &amp; Pre-University</h3>
                        <p>Pre-university pathways designed to prepare students for degree progression.</p>
                    </article>
                    <article class="mfls-pathway">
                        <h3>3. Diploma Programmes (Applied &amp; Career-Focused)</h3>
                        <p>Industry-relevant programmes combining theory and practical skills.</p>
                    </article>
                    <article class="mfls-pathway">
                        <h3>4. Degree Programmes</h3>
                        <p>Undergraduate pathways across key professional and academic disciplines.</p>
                    </article>
                    <article class="mfls-pathway">
                        <h3>5. Postgraduate (Master&rsquo;s Programmes)</h3>
                        <p>Advanced qualifications for leadership and professional growth.</p>
                    </article>
                </div>
            </div>

            <div class="content-block">
                <p class="mfls-notice">Further details on MFLS will be shared soon. Stay tuned!</p>
                <a href="{{ route('welfare.impact') }}#education" class="mfls-back">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to Impact Areas
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
