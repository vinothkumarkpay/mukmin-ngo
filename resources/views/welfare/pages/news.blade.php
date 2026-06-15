@extends('welfare.layouts.app')

@section('title', 'News & Gallery')

@section('content')
<style>
/* Premium News & Gallery Page Styles */
.news-gallery-page {
    font-family: var(--font-main, 'Roboto', sans-serif);
    color: #334155;
    background-color: #f8fafc;
    padding: 60px 0;
}

.news-section-header {
    margin-bottom: 45px;
    text-align: center;
}

.news-section-header h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.news-section-divider {
    height: 3px;
    width: 60px;
    background-color: #d43c18;
    margin: 0 auto 20px;
    border-radius: 2px;
}

.news-section-subtitle {
    font-size: 16px;
    color: #64748b;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

/* ==========================================================================
   SUB BLOCK 1: Impact at a Glance
   ========================================================================== */
.impact-glance-container {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 60px;
}

.news-split-pane {
    display: flex;
    min-height: 650px;
    height: auto;
    align-items: stretch;
}

/* Left Sidebar Tabs */
.news-sidebar {
    width: 38%;
    border-right: 1px solid #e2e8f0;
    background: #f8fafc;
    padding: 15px;
}

@media (min-width: 992px) {
    .news-sidebar {
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .news-tab-list {
        --insights-tab-row-height: 80px;
        --insights-tab-gap: 8px;
        max-height: calc(var(--insights-tab-row-height) * 10 + var(--insights-tab-gap) * 9);
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-gutter: stable;
        padding-right: 4px;
    }

    .news-tab-list::-webkit-scrollbar {
        width: 6px;
    }

    .news-tab-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .news-tab-list::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
}

.news-tab-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.news-tab-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 15px 18px;
    cursor: pointer;
    transition: all 0.25s ease;
    border-left: 4px solid transparent;
    text-align: left;
}

.news-tab-item:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    transform: translateX(2px);
}

.news-tab-item.active {
    background: #fff5f2;
    border-color: #fbcfe8; /* bridge color */
    border-left-color: #d43c18;
    box-shadow: 0 4px 10px rgba(212, 60, 24, 0.06);
}

.news-tab-number {
    font-size: 10px;
    font-weight: 800;
    color: #d43c18;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
    display: block;
}

.news-tab-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.4;
    margin: 0;
}

.news-tab-date {
    font-size: 11px;
    color: #64748b;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Right Content Display */
.news-content-display {
    width: 62%;
    padding: 30px;
    background: #ffffff;
    overflow-y: visible;
}

.news-card-detail {
    display: none;
}

.news-card-detail.active {
    display: flex;
    flex-direction: column;
    animation: contentFadeIn 0.40s ease-out;
}

@keyframes contentFadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

.news-detail-image-wrap {
    width: 100%;
    flex-shrink: 0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 22px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    background: #f3f4f6;
    line-height: 0;
}

.news-detail-image-wrap img {
    width: 100%;
    height: auto;
    display: block;
    max-width: 100%;
}

.news-detail-header {
    margin-bottom: 15px;
    flex-shrink: 0;
}

.news-detail-title-wrap {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
}

.news-detail-accent {
    width: 4px;
    height: 24px;
    background-color: #d43c18;
    margin-right: 12px;
    border-radius: 2px;
    flex-shrink: 0;
    margin-top: 3px;
}

.news-detail-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.35;
}

.news-detail-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 12.5px;
    color: #64748b;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 15px;
}

.news-detail-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.news-detail-meta i {
    color: #d43c18;
}

.news-detail-description {
    font-size: 14.5px;
    line-height: 1.65;
    color: #475569;
    margin-bottom: 25px;
    text-align: justify;
}

.news-detail-description ul {
    margin-top: 10px;
    padding-left: 20px;
}

.news-detail-description li {
    margin-bottom: 8px;
}

.news-detail-cta-wrap {
    flex-shrink: 0;
    margin-top: auto;
    padding-top: 15px;
}

.news-detail-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background-color: #d43c18;
    color: #ffffff !important;
    padding: 12px 24px;
    font-size: 13.5px;
    font-weight: 700;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(212, 60, 24, 0.15);
}

.news-detail-cta:hover {
    background-color: #b83210;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(212, 60, 24, 0.22);
}

/* ==========================================================================
   SUB BLOCK 2: Moments of MUKMIN
   ========================================================================== */
.section-moments {
    padding: 60px 0 20px;
    border-top: 1px solid #e2e8f0;
}

.gallery-filter-bar {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 35px;
    flex-wrap: wrap;
}

.gallery-filter-btn {
    padding: 8px 20px;
    border-radius: 30px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-filter-btn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}

.gallery-filter-btn.active {
    background: #d43c18;
    border-color: #d43c18;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(212, 60, 24, 0.15);
}

.gallery-masonry-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.gallery-card {
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    position: relative;
    cursor: pointer;
    transition: all 0.35s ease;
    aspect-ratio: 4/3;
    border: 1px solid #f1f5f9;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(15, 23, 42, 0.1);
}

.gallery-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.gallery-card:hover img {
    transform: scale(1.06);
}

.gallery-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(212, 60, 24, 0.92);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    padding: 24px;
    text-align: center;
    color: #ffffff;
}

.gallery-card:hover .gallery-card-overlay {
    opacity: 1;
}

.gallery-overlay-icon {
    font-size: 24px;
    margin-bottom: 12px;
    transform: translateY(-10px);
    transition: transform 0.3s ease;
}

.gallery-card:hover .gallery-overlay-icon {
    transform: translateY(0);
}

.gallery-overlay-title {
    font-size: 15px;
    font-weight: 700;
    margin: 0 0 6px;
    color: #ffffff;
}

.gallery-overlay-cat {
    font-size: 10.5px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    opacity: 0.85;
    font-weight: 600;
}

/* Custom Lightbox Modal */
.custom-lightbox {
    position: fixed;
    inset: 0;
    background-color: rgba(15, 23, 42, 0.97);
    z-index: 10000;
    display: none;
    flex-direction: column;
    height: 100vh;
    height: 100dvh;
    opacity: 0;
    transition: opacity 0.3s ease;
    padding: 0;
    box-sizing: border-box;
    overflow: hidden;
}

.custom-lightbox.show {
    display: block;
    opacity: 1;
}

.lightbox-viewport {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
}

.lightbox-image-wrap {
    position: absolute;
    inset: 0;
    display: block;
    overflow: hidden;
    overscroll-behavior: contain;
}

.lightbox-image-stage {
    min-height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
}

