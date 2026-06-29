@if($showFilter ?? true)
    @include('welfare.admin.partials.payments-filter', [
        'donationPayments' => $donationPayments,
        'donationPaymentMethods' => $donationPaymentMethods,
    ])
@endif

<div class="table-responsive">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Order ID</th>
                <th>Donor Name</th>
                <th>Email</th>
                <th>Amount (RM)</th>
                <th>Payment Mode</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donationPayments as $payment)
                <tr>
                    <td>#{{ $payment->id }}</td>
                    <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                    <td><code>{{ $payment->order_id }}</code></td>
                    <td><strong>{{ $payment->name }}</strong></td>
                    <td>{{ $payment->email }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>
                        <span class="badge badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" onclick="viewDetail('donation', {{ $payment->id }})" class="btn-admin btn-admin-primary">View</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--admin-text-muted);">No payments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
