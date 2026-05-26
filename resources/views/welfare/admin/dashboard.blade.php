@extends('welfare.layouts.admin')

@section('title', 'Admin Dashboard - MUKMIN Portal')

@section('body')
<div class="dashboard-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-logo"><i class="fas fa-mosque"></i> mukmin <span>admin</span></h1>
        </div>
        <ul class="sidebar-menu">
            <li>
                <div class="sidebar-link active" data-tab="panel-overview">
                    <i class="fas fa-chart-pie"></i> Overview
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-feedback">
                    <i class="fas fa-comment-alt"></i> Feedback & Ideas
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-ordinary">
                    <i class="fas fa-building"></i> Ordinary Members
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-friends">
                    <i class="fas fa-users"></i> Friends of MUKMIN
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-mentor">
                    <i class="fas fa-user-tie"></i> Mentors
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-partner">
                    <i class="fas fa-handshake"></i> Partnerships
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-volunteer">
                    <i class="fas fa-hands-helping"></i> Volunteers
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-aid">
                    <i class="fas fa-hand-holding-medical"></i> Community Aid Requests
                </div>
            </li>
            <li>
                <div class="sidebar-link" data-tab="panel-contact">
                    <i class="fas fa-envelope"></i> Contact Messages
                </div>
            </li>
            <li style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 15px; padding-top: 15px;">
                <div class="sidebar-link" data-tab="panel-options">
                    <i class="fas fa-sliders-h"></i> Options Manager
                </div>
            </li>
        </ul>
        <div class="sidebar-footer">
            <span>v1.0.0</span>
            <a href="{{ route('welfare.admin.logout') }}" class="logout-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </aside>

    <!-- MAIN PANEL -->
    <main class="main-content">
        <!-- TOP NAV -->
        <header class="top-nav">
            <h2 id="top-nav-title">Dashboard Overview</h2>
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="user-profile">
                    <i class="fas fa-user-shield"></i>
                    <span>System Administrator</span>
                </div>
                <a href="{{ route('welfare.admin.logout') }}" class="btn-admin btn-admin-secondary" style="padding: 8px 14px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;" title="Logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </header>

        <!-- CONTENT BODY -->
        <div class="content-body">
            @if(session('success'))
                <div class="alert-admin alert-admin-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- 1. OVERVIEW PANEL -->
            <div class="dashboard-panel active" id="panel-overview">
                <div class="stats-grid">
                    <div class="stat-card" onclick="switchTab('panel-feedback')">
                        <div class="stat-icon"><i class="fas fa-comment-dots"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['feedback'] }}</h3>
                            <p>Feedback & Ideas</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchTab('panel-ordinary')">
                        <div class="stat-icon"><i class="fas fa-building"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['ordinary'] }}</h3>
                            <p>Ordinary Members</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchTab('panel-friends')">
                        <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['friends'] }}</h3>
                            <p>Friends of MUKMIN</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchTab('panel-mentor')">
                        <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['mentor'] }}</h3>
                            <p>Mentors Registered</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchTab('panel-partner')">
                        <div class="stat-icon"><i class="fas fa-handshake-angle"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['partner'] }}</h3>
                            <p>Partnerships</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchTab('panel-volunteer')">
                        <div class="stat-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['volunteer'] }}</h3>
                            <p>Volunteers Registered</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchTab('panel-aid')">
                        <div class="stat-icon"><i class="fas fa-hand-holding-medical"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['aid'] }}</h3>
                            <p>Community Aid Requests</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="switchTab('panel-contact')">
                        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                        <div class="stat-info">
                            <h3>{{ $stats['contact'] }}</h3>
                            <p>Contact Messages</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Welcome to the MUKMIN Administrative Portal</h3>
                    </div>
                    <div class="card-body">
                        <p style="margin-bottom: 15px;">Use the left navigation sidebar to view individual form submissions, download CSV reports, approve/reject applications, and configure form dropdown option items dynamically.</p>
                        <div class="important-notes" style="margin-bottom: 0;">
                            <h4>SYSTEM NOTE</h4>
                            <p style="font-size: 13.5px; color: #555;">All data changes (like changing approval status, deleting dropdown lists, or submitting new forms) will take effect instantly in the database.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. FEEDBACK PANEL -->
            <div class="dashboard-panel" id="panel-feedback">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Feedback & Suggestion Submissions</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'feedback') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('feedback', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">No submissions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. ORDINARY MEMBERS PANEL -->
            <div class="dashboard-panel" id="panel-ordinary">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Ordinary Organisation Membership Applications</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'ordinary') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                        <th>Status</th>
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
                                                <span class="badge badge-{{ $item->status }}" id="badge-ordinary-{{ $item->id }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td style="text-align: right; display: flex; gap: 5px; justify-content: flex-end;">
                                                <button onclick="viewDetail('ordinary', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                                <button onclick="updateStatus('ordinary', {{ $item->id }}, 'approved')" class="btn-admin btn-admin-secondary" style="color: #059669;" title="Approve"><i class="fas fa-check"></i></button>
                                                <button onclick="updateStatus('ordinary', {{ $item->id }}, 'rejected')" class="btn-admin btn-admin-danger" style="padding: 8px 12px;" title="Reject"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">No applications found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. FRIENDS PANEL -->
            <div class="dashboard-panel" id="panel-friends">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Friends of MUKMIN Applications</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'friends') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                        <th>Status</th>
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
                                                <span class="badge badge-{{ $item->status }}" id="badge-friends-{{ $item->id }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td style="text-align: right; display: flex; gap: 5px; justify-content: flex-end;">
                                                <button onclick="viewDetail('friends', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                                <button onclick="updateStatus('friends', {{ $item->id }}, 'approved')" class="btn-admin btn-admin-secondary" style="color: #059669;" title="Approve"><i class="fas fa-check"></i></button>
                                                <button onclick="updateStatus('friends', {{ $item->id }}, 'rejected')" class="btn-admin btn-admin-danger" style="padding: 8px 12px;" title="Reject"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">No submissions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. MENTOR PANEL -->
            <div class="dashboard-panel" id="panel-mentor">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Mentor Registration Submissions</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'mentor') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('mentor', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; color: var(--admin-text-muted);">No submissions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. PARTNERSHIPS PANEL -->
            <div class="dashboard-panel" id="panel-partner">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Partnership & Collaboration Proposals</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'partner') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                        <th>Status</th>
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
                                                <span class="badge badge-{{ $item->status }}" id="badge-partner-{{ $item->id }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td style="text-align: right; display: flex; gap: 5px; justify-content: flex-end;">
                                                <button onclick="viewDetail('partner', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                                <button onclick="updateStatus('partner', {{ $item->id }}, 'approved')" class="btn-admin btn-admin-secondary" style="color: #059669;" title="Approve"><i class="fas fa-check"></i></button>
                                                <button onclick="updateStatus('partner', {{ $item->id }}, 'rejected')" class="btn-admin btn-admin-danger" style="padding: 8px 12px;" title="Reject"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; color: var(--admin-text-muted);">No proposals found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. VOLUNTEER PANEL -->
            <div class="dashboard-panel" id="panel-volunteer">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Volunteer Registration Submissions</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'volunteer') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('volunteer', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" style="text-align: center; color: var(--admin-text-muted);">No volunteers found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 9. CONTACT MESSAGES PANEL -->
            <div class="dashboard-panel" id="panel-contact">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Contact Us Messages</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'contact') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                            <td style="text-align: right;">
                                                <button onclick="viewDetail('contact', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center; color: var(--admin-text-muted);">No messages found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 10. COMMUNITY AID PANEL -->
            <div class="dashboard-panel" id="panel-aid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Community Aid & Assistance Requests</h3>
                        <div class="card-actions">
                            <a href="{{ route('welfare.admin.export', 'aid') }}" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
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
                                        <th>Status</th>
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
                                                <span class="badge badge-{{ $item->status }}" id="badge-aid-{{ $item->id }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td style="text-align: right; display: flex; gap: 5px; justify-content: flex-end;">
                                                <button onclick="viewDetail('aid', {{ $item->id }})" class="btn-admin btn-admin-primary">View</button>
                                                <button onclick="updateStatus('aid', {{ $item->id }}, 'approved')" class="btn-admin btn-admin-secondary" style="color: #059669;" title="Approve"><i class="fas fa-check"></i></button>
                                                <button onclick="updateStatus('aid', {{ $item->id }}, 'rejected')" class="btn-admin btn-admin-danger" style="padding: 8px 12px;" title="Reject"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; color: var(--admin-text-muted);">No aid requests found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. OPTIONS MANAGER PANEL -->
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

