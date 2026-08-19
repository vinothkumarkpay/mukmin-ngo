@extends('welfare.layouts.admin')

@section('title', 'Admin Dashboard - MUKMIN Portal')

@section('body')
@php
    $allowedPanelIds = $allowedPanelIds ?? [];
    $canPanel = fn ($panelId) => in_array($panelId, $allowedPanelIds, true);
    $isActivePanel = fn ($panelId) => ($activeTab ?? 'panel-overview') === $panelId;
@endphp
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'dashboard',
        'activeTab' => $activeTab ?? 'panel-overview',
    ])

    <!-- MAIN PANEL -->
    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Dashboard Overview'])

        <!-- CONTENT BODY -->
        <div class="content-body">
            @if(session('success'))
                <div class="alert-admin alert-admin-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('import_errors'))
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Import issues:</strong>
                        <ul style="margin: 8px 0 0 18px; padding: 0;">
                            @foreach(session('import_errors') as $importError)
                                <li>{{ $importError }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @php
                $emptySubmissionMessage = $submissionStatusFilter
                    ? 'No submissions found with status "' . \App\Support\SubmissionStatus::label($submissionStatusFilter) . '".'
                    : 'No submissions found.';
            @endphp

            @include('welfare.admin.partials.submissions-status-filter')

            <!-- 1. OVERVIEW PANEL -->
            @if($canPanel('panel-overview'))
            <div class="dashboard-panel{{ $isActivePanel('panel-overview') ? ' active' : '' }}" id="panel-overview">
                <div class="stats-grid">
                    @if($canPanel('panel-feedback'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-feedback')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-comment-dots"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['feedback'] }}</h3>
                                <p>Feedback & Ideas</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['feedback'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-ordinary'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-ordinary')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-building"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['ordinary'] }}</h3>
                                <p>Ordinary Members</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['ordinary'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-friends'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-friends')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['friends'] }}</h3>
                                <p>Friends of MUKMIN</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['friends'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-mentor'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-mentor')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['mentor'] }}</h3>
                                <p>Mentors Registered</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['mentor'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-partner'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-partner')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-handshake-angle"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['partner'] }}</h3>
                                <p>Partnerships</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['partner'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-volunteer'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-volunteer')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-hand-holding-heart"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['volunteer'] }}</h3>
                                <p>Volunteers Registered</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['volunteer'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-aid'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-aid')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-hand-holding-medical"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['aid'] }}</h3>
                                <p>Community Aid Requests</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['aid'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-mfls'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-mfls')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['mfls'] }}</h3>
                                <p>MFLS Scholarship Applications</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['mfls'] ?? []])
                    </div>
                    @endif
                    @if($canPanel('panel-payments'))
                    <div class="stat-card" onclick="window.location='{{ route('welfare.admin.donation-payments') }}'">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-credit-card"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['donations'] }}</h3>
                                <p>Donation Payments</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', [
                            'breakdown' => $statBreakdowns['donations'] ?? [],
                            'kind' => 'donation',
                        ])
                    </div>
                    @endif
                    @if($canPanel('panel-contact'))
                    <div class="stat-card" onclick="navigateToDashboardTab('panel-contact')">
                        <div class="stat-card-main">
                            <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                            <div class="stat-info">
                                <h3>{{ $stats['contact'] }}</h3>
                                <p>Contact Messages</p>
                            </div>
                        </div>
                        @include('welfare.admin.partials.stat-status-breakdown', ['breakdown' => $statBreakdowns['contact'] ?? []])
                    </div>
                    @endif
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Welcome to the MUKMIN Administrative Portal</h3>
                    </div>
                    <div class="card-body">
                        <p style="margin-bottom: 15px;">Use the left navigation sidebar to view individual form submissions, download CSV reports, import bulk records from Excel templates, update submission status, and configure form dropdown option items dynamically.</p>
                        <div class="important-notes" style="margin-bottom: 0;">
                            <h4>SYSTEM NOTE</h4>
                            <p style="font-size: 13.5px; color: #555;">All data changes (like changing approval status, deleting dropdown lists, or submitting new forms) will take effect instantly in the database.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 2. FEEDBACK PANEL -->
            @if($canPanel('panel-feedback'))
            <div class="dashboard-panel" id="panel-feedback">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Feedback & Suggestion Submissions</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'feedback') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'feedback'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>State</th>
                                        <th>Categories Selected</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($feedback as $item)
                                        <tr>
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y, h:i A') }}</td>
                                            <td><strong>{{ $item->full_name }}</strong></td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->state_residency }}</td>
                                            <td>
                                                @if(is_array($item->categories))
                                                    {{ implode(', ', $item->categories) }}
                                                @else
                                                    {{ $item->categories }}
                                                @endif
                                            </td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'feedback', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('feedback', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 3. ORDINARY MEMBERS PANEL -->
            @if($canPanel('panel-ordinary'))
            <div class="dashboard-panel" id="panel-ordinary">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Ordinary Organisation Membership Applications</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'ordinary') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'ordinary'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Organisation Name</th>
                                        <th>State</th>
                                        <th>ROS Registered</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ordinary as $item)
                                        <tr id="row-ordinary-{{ $item->id }}">
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td><strong>{{ $item->name_of_organisation }}</strong></td>
                                            <td>{{ $item->registered_state }}</td>
                                            <td>{{ $item->is_registered_ros ? 'Yes' : 'No' }}</td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'ordinary', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('ordinary', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 4. FRIENDS PANEL -->
            @if($canPanel('panel-friends'))
            <div class="dashboard-panel" id="panel-friends">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Friends of MUKMIN Applications</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'friends') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'friends'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Entity Type</th>
                                        <th>Name / Organisation</th>
                                        <th>Residency / State</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($friends as $item)
                                        <tr id="row-friends-{{ $item->id }}">
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>{{ $item->entity_type }}</td>
                                            <td>
                                                <strong>
                                                    @if($item->entity_type == 'Individual')
                                                        {{ $item->ind_name }}
                                                    @else
                                                        {{ $item->org_name }}
                                                    @endif
                                                </strong>
                                            </td>
                                            <td>
                                                @if($item->entity_type == 'Individual')
                                                    {{ $item->ind_state }}
                                                @else
                                                    {{ $item->org_state }}
                                                @endif
                                            </td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'friends', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('friends', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 5. MENTOR PANEL -->
            @if($canPanel('panel-mentor'))
            <div class="dashboard-panel" id="panel-mentor">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Mentor Registration Submissions</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'mentor') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'mentor'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Full Name</th>
                                        <th>Profession</th>
                                        <th>Organisation</th>
                                        <th>Exp Years</th>
                                        <th>State</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mentor as $item)
                                        <tr>
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td><strong>{{ $item->full_name }}</strong></td>
                                            <td>{{ $item->occupation }}</td>
                                            <td>{{ $item->organisation }}</td>
                                            <td>{{ $item->experience_years }} yrs</td>
                                            <td>{{ $item->state_residency }}</td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'mentor', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('mentor', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 6. PARTNERSHIPS PANEL -->
            @if($canPanel('panel-partner'))
            <div class="dashboard-panel" id="panel-partner">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Partnership & Collaboration Proposals</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'partner') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'partner'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Company/Org Name</th>
                                        <th>Contact Person</th>
                                        <th>Email</th>
                                        <th>State/Country</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($partner as $item)
                                        <tr id="row-partner-{{ $item->id }}">
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td><strong>{{ $item->company_name }}</strong></td>
                                            <td>{{ $item->contact_person }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->state_country }}</td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'partner', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('partner', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 7. VOLUNTEER PANEL -->
            @if($canPanel('panel-volunteer'))
            <div class="dashboard-panel" id="panel-volunteer">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Volunteer Registration Submissions</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'volunteer') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'volunteer'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Full Name</th>
                                        <th>Gender</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>State</th>
                                        <th>Mode</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($volunteer as $item)
                                        <tr>
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td><strong>{{ $item->full_name }}</strong></td>
                                            <td>{{ $item->gender }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->contact_number }}</td>
                                            <td>{{ $item->state_residency }}</td>
                                            <td>{{ $item->preferred_mode }}</td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'volunteer', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('volunteer', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 9. DONATION PAYMENTS PANEL -->
            @if($canPanel('panel-payments'))
            <div class="dashboard-panel" id="panel-payments">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Donation Payments</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.donation-payments') }}" class="btn-admin btn-admin-primary">
                                <i class="fas fa-search"></i> Search &amp; Filter
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('welfare.admin.partials.donation-payments-content', [
                            'donationPayments' => $donationPayments,
                            'donationPaymentMethods' => $donationPaymentMethods,
                            'showFilter' => false,
                        ])
                    </div>
                </div>
            </div>
            @endif

            <!-- 10. CONTACT MESSAGES PANEL -->
            @if($canPanel('panel-contact'))
            <div class="dashboard-panel" id="panel-contact">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Contact Us Messages</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'contact') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'contact'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contact as $item)
                                        <tr>
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y, h:i A') }}</td>
                                            <td><strong>{{ $item->name }}</strong></td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'contact', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('contact', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 10. COMMUNITY AID PANEL -->
            @if($canPanel('panel-aid'))
            <div class="dashboard-panel" id="panel-aid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Community Aid & Assistance Requests</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'aid') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'aid'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>State</th>
                                        <th>Aid Type(s)</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($aid as $item)
                                        <tr id="row-aid-{{ $item->id }}">
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td><strong>{{ $item->full_name }}</strong></td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->state_residency }}</td>
                                            <td>
                                                @if(is_array($item->type_of_aid))
                                                    {{ implode(', ', $item->type_of_aid) }}
                                                @else
                                                    {{ $item->type_of_aid }}
                                                @endif
                                            </td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'aid', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('aid', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 8. MFLS SCHOLARSHIP PANEL -->
            @if($canPanel('panel-mfls'))
            <div class="dashboard-panel" id="panel-mfls">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>MFLS Scholarship Applications</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'mfls') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                            @include('welfare.admin.partials.import-actions', ['type' => 'mfls'])
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Qualification</th>
                                        <th>Partner</th>
                                        <th>Programme</th>
                                        <th>Household Income</th>
                                        <th class="status-col">Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mfls as $item)
                                        <tr id="row-mfls-{{ $item->id }}">
                                            <td>#{{ $item->id }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td><strong>{{ $item->full_name }}</strong></td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->current_qualification }}</td>
                                            <td>{{ $item->partner_institution_name ?: '—' }}</td>
                                            <td>{{ $item->programme_course_applied }}</td>
                                            <td>{{ $item->household_income }}</td>
                                            <td>
                                                @include('welfare.admin.partials.status-select', ['type' => 'mfls', 'item' => $item])
                                            </td>
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('mfls', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" style="text-align: center; color: var(--admin-text-muted);">{{ $emptySubmissionMessage }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($canPanel('panel-mfls-documents'))
            <div class="dashboard-panel" id="panel-mfls-documents">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>MFLS Partner Programme Documents</h3>
                    </div>
                    <div class="card-body">
                        <p style="margin-bottom: 18px; color: #555; font-size: 14px;">
                            Upload or replace the Excel programme information sheet for each partner institution. Updates appear immediately on the public MFLS page under the <strong>More Info</strong> button.
                        </p>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Partner Institution</th>
                                        <th>Current File</th>
                                        <th>Last Updated</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mflsPartnerDocuments as $partnerDocument)
                                        <tr>
                                            <td><strong>{{ $partnerDocument['name'] }}</strong></td>
                                            <td>
                                                @if($partnerDocument['has_document'])
                                                    {{ $partnerDocument['document']->original_filename }}
                                                @else
                                                    <span style="color: var(--admin-text-muted);">No document uploaded</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($partnerDocument['updated_at'])
                                                    {{ $partnerDocument['updated_at']->format('d M Y, h:i A') }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td style="text-align: right;">
                                                <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; align-items: center;">
                                                    @if($partnerDocument['has_document'])
                                                        <button
                                                            type="button"
                                                            class="btn-admin btn-admin-primary js-partner-doc-view"
                                                            data-partner-id="{{ $partnerDocument['id'] }}"
                                                            data-partner-name="{{ $partnerDocument['name'] }}"
                                                            data-view-url="{{ route('welfare.admin.mfls.partner-documents.view', $partnerDocument['id']) }}"
                                                            data-download-url="{{ route('welfare.admin.mfls.partner-documents.download', $partnerDocument['id']) }}"
                                                        >
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        <a href="{{ route('welfare.admin.mfls.partner-documents.download', $partnerDocument['id']) }}" class="btn-admin btn-admin-secondary">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('welfare.admin.mfls.partner-documents.upload', $partnerDocument['id']) }}" method="POST" enctype="multipart/form-data" class="admin-import-form" style="justify-content: flex-end; margin: 0;">
                                                        @csrf
                                                        <input type="hidden" name="import_tab" value="panel-mfls-documents">
                                                        <label class="admin-import-file-label">
                                                            <input type="file" name="programme_file" accept=".xlsx,.xls" required class="admin-import-file-input">
                                                            <span class="btn-admin btn-admin-secondary admin-import-file-btn"><i class="fas fa-folder-open"></i> Choose Excel</span>
                                                        </label>
                                                        <button type="submit" class="btn-admin btn-admin-primary">
                                                            <i class="fas fa-upload"></i> Update
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 9. OPTIONS MANAGER PANEL -->
            @if($canPanel('panel-options'))
            <div class="dashboard-panel" id="panel-options">
                <div class="options-grid">
                    <!-- Dropdown types sidebar -->
                    <div class="options-sidebar">
                        <h4 style="font-size: 13.5px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 12px; padding: 0 10px;">Select Dropdown List</h4>
                        @foreach($formTypesMap as $type => $label)
                            <div class="options-type-item {{ $loop->first ? 'active' : '' }}" onclick="switchOptionType('{{ $type }}')" id="type-btn-{{ $type }}">
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Dropdown items display -->
                    <div class="options-content">
                        @foreach($formTypesMap as $type => $label)
                            <div class="option-type-panel {{ $loop->first ? 'active-panel' : '' }}" id="panel-opt-{{ $type }}" style="display: {{ $loop->first ? 'block' : 'none' }};">
                                <div class="dashboard-card" style="margin-bottom: 20px;">
                                    <div class="card-header">
                                        <h3>Manage: {{ $label }}</h3>
                                    </div>
                                    <div class="card-body" style="padding: 20px;">
                                        <table class="admin-table" style="margin-bottom: 25px;">
                                            <thead>
                                                <tr>
                                                    <th>Value</th>
                                                    <th>Sort Order</th>
                                                    <th style="text-align: right; width: 100px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($options->get($type, collect()) as $opt)
                                                    <tr>
                                                        <td>
                                                            <form method="POST" action="{{ route('welfare.admin.options.edit', $opt->id) }}" style="display: flex; gap: 10px;">
                                                                @csrf
                                                                <input type="text" name="option_value" value="{{ $opt->option_value }}" class="admin-input" style="padding: 5px 8px; width: 220px;" required>
                                                        </td>
                                                        <td>
                                                                <input type="number" name="sort_order" value="{{ $opt->sort_order }}" class="admin-input" style="padding: 5px 8px; width: 80px;" required>
                                                        </td>
                                                        <td style="text-align: right; display: flex; gap: 5px;">
                                                                <button type="submit" class="btn-admin btn-admin-secondary" style="padding: 6px 10px;"><i class="fas fa-save"></i></button>
                                                            </form>
                                                            <form method="POST" action="{{ route('welfare.admin.options.delete', $opt->id) }}" onsubmit="return confirm('Are you sure you want to delete this option?')">
                                                                @csrf
                                                                <button type="submit" class="btn-admin btn-admin-danger" style="padding: 6px 10px;"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" style="text-align: center; color: var(--admin-text-muted);">No options found. Add one below.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <!-- Add Form -->
                                        <div style="background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 8px; padding: 20px;">
                                            <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 12px; color: var(--admin-text-dark);">Add New Dropdown Option</h4>
                                            <form method="POST" action="{{ route('welfare.admin.options.add') }}" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                                                @csrf
                                                <input type="hidden" name="form_type" value="{{ $type }}">
                                                <div style="flex-grow: 1; min-width: 200px;">
                                                    <label style="display:block; font-size:12px; font-weight:600; margin-bottom:5px; color:#475569;">Option Value *</label>
                                                    <input type="text" name="option_value" class="admin-input" placeholder="e.g. Health & Safety" required>
                                                </div>
                                                <div style="width: 100px;">
                                                    <label style="display:block; font-size:12px; font-weight:600; margin-bottom:5px; color:#475569;">Sort Order</label>
                                                    <input type="number" name="sort_order" class="admin-input" value="0">
                                                </div>
                                                <button type="submit" class="btn-admin btn-admin-primary" style="padding: 10px 20px;">Add Item</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </main>
