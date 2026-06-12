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
.mfls-page .intro-copy h2 {
    font-size: 18px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 28px 0 12px;
    text-align: center;
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
.mfls-step-content h3 {
    font-size: 15px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 6px;
    line-height: 1.35;
}
.mfls-step-content p {
    font-size: 14px;
    line-height: 22px;
    color: #555;
    margin: 0;
}
.mfls-expandable-tabs {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.mfls-expand-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.mfls-expand-item.is-open {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    border-color: rgba(212, 60, 24, 0.25);
}
.mfls-expand-trigger {
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
.mfls-expand-trigger:hover {
    background: #fafafa;
}
.mfls-expand-item.is-open .mfls-expand-trigger {
    background: #fdf8f6;
    border-bottom: 1px solid #f0ebe8;
}
.mfls-expand-icon-wrap {
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
.mfls-expand-trigger h2 {
    flex: 1;
    font-size: 17px;
    font-weight: 700;
    margin: 0;
    color: var(--color-heading);
    line-height: 1.35;
}
.mfls-expand-chevron {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-primary, #d43c18);
    transition: transform 0.3s ease;
}
.mfls-expand-item.is-open .mfls-expand-chevron {
    transform: rotate(180deg);
}
.mfls-expand-panel {
    display: none;
    padding: 0 24px 24px 84px;
}
.mfls-expand-item.is-open .mfls-expand-panel {
    display: block;
    animation: mflsExpandFadeIn 0.35s ease forwards;
}
@keyframes mflsExpandFadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.mfls-criteria-list {
    list-style: none;
    padding: 0;
    margin: 0 0 18px;
}
.mfls-criteria-list li {
    position: relative;
    padding-left: 18px;
    font-size: 14px;
    line-height: 24px;
    color: #555;
    margin-bottom: 10px;
}
.mfls-criteria-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 10px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--color-primary, #d43c18);
}
.mfls-criteria-list li:last-child {
    margin-bottom: 0;
}
.mfls-criteria-subheading {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 10px;
}
@media (max-width: 767px) {
    .mfls-expand-panel {
        padding: 0 20px 20px 20px;
    }
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
.mfls-partner-logos {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.mfls-partner-logo-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    min-height: 180px;
    padding: 24px 18px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: var(--font-main);
}
.mfls-partner-logo-btn:hover,
.mfls-partner-logo-btn.is-active {
    border-color: var(--color-primary, #d43c18);
    box-shadow: 0 6px 18px rgba(212, 60, 24, 0.12);
    background: #fdf8f6;
}
.mfls-partner-logo-btn img {
    width: 100%;
    max-width: 260px;
    height: 104px;
    object-fit: contain;
}
.mfls-partner-logo-btn span {
    font-size: 13px;
    font-weight: 700;
    color: #666;
    text-align: center;
    line-height: 1.3;
}
.mfls-partner-details {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.mfls-partner-detail {
    display: none;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid var(--color-primary, #d43c18);
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.mfls-partner-detail.is-open {
    display: block;
    animation: mflsExpandFadeIn 0.35s ease forwards;
}
.mfls-partner-detail h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--color-heading);
    margin: 0 0 10px;
}
.mfls-partner-detail > p {
    font-size: 14px;
    line-height: 22px;
    color: #555;
    margin: 0 0 16px;
}
.mfls-partner-detail h4 {
    font-size: 13px;
    font-weight: 700;
    color: var(--color-heading);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 12px;
}
.mfls-programme-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px 20px;
}
.mfls-programme-list li {
    position: relative;
    padding-left: 16px;
    font-size: 13.5px;
    line-height: 20px;
    color: #555;
}
.mfls-programme-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--color-primary, #d43c18);
}
.mfls-apply-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--color-primary, #d43c18);
    color: #ffffff !important;
    padding: 11px 22px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.3s ease;
}
.mfls-apply-btn:hover {
    background: var(--color-primary-dk, #b83210);
    color: #ffffff !important;
}
.mfls-faq-list .mfls-expand-trigger h2 {
    font-size: 15px;
    line-height: 1.45;
}
.mfls-faq-list .mfls-expand-panel p {
    font-size: 14px;
    line-height: 24px;
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
    .mfls-partner-logos {
        grid-template-columns: repeat(2, 1fr);
    }
    .mfls-partner-logo-btn {
        min-height: 150px;
        padding: 18px 14px;
    }
    .mfls-partner-logo-btn img {
        max-width: 200px;
        height: 84px;
    }
    .mfls-programme-list {
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
            </header>
            <div class="intro-copy">
                <p>MFLS serves as a structured talent pipeline that connects education, leadership development and long-term community impact.</p>
                <p>Through strategic collaborations with leading universities and colleges, MFLS provides scholarship opportunities across multiple education pathways while nurturing future leaders equipped with knowledge, skills and values-driven leadership.</p>
                <h2>2025/2026 Intake Update</h2>
                <p>Applications are facilitated through a coordinated selection process with partner institutions, aligning academic excellence, talent development and community leadership aspirations.</p>
            </div>
        </div>
    </section>

    <section class="section-padding bg-light" style="padding-top: 0;">
        <div class="container">
            <div class="content-block" style="margin-bottom: 48px;">
                <div class="mfls-expandable-tabs">
                    <article class="mfls-expand-item" data-expand-item id="application-criteria">
                        <button type="button" class="mfls-expand-trigger" aria-expanded="false" aria-controls="expand-application-criteria" id="expand-btn-application-criteria">
                            <span class="mfls-expand-icon-wrap" aria-hidden="true"><i class="fas fa-clipboard-check"></i></span>
                            <h2>Application Criteria</h2>
                            <span class="mfls-expand-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                        </button>
                        <div class="mfls-expand-panel" id="expand-application-criteria" role="region" aria-labelledby="expand-btn-application-criteria" hidden>
                            <ul class="mfls-criteria-list">
                                <li>Applicants must be of Indian Muslim heritage.</li>
                                <li>Applicants must be a Malaysian citizen.</li>
                                <li>Priority will be given to applicants from B40 and financially deserving backgrounds.</li>
                            </ul>
                            <p class="mfls-criteria-subheading">Age Requirements:</p>
                            <ul class="mfls-criteria-list">
                                <li>Foundation / Diploma: 17&ndash;21 years old</li>
                                <li>Degree Programmes: Up to 25 years old</li>
                                <li>ODL / Working Adult Programmes: Eligible subject to programme requirements</li>
                                <li>Master&rsquo;s / Postgraduate Programmes: Up to 55 years old</li>
                            </ul>
                            <ul class="mfls-criteria-list">
                                <li>Applicants must meet the entry requirements of the chosen programme.</li>
                                <li>Applicants must not be receiving another full scholarship or equivalent funding support.</li>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>

            <div class="content-block" style="margin-bottom: 48px;">
                <h2>How It Works</h2>
                <ol class="mfls-steps">
                    <li>
                        <span class="mfls-step-num">1</span>
                        <div class="mfls-step-content">
                            <h3>Application Submission</h3>
                            <p>Submit your MFLS Scholarship Application before the stated application deadline.</p>
                        </div>
                    </li>
                    <li>
                        <span class="mfls-step-num">2</span>
                        <div class="mfls-step-content">
                            <h3>Eligibility Review</h3>
                            <p>The MFLS Secretariat reviews all applications and supporting documents to assess eligibility and shortlist qualified candidates.</p>
                        </div>
                    </li>
                    <li>
                        <span class="mfls-step-num">3</span>
                        <div class="mfls-step-content">
                            <h3>Interview &amp; Assessment</h3>
                            <p>Shortlisted applicants will be invited for an interview and evaluation by the MFLS Review Panel.</p>
                        </div>
                    </li>
                    <li>
                        <span class="mfls-step-num">4</span>
                        <div class="mfls-step-content">
                            <h3>Scholarship Outcome</h3>
                            <p>Successful applicants will be notified of their application outcome together with the next steps for programme enrolment and scholarship onboarding.</p>
                        </div>
                    </li>
                    <li>
                        <span class="mfls-step-num">5</span>
                        <div class="mfls-step-content">
                            <h3>Admission &amp; Scholarship Award</h3>
                            <p>Applicants receive their university placement confirmation and scholarship offer through the respective partner institution.</p>
                        </div>
                    </li>
                    <li>
                        <span class="mfls-step-num">6</span>
                        <div class="mfls-step-content">
                            <h3>Begin Your Learning Journey</h3>
                            <p>Start your academic programme and become part of the MUKMIN Future Leaders ecosystem.</p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="content-block" style="margin-bottom: 48px;">
                <h2>Partner Institutions &amp; Programmes</h2>
                <p style="font-size: 14.5px; line-height: 24px; color: #555; margin-bottom: 24px;">Select a partner institution to view available MFLS programmes and application details.</p>

                <div class="mfls-partner-logos">
                    @foreach($partners as $partner)
                        <button
                            type="button"
                            class="mfls-partner-logo-btn{{ $loop->first ? ' is-active' : '' }}"
                            data-partner-id="{{ $partner['id'] }}"
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                            aria-controls="partner-detail-{{ $partner['id'] }}"
                        >
                            <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['name'] }} logo">
                            <span>{{ $partner['name'] }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="mfls-partner-details">
                    @foreach($partners as $partner)
                        <article
                            class="mfls-partner-detail{{ $loop->first ? ' is-open' : '' }}"
                            id="partner-detail-{{ $partner['id'] }}"
                            data-partner-detail
                            @unless($loop->first) hidden @endunless
                        >
                            <h3>{{ $partner['name'] }}</h3>
                            <p>{{ $partner['info'] }}</p>
                            <h4>Available Programmes</h4>
                            <ul class="mfls-programme-list">
                                @foreach($partner['programmes'] as $programme)
                                    <li>{{ $programme }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ $mflsApplyUrl }}" class="mfls-apply-btn">
                                Apply Now <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="content-block" style="margin-bottom: 32px;">
                <h2>Frequently Asked Questions (FAQ)</h2>
                <div class="mfls-expandable-tabs mfls-faq-list">
                    @foreach($faqs as $faq)
                        <article class="mfls-expand-item" data-expand-item id="faq-{{ $loop->index + 1 }}">
                            <button type="button" class="mfls-expand-trigger mfls-faq-trigger" aria-expanded="false" aria-controls="expand-faq-{{ $loop->index + 1 }}" id="expand-btn-faq-{{ $loop->index + 1 }}">
                                <span class="mfls-expand-icon-wrap" aria-hidden="true"><i class="fas fa-question-circle"></i></span>
                                <h2>{{ $faq['question'] }}</h2>
                                <span class="mfls-expand-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="mfls-expand-panel" id="expand-faq-{{ $loop->index + 1 }}" role="region" aria-labelledby="expand-btn-faq-{{ $loop->index + 1 }}" hidden>
                                <p>{{ $faq['answer'] }}</p>
                            </div>
                        </article>
                    @endforeach
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

@push('scripts')
<script>
(function () {
    document.querySelectorAll('[data-expand-item]').forEach(function (item) {
        var trigger = item.querySelector('.mfls-expand-trigger');
        var panel = item.querySelector('.mfls-expand-panel');
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

    var logoBtns = document.querySelectorAll('[data-partner-id]');
    var detailPanels = document.querySelectorAll('[data-partner-detail]');

    logoBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var partnerId = btn.getAttribute('data-partner-id');
            var isActive = btn.classList.contains('is-active');

            logoBtns.forEach(function (otherBtn) {
                otherBtn.classList.remove('is-active');
                otherBtn.setAttribute('aria-expanded', 'false');
            });

            detailPanels.forEach(function (panel) {
                panel.classList.remove('is-open');
                panel.hidden = true;
            });

            if (!isActive) {
                btn.classList.add('is-active');
                btn.setAttribute('aria-expanded', 'true');
                var panel = document.getElementById('partner-detail-' + partnerId);
                if (panel) {
                    panel.classList.add('is-open');
                    panel.hidden = false;
                    setTimeout(function () {
                        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 50);
                }
            }
        });
    });
})();
</script>
@endpush
@endsection
