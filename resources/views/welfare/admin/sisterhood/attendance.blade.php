@extends('welfare.layouts.admin')

@section('title', 'Scan attendance - Sisterhood Collective')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'sisterhood',
        'activeTab' => 'panel-sisterhood',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Scan attendance'])

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
                    <h3>The Sisterhood Collective 2026</h3>
                    <div class="card-actions">
                        <a href="{{ route('welfare.admin.sisterhood.index') }}" class="btn-admin btn-admin-secondary">Back to registrations</a>
                    </div>
                </div>
                <div class="card-body">
                    <p style="margin-top: 0;">Point the camera at the QR code from the confirmation email, or type the check-in code. A paid registration is marked present. {{ $presentCount }} of {{ $paidCount }} paid participants are present.</p>

                    <div id="attendance-result" class="attendance-result" hidden></div>

                    <div id="qr-reader" class="qr-reader"></div>
                    <p id="qr-reader-status" class="field-hint"></p>

                    <form id="attendance-form" method="POST" action="{{ route('welfare.admin.sisterhood.attendance.store') }}" class="attendance-manual">
                        @csrf
                        <label for="check-in-code">Check-in code</label>
                        <div class="attendance-manual-row">
                            <input type="text" id="check-in-code" name="code" class="form-control" maxlength="2000" placeholder="Scan or type the code" autocomplete="off" required>
                            <input type="hidden" name="present" value="1">
                            <button type="submit" class="btn-admin btn-admin-primary">Mark present</button>
                        </div>
                    </form>

                    <div class="attendance-log">
                        <h4>This session</h4>
                        <ul id="attendance-log-list">
                            <li class="attendance-log-empty">No codes scanned yet.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<style>
.qr-reader { max-width: 480px; margin: 12px 0 8px; }
.attendance-result {
    margin: 0 0 16px;
    padding: 16px 18px;
    border-radius: 8px;
    border: 1px solid #d1fae5;
    background: #ecfdf5;
}
.attendance-result.is-error { border-color: #fecaca; background: #fef2f2; }
.attendance-result h3 { margin: 0 0 6px; }
.attendance-result p { margin: 0; }
.attendance-manual { max-width: 560px; margin-top: 18px; }
.attendance-manual label { display: block; font-weight: 600; margin-bottom: 6px; }
.attendance-manual-row { display: flex; gap: 8px; align-items: center; }
.attendance-manual-row .form-control { flex: 1; padding: 10px 12px; border: 1px solid #d2d8d5; border-radius: 6px; }
.attendance-log { margin-top: 28px; }
.attendance-log h4 { margin: 0 0 8px; }
.attendance-log ul { list-style: none; margin: 0; padding: 0; }
.attendance-log li { padding: 8px 0; border-bottom: 1px solid #edf2f7; }
.attendance-log-empty { color: var(--admin-text-muted, #718096); }
.field-hint { color: #718096; font-size: 13px; }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('attendance-form');
    var codeInput = document.getElementById('check-in-code');
    var result = document.getElementById('attendance-result');
    var status = document.getElementById('qr-reader-status');
    var log = document.getElementById('attendance-log-list');
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var lastCode = '';
    var lastAt = 0;
    var busy = false;

    function showResult(ok, message, registration) {
        result.hidden = false;
        result.className = 'attendance-result' + (ok ? '' : ' is-error');
        var details = '';
        if (registration) {
            details = '<p>' + registration.preferred_name + ' · ' + registration.order_id + '<br>'
                + registration.email + '<br>'
                + registration.payment + ' · ' + registration.attendance
                + (registration.attended_at ? ' · ' + registration.attended_at : '')
                + '</p><p><a href="' + registration.url + '">Open registration</a></p>';
        }
        result.innerHTML = '<h3>' + message + '</h3>' + details;
    }

    function addLog(message) {
        var empty = log.querySelector('.attendance-log-empty');
        if (empty) {
            empty.remove();
        }
        var item = document.createElement('li');
        item.textContent = message;
        log.prepend(item);
    }

    function submitCode(raw) {
        var code = (raw || '').trim();
        if (!code || busy) {
            return;
        }
        var now = Date.now();
        if (code === lastCode && now - lastAt < 4000) {
            return;
        }
        lastCode = code;
        lastAt = now;
        busy = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ code: code, present: true })
        }).then(function (response) {
            return response.json().then(function (body) {
                return { ok: response.ok, body: body };
            });
        }).then(function (result) {
            var registration = result.body.registration || null;
            showResult(result.ok && result.body.ok, result.body.message || 'Could not mark attendance.', registration);
            addLog(result.body.message || 'Unrecognised code');
            codeInput.value = '';
        }).catch(function () {
            showResult(false, 'Could not reach the attendance check. Try the code again.', null);
        }).finally(function () {
            busy = false;
        });
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        submitCode(codeInput.value);
    });

    if (typeof Html5Qrcode === 'undefined') {
        status.textContent = 'The camera scanner did not load. Type the check-in code instead.';
        return;
    }

    var scanner = new Html5Qrcode('qr-reader');
    scanner.start(
        { facingMode: 'environment' },
        { fps: 8, qrbox: { width: 240, height: 240 } },
        function (decodedText) { submitCode(decodedText); },
        function () {}
    ).then(function () {
        status.textContent = 'Camera is ready. Hold the QR code inside the frame.';
    }).catch(function () {
        status.textContent = 'The camera is unavailable on this connection. Type the check-in code from the email or the registration page. A secure https address is required for the camera.';
    });
});
</script>
@endpush
