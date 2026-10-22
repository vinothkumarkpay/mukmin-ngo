@extends('welfare.layouts.admin')

@section('title', 'Edit registration #' . $registration->id . ' - Sisterhood Collective')

@section('body')
<style>
.sisterhood-fields .form-group { margin-bottom: 18px; }
.sisterhood-fields .choice-list { display: grid; gap: 8px; margin-top: 8px; }
.sisterhood-fields .choice-list label { display: flex; align-items: flex-start; gap: 10px; font-weight: 500; line-height: 1.45; }
.sisterhood-fields .choice-list input { margin-top: 3px; }
.sisterhood-fields .field-hint { display: block; margin-top: 6px; color: var(--admin-text-muted); font-size: 13px; }
.sisterhood-fields .field-error { display: block; margin-top: 6px; color: #b91c1c; font-size: 13px; }
.sisterhood-fields .declaration-box { background: #fdf6f4; border-left: 4px solid #d43c18; padding: 16px; }
</style>
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'sisterhood',
        'activeTab' => 'panel-sisterhood',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Edit registration #' . $registration->id])

        <div class="content-body">
            @if($errors->any())
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 8px 0 0 18px; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Edit {{ $registration->full_name }}</h3>
                    <div class="card-actions">
                        <a href="{{ route('welfare.admin.sisterhood.show', $registration) }}" class="btn-admin btn-admin-secondary">Cancel</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('welfare.admin.sisterhood.update', $registration) }}" class="admin-form-grid sisterhood-fields">
                        @csrf
                        @method('PUT')

                        @include('welfare.partials.sisterhood-fields', ['registration' => $registration])

                        <div class="form-group">
                            <label for="payment_status">Payment status</label>
                            <select id="payment_status" name="payment_status" class="form-control" required>
                                @foreach(['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('payment_status', $registration->payment_status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <span class="field-hint">The registration is successful only when payment is Paid. Use this when a participant has paid but the gateway confirmation needs to be recorded manually.</span>
                        </div>

                        <div class="form-group">
                            <label>Registration fee</label>
                            <input type="text" class="form-control" value="RM {{ number_format($registration->amount, 2) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="payment_reference">Payment reference</label>
                            <input type="text" id="payment_reference" name="payment_reference" class="form-control" maxlength="100" value="{{ old('payment_reference', $registration->payment_reference) }}">
                        </div>

                        <div class="form-group">
                            <label>Order ID</label>
                            <input type="text" class="form-control" value="{{ $registration->order_id }}" readonly>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="admin_notes">Admin notes</label>
                            <textarea id="admin_notes" name="admin_notes" class="form-control" rows="4" maxlength="2000">{{ old('admin_notes', $registration->admin_notes) }}</textarea>
                        </div>

                        <div class="form-group form-group-full">
                            <button type="submit" class="btn-admin btn-admin-primary">Save registration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
    @include('welfare.admin.partials.sidebar-toggle-script')
@endpush
