@extends('welfare.layouts.app')

@section('title', 'Leadership & Governance - Pertubuhan Gabungan MUKMIN Nasional')

@push('styles')
    @include('welfare.partials.about-styles')
@endpush

@section('content')
<!-- Sub Block 3: Leadership & Governance (Dynamic Tabs) -->
<section class="section-padding bg-white">
    <div class="container">
        <!-- Header -->
        <div class="leadership-header">
            <h2>Leadership & Governance</h2>
            <div class="subtitle">Guided by Experience. Driven by Purpose.</div>
            <p class="intro-text">MUKMIN is led by a structured leadership ecosystem comprising experienced advisors, organisational leaders and strategic committees committed to advancing sustainable community development and national collaboration.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="leadership-tabs">
            <button class="leadership-tab-btn active" data-target="tab-coa">Council of Advisors (COA)</button>
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
                    <span class="member-org">{{ $member['org'] }}</span>
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
                    <span class="member-org">{{ $member['org'] }}</span>
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
                    <span class="member-tag">{{ $member['tag'] }}</span>
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
                    <span class="member-role">{{ $member['role'] }}</span>
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
