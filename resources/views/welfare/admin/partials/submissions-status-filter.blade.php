@php
    use App\Support\SubmissionStatus;

    $activeStatus = request('submission_status');
    $activeStatusNormalized = $activeStatus ? SubmissionStatus::normalize($activeStatus) : null;
    $activeStatusLabel = $activeStatusNormalized ? SubmissionStatus::label($activeStatusNormalized) : null;

    $partnerFilterId = request('filter_partner');
    $partnerFilterLabel = null;
    if (filled($partnerFilterId)) {
        $partnerFilterLabel = collect($submissionFilterPartners ?? [])
            ->firstWhere('id', $partnerFilterId)['name'] ?? $partnerFilterId;
    }

    $activeFilters = collect([
        'Status' => $activeStatusLabel,
        'Search' => request('filter_q'),
        'State' => request('filter_state'),
        'Date from' => request('filter_date_from'),
        'Date to' => request('filter_date_to'),
        'Partner' => $partnerFilterLabel,
        'Programme' => request('filter_programme'),
        'Qualification' => request('filter_qualification'),
        'Household income' => request('filter_household_income'),
        'Gender' => request('filter_gender'),
        'Mode' => request('filter_mode'),
        'Entity type' => request('filter_entity_type'),
        'ROS registered' => request('filter_ros') === '1' ? 'Yes' : (request('filter_ros') === '0' ? 'No' : null),
        'Aid type' => request('filter_aid_type'),
    ])->filter(fn ($value) => filled($value));

    $currentAdminTab = request('admin_tab', $activeTab ?? 'panel-overview');
@endphp

<div class="payments-filter-card submissions-status-filter-card" id="submissions-status-filter-card">
    <div class="payments-filter-header">
        <div class="payments-filter-title">
            <span class="payments-filter-icon"><i class="fas fa-filter"></i></span>
            <div>
                <h4>Search &amp; Filter Submissions</h4>
                <p>Filter by status and form fields for the current tab only</p>
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
        <input type="hidden" name="admin_tab" id="status-filter-admin-tab" value="{{ $currentAdminTab }}">

        <div class="payments-filter-grid">
            <div class="payments-filter-field">
                <label for="filter_submission_status">Status</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-flag"></i>
                    <select name="submission_status" id="filter_submission_status">
                        <option value="">All statuses</option>
                        @foreach(SubmissionStatus::options() as $value => $label)
                            <option value="{{ $value }}" @selected($activeStatusNormalized === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="payments-filter-field">
                <label for="filter_q">Search</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" name="filter_q" id="filter_q" value="{{ request('filter_q') }}" placeholder="Name, email, organisation…" autocomplete="off">
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-feedback panel-ordinary panel-friends panel-mentor panel-partner panel-volunteer panel-aid panel-mfls">
                <label for="filter_state">State</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-map-marker-alt"></i>
                    <select name="filter_state" id="filter_state">
                        <option value="">All states</option>
                        @foreach(($submissionFilterStates ?? []) as $stateOption)
                            <option value="{{ $stateOption }}" @selected(request('filter_state') === $stateOption)>{{ $stateOption }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="payments-filter-field">
                <label for="filter_date_from">Date from</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-calendar"></i>
                    <input type="date" name="filter_date_from" id="filter_date_from" value="{{ request('filter_date_from') }}">
                </div>
            </div>

            <div class="payments-filter-field">
                <label for="filter_date_to">Date to</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-calendar"></i>
                    <input type="date" name="filter_date_to" id="filter_date_to" value="{{ request('filter_date_to') }}">
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-mfls">
                <label for="filter_partner">Partner</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-university"></i>
                    <select name="filter_partner" id="filter_partner">
                        <option value="">All partners</option>
                        @foreach(($submissionFilterPartners ?? []) as $partnerOption)
                            <option value="{{ $partnerOption['id'] }}" @selected(request('filter_partner') === $partnerOption['id'])>{{ $partnerOption['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-mfls">
                <label for="filter_programme">Programme</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-graduation-cap"></i>
                    <input type="text" name="filter_programme" id="filter_programme" value="{{ request('filter_programme') }}" placeholder="Programme name…" autocomplete="off">
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-mfls">
                <label for="filter_qualification">Qualification</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-book"></i>
                    <select name="filter_qualification" id="filter_qualification">
                        <option value="">All qualifications</option>
                        @foreach(($submissionFilterQualifications ?? []) as $qualification)
                            <option value="{{ $qualification }}" @selected(request('filter_qualification') === $qualification)>{{ $qualification }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-mfls">
                <label for="filter_household_income">Household income</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-coins"></i>
                    <select name="filter_household_income" id="filter_household_income">
                        <option value="">All income bands</option>
                        @foreach(($submissionFilterHouseholdIncomes ?? []) as $income)
                            <option value="{{ $income }}" @selected(request('filter_household_income') === $income)>{{ $income }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-volunteer">
                <label for="filter_gender">Gender</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-venus-mars"></i>
                    <select name="filter_gender" id="filter_gender">
                        <option value="">All genders</option>
                        <option value="Male" @selected(request('filter_gender') === 'Male')>Male</option>
                        <option value="Female" @selected(request('filter_gender') === 'Female')>Female</option>
                    </select>
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-volunteer">
                <label for="filter_mode">Preferred mode</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-laptop-house"></i>
                    <input type="text" name="filter_mode" id="filter_mode" value="{{ request('filter_mode') }}" placeholder="e.g. On-site, Online…" autocomplete="off">
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-friends">
                <label for="filter_entity_type">Entity type</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-users"></i>
                    <select name="filter_entity_type" id="filter_entity_type">
                        <option value="">All entity types</option>
                        @foreach(($submissionFilterEntityTypes ?? []) as $entityType)
                            <option value="{{ $entityType }}" @selected(request('filter_entity_type') === $entityType)>{{ $entityType }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-ordinary">
                <label for="filter_ros">ROS registered</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-stamp"></i>
                    <select name="filter_ros" id="filter_ros">
                        <option value="">All</option>
                        <option value="1" @selected(request('filter_ros') === '1')>Yes</option>
                        <option value="0" @selected(request('filter_ros') === '0')>No</option>
                    </select>
                </div>
            </div>

            <div class="payments-filter-field" data-filter-panels="panel-aid">
                <label for="filter_aid_type">Aid type</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-hands-helping"></i>
                    <input type="text" name="filter_aid_type" id="filter_aid_type" value="{{ request('filter_aid_type') }}" placeholder="e.g. Financial Assistance…" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="payments-filter-footer">
            @if($activeFilters->isNotEmpty())
                <div class="payments-active-filters">
                    <span class="payments-active-label">Active:</span>
                    @foreach($activeFilters as $label => $value)
                        <span class="payments-active-chip">{{ $label }}: {{ $value }}</span>
                    @endforeach
                </div>
            @else
                <div class="payments-active-filters payments-active-filters--empty">
                    <span>No filters applied — showing all submissions for this tab</span>
                </div>
            @endif

            <div class="payments-filter-actions">
                <a href="{{ route('welfare.admin.dashboard', ['admin_tab' => $currentAdminTab]) }}" id="submissions-status-reset-link" class="btn-admin btn-admin-secondary payments-btn-clear">
                    <i class="fas fa-rotate-left"></i> Reset
                </a>
                <button type="submit" class="btn-admin btn-admin-primary payments-btn-apply">
                    <i class="fas fa-sliders"></i> Apply filters
                </button>
            </div>
        </div>
    </form>
</div>