.custom-lightbox .lightbox-image {
    display: block;
    object-fit: contain;
    border-radius: 0;
    box-shadow: none;
}

.lightbox-meta {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: 118px;
    z-index: 31;
    width: auto;
    padding: 6px 14px;
    text-align: center;
    background: rgba(15, 23, 42, 0.82);
    border-radius: 999px;
    pointer-events: none;
    backdrop-filter: blur(6px);
}

.lightbox-counter {
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
    font-size: 12px;
    font-weight: 600;
}

.lightbox-close-btn {
    position: fixed;
    top: 16px;
    right: 20px;
    color: #ffffff;
    font-size: 36px;
    line-height: 1;
    cursor: pointer;
    background: none;
    border: none;
    opacity: 0.85;
    transition: opacity 0.2s;
    z-index: 25;
}

.lightbox-close-btn:hover {
    opacity: 1;
}

.lightbox-arrow {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    z-index: 25;
    color: #ffffff;
    font-size: 32px;
    line-height: 1;
    background: rgba(255, 255, 255, 0.12);
    border: none;
    cursor: pointer;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.lightbox-arrow:hover {
    background: rgba(255, 255, 255, 0.24);
}

.lightbox-arrow-left {
    left: 12px;
}

.lightbox-arrow-right {
    right: 12px;
}

.lightbox-thumbs-wrap {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 30;
    width: 100%;
    padding: 28px 20px 16px;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.96) 0%, rgba(15, 23, 42, 0.82) 50%, transparent 100%);
    border-top: none;
    pointer-events: auto;
}

.lightbox-thumbs {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding: 4px 2px 6px;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.lightbox-thumbs::-webkit-scrollbar {
    height: 6px;
}

.lightbox-thumbs::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.25);
    border-radius: 999px;
}

.lightbox-thumb {
    flex: 0 0 104px;
    width: 104px;
    height: 72px;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    opacity: 0.55;
    border: 2px solid transparent;
    transition: opacity 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
    background: rgba(255, 255, 255, 0.06);
}

.lightbox-thumb:hover {
    opacity: 0.85;
}

.lightbox-thumb.active {
    opacity: 1;
    border-color: #d43c18;
    transform: translateY(-2px);
}

.lightbox-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* ==========================================================================
   Responsive Breakpoints
   ========================================================================== */
@media (max-width: 991px) {
    .news-split-pane {
        flex-direction: column;
        height: auto;
    }
    
    .news-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        max-height: none;
        overflow-x: auto;
        padding: 15px;
    }
    
    .news-tab-list {
        flex-direction: row;
        gap: 10px;
        max-height: none;
        overflow-y: visible;
        padding-right: 0;
    }
    
    .news-tab-item {
        flex: 0 0 250px;
        margin-bottom: 0;
        border-left: 1px solid #e2e8f0;
        border-bottom: 4px solid transparent;
        white-space: normal;
    }
    
    .news-tab-item.active {
        border-left-color: #e2e8f0;
        border-bottom-color: #d43c18;
    }
    
    .news-content-display {
        width: 100%;
        padding: 25px;
        height: auto;
    }
    
    .gallery-masonry-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767px) {
    .news-gallery-page {
        padding: 40px 0;
    }
    .news-section-header {
        margin-bottom: 30px;
    }
    .news-section-header h2 {
        font-size: 26px;
    }
    .gallery-masonry-grid {
        grid-template-columns: 1fr;
    }
}

.custom-lightbox.is-mobile {
    padding: 0;
    overflow: hidden;
}

.custom-lightbox.is-mobile .lightbox-thumbs-wrap {
    display: none !important;
}

.custom-lightbox.is-mobile .lightbox-viewport {
    position: absolute;
    inset: 0;
    overflow: hidden;
}

.custom-lightbox.is-mobile .lightbox-image-wrap {
    position: absolute;
    inset: 0;
    display: block;
    overflow: auto;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
    padding: 0;
}

.custom-lightbox.is-mobile .lightbox-image-stage {
    min-height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    padding: 48px 0 104px;
}

.custom-lightbox.is-mobile .lightbox-image {
    display: block;
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    max-height: none !important;
    object-fit: unset !important;
    border-radius: 0;
    box-shadow: none;
}

.custom-lightbox.is-mobile .lightbox-close-btn {
    position: fixed;
    top: max(10px, env(safe-area-inset-top));
    right: max(12px, env(safe-area-inset-right));
    z-index: 25;
}

.custom-lightbox.is-mobile .lightbox-arrow {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    z-index: 25;
    width: 40px;
    height: 40px;
    font-size: 26px;
}

.custom-lightbox.is-mobile .lightbox-arrow-left {
    left: max(8px, env(safe-area-inset-left));
}

.custom-lightbox.is-mobile .lightbox-arrow-right {
    right: max(8px, env(safe-area-inset-right));
}

.custom-lightbox.is-mobile .lightbox-meta {
    position: fixed;
    left: 12px;
    right: 12px;
    transform: none;
    width: auto;
    bottom: max(12px, env(safe-area-inset-bottom));
    z-index: 25;
}

#insights, #moments, #gallery-grid {
    scroll-margin-top: 160px;
}
@media (max-width: 991px) {
    #insights, #moments, #gallery-grid {
        scroll-margin-top: 90px;
    }
}

#gallery-grid:focus {
    outline: none;
}
</style>

@php
    use Illuminate\Support\Str;

    $newsGallerySlugs = collect(config('welfare_gallery.news_folders', []))
        ->map(fn ($folder) => Str::slug($folder))
        ->all();
@endphp

