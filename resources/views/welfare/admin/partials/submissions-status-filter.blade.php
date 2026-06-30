@php
    use App\Support\SubmissionStatus;

    $activeStatus = request('submission_status');
    $activeStatusNormalized = $activeStatus ? SubmissionStatus::normalize($activeStatus) : null;
    $activeStatusLabel = $activeStatusNormalized ? SubmissionStatus::label($activeStatusNormalized) : null;
@endphp

<div class="payments-filter-card submissions-status-filter-card" id="submissions-status-filter-card">
    <div class="payments-filter-header">
        <div class="payments-filter-title">
            <span class="payments-filter-icon"><i class="fas fa-filter"></i></span>
            <div>
                <h4>Filter Submissions by Status</h4>
                <p>Show submissions matching a workflow status on the current tab only</p>
            </div>
        </div>
        <div class="payments-filter-meta">
            <span class="payments-result-count">
                <i class="fas fa-inbox"></i>
                {{ $filteredSubmissionCount }} {{ Str::plural('result', $filteredSubmissionCount) }}
            </span>
        </div>
    </div>

    <form method="GET" action="{{ route('welfare.admin.dashboard') }}" id="submissions-status-filter-form" class="payments-filter-form">
        <input type="hidden" name="admin_tab" id="status-filter-admin-tab" value="{{ request('admin_tab', 'panel-overview') }}">

        <div class="payments-filter-status-row" style="margin-top: 0; padding-top: 0; border-top: none;">
            <span class="payments-filter-status-label">Status</span>
            <div class="payments-status-pills">
                <label class="payments-status-pill">
                    <input type="radio" name="submission_status" value="" @checked(! $activeStatusNormalized)>
                    <span>All</span>
                </label>
                @foreach(SubmissionStatus::options() as $value => $label)
                    <label class="payments-status-pill submissions-status-pill--{{ $value }}">
                        <input type="radio" name="submission_status" value="{{ $value }}" @checked($activeStatusNormalized === $value)>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="payments-filter-footer">
            @if($activeStatusLabel)
                <div class="payments-active-filters">
                    <span class="payments-active-label">Active:</span>
                    <span class="payments-active-chip">Status: {{ $activeStatusLabel }}</span>
                </div>
            @else
                <div class="payments-active-filters payments-active-filters--empty">
                    <span>No status filter applied — showing all submissions</span>
                </div>
            @endif

            <div class="payments-filter-actions">
                <a href="{{ route('welfare.admin.dashboard', ['admin_tab' => request('admin_tab', 'panel-overview')]) }}" id="submissions-status-reset-link" class="btn-admin btn-admin-secondary payments-btn-clear">
                    <i class="fas fa-rotate-left"></i> Reset
                </a>
                <button type="submit" class="btn-admin btn-admin-primary payments-btn-apply">
                    <i class="fas fa-sliders"></i> Apply filter
                </button>
            </div>
        </div>
    </form>
</div>