</div>

<!-- SUBMISSION DETAIL MODAL -->
<div class="modal-backdrop" id="detail-modal" onclick="closeModal()">
    <div class="modal-window" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modal-title">Submission Details</h3>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="modal-body">
            <!-- Populated by JS -->
            Loading details...
        </div>
        <div class="modal-footer">
            <button class="btn-admin btn-admin-secondary" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>

<!-- MFLS PARTNER DOCUMENT PREVIEW MODAL -->
<div class="modal-backdrop" id="mfls-doc-modal" onclick="closePartnerDocumentModal()">
    <div class="modal-window modal-window-wide" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="mfls-doc-modal-title">Programme Document</h3>
            <button class="modal-close" onclick="closePartnerDocumentModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body mfls-doc-preview-body" id="mfls-doc-modal-body">
            Loading document preview...
        </div>
        <div class="modal-footer" style="justify-content: space-between;">
            <a href="#" id="mfls-doc-download-link" class="btn-admin btn-admin-primary" style="text-decoration: none;">
                <i class="fas fa-download"></i> Download Excel
            </a>
            <button class="btn-admin btn-admin-secondary" onclick="closePartnerDocumentModal()">Close</button>
        </div>
    </div>
</div>

<!-- STATUS CHANGE CONFIRMATION MODAL -->
<div class="modal-backdrop" id="status-confirm-modal" onclick="cancelStatusChange()">
    <div class="modal-window modal-window-confirm" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Status Confirmation</h3>
            <button type="button" class="modal-close" onclick="cancelStatusChange()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="margin: 0; color: var(--admin-text-dark); font-size: 15px; line-height: 1.6;">
                Are you sure you want to change the status for this submission?
            </p>
        </div>
        <div class="modal-footer" style="justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn-admin btn-admin-secondary" onclick="cancelStatusChange()">Cancel</button>
            <button type="button" class="btn-admin btn-admin-primary" onclick="confirmStatusChange()">Confirm</button>
        </div>
    </div>