<div class="news-gallery-page">
    <div class="container">
        
        <!-- SECTION TITLE -->
        <div class="news-section-header">
            <h2>News & Gallery</h2>
            <div class="news-section-divider"></div>
            <p class="news-section-subtitle">Stay updated with our latest activities, announcements, and event highlights.</p>
        </div>

        <!-- ==========================================================================
           SUB BLOCK 1: Impact at a Glance
           ========================================================================== -->
        <div class="news-section-header" style="margin-bottom: 25px; text-align: left;" id="insights">
            <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0;">Impact Insights</h3>
        </div>

        <div class="impact-glance-container">
            <div class="news-split-pane">
                
                <!-- Left Sidebar: Event Selector -->
                <aside class="news-sidebar">
                    <div class="news-tab-list" role="tablist" aria-label="Impact Events">

                        <!-- Tab 15 -->
                        <button class="news-tab-item active" role="tab" aria-selected="true" aria-controls="event-tab-15" id="tab-15" data-index="15">
                            <span class="news-tab-title">MUKMIN's 1st Inaugural AGM</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 31 May 2026</span>
                        </button>

                        <!-- Tab 16 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-16" id="tab-16" data-index="16">
                            <span class="news-tab-title">India High Commissioner Felicitation Ceremony</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 25 May 2026</span>
                        </button>

                        <!-- Tab 13 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-13" id="tab-13" data-index="13">
                            <span class="news-tab-title">MUKMIN Takbir Raya</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 17 March 2026</span>
                        </button>

                        <!-- Tab 12 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-12" id="tab-12" data-index="12">
                            <span class="news-tab-title">MUKMIN Majlis Berbuka Puasa Kuala Lumpur</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 17 March 2026</span>
                        </button>

                        <!-- Tab 11 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-11" id="tab-11" data-index="11">
                            <span class="news-tab-title">Ramadhan Assistance for Scholars & Ustaz</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 11 March 2026</span>
                        </button>

                        <!-- Tab 10 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-10" id="tab-10" data-index="10">
                            <span class="news-tab-title">MUKMIN Majlis Berbuka Puasa Penang</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 7 March 2026</span>
                        </button>

                        <!-- Tab 9 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-9" id="tab-9" data-index="9">
                            <span class="news-tab-title">MUKMIN Ramadan Food Basket Initiative</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 28 Feb – 17 Mar 2026</span>
                        </button>

                        <!-- Tab 8 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-8" id="tab-8" data-index="8">
                            <span class="news-tab-title">The KL Declaration</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 25 January 2026</span>
                        </button>

                        <!-- Tab 17 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-17" id="tab-17" data-index="17">
                            <span class="news-tab-title">Golden Dinar Awards</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 25 January 2026</span>
                        </button>

                        <!-- Tab 6 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-6" id="tab-6" data-index="6">
                            <span class="news-tab-title">MUKMIN Future Leaders Scholarship Pledge</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 24 January 2026</span>
                        </button>

                        <!-- Tab 7 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-7" id="tab-7" data-index="7">
                            <span class="news-tab-title">SIRAT Global Forum 2026</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 – 25 January 2026</span>
                        </button>

                        <!-- Tab 5 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-5" id="tab-5" data-index="5">
                            <span class="news-tab-title">FIKRAH Global Roundtable</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 January 2026</span>
                        </button>

                        <!-- Tab 20 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-20" id="tab-20" data-index="20">
                            <span class="news-tab-title">MUKMIN Football Friendly: KL vs Penang</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 November 2025</span>
                        </button>

                        <!-- Tab 14 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-14" id="tab-14" data-index="14">
                            <span class="news-tab-title">MUKMIN Youth Icon Awards</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                        </button>

                        <!-- Tab 4 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-4" id="tab-4" data-index="4">
                            <span class="news-tab-title">FIKRAH Launch</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                        </button>

                        <!-- Tab 3 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-3" id="tab-3" data-index="3">
                            <span class="news-tab-title">SIRAT Youth Summit 2026</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                        </button>

                        <!-- Tab 18 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-18" id="tab-18" data-index="18">
                            <span class="news-tab-title">FIKRAH Chai & Chat</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 21 November 2025</span>
                        </button>

                        <!-- Tab 19 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-19" id="tab-19" data-index="19">
                            <span class="news-tab-title">MUKMIN Shark Tank Pitching</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 21 November 2025</span>
                        </button>

                        <!-- Tab 21 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-21" id="tab-21" data-index="21">
                            <span class="news-tab-title">MUKMIN Official Jersey Launch</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 5 November 2025</span>
                        </button>

                        <!-- Tab 2 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-2" id="tab-2" data-index="2">
                            <span class="news-tab-title">SIRAT Leaders Forum 2025</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 29 – 31 August 2025</span>
                        </button>

                        <!-- Tab 1 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-1" id="tab-1" data-index="1">
                            <span class="news-tab-title">MUKMIN Hari Raya Aidilfitri Open House 2025</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 12 April 2025</span>
                        </button>

                    </div>
                </aside>

                <!-- Right Display Area -->
                <main class="news-content-display">

                    <!-- Detail 15 (Inaugural AGM) -->
                    <article class="news-card-detail active" id="event-tab-15" role="tabpanel" aria-labelledby="tab-15" data-gallery-filter="{{ $newsGallerySlugs[15] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/15.png') }}" alt="MUKMIN's 1st Inaugural AGM">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN's 1st Inaugural AGM</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 31 May 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Main Hall, University of Cyberjaya, Cyberjaya, Selangor</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The 1st Inaugural Annual General Meeting (AGM) marked a historic milestone in the journey of Pertubuhan Gabungan MUKMIN Nasional (MUKMIN), formally bringing together community leaders, affiliate organisations, strategic partners and members under a shared vision of strengthening and advancing the Indian Muslim community.</p>
                            <p>Beyond fulfilling a key governance milestone, the AGM symbolised the transition of MUKMIN into a structured national coordinating ecosystem, anchored on collaboration, accountability and long-term community impact. Members came together to endorse the organisation's Constitution, appoint its leadership structure and collectively chart the strategic direction for the years ahead.</p>
                            <p>The gathering also served as a platform to reaffirm MUKMIN's commitment to building resilient communities through its five strategic pillars—socio-economic mobility, education and future readiness, entrepreneurship and innovation, faith, identity and ukhuwah, as well as leadership and capacity building.</p>
                            <p>As MUKMIN embarks on this new chapter, the Inaugural AGM stands as a testament to the collective aspiration to build a stronger, more connected and future-ready community for generations to come.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 16 (India High Commissioner Felicitation) -->
                    <article class="news-card-detail" id="event-tab-16" role="tabpanel" aria-labelledby="tab-16" data-gallery-filter="{{ $newsGallerySlugs[16] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/16.png') }}" alt="India High Commissioner to Malaysia Felicitation Ceremony">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">India High Commissioner to Malaysia Felicitation Ceremony</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 25 May 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> India Gate Restaurant, Puchong, Selangor</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>MUKMIN was honoured to host a special Felicitation Ceremony in appreciation of His Excellency B. N. Reddy, recognising his dedicated service and significant contributions towards strengthening the longstanding friendship and strategic partnership between India and Malaysia.</p>
                            <p>The event brought together community leaders, professionals, organisations and distinguished guests to celebrate a diplomatic tenure marked by collaboration, mutual respect and the strengthening of people-to-people connections between the two nations. It also served as an opportunity to acknowledge the positive role of diplomacy in fostering greater cooperation across community, education, business and cultural spheres.</p>
                            <p>On behalf of the MUKMIN ecosystem, we express our deepest appreciation for H.E. B. N. Reddy's visionary leadership, commitment to public service and unwavering efforts in advancing stronger bilateral relations. We extend our heartfelt gratitude and best wishes for continued success in his future endeavours.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 13 (Takbir Raya) -->
                    <article class="news-card-detail" id="event-tab-13" role="tabpanel" aria-labelledby="tab-13" data-gallery-filter="{{ $newsGallerySlugs[13] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/1.png') }}" alt="MUKMIN Takbir Raya">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Takbir Raya</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 17 March 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Masjid Negara, Kuala Lumpur</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The MUKMIN Takbir Raya Initiative, organised in conjunction with the Aidilfitri celebrations, was among the organisation’s key community engagement programmes, bringing together NGO leaders from across Malaysia, including presidents and senior representatives from Indian Muslim organisations nationwide.</p>
                            <p>Approximately 50 community leaders participated in the recording, which was subsequently published across MUKMIN’s official social media platforms and YouTube channel. The initiative received overwhelmingly positive feedback and was widely appreciated for fostering unity, strengthening community bonds, and celebrating a shared sense of identity and togetherness within the Indian Muslim community.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 12 (Kembara KL) -->
                    <article class="news-card-detail" id="event-tab-12" role="tabpanel" aria-labelledby="tab-12" data-gallery-filter="{{ $newsGallerySlugs[12] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/2.png') }}" alt="MUKMIN Majlis Berbuka Puasa Kuala Lumpur">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Majlis Berbuka Puasa Kuala Lumpur</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 17 March 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Kuala Lumpur Golf & Country Club (KLGCC), Petaling Jaya, Selangor</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The Majlis Berbuka Puasa & Kembara Ramadhan MUKMIN Contribution Ceremony Kuala Lumpur was successfully held with the presence of YB Khairy Jamaluddin.</p>
                            <p>The programme featured a Fireside Chat session that brought together more than 100 community leaders on a constructive dialogue platform to discuss current issues, strengthen networks and explore collaborative approaches towards more structured and sustainable community development.</p>
                            <p>In addition, food basket contributions were distributed to single mothers, while duit raya contributions were presented to tahfiz students as a gesture of care and support.</p>
                            <p>Overall, the programme reflected a balance between strategic discourse and community welfare efforts in strengthening social well-being, unity and collective progress.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 11 (Ustaz Assistance) -->
                    <article class="news-card-detail" id="event-tab-11" role="tabpanel" aria-labelledby="tab-11" data-gallery-filter="{{ $newsGallerySlugs[11] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/3.png') }}" alt="Ramadhan Assistance for Religious Scholars & Ustaz">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">Ramadhan Assistance for Religious Scholars & Ustaz</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 11 March 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Madrasah Kampung Pandan, Kuala Lumpur and Masjid Kapitan Keling, Pulau Pinang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The Ramadhan Assistance Programme for Religious Scholars & Ustaz was carried out to support religious scholars and ustaz across Selangor and Kuala Lumpur through food basket contributions and financial assistance.</p>
                            <p>As part of the initiative, 175 ustaz were also registered to receive complimentary medical protection cards in appreciation of their service and contributions to the community.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 10 (Kembara Penang) -->
                    <article class="news-card-detail" id="event-tab-10" role="tabpanel" aria-labelledby="tab-10" data-gallery-filter="{{ $newsGallerySlugs[10] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/4.png') }}" alt="MUKMIN Majlis Berbuka Puasa Penang">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Majlis Berbuka Puasa Penang</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 7 March 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Chinese Town Hall, Georgetown, Pulau Pinang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The Majlis Berbuka Puasa & Kembara Ramadhan MUKMIN Contribution Ceremony was successfully held with the presence of YB Tuan Steven Sim Chee Keong.</p>
                            <p>During the programme, a total of RM230,000 in contributions was channeled to NGOs, mosques and madrasahs, alongside the distribution of 50 food baskets and Ramadan aid for orphans and tahfiz students.</p>
                            <p>The event hosted approximately 400 guests, further fostering the spirit of togetherness, compassion and community solidarity during the month of Ramadan.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 9 (Kembara Ramadhan) -->
                    <article class="news-card-detail" id="event-tab-9" role="tabpanel" aria-labelledby="tab-9" data-gallery-filter="{{ $newsGallerySlugs[9] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/5.png') }}" alt="MUKMIN Ramadan Food Basket Initiative">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Ramadan Food Basket Initiative</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 28 February – 17 March 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Nationwide, Malaysia</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The Kembara Ramadan MUKMIN programme successfully distributed 5,000 food baskets to communities in need across the country. Contributions were channeled through mosques, madrasahs, suraus and NGOs nationwide.</p>
                            <p>This initiative reflects MUKMIN’s continued commitment to easing the burden of vulnerable communities while fostering compassion, unity and solidarity throughout the month of Ramadan.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 8 (KL Declaration) -->
                    <article class="news-card-detail" id="event-tab-8" role="tabpanel" aria-labelledby="tab-8" data-gallery-filter="{{ $newsGallerySlugs[8] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/6.png') }}" alt="The KL Declaration">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">The KL Declaration</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 25 January 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dewan Tun Abdul Razak, Menara Bank Rakyat, Kuala Lumpur</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>As part of its commitment to shaping policy and driving collective action, FIKRAH introduced the Kuala Lumpur Declaration — a strategic document outlining shared aspirations, priorities and commitments towards sustainable community development.</p>
                            <p>The declaration serves as:</p>
                            <ul>
                                <li>A framework for cross-sector coordination</li>
                                <li>A policy reference for programmes and strategic collaborations</li>
                                <li>A call to action for long-term impact and sustainable progress</li>
                            </ul>
                            <p>It further strengthens MUKMIN’s role in translating dialogue into meaningful action that contributes to national and community development.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 17 (Golden Dinar Awards) -->
                    <article class="news-card-detail" id="event-tab-17" role="tabpanel" aria-labelledby="tab-17" data-gallery-filter="{{ $newsGallerySlugs[17] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/17.png') }}" alt="Golden Dinar Awards">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">Golden Dinar Awards</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 25 January 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dewan Tun Abdul Razak, Menara Bank Rakyat, Kuala Lumpur</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The Golden Dinar Awards was established to recognise and celebrate individuals whose leadership, achievements and contributions have created meaningful impact within the Indian Muslim community, both in Malaysia and around the world.</p>
                            <p>The awards honour outstanding business leaders, professionals, entrepreneurs, academics, innovators and distinguished personalities who have demonstrated excellence in their respective fields while contributing towards the advancement and empowerment of the community.</p>
                            <p>More than an awards ceremony, the Golden Dinar Awards serves as a platform to acknowledge inspiring role models, strengthen community pride and showcase the remarkable achievements of individuals whose dedication and success continue to shape a stronger and more resilient future for generations to come.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 7 (Global Forum) -->
                    <article class="news-card-detail" id="event-tab-7" role="tabpanel" aria-labelledby="tab-7" data-gallery-filter="{{ $newsGallerySlugs[7] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/7.png') }}" alt="SIRAT Global Forum 2026">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">SIRAT Global Forum 2026</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 – 25 January 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dewan Tun Abdul Razak, Menara Bank Rakyat, Kuala Lumpur</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>SIRAT Global Forum 2026 marked a significant milestone as a premier international forum bringing together business leaders, professionals, innovators and Tamil Muslim youths from more than 20 countries, with approximately 500 participants gathered on a global platform.</p>
                            <p>Held over three days, the forum aimed to strengthen cross-border collaboration, unlock global economic opportunities and drive inclusive and sustainable growth. Guided by the SIRAT vision — Strengthening Indian Muslim Roots & Aspiration Together — the forum focused on strategic areas including global collaboration, entrepreneurship and innovation, Islamic finance, education and talent development, as well as social impact.</p>
                            <p>The forum further strengthened international networks while advancing community aspirations towards a more competitive, inclusive and sustainable future within the global ecosystem.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 6 (Scholarship Pledge) -->
                    <article class="news-card-detail" id="event-tab-6" role="tabpanel" aria-labelledby="tab-6" data-gallery-filter="{{ $newsGallerySlugs[6] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/8.png') }}" alt="MUKMIN Future Leaders Scholarship Pledge">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Future Leaders Scholarship Pledge</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 24 January 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dewan Tun Abdul Razak, Menara Bank Rakyat, Kuala Lumpur</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>Five universities — UNITAR, MAHSA, BAC Group, UOC, and Binary College — have collectively pledged higher education scholarships as part of a shared commitment to expanding access to quality education and strengthening future talent development.</p>
                            <p>This initiative not only opens greater pathways for students to pursue their educational aspirations, but also helps alleviate financial barriers that often limit access to higher learning. To date, RM5 million in scholarships has been committed, representing a significant investment in human capital development and the future of the next generation.</p>
                            <p>In parallel, ongoing efforts are being undertaken to further expand scholarship opportunities through strategic collaborations and institutional partnerships leading into 2026.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 5 (FIKRAH Roundtable) -->
                    <article class="news-card-detail" id="event-tab-5" role="tabpanel" aria-labelledby="tab-5" data-gallery-filter="{{ $newsGallerySlugs[5] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/9.png') }}" alt="FIKRAH Global Roundtable">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">FIKRAH Global Roundtable</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 January 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> MAHSA Avenue, Kuala Lumpur</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The FIKRAH Global Roundtable, held in conjunction with the SIRAT Global Forum, brought together more than 50 international industry leaders in a high-impact strategic discussion session.</p>
                            <p>The roundtable focused on collective impact initiatives, emphasising cross-border collaboration, strategic investments and sustainable community empowerment. Discussions also explored potential partnerships and joint initiatives, further strengthening FIKRAH’s role as a strategic connector in advancing inclusive and globally competitive community development.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 20 (Football Friendly) -->
                    <article class="news-card-detail" id="event-tab-20" role="tabpanel" aria-labelledby="tab-20" data-gallery-filter="{{ $newsGallerySlugs[20] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/20.png') }}" alt="MUKMIN Football Friendly: KL vs Penang">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Football Friendly: KL vs Penang</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Soccer Experience, Jalan Mccalister, Georgetown, Penang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The MUKMIN Football Friendly brought together members of the MUKMIN community for a spirited morning of sportsmanship, camaraderie and healthy competition as MUKMIN KL took on MUKMIN Penang in a friendly football match held in Georgetown.</p>
                            <p>More than just a sporting event, the programme was organised to strengthen bonds of ukhuwah, encourage youth engagement and foster closer connections between members and communities across different regions. The event reflected MUKMIN's commitment to promoting holistic community development through meaningful social and recreational activities.</p>
                            <p>The occasion was further elevated by the presence of distinguished representatives from the Football Association of Malaysia (FAM), Football Association of Selangor (FAS) and Football Association of Penang (FAP), who attended to officiate and inaugurate the friendly match, underscoring the significance of the initiative and the value of strategic community partnerships.</p>
                            <p>Through programmes such as the MUKMIN Football Friendly, the organisation continues to create platforms that bring people together, strengthen relationships and cultivate a vibrant, united and active community.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 14 (MUKMIN Youth Icon Awards) -->
                    <article class="news-card-detail" id="event-tab-14" role="tabpanel" aria-labelledby="tab-14" data-gallery-filter="{{ $newsGallerySlugs[14] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/14.png') }}" alt="MUKMIN Youth Icon Awards">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Youth Icon Awards</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> PICCA Convention Centre, Penang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The SIRAT Youth Icon Awards was established to recognise and celebrate outstanding young individuals who have demonstrated excellence, leadership and meaningful contributions across various fields while inspiring positive change within the community.</p>
                            <p>Held as a flagship segment of the SIRAT Youth Summit, the awards honoured a new generation of trailblazers, including entrepreneurs, professionals, innovators, academics, creatives and community leaders, whose achievements embody the values of resilience, service and purposeful leadership.</p>
                            <p>Beyond recognising individual accomplishments, the SIRAT Youth Icon Awards serves as a platform to inspire future generations, showcase positive role models and encourage greater youth participation in nation-building and community development. The initiative reflects MUKMIN's commitment to nurturing future-ready leaders who are equipped to make a lasting impact both locally and globally.</p>
                            <p>By celebrating excellence and aspiration, the SIRAT Youth Icon Awards reinforces the belief that empowered youth are the foundation of a stronger, more resilient and united community.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 4 (FIKRAH Launch) -->
                    <article class="news-card-detail" id="event-tab-4" role="tabpanel" aria-labelledby="tab-4" data-gallery-filter="{{ $newsGallerySlugs[4] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/10.png') }}" alt="FIKRAH Launch">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">FIKRAH Launch</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> PICCA Convention Centre, Penang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The official launch of FIKRAH was officiated by YB Tuan Steven Sim Chee Keong. Spearheaded by MUKMIN’s women leadership, FIKRAH is a strategic think tank that convenes cross-sector experts, leaders, and practitioners to drive impactful ideas, research, innovation, and collaborative solutions.</p>
                            <p>Representing the Foundation for Innovation, Knowledge and Research in Advancing Humanity, FIKRAH serves as a strategic platform for shaping discourse, developing policy frameworks, and advancing community development towards meaningful and sustainable societal progress.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 3 (Youth Summit) -->
                    <article class="news-card-detail" id="event-tab-3" role="tabpanel" aria-labelledby="tab-3" data-gallery-filter="{{ $newsGallerySlugs[3] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/11.png') }}" alt="SIRAT Youth Summit 2026">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">SIRAT Youth Summit 2026</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> PICCA Convention Centre, Penang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The SIRAT Youth Summit brought together more than 1,000 youths, making it one of the largest Indian Muslim youth gatherings in Malaysia.</p>
                            <p>Featuring inspirational talks, panel discussions, fireside chats and pitching sessions, the programme provided a platform for youths to showcase ideas, build networks and explore future opportunities. The event also celebrated outstanding young individuals through the Youth Icon Awards, strengthening the spirit of youth leadership and empowerment.</p>
                            <p>In recognition of its scale and impact, the programme was also recorded in the Malaysian Book of Records for the largest participation in an Indian Muslim youth gathering.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 18 (FIKRAH Chai & Chat) -->
                    <article class="news-card-detail" id="event-tab-18" role="tabpanel" aria-labelledby="tab-18" data-gallery-filter="{{ $newsGallerySlugs[18] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/18.png') }}" alt="FIKRAH Chai & Chat">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">FIKRAH Chai & Chat</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 21 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> PICCA Convention Centre, Penang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>FIKRAH Chai & Chat marked the inaugural roundtable dialogue under the FIKRAH platform, bringing together a diverse group of talented, forward-thinking and future-focused young professionals and community leaders for an open exchange of ideas.</p>
                            <p>The session served as an introduction to FIKRAH's role as MUKMIN's strategic think tank, creating a space for meaningful conversations on the aspirations, challenges and opportunities facing the Indian Muslim community. Through constructive dialogue and the sharing of perspectives, participants explored how research, innovation and collaborative leadership can contribute towards building stronger and more resilient communities.</p>
                            <p>The gathering reflects FIKRAH's commitment to fostering thought leadership, nurturing emerging voices and translating ideas into actionable strategies for long-term community impact.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 19 (MUKMIN Shark Tank Pitching) -->
                    <article class="news-card-detail" id="event-tab-19" role="tabpanel" aria-labelledby="tab-19" data-gallery-filter="{{ $newsGallerySlugs[19] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/19.png') }}" alt="MUKMIN Shark Tank Pitching">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Shark Tank Pitching</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 21 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> PICCA Convention Centre, Penang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>Held in conjunction with the SIRAT Youth Summit, the MUKMIN Shark Tank Pitching session provided a platform for aspiring entrepreneurs to showcase innovative business ideas and connect with experienced industry leaders.</p>
                            <p>Following a competitive selection process, six dynamic and promising young entrepreneurs were shortlisted to present their ventures before a panel of distinguished business leaders and professionals from the Indian Muslim community, who served as the programme's very own "Sharks."</p>
                            <p>The session was designed not only as a pitching competition, but also as a valuable opportunity for mentorship, constructive feedback and strategic networking. Participants gained practical insights into business growth, investment readiness and entrepreneurship, while fostering meaningful connections with established leaders and potential collaborators.</p>
                            <p>As part of MUKMIN's commitment to strengthening entrepreneurship and innovation, the initiative reflects the organisation's broader vision of nurturing future-ready talent, empowering young founders and creating pathways for sustainable socio-economic advancement within the community.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 21 (Official Jersey Launch) -->
                    <article class="news-card-detail" id="event-tab-21" role="tabpanel" aria-labelledby="tab-21" data-gallery-filter="{{ $newsGallerySlugs[21] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/21.png') }}" alt="MUKMIN Official Jersey Launch">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Official Jersey Launch</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 5 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Syed Bistro, Dang Wangi, Kuala Lumpur</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>In conjunction with the SIRAT Youth Summit, MUKMIN proudly unveiled its Official MUKMIN Jersey, symbolising unity, identity and a shared commitment towards strengthening community engagement through youth, leadership and collective action.</p>
                            <p>The launch marked more than the introduction of a new official attire—it represents the spirit of togetherness and collaboration that defines the MUKMIN ecosystem, bringing organisations, leaders and communities together under one shared vision.</p>
                            <p>The initiative was made possible through the valued support and collaboration of GM Group, MyEvents International, Mahaberjaya and Syed Group, whose partnership reflects the importance of cross-sector collaboration in advancing meaningful community initiatives.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 2 (SIRAT Leaders Forum) -->
                    <article class="news-card-detail" id="event-tab-2" role="tabpanel" aria-labelledby="tab-2" data-gallery-filter="{{ $newsGallerySlugs[2] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/SIRAT.png') }}" alt="SIRAT Leaders Forum 2025">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">SIRAT Leaders Forum 2025</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 29 – 31 August 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> The Chateau Resort, Bukit Tinggi, Pahang</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The SIRAT Leaders Forum brought together NGO leaders, religious institutions, policymakers and professionals from across the country on a strategic platform aimed at strengthening collaboration and shaping a more structured, inclusive and sustainable community development agenda.</p>
                            <p>Throughout the three-day programme, six panel sessions were held featuring experts in education, youth and politics, women empowerment, economics, religion and social development. These sessions focused on addressing current challenges and developing strategic solutions for the progress of the Indian Muslim community.</p>
                            <p>The programme also gathered approximately 250 delegates and panelists, further strengthening collaborative networks and fostering a stronger spirit of unity among participants.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 1 (Hari Raya) -->
                    <article class="news-card-detail" id="event-tab-1" role="tabpanel" aria-labelledby="tab-1" data-gallery-filter="{{ $newsGallerySlugs[1] ?? '' }}">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/13.png') }}" alt="MUKMIN Hari Raya Aidilfitri Open House 2025">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">MUKMIN Hari Raya Aidilfitri Open House 2025</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 12 April 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> IDCC, Shah Alam</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The MUKMIN Hari Raya Aidilfitri Open House 2025 brought together representatives from NGOs, mosque, madrasah, surau and tahfiz institutions from across the country in a spirit of unity and togetherness. The event was attended by approximately 2,000 guests, including more than 200 religious scholars. As a gesture of appreciation and concern for their well-being, medical protection cards were also presented to the scholars during the event.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                </main>
            </div>
        </div>

        <!-- ==========================================================================
           SUB BLOCK 2: Moments of MUKMIN
           ========================================================================== -->
        <section class="section-moments" id="moments">
            <div class="news-section-header" style="margin-bottom: 25px; text-align: left;">
                <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0;">Moments of MUKMIN</h3>
            </div>

            <!-- Filter Controls -->
            <div class="gallery-filter-bar">
                <button class="gallery-filter-btn active" data-filter="all">All Photos</button>
                @foreach($momentsCategories as $category)
                <button class="gallery-filter-btn" data-filter="{{ $category['slug'] }}">{{ $category['label'] }}</button>
                @endforeach
            </div>

            <!-- Image Grid -->
            <div class="gallery-masonry-grid" id="gallery-grid" tabindex="-1">
                @forelse($momentsGallery as $image)
                <div class="gallery-card"
                     data-category="{{ $image['category'] }}"
                     data-title="{{ $image['title'] }}"
                     data-src="{{ $image['src'] }}">
                    <img src="{{ $image['src'] }}" alt="{{ $image['title'] }}" loading="lazy">
                    <div class="gallery-card-overlay">
                        <div class="gallery-overlay-icon"><i class="fas fa-search-plus" aria-hidden="true"></i></div>
                        <h4 class="gallery-overlay-title">{{ $image['title'] }}</h4>
                        <span class="gallery-overlay-cat">{{ $image['category_label'] }}</span>
                    </div>
                </div>
                @empty
                <p style="grid-column: 1 / -1; text-align: center; color: #666; font-size: 14px; padding: 24px 0;">
                    Photos will appear here once images are uploaded to the gallery folders.
                </p>
                @endforelse
            </div>
        </section>

    </div>
