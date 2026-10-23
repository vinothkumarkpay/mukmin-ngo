@extends('welfare.layouts.admin')

@section('title', 'Sisterhood Collective - MUKMIN Admin')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'sisterhood',
        'activeTab' => 'panel-sisterhood',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Sisterhood Collective'])

        <div class="content-body">
            <div class="stats-grid" style="margin-bottom: 20px;">
                <div class="stat-card">
                    <div class="stat-card-main">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-info">
                            <h3>{{ $paidCount }}</h3>
                            <p>Paid registrations</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-main">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-info">
                            <h3>{{ $pendingCount }}</h3>
                            <p>Pending payment</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-main">
                        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                        <div class="stat-info">
                            <h3>{{ $failedCount }}</h3>
                            <p>Failed payment</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-main">
                        <div class="stat-icon"><i class="fas fa-qrcode"></i></div>
                        <div class="stat-info">
                            <h3>{{ $presentCount }}</h3>
                            <p>Present</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>The Sisterhood Collective 2026</h3>
                    <div class="card-actions">
                        <a href="{{ route('welfare.admin.sisterhood.attendance') }}" class="btn-admin btn-admin-primary">Scan attendance</a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $activeFilters = collect([
                            'search' => request('search'),
                            'payment_status' => request('payment_status'),
                        ])->filter(fn ($value) => filled($value));
                    @endphp

                    <div class="payments-filter-card">
                        <div class="payments-filter-header">
                            <div class="payments-filter-title">
                                <span class="payments-filter-icon"><i class="fas fa-search"></i></span>
                                <div>
                                    <h4>Search registrations and payments</h4>
                                    <p>Find a participant by name, contact, NRIC, email, order reference, check-in code, or payment status</p>
                                </div>
                            </div>
                            <div class="payments-filter-meta">
                                <span class="payments-result-count">
                                    <i class="fas fa-users"></i>
                                    {{ $registrations->total() }} {{ \Illuminate\Support\Str::plural('registration', $registrations->total()) }}
                                </span>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('welfare.admin.sisterhood.index') }}" class="payments-filter-form">
                            <div class="payments-filter-grid">
                                <div class="payments-filter-field">
                                    <label for="filter_search">Name, contact, NRIC, email, or order</label>
                                    <div class="payments-input-wrap">
                                        <i class="fas fa-search"></i>
                                        <input type="text" name="search" id="filter_search" value="{{ request('search') }}" placeholder="Search registrations">
                                    </div>
                                </div>
                            </div>
                            <div class="payments-filter-status-row">
                                <span class="payments-filter-status-label">Payment</span>
                                <div class="payments-status-pills">
                                    <label class="payments-status-pill">
                                        <input type="radio" name="payment_status" value="" @checked(!request('payment_status'))>
                                        <span>All</span>
                                    </label>
                                    <label class="payments-status-pill payments-status-pill--pending">
                                        <input type="radio" name="payment_status" value="pending" @checked(request('payment_status') === 'pending')>
                                        <span>Pending</span>
                                    </label>
                                    <label class="payments-status-pill payments-status-pill--paid">
                                        <input type="radio" name="payment_status" value="paid" @checked(request('payment_status') === 'paid')>
                                        <span>Paid</span>
                                    </label>
                                    <label class="payments-status-pill payments-status-pill--failed">
                                        <input type="radio" name="payment_status" value="failed" @checked(request('payment_status') === 'failed')>
                                        <span>Failed</span>
                                    </label>
                                </div>
                            </div>
                            <div class="payments-filter-footer">
                                @if($activeFilters->isNotEmpty())
                                    <div class="payments-active-filters">
                                        <span class="payments-active-label">Active:</span>
                                        @foreach($activeFilters as $key => $value)
                                            <span class="payments-active-chip">{{ str_replace('_', ' ', ucfirst($key)) }}: {{ $value }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="payments-active-filters payments-active-filters--empty">
                                        <span>No filters applied — showing all registrations</span>
                                    </div>
                                @endif
                                <div class="payments-filter-actions">
                                    <a href="{{ route('welfare.admin.sisterhood.index') }}" class="btn-admin btn-admin-secondary payments-btn-clear">
                                        <i class="fas fa-rotate-left"></i> Reset
                                    </a>
                                    <button type="submit" class="btn-admin btn-admin-primary payments-btn-apply">
                                        <i class="fas fa-sliders"></i> Apply filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Participant</th>
                                    <th>Contact</th>
                                    <th>Order</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Registration</th>
                                    <th>Attendance</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $item)
                                    <tr>
                                        <td>{{ $item->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            <strong>{{ $item->full_name }}</strong><br>
                                            <span style="color: var(--admin-text-muted);">{{ $item->salutation }} · {{ $item->state }}</span>
                                        </td>
                                        <td>
                                            {{ $item->contact_number }}<br>
                                            <span style="color: var(--admin-text-muted);">{{ $item->email }}</span>
                                        </td>
                                        <td><code>{{ $item->order_id }}</code></td>
                                        <td>RM {{ number_format($item->amount, 2) }}</td>
                                        <td><span class="badge badge-{{ $item->payment_status }}">{{ $item->paymentStatusLabel() }}</span></td>
                                        <td>{{ $item->registrationStatusLabel() }}</td>
                                        <td><span class="badge {{ $item->isPresent() ? 'badge-paid' : 'badge-pending' }}">{{ $item->attendanceLabel() }}</span></td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('welfare.admin.sisterhood.show', $item) }}" class="btn-admin btn-admin-secondary">View</a>
                                            @if($canManage)
                                                <a href="{{ route('welfare.admin.sisterhood.edit', $item) }}" class="btn-admin btn-admin-primary">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center; color: var(--admin-text-muted);">No registrations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 18px;">
                        {{ $registrations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
    @include('welfare.admin.partials.sidebar-toggle-script')
@endpush
