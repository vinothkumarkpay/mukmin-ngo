@extends('welfare.layouts.app')

@section('title', 'Impact Areas - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
<style>
.impact-page {
    font-family: var(--font-main);
    color: var(--color-heading);
    background-color: #f5f5f5;
}

.section-padding {
    padding: 60px 0;
}
.bg-white { background: #ffffff; }
.bg-light { background: #f9f9f9; }

.section-header {
    text-align: center;
    margin-bottom: 35px;
}
.section-header .section-tag {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
    display: block;
}
.section-header h1 {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--color-heading);
}
.section-header .headline {
    font-size: 22px;
    font-weight: 600;
    color: var(--color-primary, #d43c18);
    margin-bottom: 14px;
    line-height: 1.35;
}
.section-header .subheadline {
    font-size: 15px;
    line-height: 26px;
    color: #666;
    max-width: 780px;
    margin: 0 auto;
}

.intro-copy {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 20px;
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

/* Expandable sub-tabs */
.expandable-tabs {
    max-width: 960px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.expand-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.expand-item.is-open {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    border-color: rgba(212, 60, 24, 0.25);
}
.expand-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    border: none;
    background: #ffffff;
    text-align: left;
    cursor: pointer;
    font-family: var(--font-main);
    transition: background 0.2s ease;
}
.expand-trigger:hover {
    background: #fafafa;
}
.expand-item.is-open .expand-trigger {
    background: #fdf8f6;
    border-bottom: 1px solid #f0ebe8;
}
.expand-icon-wrap {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    border-radius: 50%;
    background: rgba(212, 60, 24, 0.1);
    color: var(--color-primary, #d43c18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.expand-trigger h2 {
    flex: 1;
    font-size: 17px;
    font-weight: 700;
    margin: 0;
    color: var(--color-heading);
    line-height: 1.35;
}
.expand-chevron {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-primary, #d43c18);
    transition: transform 0.3s ease;
}
.expand-item.is-open .expand-chevron {
    transform: rotate(180deg);
}
.expand-panel {
    display: none;
    padding: 0 24px 24px 84px;
}
.expand-item.is-open .expand-panel {
    display: block;
    animation: expandFadeIn 0.35s ease forwards;
}
@keyframes expandFadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.expand-panel > p {
    font-size: 14px;
    line-height: 24px;
    color: #555;
    margin: 0 0 20px;
}
.expand-panel h3 {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.flagship-box {
    background: #f9f9f9;
    border-left: 3px solid var(--color-primary, #d43c18);
    padding: 14px 18px;
    margin-bottom: 20px;
    border-radius: 0 4px 4px 0;
}
.flagship-box .flagship-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--color-primary, #d43c18);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
}
.flagship-box p {
    font-size: 14px;
    font-weight: 600;
    color: var(--color-heading);
    margin: 0;
}
.focus-list {
    list-style: none;
    margin: 0 0 20px;
    padding: 0;
}
.focus-list li {
    position: relative;
    padding-left: 18px;
    font-size: 14px;
    line-height: 24px;
    color: #555;
    margin-bottom: 8px;
}
.focus-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 10px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--color-primary, #d43c18);
}
.expand-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--color-primary, #d43c18);
    color: #ffffff !important;
    border: 2px solid var(--color-primary, #d43c18);
    padding: 10px 22px;
    font-size: 13px;
    font-weight: 700;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.expand-cta:hover {
    background: var(--color-primary-dk, #b83210);
    border-color: var(--color-primary-dk, #b83210);
    transform: translateY(-1px);
}

@media (max-width: 767px) {
    .expand-panel {
        padding: 0 20px 20px 20px;
    }
    .expand-trigger {
        padding: 16px 18px;
        gap: 12px;
    }
    .expand-trigger h2 {
        font-size: 15px;
    }
}
</style>

<div class="impact-page">
    <!-- SUB BLOCK 1: Intro (static) -->
    <section class="section-padding bg-white" style="padding-bottom: 40px;">
        <div class="container">
            <header class="section-header">
                <span class="section-tag">Impact Areas</span>
                <h1>Strategic Initiatives (2026–2030)</h1>
                <p class="subheadline">A structured framework driving inclusive growth, talent development, leadership, innovation, and community cohesion.</p>
            </header>
            <div class="intro-copy">
                <p>MUKMIN&rsquo;s strategic impact areas define a coordinated pathway to strengthen communities through empowerment, education, leadership development, innovation, and values-driven engagement.</p>
                <p>Together, these initiatives create a scalable ecosystem that connects collaboration with opportunity, and ideas with meaningful impact.</p>
            </div>
        </div>
    </section>

    <!-- Expandable sub-tabs -->
    <section class="section-padding bg-light" style="padding-top: 0;">
        <div class="container">
            <div class="expandable-tabs" id="impact-expandable-tabs">

                <!-- Sub Expandable Tab 1 -->
                <article class="expand-item is-open" data-expand-item>
                    <button type="button" class="expand-trigger" aria-expanded="true" aria-controls="expand-socio-economic" id="expand-btn-socio-economic">
                        <span class="expand-icon-wrap" aria-hidden="true"><i class="fas fa-chart-line"></i></span>
                        <h2>Socio-Economic Mobility</h2>
                        <span class="expand-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                    </button>
                    <div class="expand-panel" id="expand-socio-economic" role="region" aria-labelledby="expand-btn-socio-economic">
                        <p>We enable communities to transition from assistance towards sustainable economic participation by strengthening pathways for income generation, entrepreneurship, and long-term resilience.</p>
                        <h3>Strategic Focus</h3>
                        <ul class="focus-list">
                            <li>Community enterprise development</li>
                            <li>Empowering women and youth as drivers of inclusive growth</li>
                            <li>Strengthening pathways towards financial resilience and sustainable livelihoods</li>
                            <li>Expanding access to opportunities through collaborative ecosystem partnerships</li>
                        </ul>
                    </div>
                </article>

                <!-- Sub Expandable Tab 2 -->
                <article class="expand-item" data-expand-item>
                    <button type="button" class="expand-trigger" aria-expanded="false" aria-controls="expand-education" id="expand-btn-education">
                        <span class="expand-icon-wrap" aria-hidden="true"><i class="fas fa-graduation-cap"></i></span>
                        <h2>Education &amp; Future Readiness</h2>
                        <span class="expand-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                    </button>
                    <div class="expand-panel" id="expand-education" role="region" aria-labelledby="expand-btn-education" hidden>
                        <p>We develop a future-ready talent pipeline through integrated academic, technical, and skills-based pathways aligned to industry relevance and long-term employability.</p>
                        <div class="flagship-box">
                            <p class="flagship-label">Flagship Initiative</p>
                            <p>MUKMIN Future Leaders Scholarship (MFLS)</p>
                        </div>
                        <h3>Strategic Focus</h3>
                        <ul class="focus-list">
                            <li>RM20&ndash;30 million cumulative scholarship and talent development target (2026&ndash;2030)</li>
                            <li>Academic, TVET, and industry certification pathways</li>
                            <li>Structured mentorship and leadership development</li>
                            <li>Industry and institutional collaboration</li>
                            <li>Development of values-driven and future-ready talent</li>
                        </ul>
                        <a href="#" class="expand-cta" title="MFLS site — update link when available">Read More <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>

                <!-- Sub Expandable Tab 3 -->
                <article class="expand-item" data-expand-item>
                    <button type="button" class="expand-trigger" aria-expanded="false" aria-controls="expand-leadership" id="expand-btn-leadership">
                        <span class="expand-icon-wrap" aria-hidden="true"><i class="fas fa-users"></i></span>
                        <h2>Leadership &amp; Capacity Building</h2>
                        <span class="expand-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                    </button>
                    <div class="expand-panel" id="expand-leadership" role="region" aria-labelledby="expand-btn-leadership" hidden>
                        <p>We nurture leaders equipped to contribute meaningfully across communities, institutions, and sectors through values-based leadership development and collaborative platforms.</p>
                        <div class="flagship-box">
                            <p class="flagship-label">Flagship Platform</p>
                            <p>SIRAT Series</p>
                        </div>
                        <h3>Strategic Focus</h3>
                        <ul class="focus-list">
                            <li>Leadership development and youth mobilisation</li>
                            <li>Strategic dialogue and stakeholder alignment</li>
                            <li>Community engagement and collaborative action</li>
                            <li>Capacity building through networking and ecosystem partnerships</li>
                        </ul>
                        <a href="#" class="expand-cta" title="SIRAT Series site — update link when available">Read More <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>

                <!-- Sub Expandable Tab 4 -->
                <article class="expand-item" data-expand-item>
                    <button type="button" class="expand-trigger" aria-expanded="false" aria-controls="expand-entrepreneurship" id="expand-btn-entrepreneurship">
                        <span class="expand-icon-wrap" aria-hidden="true"><i class="fas fa-lightbulb"></i></span>
                        <h2>Entrepreneurship &amp; Innovation</h2>
                        <span class="expand-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                    </button>
                    <div class="expand-panel" id="expand-entrepreneurship" role="region" aria-labelledby="expand-btn-entrepreneurship" hidden>
                        <p>We strengthen participation in the innovation and digital economy by empowering entrepreneurs, SMEs, and emerging talent with future-ready capabilities and scalable opportunities.</p>
                        <h3>Strategic Focus</h3>
                        <ul class="focus-list">
                            <li>Strengthening SMEs and micro-entrepreneurs</li>
                            <li>Expanding participation in the digital economy</li>
                            <li>Innovation-driven ecosystem collaboration</li>
                            <li>Entrepreneurship pathways for youth and communities</li>
                        </ul>
                    </div>
                </article>

                <!-- Sub Expandable Tab 5 -->
                <article class="expand-item" data-expand-item>
                    <button type="button" class="expand-trigger" aria-expanded="false" aria-controls="expand-faith" id="expand-btn-faith">
                        <span class="expand-icon-wrap" aria-hidden="true"><i class="fas fa-hands-helping"></i></span>
                        <h2>Faith, Identity &amp; Ukhwah</h2>
                        <span class="expand-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                    </button>
                    <div class="expand-panel" id="expand-faith" role="region" aria-labelledby="expand-btn-faith" hidden>
                        <p>We anchor development in values, identity, and unity &mdash; strengthening communities through shared purpose, lifelong learning, and social cohesion.</p>
                        <h3>Strategic Focus</h3>
                        <ul class="focus-list">
                            <li>Strengthening values-based community development</li>
                            <li>Scalable learning platforms including Digital Madrasah</li>
                            <li>Preservation of heritage, identity, and shared values</li>
                            <li>Building unity, resilience, and social cohesion across communities</li>
                        </ul>
                    </div>
                </article>

            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
(function () {
    var items = document.querySelectorAll('[data-expand-item]');
    items.forEach(function (item) {
        var trigger = item.querySelector('.expand-trigger');
        var panel = item.querySelector('.expand-panel');
        if (!trigger || !panel) return;

        trigger.addEventListener('click', function () {
            var isOpen = item.classList.contains('is-open');
            if (isOpen) {
                item.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
                panel.hidden = true;
            } else {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
                panel.hidden = false;
            }
        });
    });
})();
</script>
@endpush
@endsection
