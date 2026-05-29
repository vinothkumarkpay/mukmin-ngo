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
    align-items: center;
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
}
.who-image img {
    width: 100%;
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
    font-size: 12px;
    opacity: 0.9;
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
.president-letter p {
    font-size: 14.5px;
    line-height: 24px;
    color: #444;
    margin-bottom: 15px;
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
    font-size: 30px;
    margin-bottom: 10px;
    color: var(--color-heading);
}
.leadership-header .subtitle {
    font-size: 17px;
    color: var(--color-primary);
    font-weight: 600;
    margin-bottom: 15px;
}
.leadership-header .intro-text {
    font-size: 14.5px;
    line-height: 22px;
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
    font-size: 15px;
    font-weight: 600;
    color: var(--color-text);
    cursor: pointer;
    padding: 10px 18px;
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
    font-size: 14px;
    line-height: 22px;
    color: #555;
    font-style: italic;
    background: #fcfcfc;
    padding: 12px 20px;
    border-left: 3px solid var(--color-primary);
    border-radius: 0 4px 4px 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

/* Members Grid Layout */
.members-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}
.members-grid.exco-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* Member Card Layout */
.member-card {
    background: #ffffff;
    border: 1px solid var(--color-border);
    border-radius: 6px;
    padding: 22px 18px;
    text-align: center;
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    position: relative;
    overflow: hidden;
}
.member-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
    border-color: var(--color-primary);
}
.member-avatar {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    margin: 0 auto 12px;
    overflow: hidden;
    border: 3px solid #f3f3f3;
    transition: border-color var(--transition);
}
.member-card:hover .member-avatar {
    border-color: var(--color-primary);
}
.member-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.member-name {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--color-heading);
    margin-bottom: 5px;
    line-height: 1.35;
}
.member-role {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 4px;
    display: block;
}
.member-org {
    font-size: 11.5px;
    color: #666;
    line-height: 1.35;
    display: block;
}
.member-tag {
    display: inline-block;
    background: #f1f5f9;
    color: #475569;
    font-size: 10.5px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 12px;
    margin-top: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.member-card:hover .member-tag {
    background: var(--color-primary);
    color: #fff;
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
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 575px) {
    .members-grid, .members-grid.exco-grid {
        grid-template-columns: 1fr;
    }
}
</style>


<!-- Sub Block 1: Who We Are -->
<section id="who-we-are" class="section-padding bg-white">
    <div class="container">
        <div class="who-we-are-grid">
            <div class="who-body">
                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--color-primary); font-weight: 700; display: block; margin-bottom: 6px;">Who We Are</span>
                <h2 class="who-headline">A National Ecosystem for Community Transformation</h2>
                <h3 class="who-subheadline">Uniting communities, organisations, and partners to drive inclusive and sustainable socio-economic development across Malaysia.</h3>
                
                <p>MUKMIN is a national coordinating ecosystem that brings together NGOs, civil society organisations, chambers of commerce, institutions, global think tanks and impact organisations, alongside grassroots community networks including surau, madrasah and mosques across Malaysia under a shared mission to advance inclusive and sustainable development.</p>
                <p>Anchored in collaboration and collective action, MUKMIN serves as a connector between communities, institutions, industry, and ecosystem partners — aligning ideas, mobilising partnerships, and transforming community-driven efforts into scalable initiatives with long-term impact.</p>
                <p>Through a structured ecosystem approach, MUKMIN strengthens pathways for socio-economic mobility, education and talent development, leadership, entrepreneurship, innovation, and community cohesion.</p>
                <p>More than a movement, MUKMIN represents a coordinated national effort to build resilient communities, unlock opportunities, and create sustainable progress for future generations.</p>
            </div>
            <div class="who-image">
                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=800" alt="MUKMIN Community Transformation Ecosystem">
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
                </div>
            </div>
            <div class="president-letter">
                <h3>President's Note</h3>
                <p>Assalamu'alaikum Warahmatullahi Wabarakatuh and Warm Greetings,</p>
                <p>Welcome to the Pertubuhan Gabungan MUKMIN Nasional.</p>
                <p>As we stand at the threshold of a new era of community transformation in Malaysia, our mission is clearer and more urgent than ever. MUKMIN was established as a national coordinating ecosystem to address the socio-economic disparities and developmental gaps that exist in our society by uniting grassroots communities, civil society, chambers of commerce, and institutions.</p>
                <p>Our structured ecosystem approach enables us to channel aid, build capacity, and cultivate values-driven leadership systematically. We believe that true, lasting community transformation cannot happen in isolation; it requires a collective, coordinated commitment from every sector of society.</p>
                <p>By bridging the gap between national-level strategic vision and grassroots execution, we are shaping an ecosystem that is not just reactive to immediate needs, but proactive in building resilient, future-ready communities. We invite you to join us on this journey of purpose, collaboration, and shared impact.</p>
                <p>Thank you and Wassalam.</p>
                
                <div class="president-signature">
                    <h5>Datuk Wira Shahul Dawood</h5>
                    <p>President, Pertubuhan Gabungan MUKMIN Nasional</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sub Block 3: Leadership & Governance (Dynamic Tabs) -->
