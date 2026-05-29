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
    overflow-y: auto;
    padding: 15px;
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
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(15, 23, 42, 0.97);
    z-index: 10000;
    display: none;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.custom-lightbox.show {
    display: flex;
    opacity: 1;
}

.lightbox-container {
    max-width: 90%;
    max-height: 85%;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.lightbox-image {
    max-width: 100%;
    max-height: 75vh;
    border-radius: 6px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
    display: block;
    object-fit: contain;
}

.lightbox-title {
    color: #ffffff;
    text-align: center;
    margin-top: 18px;
    font-size: 16px;
    font-weight: 700;
}

.lightbox-close-btn {
    position: absolute;
    top: -50px;
    right: 0;
    color: #ffffff;
    font-size: 32px;
    cursor: pointer;
    background: none;
    border: none;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.lightbox-close-btn:hover {
    opacity: 1;
}

.lightbox-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #ffffff;
    font-size: 40px;
    background: rgba(255, 255, 255, 0.05);
    border: none;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.2s;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-arrow:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.15);
}

.lightbox-arrow-left {
    left: -80px;
}

.lightbox-arrow-right {
    right: -80px;
}

/* ==========================================================================
   Responsive Breakpoints
   ========================================================================== */
@media (max-width: 1200px) {
    .lightbox-arrow-left { left: 10px; }
    .lightbox-arrow-right { right: 10px; }
    .lightbox-close-btn { right: 10px; }
}

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
#insights, #moments {
    scroll-margin-top: 160px;
}
@media (max-width: 991px) {
    #insights, #moments {
        scroll-margin-top: 90px;
    }
}
</style>

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
                        
                        <!-- Tab 13 -->
                        <button class="news-tab-item active" role="tab" aria-selected="true" aria-controls="event-tab-13" id="tab-13" data-index="13">
                            <span class="news-tab-title">Takbir Raya MUKMIN</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 17 March 2026</span>
                        </button>
                        
                        <!-- Tab 12 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-12" id="tab-12" data-index="12">
                            <span class="news-tab-title">Kembara Ramadhan MUKMIN Kuala Lumpur</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 17 March 2026</span>
                        </button>
                        
                        <!-- Tab 11 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-11" id="tab-11" data-index="11">
                            <span class="news-tab-title">Ramadhan Assistance for Scholars & Ustaz</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 11 March 2026</span>
                        </button>
                        
                        <!-- Tab 10 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-10" id="tab-10" data-index="10">
                            <span class="news-tab-title">Kembara Ramadhan MUKMIN Penang</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 7 March 2026</span>
                        </button>
                        
                        <!-- Tab 9 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-9" id="tab-9" data-index="9">
                            <span class="news-tab-title">Kembara Ramadhan MUKMIN</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 28 Feb – 17 Mar 2026</span>
                        </button>
                        
                        <!-- Tab 8 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-8" id="tab-8" data-index="8">
                            <span class="news-tab-title">The KL Declaration</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 25 January 2026</span>
                        </button>
                        
                        <!-- Tab 7 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-7" id="tab-7" data-index="7">
                            <span class="news-tab-title">SIRAT Global Forum 2026</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 – 25 January 2026</span>
                        </button>
                        
                        <!-- Tab 6 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-6" id="tab-6" data-index="6">
                            <span class="news-tab-title">MUKMIN Future Leaders Scholarship Pledge</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 24 January 2026</span>
                        </button>
                        
                        <!-- Tab 5 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-5" id="tab-5" data-index="5">
                            <span class="news-tab-title">FIKRAH Global Roundtable</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 23 January 2026</span>
                        </button>
                        
                        <!-- Tab 4 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-4" id="tab-4" data-index="4">
                            <span class="news-tab-title">FIKRAH Launch</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                        </button>
                        
                        <!-- Tab 3 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-3" id="tab-3" data-index="3">
                            <span class="news-tab-title">SIRAT Youth Summit & Youth Icon Awards</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                        </button>
                        
                        <!-- Tab 2 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-2" id="tab-2" data-index="2">
                            <span class="news-tab-title">SIRAT Leaders Forum (Strengthening India Muslim Roots)</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 29 – 31 August 2025</span>
                        </button>
                        
                        <!-- Tab 1 -->
                        <button class="news-tab-item" role="tab" aria-selected="false" aria-controls="event-tab-1" id="tab-1" data-index="1">
                            <span class="news-tab-title">Majlis Rumah Terbuka MUKMIN Sempena Hari Raya Aidilfitri</span>
                            <span class="news-tab-date"><i class="fas fa-calendar-alt" aria-hidden="true"></i> 12 April 2025</span>
                        </button>
                        
                    </div>
                </aside>

                <!-- Right Display Area -->
                <main class="news-content-display">
                    
                    <!-- Detail 13 (Takbir Raya) -->
                    <article class="news-card-detail active" id="event-tab-13" role="tabpanel" aria-labelledby="tab-13">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/1.png') }}" alt="Takbir Raya MUKMIN">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">Takbir Raya MUKMIN</h4>
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
                    <article class="news-card-detail" id="event-tab-12" role="tabpanel" aria-labelledby="tab-12">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/2.png') }}" alt="Kembara Ramadhan MUKMIN Kuala Lumpur">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">Majlis Berbuka Puasa & Kembara Ramadhan MUKMIN Kuala Lumpur</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 17 March 2026</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Kuala Lumpur Golf & Country Club (KLGCC)</span>
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
                    <article class="news-card-detail" id="event-tab-11" role="tabpanel" aria-labelledby="tab-11">
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
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Madrasah Kampung Pandan, Kuala Lumpur</span>
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
                    <article class="news-card-detail" id="event-tab-10" role="tabpanel" aria-labelledby="tab-10">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/4.png') }}" alt="Kembara Ramadhan MUKMIN Penang">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">Majlis Berbuka Puasa & Kembara Ramadhan MUKMIN Penang</h4>
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
                    <article class="news-card-detail" id="event-tab-9" role="tabpanel" aria-labelledby="tab-9">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/5.png') }}" alt="Kembara Ramadhan MUKMIN">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">Kembara Ramadhan MUKMIN</h4>
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
                    <article class="news-card-detail" id="event-tab-8" role="tabpanel" aria-labelledby="tab-8">
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
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dewan Tun Abdul Razak, Menara Bank Rakyat, KL</span>
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

                    <!-- Detail 7 (Global Forum) -->
                    <article class="news-card-detail" id="event-tab-7" role="tabpanel" aria-labelledby="tab-7">
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
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dewan Tun Abdul Razak, Menara Bank Rakyat, KL</span>
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
                    <article class="news-card-detail" id="event-tab-6" role="tabpanel" aria-labelledby="tab-6">
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
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Dewan Tun Abdul Razak, Menara Bank Rakyat, KL</span>
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
                    <article class="news-card-detail" id="event-tab-5" role="tabpanel" aria-labelledby="tab-5">
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

                    <!-- Detail 4 (FIKRAH Launch) -->
                    <article class="news-card-detail" id="event-tab-4" role="tabpanel" aria-labelledby="tab-4">
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
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> PICCA Convention Centre</span>
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
                    <article class="news-card-detail" id="event-tab-3" role="tabpanel" aria-labelledby="tab-3">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/11.png') }}" alt="SIRAT Youth Summit & Youth Icon Awards">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">SIRAT Youth Summit & Youth Icon Awards</h4>
                            </div>
                            <div class="news-detail-meta">
                                <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> 22 November 2025</span>
                                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> PICCA Convention Centre</span>
                            </div>
                        </div>
                        <div class="news-detail-description">
                            <p>The SIRAT Youth Summit & Youth Icon Awards brought together more than 1,000 youths, making it one of the largest Indian Muslim youth gatherings in Malaysia.</p>
                            <p>Featuring inspirational talks, panel discussions, fireside chats and pitching sessions, the programme provided a platform for youths to showcase ideas, build networks and explore future opportunities. The event also celebrated outstanding young individuals through the Youth Icon Awards, strengthening the spirit of youth leadership and empowerment.</p>
                            <p>In recognition of its scale and impact, the programme was also recorded in the Malaysian Book of Records for the largest participation in an Indian Muslim youth gathering.</p>
                        </div>
                        <div class="news-detail-cta-wrap">
                            <button class="news-detail-cta scroll-to-gallery">
                                View Gallery <i class="fas fa-images" aria-hidden="true"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Detail 2 (SIRAT Leaders Forum) -->
                    <article class="news-card-detail" id="event-tab-2" role="tabpanel" aria-labelledby="tab-2">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/SIRAT.png') }}" alt="SIRAT Leaders Forum">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">SIRAT Leaders Forum (Strengthening India Muslim Roots & Aspiration Together)</h4>
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
                    <article class="news-card-detail" id="event-tab-1" role="tabpanel" aria-labelledby="tab-1">
                        <div class="news-detail-image-wrap">
                            <img src="{{ asset('welfare/img/news/insights/13.png') }}" alt="Majlis Rumah Terbuka MUKMIN Sempena Hari Raya Aidilfitri">
                        </div>
                        <div class="news-detail-header">
                            <div class="news-detail-title-wrap">
                                <span class="news-detail-accent"></span>
                                <h4 class="news-detail-title">Majlis Rumah Terbuka MUKMIN Sempena Hari Raya Aidilfitri</h4>
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
                @foreach(config('welfare_gallery.categories', []) as $category)
                <button class="gallery-filter-btn" data-filter="{{ $category['slug'] }}">{{ $category['label'] }}</button>
                @endforeach
            </div>

            <!-- Image Grid -->
            <div class="gallery-masonry-grid">
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
    <button class="lightbox-arrow lightbox-arrow-left" id="lightboxPrev" aria-label="Previous image">&lsaquo;</button>
    
    <div class="lightbox-container">
        <img src="" alt="" class="lightbox-image" id="lightboxImage">
        <div class="lightbox-title" id="lightboxTitle"></div>
    </div>
    
    <button class="lightbox-arrow lightbox-arrow-right" id="lightboxNext" aria-label="Next image">&rsaquo;</button>
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
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active classes
            tabs.forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            cards.forEach(c => c.classList.remove('active'));
            
            // Set active states
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            const tabIndex = this.getAttribute('data-index');
            const targetContent = document.getElementById(`event-tab-${tabIndex}`);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });

    // -------------------------------------------------------------
    // 2. Smooth Scroll CTA to Moments of MUKMIN
    // -------------------------------------------------------------
    const scrollCtaBtns = document.querySelectorAll('.scroll-to-gallery');
    const momentsSection = document.getElementById('moments');
    
    scrollCtaBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (momentsSection) {
                momentsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // -------------------------------------------------------------
    // 3. Moments of MUKMIN Gallery Category Filters
    // -------------------------------------------------------------
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const galleryCards = document.querySelectorAll('.gallery-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            galleryCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                
                if (filterValue === 'all' || cardCategory === filterValue) {
                    card.style.display = 'block';
                    // Trigger reflow for animation
                    void card.offsetWidth;
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    // Delay hiding until fade transition completes
                    setTimeout(() => {
                        if (card.style.opacity === '0') {
                            card.style.display = 'none';
                        }
                    }, 300);
                }
            });
        });
    });

    // -------------------------------------------------------------
    // 4. Custom Gallery Lightbox Modal
    // -------------------------------------------------------------
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    
    let currentImageIndex = 0;
    let visibleGalleryItems = [];

    // Collect all visible elements for navigation based on active filter
    function updateVisibleItems() {
        visibleGalleryItems = Array.from(galleryCards).filter(card => {
            return window.getComputedStyle(card).display !== 'none';
        });
    }

    function showLightboxImage(index) {
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
            lightboxTitle.textContent = imgTitle;
        }
    }

    galleryCards.forEach(card => {
        card.addEventListener('click', function() {
            updateVisibleItems();
            currentImageIndex = visibleGalleryItems.indexOf(this);
            
            lightboxModal.style.display = 'flex';
            // Trigger reflow for CSS transition
            void lightboxModal.offsetWidth;
            lightboxModal.classList.add('show');
            lightboxModal.setAttribute('aria-hidden', 'false');
            
            showLightboxImage(currentImageIndex);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        });
    });

    function closeLightbox() {
        lightboxModal.classList.remove('show');
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
