@extends('welfare.layouts.admin')

@section('title', 'Registration #' . $registration->id . ' - Sisterhood Collective')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'sisterhood',
        'activeTab' => 'panel-sisterhood',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Registration #' . $registration->id])

        <div class="content-body">
            @if(session('success'))
                <div class="alert-admin alert-admin-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>{{ $registration->full_name }}</h3>
                    <div class="card-actions">
                        <a href="{{ route('welfare.admin.sisterhood.index') }}" class="btn-admin btn-admin-secondary">Back to list</a>
                        @if($canManage)
                            <a href="{{ route('welfare.admin.sisterhood.edit', $registration) }}" class="btn-admin btn-admin-primary">Edit</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <p style="margin-top: 0;">
                        <span class="badge badge-{{ $registration->payment_status }}">{{ $registration->paymentStatusLabel() }}</span>
                        Registration {{ strtolower($registration->registrationStatusLabel()) }}
                    </p>

                    <div class="detail-grid">
                        @foreach([
                            'Submitted' => $registration->created_at->format('d M Y, h:i A'),
                            'Registration' => $registration->registrationStatusLabel(),
                            'Payment status' => $registration->paymentStatusLabel(),
                            'Amount' => 'RM ' . number_format($registration->amount, 2),
                            'Order ID' => $registration->order_id,
                            'Payment reference' => $registration->payment_reference ?: '—',
                            'Paid at' => optional($registration->paid_at)->format('d M Y, h:i A') ?: '—',
                            'Payment method' => $registration->payment_method,
                            'Full name' => $registration->full_name,
                            'Salutation' => $registration->salutation,
                            'Preferred name' => $registration->preferred_name,
                            'NRIC / passport' => $registration->nric_passport,
                            'Email' => $registration->email,
                            'WhatsApp' => $registration->contact_number,
                            'Age' => $registration->age,
                            'Profession' => $registration->professionLabel(),
                            'Education' => $registration->educationLabel(),
                            'Address' => $registration->full_address,
                            'State' => $registration->state,
                            'Area of interest' => implode(', ', $registration->areas_of_interest ?? []),
                            'Looking forward to' => implode(', ', $registration->looking_forward ?? []),
                            'Food preference' => $registration->food_preference,
                            'Emergency contact' => $registration->emergency_contact,
                            'Relationship' => $registration->emergency_relationship,
                            'Heard about programme' => $registration->heardFromLabel(),
                            'Declarations' => implode("\n", $registration->declarations ?? []),
                            'Admin notes' => $registration->admin_notes ?: '—',
                        ] as $label => $value)
                            <div class="detail-label">{{ $label }}</div>
                            <div class="detail-value">{!! nl2br(e($value)) !!}</div>
                        @endforeach

                        @if($registration->payment_payload)
                            <div class="detail-label">Gateway response</div>
                            <div class="detail-value"><pre style="margin:0; white-space:pre-wrap; font-size:12px;">{{ json_encode($registration->payment_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre></div>
                        @endif
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