@push('scripts')
<script>
    // Tab toggling
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', function () {
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    function switchTab(tabId) {
        document.querySelectorAll('.dashboard-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        const activePanel = document.getElementById(tabId);
        activePanel.classList.add('active');

        // Scroll to top of view
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Update nav bar title
        const menuText = document.querySelector(`.sidebar-link[data-tab="${tabId}"]`).textContent.trim();
        document.getElementById('top-nav-title').textContent = menuText;

        // Set sidebar item active in case switched programmatically from stats card
        document.querySelectorAll('.sidebar-link').forEach(l => {
            if (l.getAttribute('data-tab') === tabId) {
                l.classList.add('active');
            } else {
                l.classList.remove('active');
            }
        });
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
        modalTitle.textContent = `${type.toUpperCase()} Submission details (#${id})`;
        
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

                // Add standard elements
                html += `<div class="detail-label">Date Submitted</div><div class="detail-value">${formatDate(data.created_at)}</div>`;

                for (let key in data) {
                    if (['id', 'created_at', 'updated_at', 'status'].includes(key)) continue;

                    let label = key.replace(/_/g, ' ').toUpperCase();
                    let val = data[key];

                    if (val === null || val === undefined) {
                        val = '-';
                    } else if (typeof val === 'boolean') {
                        val = val ? 'Yes' : 'No';
                    } else if (key === 'registration_certificate' || key === 'committee_members') {
                        val = getFileLinkHtml(val);
                    } else if (key === 'supporting_documents') {
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
                                bearersHtml += `<div><strong>${role.toUpperCase()}:</strong> ${escapeHtml(val[role].name)} (${escapeHtml(val[role].email)} / ${escapeHtml(val[role].phone)})</div>`;
                            }
                            bearersHtml += '</div>';
                            val = bearersHtml;
                        } else {
                            val = Array.isArray(val) ? val.join(', ') : JSON.stringify(val);
                        }
                    } else {
                        val = escapeHtml(val).replace(/\n/g, '<br>');
                    }

                    html += `<div class="detail-label">${label}</div><div class="detail-value">${val}</div>`;
                }

                if (data.status) {
                    html += `<div class="detail-label">APPLICATION STATUS</div><div class="detail-value" style="font-weight:700;"><span class="badge badge-${data.status}">${data.status}</span></div>`;
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

    // AJAX status updates
    function updateStatus(type, id, status) {
        if (!confirm(`Are you sure you want to set status of submission #${id} to ${status.toUpperCase()}?`)) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                // Update badge text and class
                const badge = document.getElementById(`badge-${type}-${id}`);
                if (badge) {
                    badge.textContent = status;
                    badge.className = `badge badge-${status}`;
                }
                alert(`Status successfully updated to ${status.toUpperCase()}!`);
            }
        })
        .catch(error => {
            alert(`Error updating status: ${error.message}`);
        });
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush
@endsection
