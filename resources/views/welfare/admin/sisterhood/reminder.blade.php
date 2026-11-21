@extends('welfare.layouts.admin')

@section('title', 'Agenda reminder - Sisterhood Collective')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'sisterhood',
        'activeTab' => 'panel-sisterhood',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Agenda reminder email'])

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
            @if($errors->any())
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>The Sisterhood Collective 2026 – Agenda reminder</h3>
                    <div class="card-actions">
                        <a href="{{ route('welfare.admin.sisterhood.index') }}" class="btn-admin btn-admin-secondary">Back to registrations</a>
                    </div>
                </div>
                <div class="card-body">
                    <p style="margin-top: 0;">
                        Send the Sunday reminder with the programme agenda PDF attached.
                        Only successful (paid) registrations with an email address are included.
                        Duplicate emails are sent once.
                    </p>

                    <div class="stats-grid" style="margin: 18px 0 24px;">
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
                                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                                <div class="stat-info">
                                    <h3>{{ $recipientCount }}</h3>
                                    <p>Unique paid emails</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-main">
                                <div class="stat-icon"><i class="fas fa-paperclip"></i></div>
                                <div class="stat-info">
                                    <h3>{{ $agendaAvailable ? 'Ready' : 'Missing' }}</h3>
                                    <p>{{ $agendaFilename }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @unless($agendaAvailable)
                        <div class="alert-admin alert-admin-error" style="margin-bottom: 20px;">
                            <i class="fas fa-exclamation-circle"></i>
                            Agenda PDF not found at <code>public/welfare/docs/sisterhood-agenda.pdf</code>. Upload it before sending.
                        </div>
                    @endunless

                    <div class="reminder-panels">
                        <section class="reminder-panel">
                            <h4>1. Send a test email</h4>
                            <p>Sends the same reminder and agenda attachment to one address first.</p>
                            <form method="POST" action="{{ route('welfare.admin.sisterhood.reminder.test') }}" class="reminder-form">
                                @csrf
                                <label for="test_email">Test email address</label>
                                <div class="reminder-form-row">
                                    <input
                                        type="email"
                                        id="test_email"
                                        name="test_email"
                                        class="form-control"
                                        value="{{ old('test_email') }}"
                                        placeholder="you@example.com"
                                        required
                                        {{ $agendaAvailable ? '' : 'disabled' }}
                                    >
                                    <button type="submit" class="btn-admin btn-admin-primary" {{ $agendaAvailable ? '' : 'disabled' }}>
                                        Send test email
                                    </button>
                                </div>
                            </form>
                        </section>

                        <section class="reminder-panel">
                            <h4>2. Send to all paid participants</h4>
                            <p>
                                Emails <strong>{{ $recipientCount }}</strong> unique paid {{ \Illuminate\Support\Str::plural('participant', $recipientCount) }}
                                with subject
                                <em>See you this Sunday! The Sisterhood Collective 2026 – Programme Agenda Inside</em>
                                and the agenda PDF attached.
                            </p>
                            <form
                                method="POST"
                                action="{{ route('welfare.admin.sisterhood.reminder.send') }}"
                                class="reminder-form"
                                onsubmit="return confirm('Send the agenda reminder to {{ $recipientCount }} paid participants now?');"
                            >
                                @csrf
                                <label class="reminder-confirm">
                                    <input type="checkbox" name="confirm" value="1" required {{ ($agendaAvailable && $recipientCount >= 1) ? '' : 'disabled' }}>
                                    <span>I confirm this should go to all paid Sisterhood participants ({{ $recipientCount }} emails).</span>
                                </label>
                                <button
                                    type="submit"
                                    class="btn-admin btn-admin-primary"
                                    {{ ($agendaAvailable && $recipientCount >= 1) ? '' : 'disabled' }}
                                >
                                    Send to paid participants
                                </button>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<style>
.reminder-panels {
    display: grid;
    gap: 20px;
}
.reminder-panel {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 18px 20px;
    background: #fff;
}
.reminder-panel h4 {
    margin: 0 0 8px;
}
.reminder-panel p {
    margin: 0 0 14px;
    color: #4b5563;
}
.reminder-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
}
.reminder-form-row {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}
.reminder-form-row .form-control {
    flex: 1 1 240px;
    min-width: 220px;
}
.reminder-confirm {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    font-weight: 500;
    margin-bottom: 14px;
}
.reminder-confirm input {
    margin-top: 3px;
}
</style>
@endpush
