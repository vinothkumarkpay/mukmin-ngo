<!-- Leadership & Governance (Dynamic Tabs) -->
<section id="leadership" class="section-padding bg-white">
    <div class="container">
        <div class="leadership-header">
            <h2>Leadership & Governance</h2>
            <div class="subtitle">Guided by Experience. Driven by Purpose.</div>
            <p class="intro-text">MUKMIN is led by a structured leadership ecosystem comprising experienced advisors, organisational leaders and strategic committees committed to advancing sustainable community development and national collaboration.</p>
        </div>

        <div class="leadership-tabs">
            <button type="button" class="leadership-tab-btn active" data-target="tab-coa">Council of Advisor</button>
            <button type="button" class="leadership-tab-btn" data-target="tab-cec">Central Executive Committee (CEC)</button>
            <button type="button" class="leadership-tab-btn" data-target="tab-exco">Executive Committee (EXCO)</button>
            <button type="button" class="leadership-tab-btn" data-target="tab-bureau">Bureau Chairs</button>
        </div>

        <div class="leadership-tab-content active" id="tab-coa">
            <div class="tab-intro">
                Providing strategic counsel, institutional guidance and long-term direction to strengthen MUKMIN’s vision, governance and national impact.
            </div>
            <div class="members-grid">
                @foreach($coa as $member)
                    @include('welfare.partials.leadership-member-card', [
                        'member' => $member,
                        'committee' => 'Council of Advisor',
                        'showRole' => false,
                        'showOrg' => false,
                        'showTag' => false,
                    ])
                @endforeach
            </div>
        </div>

        <div class="leadership-tab-content" id="tab-cec">
            <div class="tab-intro">
                Leading national coordination, organisational governance and strategic decision-making across the MUKMIN ecosystem.
            </div>
            <div class="members-grid">
                @foreach($cec as $member)
                    @include('welfare.partials.leadership-member-card', [
                        'member' => $member,
                        'committee' => 'Central Executive Committee (CEC)',
                        'showRole' => true,
                        'showOrg' => false,
                        'showTag' => false,
                    ])
                @endforeach
            </div>
        </div>

        <div class="leadership-tab-content" id="tab-exco">
            <div class="tab-intro">
                Overseeing operational leadership, programme execution and cross-functional coordination to drive impactful initiatives and organisational growth.
            </div>
            <div class="members-grid exco-grid">
                @foreach($exco as $member)
                    @include('welfare.partials.leadership-member-card', [
                        'member' => $member,
                        'committee' => 'Executive Committee (EXCO)',
                        'showRole' => false,
                        'showOrg' => false,
                        'showTag' => false,
                    ])
                @endforeach
            </div>
        </div>

        <div class="leadership-tab-content" id="tab-bureau">
            <div class="tab-intro">
                Leading specialised focus areas and strategic portfolios that support MUKMIN’s mission, programmes and community engagement efforts.
            </div>
            <div class="members-grid">
                @foreach($bureau as $member)
                    @include('welfare.partials.leadership-member-card', [
                        'member' => $member,
                        'committee' => 'Bureau Chairs',
                        'showRole' => false,
                        'showOrg' => false,
                        'showTag' => true,
                    ])
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="member-hover-preview" id="memberHoverPreview" aria-hidden="true">
    <div class="member-hover-preview-photo">
        <img src="" alt="" id="memberHoverPreviewImage">
    </div>
    <div class="member-hover-preview-body">
        <span class="member-hover-preview-committee" id="memberHoverPreviewCommittee"></span>
        <h4 class="member-hover-preview-name" id="memberHoverPreviewName"></h4>
        <p class="member-hover-preview-role" id="memberHoverPreviewRole"></p>
        <p class="member-hover-preview-meta" id="memberHoverPreviewMeta"></p>
    </div>
</div>

<div class="leadership-member-modal" id="leadershipMemberModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="leadershipMemberModalName">
    <div class="leadership-member-modal-backdrop" data-close-modal></div>
    <div class="leadership-member-modal-panel">
        <button type="button" class="leadership-member-modal-close" data-close-modal aria-label="Close profile">&times;</button>
        <div class="leadership-member-modal-content">
            <div class="leadership-member-modal-photo">
                <img src="" alt="" id="leadershipMemberModalImage">
            </div>
            <div class="leadership-member-modal-details">
                <span class="leadership-member-modal-committee" id="leadershipMemberModalCommittee"></span>
                <h3 id="leadershipMemberModalName"></h3>
                <p class="leadership-member-modal-role" id="leadershipMemberModalRole"></p>
                <p class="leadership-member-modal-meta" id="leadershipMemberModalMeta"></p>
            </div>
        </div>
    </div>
</div>