</div>

<!-- ==========================================================================
   CUSTOM LIGHTBOX HTML
   ========================================================================== -->
<div class="custom-lightbox" id="lightboxModal" role="dialog" aria-modal="true" aria-hidden="true">
    <button class="lightbox-close-btn" id="lightboxClose" aria-label="Close lightbox">&times;</button>

    <div class="lightbox-viewport">
        <button type="button" class="lightbox-arrow lightbox-arrow-left" id="lightboxPrev" aria-label="Previous image">&lsaquo;</button>

        <div class="lightbox-image-wrap">
            <div class="lightbox-image-stage">
                <img src="" alt="" class="lightbox-image" id="lightboxImage">
            </div>
        </div>

        <button type="button" class="lightbox-arrow lightbox-arrow-right" id="lightboxNext" aria-label="Next image">&rsaquo;</button>

        <div class="lightbox-meta">
            <div class="lightbox-counter" id="lightboxCounter"></div>
        </div>

        <div class="lightbox-thumbs-wrap">
            <div class="lightbox-thumbs" id="lightboxThumbs"></div>
        </div>
    </div>
</div>

<!-- ==========================================================================
   PAGE LOGIC (TAB SWITCHING, GALLERY FILTER & LIGHTBOX)
   ========================================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // -------------------------------------------------------------
    // 1. Tab Switching Logic for Sub Block 1
    // -------------------------------------------------------------
    const tabs = document.querySelectorAll('.news-tab-item');
    const cards = document.querySelectorAll('.news-card-detail');

    function activateInsightTab(tabIndex) {
        const tab = document.querySelector('.news-tab-item[data-index="' + tabIndex + '"]');
        if (!tab) return false;

        tabs.forEach(t => {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });
        cards.forEach(c => c.classList.remove('active'));

        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');

        const targetContent = document.getElementById('event-tab-' + tabIndex);
        if (targetContent) {
            targetContent.classList.add('active');
        }

        return true;
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            activateInsightTab(this.getAttribute('data-index'));
            this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });

    function handleNewsInsightHash(hash) {
        if (!hash) return;

        const cleanHash = hash.replace('#', '');
        const match = cleanHash.match(/^insights-(\d+)$/);

        if (match) {
            if (activateInsightTab(match[1])) {
                const insightsSection = document.getElementById('insights');
                window.requestAnimationFrame(function () {
                    if (insightsSection) {
                        insightsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                    const activeTab = document.querySelector('.news-tab-item[data-index="' + match[1] + '"]');
                    if (activeTab) {
                        activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                });
            }
            return;
        }

        if (cleanHash === 'insights') {
            const insightsSection = document.getElementById('insights');
            if (insightsSection) {
                insightsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    }

    if (window.location.hash) {
        handleNewsInsightHash(window.location.hash);
    }

    window.addEventListener('hashchange', function () {
        handleNewsInsightHash(window.location.hash);
    });

    window.addEventListener('popstate', function () {
        if (window.location.hash) {
            handleNewsInsightHash(window.location.hash);
        }
    });

    // -------------------------------------------------------------
    // 2. View Gallery — filter by event folder & scroll to images
    // -------------------------------------------------------------
    const scrollCtaBtns = document.querySelectorAll('.scroll-to-gallery');
    const galleryGrid = document.getElementById('gallery-grid');
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const galleryCards = document.querySelectorAll('.gallery-card');

    function applyGalleryFilter(filterValue) {
        filterBtns.forEach(b => b.classList.remove('active'));

        const targetBtn = Array.from(filterBtns).find(
            btn => btn.getAttribute('data-filter') === filterValue
        );

        if (targetBtn) {
            targetBtn.classList.add('active');
            targetBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else if (filterValue === 'all' && filterBtns.length) {
            filterBtns[0].classList.add('active');
        }

        galleryCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');

            if (filterValue === 'all' || cardCategory === filterValue) {
                card.style.display = 'block';
                void card.offsetWidth;
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    if (card.style.opacity === '0') {
                        card.style.display = 'none';
                    }
                }, 300);
            }
        });
    }

    function scrollToGalleryImages() {
        if (!galleryGrid) {
            return;
        }

        galleryGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });

        window.setTimeout(() => {
            galleryGrid.focus({ preventScroll: true });

            const firstVisibleCard = Array.from(galleryCards).find(card => {
                return window.getComputedStyle(card).display !== 'none';
            });

            if (firstVisibleCard) {
                firstVisibleCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 450);
    }

    scrollCtaBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const article = this.closest('.news-card-detail');
            const filterValue = article ? article.getAttribute('data-gallery-filter') : '';

            if (filterValue) {
                applyGalleryFilter(filterValue);
            }

            scrollToGalleryImages();
        });
    });

    // -------------------------------------------------------------
    // 3. Moments of MUKMIN Gallery Category Filters
    // -------------------------------------------------------------
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            applyGalleryFilter(this.getAttribute('data-filter'));
            scrollToGalleryImages();
        });
    });

    // -------------------------------------------------------------
    // 4. Custom Gallery Lightbox Modal
    // -------------------------------------------------------------
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCounter = document.getElementById('lightboxCounter');
    const lightboxThumbs = document.getElementById('lightboxThumbs');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    const lightboxViewport = document.querySelector('.lightbox-viewport');
    
    let currentImageIndex = 0;
    let visibleGalleryItems = [];

    function isMobileLightbox() {
        return window.matchMedia('(max-width: 991px)').matches;
    }

    function clearLightboxImageSizing() {
        lightboxImage.style.removeProperty('width');
        lightboxImage.style.removeProperty('height');
        lightboxImage.style.removeProperty('max-width');
        lightboxImage.style.removeProperty('max-height');
        lightboxImage.style.removeProperty('object-fit');
    }

    function updateLightboxMode() {
        const mobile = isMobileLightbox();
        lightboxModal.classList.toggle('is-mobile', mobile);

        if (mobile) {
            clearLightboxImageSizing();
        } else {
            fitLightboxImage();
        }
    }

    function fitLightboxImage() {
        const wrap = lightboxViewport ? lightboxViewport.querySelector('.lightbox-image-wrap') : null;
        if (!wrap || !lightboxImage.getAttribute('src')) {
            return;
        }

        if (isMobileLightbox()) {
            clearLightboxImageSizing();
            return;
        }

        const maxW = wrap.clientWidth;
        const maxH = wrap.clientHeight;
        const natW = lightboxImage.naturalWidth;
        const natH = lightboxImage.naturalHeight;

        if (!maxW || !maxH) {
            return;
        }

        if (natW > 0 && natH > 0) {
            const scale = Math.min(maxW / natW, maxH / natH);
            lightboxImage.style.width = Math.round(natW * scale) + 'px';
            lightboxImage.style.height = Math.round(natH * scale) + 'px';
            lightboxImage.style.maxWidth = 'none';
            lightboxImage.style.maxHeight = 'none';
        } else {
            lightboxImage.style.width = maxW + 'px';
            lightboxImage.style.height = maxH + 'px';
            lightboxImage.style.maxWidth = maxW + 'px';
            lightboxImage.style.maxHeight = maxH + 'px';
            lightboxImage.style.objectFit = 'contain';
        }
    }

    function resetLightboxScroll() {
        const wrap = lightboxViewport ? lightboxViewport.querySelector('.lightbox-image-wrap') : null;
        if (wrap) {
            wrap.scrollTop = 0;
            wrap.scrollLeft = 0;
        }
    }

    lightboxImage.addEventListener('load', fitLightboxImage);
    window.addEventListener('resize', function() {
        if (lightboxModal.classList.contains('show')) {
            updateLightboxMode();
            fitLightboxImage();
        }
    });

    // Collect all visible elements for navigation based on active filter
    function updateVisibleItems() {
        visibleGalleryItems = Array.from(galleryCards).filter(card => {
            return window.getComputedStyle(card).display !== 'none';
        });
    }

    function buildLightboxThumbs() {
        lightboxThumbs.innerHTML = '';

        if (isMobileLightbox()) {
            return;
        }

        visibleGalleryItems.forEach((card, index) => {
            const thumb = document.createElement('button');
            thumb.type = 'button';
            thumb.className = 'lightbox-thumb';
            thumb.setAttribute('aria-label', card.getAttribute('data-title') || 'Gallery image');
            thumb.dataset.index = String(index);

            const img = document.createElement('img');
            img.src = card.getAttribute('data-src');
            img.alt = card.getAttribute('data-title') || '';
            thumb.appendChild(img);

            thumb.addEventListener('click', function(e) {
                e.stopPropagation();
                showLightboxImage(index);
            });

            lightboxThumbs.appendChild(thumb);
        });
    }

    function highlightActiveThumb() {
        if (isMobileLightbox()) {
            return;
        }

        const thumbs = lightboxThumbs.querySelectorAll('.lightbox-thumb');
        thumbs.forEach((thumb, index) => {
            thumb.classList.toggle('active', index === currentImageIndex);
        });

        const activeThumb = thumbs[currentImageIndex];
        if (activeThumb) {
            activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    function showLightboxImage(index) {
        if (visibleGalleryItems.length === 0) {
            return;
        }

        if (index < 0) {
            currentImageIndex = visibleGalleryItems.length - 1;
        } else if (index >= visibleGalleryItems.length) {
            currentImageIndex = 0;
        } else {
            currentImageIndex = index;
        }
        
        const activeCard = visibleGalleryItems[currentImageIndex];
        if (activeCard) {
            const imgSrc = activeCard.getAttribute('data-src');
            const imgTitle = activeCard.getAttribute('data-title');

            lightboxImage.setAttribute('src', imgSrc);
            lightboxImage.setAttribute('alt', imgTitle);
            clearLightboxImageSizing();
            lightboxCounter.textContent = `${currentImageIndex + 1} / ${visibleGalleryItems.length}`;
            highlightActiveThumb();
            resetLightboxScroll();

            if (lightboxImage.complete) {
                fitLightboxImage();
            }
        }
    }

    galleryCards.forEach(card => {
        card.addEventListener('click', function() {
            updateVisibleItems();
            currentImageIndex = visibleGalleryItems.indexOf(this);
            updateLightboxMode();
            buildLightboxThumbs();
            
            lightboxModal.style.display = 'block';
            // Trigger reflow for CSS transition
            void lightboxModal.offsetWidth;
            lightboxModal.classList.add('show');
            lightboxModal.setAttribute('aria-hidden', 'false');
            
            showLightboxImage(currentImageIndex);
            requestAnimationFrame(fitLightboxImage);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        });
    });

    if (lightboxViewport) {
        lightboxViewport.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    function closeLightbox() {
        lightboxModal.classList.remove('show');
        lightboxModal.classList.remove('is-mobile');
        setTimeout(() => {
            lightboxModal.style.display = 'none';
            lightboxModal.setAttribute('aria-hidden', 'true');
        }, 300);
        document.body.style.overflow = '';
    }

    lightboxClose.addEventListener('click', closeLightbox);
    lightboxModal.addEventListener('click', function(e) {
        if (e.target === lightboxModal) {
            closeLightbox();
        }
    });

    lightboxPrev.addEventListener('click', function(e) {
        e.stopPropagation();
        showLightboxImage(currentImageIndex - 1);
    });

    lightboxNext.addEventListener('click', function(e) {
        e.stopPropagation();
        showLightboxImage(currentImageIndex + 1);
    });

    // Keyboard Navigation support
    document.addEventListener('keydown', function(e) {
        if (lightboxModal.classList.contains('show')) {
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                showLightboxImage(currentImageIndex - 1);
            } else if (e.key === 'ArrowRight') {
                showLightboxImage(currentImageIndex + 1);
            }
        }
    });
});
</script>
@endsection
