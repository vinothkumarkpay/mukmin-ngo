@php
    $activeFilters = collect([
        'donor_name' => request('donor_name'),
        'email' => request('email'),
        'order_id' => request('order_id'),
        'payment_method' => request('payment_method'),
        'payment_status' => request('payment_status'),
    ])->filter(fn ($value) => filled($value));
@endphp

<div class="payments-filter-card">
    <div class="payments-filter-header">
        <div class="payments-filter-title">
            <span class="payments-filter-icon"><i class="fas fa-search"></i></span>
            <div>
                <h4>Search &amp; Filter Payments</h4>
                <p>Refine by donor details, order reference, payment mode, or status</p>
            </div>
        </div>
        <div class="payments-filter-meta">
            <span class="payments-result-count">
                <i class="fas fa-receipt"></i>
                {{ $donationPayments->count() }} {{ Str::plural('result', $donationPayments->count()) }}
            </span>
        </div>
    </div>

    <form method="GET" action="{{ route('welfare.admin.donation-payments') }}" id="donation-payments-filter-form" class="payments-filter-form">
        <div class="payments-filter-grid">
            <div class="payments-filter-field">
                <label for="filter_donor_name">Donor name</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="donor_name" id="filter_donor_name" value="{{ request('donor_name') }}" placeholder="e.g. Ahmad bin Ali" autocomplete="off">
                </div>
            </div>

            <div class="payments-filter-field">
                <label for="filter_email">Email</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="text" name="email" id="filter_email" value="{{ request('email') }}" placeholder="e.g. donor@email.com" autocomplete="off">
                </div>
            </div>

            <div class="payments-filter-field">
                <label for="filter_order_id">Order ID</label>
                <div class="payments-input-wrap">
                    <i class="fas fa-hashtag"></i>
                    <input type="text" name="order_id" id="filter_order_id" value="{{ request('order_id') }}" placeholder="e.g. MUKMIN-... or DEMO-..." autocomplete="off">
                </div>
            </div>

            <div class="payments-filter-field">
                <label for="filter_payment_method">Payment mode</label>
                <div class="payments-input-wrap payments-input-wrap--select">
                    <i class="fas fa-credit-card"></i>
                    <select name="payment_method" id="filter_payment_method">
                        <option value="">All payment modes</option>
                        @foreach($donationPaymentMethods as $method)
                            <option value="{{ $method }}" @selected(request('payment_method') === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="payments-filter-status-row">
            <span class="payments-filter-status-label">Status</span>
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
                        <span class="payments-active-chip">
                            {{ str_replace('_', ' ', ucfirst($key)) }}: {{ $value }}
                        </span>
                    @endforeach
                </div>
            @else
                <div class="payments-active-filters payments-active-filters--empty">
                    <span>No filters applied — showing all payments</span>
                </div>
            @endif

            <div class="payments-filter-actions">
                <a href="{{ route('welfare.admin.donation-payments') }}" class="btn-admin btn-admin-secondary payments-btn-clear">
                    <i class="fas fa-rotate-left"></i> Reset
                </a>
                <button type="submit" class="btn-admin btn-admin-primary payments-btn-apply">
                    <i class="fas fa-sliders"></i> Apply filters
                </button>
            </div>
        </div>
    </form>
</div>
