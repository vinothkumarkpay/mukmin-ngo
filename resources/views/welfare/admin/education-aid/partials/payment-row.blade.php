@php
    $receipts = $receipts ?? collect();
    $removedReceipts = $removedReceipts ?? [];
@endphp
<div class="ea-payment-row" data-index="{{ $index }}">
    <div class="ea-payment-fields">
        <span class="ea-payment-no">Payment <span class="ea-payment-no-value">{{ is_numeric($index) ? $index + 1 : '' }}</span></span>
        @if(! empty($row['id']))
            <input type="hidden" name="payments[{{ $index }}][id]" value="{{ $row['id'] }}">
        @endif
        <div class="ea-payment-field">
            <label class="ea-payment-label">Payment Date</label>
            <input type="date" name="payments[{{ $index }}][payment_date]" class="admin-input"
                   value="{{ $row['payment_date'] ?? '' }}" required {{ (! $canManage) ? 'disabled' : '' }}>
        </div>
        <div class="ea-payment-field">
            <label class="ea-payment-label">Amount (RM, optional)</label>
            <input type="number" step="0.01" min="0" name="payments[{{ $index }}][amount]" class="admin-input ea-payment-amount"
                   value="{{ $row['amount'] ?? '' }}" placeholder="0.00" {{ (! $canManage) ? 'disabled' : '' }}>
        </div>
        @if($canManage)
            <button type="button" class="ea-payment-remove" title="Remove this payment" aria-label="Remove this payment">
                <i class="fas fa-trash-alt"></i>
            </button>
        @endif
    </div>

    <div class="ea-payment-receipts">
        <span class="ea-payment-label">Receipts</span>
        <ul class="ea-receipt-list">
            @foreach($receipts as $receipt)
                @php $isRemoved = in_array($receipt->id, $removedReceipts, true); @endphp
                <li class="ea-receipt {{ $isRemoved ? 'is-removed' : '' }}" data-receipt-id="{{ $receipt->id }}">
                    <i class="fas fa-receipt" aria-hidden="true"></i>
                    <a href="{{ route('welfare.admin.education-aid.receipts.show', ['id' => $submission->id, 'receiptId' => $receipt->id]) }}"
                       target="_blank" rel="noopener">{{ $receipt->display_name }}</a>
                    @if($canManage)
                        <button type="button" class="ea-receipt-toggle" title="{{ $isRemoved ? 'Undo remove' : 'Remove receipt' }}">
                            <i class="fas {{ $isRemoved ? 'fa-undo' : 'fa-times' }}"></i>
                        </button>
                        @if($isRemoved)
                            <input type="hidden" name="remove_receipts[]" value="{{ $receipt->id }}">
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
        @if($receipts->isEmpty() && ! $canManage)
            <span class="ea-receipt-empty">No receipt uploaded.</span>
        @endif
        @if($canManage)
            <div class="ea-receipt-new-list"></div>
            <button type="button" class="ea-add-receipt">
                <i class="fas fa-plus"></i> Add receipt
            </button>
        @endif
    </div>
</div>
