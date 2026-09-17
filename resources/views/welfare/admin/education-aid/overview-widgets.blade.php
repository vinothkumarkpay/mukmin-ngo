@php
    $cases = $aidOverview['cases'] ?? [];
    $urgency = $aidOverview['urgency'] ?? [];
    $financial = $aidOverview['financial'] ?? [];
    $deadlines = $aidOverview['deadlines'] ?? [];

    $total = (int) ($cases['total'] ?? 0);
    $new = (int) ($cases['new'] ?? 0);
    $underAssessment = (int) ($cases['under_assessment'] ?? 0);
    $awaitingDocs = (int) ($cases['awaiting_documents'] ?? 0);
    $interview = (int) ($cases['interview_pending'] ?? 0);
    $committee = (int) ($cases['committee_review'] ?? 0);
    $approved = (int) ($cases['approved'] ?? 0);
    $rejected = (int) ($cases['rejected'] ?? 0);
    $active = $new + $underAssessment + $awaitingDocs + $interview + $committee;

    $critical = (int) ($urgency['critical'] ?? 0);
    $high = (int) ($urgency['high'] ?? 0);
    $moderate = (int) ($urgency['moderate'] ?? 0);
    $low = (int) ($urgency['low'] ?? 0);

    $due3 = (int) ($deadlines['due_3'] ?? 0);
    $due7 = (int) ($deadlines['due_7'] ?? 0);
    $due14 = (int) ($deadlines['due_14'] ?? 0);
    $dueSoon = $due3 + $due7;

    $fmtMoney = function ($value) {
        $n = (float) ($value ?? 0);
        if ($n >= 1000) {
            return 'RM ' . number_format($n, 0);
        }

        return 'RM ' . number_format($n, 2);
    };

    $statusRows = [
        ['label' => 'New', 'count' => $new],
        ['label' => 'Under assessment', 'count' => $underAssessment],
        ['label' => 'Awaiting documents', 'count' => $awaitingDocs],
        ['label' => 'Interview', 'count' => $interview],
        ['label' => 'Committee review', 'count' => $committee],
        ['label' => 'Approved', 'count' => $approved, 'tone' => 'ok'],
        ['label' => 'Rejected', 'count' => $rejected, 'tone' => 'bad'],
    ];

    $barTotal = max(1, collect($statusRows)->sum('count'));
@endphp

<section class="ea-dash dashboard-card">
    <div class="card-header">
        <h3>Education Aid Overview</h3>
    </div>

    <div class="ea-dash-metrics">
        <div class="ea-dash-metric">
            <span class="ea-dash-metric-icon"><i class="fas fa-folder-open"></i></span>
            <div class="ea-dash-metric-copy">
                <span class="ea-dash-metric-label">Total cases</span>
                <strong class="ea-dash-metric-value">{{ $total }}</strong>
            </div>
        </div>
        <div class="ea-dash-metric ea-dash-metric-primary">
            <span class="ea-dash-metric-icon"><i class="fas fa-stream"></i></span>
            <div class="ea-dash-metric-copy">
                <span class="ea-dash-metric-label">Active pipeline</span>
                <strong class="ea-dash-metric-value">{{ $active }}</strong>
            </div>
        </div>
        <div class="ea-dash-metric ea-dash-metric-critical {{ $critical > 0 ? 'is-active' : '' }}">
            <span class="ea-dash-metric-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="ea-dash-metric-copy">
                <span class="ea-dash-metric-label">Critical</span>
                <strong class="ea-dash-metric-value">{{ $critical }}</strong>
            </div>
        </div>
        <div class="ea-dash-metric ea-dash-metric-deadline {{ $dueSoon > 0 ? 'is-active' : '' }}">
            <span class="ea-dash-metric-icon"><i class="fas fa-clock"></i></span>
            <div class="ea-dash-metric-copy">
                <span class="ea-dash-metric-label">Due in 7 days</span>
                <strong class="ea-dash-metric-value">{{ $dueSoon }}</strong>
            </div>
        </div>
    </div>

    <div class="ea-dash-body">
        <div class="ea-dash-col ea-dash-col-wide">
            <div class="ea-dash-section-title">Workflow status</div>
            <div class="ea-dash-bar" aria-hidden="true">
                @foreach($statusRows as $row)
                    @if(($row['count'] ?? 0) > 0)
                        <span class="ea-dash-bar-seg ea-dash-bar-{{ $row['tone'] ?? 'neutral' }}"
                              style="flex: {{ (int) $row['count'] }} 0 0;"
                              title="{{ $row['label'] }}: {{ $row['count'] }}"></span>
                    @endif
                @endforeach
                @if(collect($statusRows)->sum('count') === 0)
                    <span class="ea-dash-bar-empty"></span>
                @endif
            </div>
            <ul class="ea-dash-status-list">
                @foreach($statusRows as $row)
                    <li class="{{ ($row['tone'] ?? '') === 'ok' ? 'is-ok' : '' }} {{ ($row['tone'] ?? '') === 'bad' ? 'is-bad' : '' }}">
                        <span class="ea-dash-dot"></span>
                        <span class="ea-dash-status-label">{{ $row['label'] }}</span>
                        <span class="ea-dash-status-count">{{ (int) $row['count'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="ea-dash-col">
            <div class="ea-dash-section-title">Urgency</div>
            <ul class="ea-dash-simple-list">
                <li class="is-critical"><span>Critical</span><strong>{{ $critical }}</strong></li>
                <li class="is-high"><span>High</span><strong>{{ $high }}</strong></li>
                <li class="is-moderate"><span>Moderate</span><strong>{{ $moderate }}</strong></li>
                <li class="is-low"><span>Low</span><strong>{{ $low }}</strong></li>
            </ul>

            <div class="ea-dash-section-title ea-dash-section-title-spaced">Deadlines</div>
            <ul class="ea-dash-simple-list">
                <li class="{{ $due3 > 0 ? 'is-critical' : '' }}"><span>Within 3 days</span><strong>{{ $due3 }}</strong></li>
                <li class="{{ $due7 > 0 ? 'is-high' : '' }}"><span>Within 7 days</span><strong>{{ $due7 }}</strong></li>
                <li class="{{ $due14 > 0 ? 'is-moderate' : '' }}"><span>Within 14 days</span><strong>{{ $due14 }}</strong></li>
            </ul>
        </div>

        <div class="ea-dash-col">
            <div class="ea-dash-section-title">Financial</div>
            <ul class="ea-dash-money-list">
                <li>
                    <span>Requested</span>
                    <strong>{{ $fmtMoney($financial['total_requested'] ?? 0) }}</strong>
                </li>
                <li>
                    <span>Recommended</span>
                    <strong>{{ $fmtMoney($financial['total_recommended'] ?? 0) }}</strong>
                </li>
                <li>
                    <span>Approved</span>
                    <strong>{{ $fmtMoney($financial['total_approved'] ?? 0) }}</strong>
                </li>
                <li>
                    <span>Disbursed</span>
                    <strong>{{ $fmtMoney($financial['total_disbursed'] ?? 0) }}</strong>
                </li>
            </ul>
        </div>
    </div>
</section>