</div>

<!-- STATUS EMAIL NOTIFICATION MODAL -->
<div class="modal-backdrop" id="status-email-modal" onclick="declineStatusEmailNotification()">
    <div class="modal-window modal-window-confirm" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Email Notification</h3>
            <button type="button" class="modal-close" onclick="declineStatusEmailNotification()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="margin: 0; color: var(--admin-text-dark); font-size: 15px; line-height: 1.6;">
                Would you like to notify the applicant of this status update via email?
            </p>
        </div>
        <div class="modal-footer" style="justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn-admin btn-admin-secondary" onclick="declineStatusEmailNotification()">No</button>
            <button type="button" class="btn-admin btn-admin-primary" id="status-email-confirm-btn" onclick="confirmStatusEmailNotification()">Yes</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const STATUS_LABELS = @json($submissionStatusOptions);

    function statusBadgeClass(status) {
        const legacyMap = { pending: 'received', under_review: 'reviewing', new: 'received' };
        const normalized = legacyMap[status] || status;
        return 'badge badge-' + normalized;
    }

    function statusLabel(status) {
        const legacyMap = { pending: 'received', under_review: 'reviewing', new: 'received' };
        const normalized = legacyMap[status] || status;
        return STATUS_LABELS[normalized] || status.replace(/_/g, ' ');
    }
    // Mobile sidebar
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');

    function openSidebar() {
        document.body.classList.add('sidebar-open');
        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', 'true');
            sidebarToggle.setAttribute('aria-label', 'Close navigation menu');
        }
    }

    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', 'false');
            sidebarToggle.setAttribute('aria-label', 'Open navigation menu');
        }
    }

    function toggleSidebar() {
        if (document.body.classList.contains('sidebar-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', closeSidebar);
    }

    // Tab toggling — only in-page tab links, not external route links
    document.querySelectorAll('.sidebar-link[data-tab]').forEach(link => {
        link.addEventListener('click', function () {
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            const tabId = this.getAttribute('data-tab');
            navigateToDashboardTab(tabId);
            closeSidebar();
        });
    });

    function switchTab(tabId) {
        document.querySelectorAll('.dashboard-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        const activePanel = document.getElementById(tabId);
        if (!activePanel) {
            return;
        }

        activePanel.classList.add('active');

        const statusFilterCard = document.getElementById('submissions-status-filter-card');
        const statusFilterTabField = document.getElementById('status-filter-admin-tab');
        const hideStatusFilterOn = ['panel-overview', 'panel-options', 'panel-mfls-documents', 'panel-payments'];

        if (statusFilterCard) {
            statusFilterCard.style.display = hideStatusFilterOn.includes(tabId) ? 'none' : '';
        }

        if (statusFilterTabField) {
            statusFilterTabField.value = tabId;
        }

        document.querySelectorAll('#submissions-status-filter-form [data-filter-panels]').forEach(function (field) {
            const panels = (field.getAttribute('data-filter-panels') || '').split(/\s+/).filter(Boolean);
            const visible = panels.includes(tabId);
            field.style.display = visible ? '' : 'none';
            field.querySelectorAll('input, select, textarea').forEach(function (input) {
                input.disabled = !visible;
            });
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });

        const menuEl = document.querySelector(`.sidebar-link[data-tab="${tabId}"]`);
        if (menuEl) {
            document.getElementById('top-nav-title').textContent = menuEl.textContent.trim();
        }

        document.querySelectorAll('.sidebar-link').forEach(l => {
            if (l.getAttribute('data-tab') === tabId) {
                l.classList.add('active');
            } else {
                l.classList.remove('active');
            }
        });

        const resetLink = document.getElementById('submissions-status-reset-link');
        if (resetLink) {
            resetLink.href = `${window.location.pathname}?admin_tab=${encodeURIComponent(tabId)}`;
        }
    }

    function navigateToDashboardTab(tabId) {
        const params = new URLSearchParams(window.location.search);
        const currentTab = params.get('admin_tab') || 'panel-overview';
        const filterKeys = [
            'submission_status',
            'filter_q',
            'filter_state',
            'filter_date_from',
            'filter_date_to',
            'filter_partner',
            'filter_programme',
            'filter_qualification',
            'filter_household_income',
            'filter_gender',
            'filter_mode',
            'filter_entity_type',
            'filter_ros',
            'filter_aid_type',
        ];
        const hasFilters = filterKeys.some(function (key) {
            return params.has(key) && params.get(key) !== '';
        });

        if (currentTab === tabId && !hasFilters) {
            switchTab(tabId);
            return;
        }

        window.location.href = `${window.location.pathname}?admin_tab=${encodeURIComponent(tabId)}`;
    }

    // Switch Option types
    function switchOptionType(type) {
        document.querySelectorAll('.options-type-item').forEach(item => {
            item.classList.remove('active');
        });
        document.getElementById(`type-btn-${type}`).classList.add('active');

        document.querySelectorAll('.option-type-panel').forEach(panel => {
            panel.style.display = 'none';
        });
        document.getElementById(`panel-opt-${type}`).style.display = 'block';
    }

    // AJAX Details view
    function viewDetail(type, id) {
        const modal = document.getElementById('detail-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');
        
        modalBody.innerHTML = '<div style="padding: 20px; text-align: center;"><i class="fas fa-spinner fa-spin fa-2x" style="color: var(--admin-primary)"></i><p style="margin-top:10px;">Loading details...</p></div>';
        modalTitle.textContent = type === 'donation'
            ? `Donation Payment Details (#${id})`
            : `${type.toUpperCase()} Submission details (#${id})`;
        
        modal.classList.add('open');

        // Fetch detail using fetch api
        fetch(`{{ url('/admin/submissions') }}/${type}/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response not ok');
                return response.json();
            })
            .then(data => {
                let html = '<div class="detail-grid">';
                
                // Format functions
                const escapeHtml = (text) => {
                    if (text === null || text === undefined) return '';
                    return String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
                };

                const formatDate = (dateStr) => {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                };

                const isFilePath = (str) => {
                    return typeof str === 'string' && (str.startsWith('documents/') || str.match(/\.(pdf|jpg|jpeg|png|doc|docx|zip|ppt|pptx)$/i));
                };

                const getFileLinkHtml = (filePath) => {
                    if (!filePath || filePath === '-') return '-';
                    if (isFilePath(filePath)) {
                        let url = `{{ asset('storage') }}/${filePath}`;
                        let fileName = filePath.substring(filePath.lastIndexOf('/') + 1);
                        return `<a href="${url}" target="_blank" class="btn-admin btn-admin-secondary" style="display:inline-flex; align-items:center; gap:6px; text-decoration:none; padding: 4px 8px; font-size: 12.5px; border-radius: 4px;"><i class="fas fa-external-link-alt"></i> View File (${escapeHtml(fileName)})</a>`;
                    }
                    return escapeHtml(filePath);
                };

                const booleanFields = new Set([
                    'applied_to_university',
                    'received_offer_letter',
                    'declaration_confirmed',
                    'contact_consent',
                    'is_registered_ros',
                    'has_served_before',
                    'has_collaborated_before',
                    'has_volunteered_before',
                    'received_aid_before',
                    'currently_studying',
                    'receiving_other_scholarship',
                ]);

                const formatBooleanDisplay = (value) => {
                    if (value === true || value === 1 || value === '1') {
                        return 'Yes';
                    }
                    if (value === false || value === 0 || value === '0') {
                        return 'No';
                    }
                    return null;
                };

                // Add standard elements
                html += `<div class="detail-label">Date Submitted</div><div class="detail-value">${formatDate(data.created_at)}</div>`;

                for (let key in data) {
                    if (['id', 'created_at', 'updated_at', 'status'].includes(key)) continue;

                    let label = key.replace(/_/g, ' ').toUpperCase();
                    let val = data[key];

                    if (type === 'donation' && key === 'payment_payload' && val && typeof val === 'object') {
                        label = 'GATEWAY RESPONSE';
                        val = '<pre style="margin:0; white-space:pre-wrap; font-size:12px;">' + escapeHtml(JSON.stringify(val, null, 2)) + '</pre>';
                        html += `<div class="detail-label">${label}</div><div class="detail-value">${val}</div>`;
                        continue;
                    }

                    if (val === null || val === undefined) {
                        val = '-';
                    } else if (booleanFields.has(key)) {
                        val = formatBooleanDisplay(val) ?? '-';
                    } else if (typeof val === 'boolean') {
                        val = val ? 'Yes' : 'No';
                    } else if (['registration_certificate', 'committee_members', 'academic_transcript', 'offer_letter', 'proof_of_government_assistance', 'recommendation_letter'].includes(key)) {
                        val = getFileLinkHtml(val);
                    } else if (key === 'supporting_documents' || key === 'relevant_certificates' || key === 'proof_of_income') {
                        if (Array.isArray(val)) {
                            let linksHtml = '<div style="display:flex; flex-direction:column; gap:6px;">';
                            val.forEach(file => {
                                linksHtml += `<div>${getFileLinkHtml(file)}</div>`;
                            });
                            linksHtml += '</div>';
                            val = linksHtml;
                        } else if (typeof val === 'string' && val !== '-' && val !== '') {
                            let parsed = null;
                            try {
                                parsed = JSON.parse(val);
                            } catch(e) {}
                            
                            if (Array.isArray(parsed)) {
                                let linksHtml = '<div style="display:flex; flex-direction:column; gap:6px;">';
                                parsed.forEach(file => {
                                    linksHtml += `<div>${getFileLinkHtml(file)}</div>`;
                                });
                                linksHtml += '</div>';
                                val = linksHtml;
                            } else if (val.includes(',')) {
                                let files = val.split(',').map(s => s.trim());
                                let linksHtml = '<div style="display:flex; flex-direction:column; gap:6px;">';
                                files.forEach(file => {
                                    linksHtml += `<div>${getFileLinkHtml(file)}</div>`;
                                });
                                linksHtml += '</div>';
                                val = linksHtml;
                            } else {
                                val = getFileLinkHtml(val);
                            }
                        } else {
                            val = '-';
                        }
                    } else if (typeof val === 'object') {
                        // Key Bearers formatting
                        if (key === 'key_office_bearers') {
                            let bearersHtml = '<div style="display:flex; flex-direction:column; gap:8px;">';
                            for (let role in val) {
                                bearersHtml += `<div><strong>${role.toUpperCase()}:</strong> ${escapeHtml([val[role].salutation, val[role].name].filter(Boolean).join(' '))} (${escapeHtml(val[role].nric || 'N/A')} / ${escapeHtml(val[role].email)} / ${escapeHtml(val[role].phone)})</div>`;
                            }
                            bearersHtml += '</div>';
                            val = bearersHtml;
                        } else if (key === 'sibling_information' && Array.isArray(val)) {
                            let siblingsHtml = '<div style="display:flex; flex-direction:column; gap:10px;">';
                            val.forEach((sibling, index) => {
                                siblingsHtml += `<div style="padding:10px 12px; background:#f7f9f8; border:1px solid #e6ece8; border-radius:8px;">`;
                                siblingsHtml += `<div><strong>Sibling ${index + 1}</strong></div>`;
                                siblingsHtml += `<div><strong>Name:</strong> ${escapeHtml(sibling.name || '-')}</div>`;
                                siblingsHtml += `<div><strong>Age:</strong> ${escapeHtml(sibling.age ?? '-')}</div>`;
                                siblingsHtml += `<div><strong>Status:</strong> ${escapeHtml(sibling.status || '-')}</div>`;
                                if (sibling.status === 'Studying') {
                                    siblingsHtml += `<div><strong>Programme:</strong> ${escapeHtml(sibling.program || '-')}</div>`;
                                    siblingsHtml += `<div><strong>University:</strong> ${escapeHtml(sibling.university || '-')}</div>`;
                                } else if (sibling.status === 'Working') {
                                    siblingsHtml += `<div><strong>Profession:</strong> ${escapeHtml(sibling.profession || '-')}</div>`;
                                }
                                siblingsHtml += `</div>`;
                            });
                            siblingsHtml += '</div>';
                            val = siblingsHtml;
                        } else {
                            val = Array.isArray(val) ? val.join(', ') : JSON.stringify(val);
                        }
                    } else {
                        val = escapeHtml(val).replace(/\n/g, '<br>');
                    }

                    html += `<div class="detail-label">${label}</div><div class="detail-value">${val}</div>`;
                }

                if (data.status && type !== 'donation') {
                    html += `<div class="detail-label">APPLICATION STATUS</div><div class="detail-value" style="font-weight:700;"><span class="${statusBadgeClass(data.status)}">${statusLabel(data.status)}</span></div>`;
                }

                html += '</div>';
                modalBody.innerHTML = html;
            })
            .catch(error => {
                modalBody.innerHTML = `<div class="alert-admin alert-admin-error" style="margin-bottom:0;"><i class="fas fa-exclamation-triangle"></i> Failed to load details: ${error.message}</div>`;
            });
    }

    function closeModal() {
        document.getElementById('detail-modal').classList.remove('open');
    }

    function closePartnerDocumentModal() {
        const modal = document.getElementById('mfls-doc-modal');
        const modalBody = document.getElementById('mfls-doc-modal-body');
        if (!modal) return;
        modal.classList.remove('open');
        if (modalBody) {
            modalBody.innerHTML = '';
        }
    }

    window.viewPartnerDocument = function (viewUrl, downloadUrl, partnerName) {
        const modal = document.getElementById('mfls-doc-modal');
        const modalBody = document.getElementById('mfls-doc-modal-body');
        const modalTitle = document.getElementById('mfls-doc-modal-title');
        const downloadLink = document.getElementById('mfls-doc-download-link');

        if (!modal || !modalBody || !viewUrl) {
            return;
        }

        modalTitle.textContent = partnerName + ' Programme Document';
        downloadLink.href = downloadUrl || '#';
        modalBody.innerHTML = '<iframe class="mfls-programme-iframe" src="' + viewUrl + '"></iframe>';
        modal.classList.add('open');
    };

    document.querySelectorAll('.js-partner-doc-view').forEach(function (button) {
        button.addEventListener('click', function () {
            viewPartnerDocument(
                button.getAttribute('data-view-url'),
                button.getAttribute('data-download-url'),
                button.getAttribute('data-partner-name') || 'Partner'
            );
        });
    });

    const submissionsStatusFilterForm = document.getElementById('submissions-status-filter-form');
    if (submissionsStatusFilterForm) {
        submissionsStatusFilterForm.addEventListener('submit', function () {
            const tabField = document.getElementById('status-filter-admin-tab');
            const activePanel = document.querySelector('.dashboard-panel.active');
            if (tabField && activePanel) {
                tabField.value = activePanel.id;
            }
        });
    }

    function reloadIfStatusFilterActive() {
        const params = new URLSearchParams(window.location.search);
        const filterKeys = [
            'submission_status',
            'filter_q',
            'filter_state',
            'filter_date_from',
            'filter_date_to',
            'filter_partner',
            'filter_programme',
            'filter_qualification',
            'filter_household_income',
            'filter_gender',
            'filter_mode',
            'filter_entity_type',
            'filter_ros',
            'filter_aid_type',
        ];
        const hasFilters = filterKeys.some(function (key) {
            return params.has(key) && params.get(key) !== '';
        });
        if (hasFilters) {
            window.location.reload();
        }
    }

    function removeRowIfStatusMismatch(select, status) {
        const activeFilter = new URLSearchParams(window.location.search).get('submission_status');
        if (!activeFilter || status === activeFilter) {
            return;
        }

        const row = select.closest('tr');
        if (!row) {
            return;
        }

        row.remove();

        const tbody = row.parentElement;
        if (tbody && tbody.querySelectorAll('tr').length === 0) {
            const columnCount = tbody.closest('table')?.querySelectorAll('thead th').length || 1;
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="${columnCount}" style="text-align: center; color: var(--admin-text-muted);">No submissions found with the active filters.</td>`;
            tbody.appendChild(emptyRow);
        }
    }

    let pendingStatusChange = null;
    let pendingEmailNotification = null;

    function handleStatusChange(event, type, id) {
        const select = event.target;
        const newStatus = select.value;
        const previousStatus = select.dataset.originalValue || newStatus;

        if (newStatus === previousStatus) {
            return;
        }

        select.value = previousStatus;

        pendingStatusChange = { select, type, id, newStatus, previousStatus };
        document.getElementById('status-confirm-modal').classList.add('open');
    }

    function cancelStatusChange() {
        document.getElementById('status-confirm-modal').classList.remove('open');
        pendingStatusChange = null;
    }

    function confirmStatusChange() {
        if (!pendingStatusChange) {
            return;
        }

        const { select, type, id, newStatus } = pendingStatusChange;
        cancelStatusChange();
        applyStatusUpdate(select, type, id, newStatus);
    }

    function applyStatusUpdate(select, type, id, status) {
        const previousStatus = select.dataset.originalValue || status;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        select.disabled = true;

        fetch(`{{ url('/admin/submissions') }}/${type}/${id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => {
            if (!response.ok) throw new Error('Status update failed');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                select.value = data.status;
                select.dataset.originalValue = data.status;
                pendingEmailNotification = { type, id, status: data.status, label: data.label };
                document.getElementById('status-email-modal').classList.add('open');
            }
        })
        .catch(error => {
            select.value = previousStatus;
            alert(`Error updating status: ${error.message}`);
        })
        .finally(() => {
            select.disabled = false;
        });
    }

    function declineStatusEmailNotification() {
        document.getElementById('status-email-modal').classList.remove('open');

        if (pendingEmailNotification) {
            const select = document.querySelector(`#status-${pendingEmailNotification.type}-${pendingEmailNotification.id}`);
            if (select) {
                removeRowIfStatusMismatch(select, pendingEmailNotification.status);
            }
        }

        pendingEmailNotification = null;
    }

    function confirmStatusEmailNotification() {
        if (!pendingEmailNotification) {
            return;
        }

        const { type, id } = pendingEmailNotification;
        const confirmBtn = document.getElementById('status-email-confirm-btn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (confirmBtn) {
            confirmBtn.disabled = true;
        }

        fetch(`{{ url('/admin/submissions') }}/${type}/${id}/status/notify`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({})
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) {
                throw new Error(data.error || 'Failed to send notification email');
            }
            declineStatusEmailNotification();
        })
        .catch(error => {
            alert(`Error sending notification email: ${error.message}`);
        })
        .finally(() => {
            if (confirmBtn) {
                confirmBtn.disabled = false;
            }
        });
    }

    // Close modal or sidebar on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (document.getElementById('status-email-modal').classList.contains('open')) {
                declineStatusEmailNotification();
            } else if (document.getElementById('status-confirm-modal').classList.contains('open')) {
                cancelStatusChange();
            } else if (document.body.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                closeModal();
                closePartnerDocumentModal();
            }
        }
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });

    (function restoreAdminTab() {
        const hashTab = window.location.hash.replace(/^#/, '');
        if (hashTab && document.getElementById(hashTab)) {
            switchTab(hashTab);
            return;
        }

        const queryTab = new URLSearchParams(window.location.search).get('admin_tab');
        if (queryTab && document.getElementById(queryTab)) {
            switchTab(queryTab);
            return;
        }

        @if(session('import_tab'))
        switchTab(@json(session('import_tab')));
        @elseif(session('admin_tab'))
        switchTab(@json(session('admin_tab')));
        @else
        switchTab(document.getElementById('status-filter-admin-tab')?.value || 'panel-overview');
        @endif
    })();

    document.querySelectorAll('.admin-import-file-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const label = this.closest('.admin-import-file-label');
            const button = label ? label.querySelector('.admin-import-file-btn') : null;
            if (!button) return;

            if (this.files && this.files.length > 0) {
                button.innerHTML = '<i class="fas fa-file-excel"></i> ' + this.files[0].name;
            }
        });
    });

    // Status chips: tap to show labels on touch devices (hover alone does not work on mobile)
    (function initStatStatusChipTooltips() {
        const chips = document.querySelectorAll('.stat-status-chip');
        if (!chips.length) return;

        function closeAllChips(except) {
            chips.forEach(function(chip) {
                if (chip !== except) {
                    chip.classList.remove('is-open');
                }
            });
        }

        chips.forEach(function(chip) {
            chip.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                const willOpen = !chip.classList.contains('is-open');
                closeAllChips(chip);
                chip.classList.toggle('is-open', willOpen);
            });
        });

        document.addEventListener('click', function() {
            closeAllChips();
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAllChips();
            }
        });
    })();
</script>
@endpush
@endsection
