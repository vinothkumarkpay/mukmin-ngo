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
            @if(session('error'))
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>{{ $registration->full_name }}</h3>
                    <div class="card-actions">
                        <a href="{{ route('welfare.admin.sisterhood.attendance') }}" class="btn-admin btn-admin-secondary">Scan attendance</a>
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
                        <span class="badge {{ $registration->isPresent() ? 'badge-paid' : 'badge-pending' }}">{{ $registration->attendanceLabel() }}</span>
                    </p>

                    <div style="display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap; margin: 8px 0 28px;">
                        <img src="{{ route('welfare.admin.sisterhood.qr', $registration) }}" alt="Check-in QR code for {{ $registration->full_name }}" width="180" height="180" style="width: 180px; height: 180px; border: 1px solid #e6e6e6; border-radius: 8px; background: #fff;">
                        <div>
                            <p style="margin: 0 0 8px;"><strong>Check-in code:</strong> <code>{{ $registration->check_in_code }}</code></p>
                            <p style="margin: 0 0 8px;">The same code is in the confirmation email. Scanning it marks this participant present.</p>
                            @if($registration->attended_at)
                                <p style="margin: 0 0 12px;">Marked present {{ $registration->attended_at->format('d M Y, h:i A') }}.</p>
                            @endif
                            @if($registration->isPaid())
                                <form method="POST" action="{{ route('welfare.admin.sisterhood.attendance.store') }}">
                                    @csrf
                                    <input type="hidden" name="code" value="{{ $registration->check_in_code }}">
                                    <input type="hidden" name="present" value="{{ $registration->isPresent() ? '0' : '1' }}">
                                    <button type="submit" class="btn-admin btn-admin-primary">{{ $registration->isPresent() ? 'Mark not present' : 'Mark present' }}</button>
                                </form>
                            @else
                                <p style="margin: 0;">Attendance can be marked after payment is confirmed.</p>
                            @endif
                        </div>
                    </div>

                    <div class="detail-grid">
                        @foreach([
                            'Submitted' => $registration->created_at->format('d M Y, h:i A'),
                            'Registration' => $registration->registrationStatusLabel(),
                            'Record status' => $registration->statusLabel(),
                            'Payment status' => $registration->paymentStatusLabel(),
                            'Amount' => 'RM ' . number_format($registration->amount, 2),
                            'Order ID' => $registration->order_id,
                            'Payment reference' => $registration->payment_reference ?: '—',
                            'Paid at' => optional($registration->paid_at)->format('d M Y, h:i A') ?: '—',
                            'Attendance' => $registration->attendanceLabel(),
                            'Attended at' => optional($registration->attended_at)->format('d M Y, h:i A') ?: '—',
                            'Check-in code' => $registration->check_in_code,
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
                            'Unit number' => $registration->unit_number,
                            'Address line 1' => $registration->address_line_1,
                            'Address line 2' => $registration->address_line_2 ?: '—',
                            'Postcode' => $registration->postcode,
                            'State' => $registration->state,
                            'Area of interest' => implode(', ', $registration->areas_of_interest ?? []),
                            'Looking forward to' => implode(', ', $registration->looking_forward ?? []),
                            'Food preference' => $registration->food_preference,
                            'Emergency contact name' => $registration->emergency_contact_name,
                            'Emergency contact number' => $registration->emergency_contact_number,
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