<section id="leadership" class="section-padding bg-white">
    <div class="container">
        <!-- Header -->
        <div class="leadership-header">
            <h2>Leadership & Governance</h2>
            <div class="subtitle">Guided by Experience. Driven by Purpose.</div>
            <p class="intro-text">MUKMIN is led by a structured leadership ecosystem comprising experienced advisors, organisational leaders and strategic committees committed to advancing sustainable community development and national collaboration.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="leadership-tabs">
            <button class="leadership-tab-btn active" data-target="tab-coa">COUNCIL OF WISDOM</button>
            <button class="leadership-tab-btn" data-target="tab-cec">Central Executive Committee (CEC)</button>
            <button class="leadership-tab-btn" data-target="tab-exco">Executive Committee (EXCO)</button>
            <button class="leadership-tab-btn" data-target="tab-bureau">Bureau Chairs</button>
        </div>

        <!-- Tab Contents -->
        
        <!-- Tab 1: Council of Advisors (COA) -->
        <div class="leadership-tab-content active" id="tab-coa">
            <div class="tab-intro">
                Providing strategic counsel, institutional guidance and long-term direction to strengthen MUKMIN’s vision, governance and national impact.
            </div>
            
            <div class="members-grid">
                @foreach($coa as $member)
                <div class="member-card">
                    <div class="member-avatar">
                        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}">
                    </div>
                    <h4 class="member-name">{{ $member['name'] }}</h4>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 2: Central Executive Committee (CEC) -->
        <div class="leadership-tab-content" id="tab-cec">
            <div class="tab-intro">
                Leading national coordination, organisational governance and strategic decision-making across the MUKMIN ecosystem.
            </div>
            
            <div class="members-grid">
                @foreach($cec as $member)
                <div class="member-card">
                    <div class="member-avatar">
                        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}">
                    </div>
                    <h4 class="member-name">{{ $member['name'] }}</h4>
                    <span class="member-role">{{ $member['role'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 3: Executive Committee (EXCO) -->
        <div class="leadership-tab-content" id="tab-exco">
            <div class="tab-intro">
                Overseeing operational leadership, programme execution and cross-functional coordination to drive impactful initiatives and organisational growth.
            </div>
            
            <div class="members-grid exco-grid">
                @foreach($exco as $member)
                <div class="member-card">
                    <div class="member-avatar">
                        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}">
                    </div>
                    <h4 class="member-name">{{ $member['name'] }}</h4>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 4: Bureau Chairs -->
        <div class="leadership-tab-content" id="tab-bureau">
            <div class="tab-intro">
                Leading specialised focus areas and strategic portfolios that support MUKMIN’s mission, programmes and community engagement efforts.
            </div>
            
            <div class="members-grid">
                @foreach($bureau as $member)
                <div class="member-card">
                    <div class="member-avatar">
                        <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}">
                    </div>
                    <h4 class="member-name">{{ $member['name'] }}</h4>
                    <span class="member-tag">{{ $member['tag'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Leadership Tabs Switching Logic
    var tabBtns = document.querySelectorAll('.leadership-tab-btn');
    var tabContents = document.querySelectorAll('.leadership-tab-content');

    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Remove active class from all buttons and contents
            tabBtns.forEach(function (b) { b.classList.remove('active'); });
            tabContents.forEach(function (c) { c.classList.remove('active'); });

            // Add active class to clicked button
            this.classList.add('active');

            // Show targeted content
            var targetId = this.getAttribute('data-target');
            var targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
});
</script>
@endpush
