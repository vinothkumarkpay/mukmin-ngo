@extends('welfare.layouts.app')

@section('title', 'The Sisterhood Collective 2026 | Participant Registration')

@section('content')
<style>
.form-page-container { background: #f7f4f8; padding: 60px 0; font-family: 'Roboto', sans-serif; }
.form-card {
    background: #fff;
    border-radius: 12px;
    padding: 0 0 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    border: 1px solid #eadff0;
    max-width: 860px;
    margin: 0 auto;
    overflow: hidden;
}
.sisterhood-form-banner {
    width: 100%;
    max-width: none;
    margin: 0 0 28px;
    display: block;
}
.form-header,
.fee-note,
.form-alert,
.sisterhood-fields {
    margin-left: 40px;
    margin-right: 40px;
}
.form-header h1 { font-size: 30px; line-height: 1.3; margin: 0 0 16px; color: #1e1e1e; }
.form-tagline {
    text-align: center;
    font-size: 18px;
    letter-spacing: 0.04em;
    color: #333;
    margin: 0 0 22px;
}
.form-copy p {
    color: #333;
    line-height: 1.75;
    margin: 0 0 14px;
    text-align: left;
}
.form-copy p.form-body {
    text-align: justify;
    hyphens: auto;
}
.event-details-title { font-size: 22px; font-weight: 700; color: #1e1e1e; margin: 22px 0 12px; }
.event-details { list-style: none; margin: 0 0 16px; padding: 0; }
.event-details > li { display: flex; align-items: flex-start; gap: 8px; margin: 8px 0; color: #222; line-height: 1.5; }
.event-details > li > span { flex-shrink: 0; width: 1.4em; }
.event-details-sub { list-style: disc; margin: 4px 0 0; padding-left: 1.2em; }
.event-details-sub li { display: list-item; margin: 2px 0; }
.dress-theme-label { margin-bottom: 4px; }
.dress-theme { margin: 0 0 16px; padding-left: 1.4em; }
.fee-note { background: #fdf6f4; border-left: 4px solid #d43c18; padding: 14px 16px; margin: 20px 0 28px; }
.fee-note p { margin: 0 0 10px; }
.fee-note p:last-child { margin-bottom: 0; }
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d2d8d5;
    border-radius: 6px;
    font-size: 14px;
    background: #fcfdfd;
    box-sizing: border-box;
}
.form-control:focus { border-color: #d43c18; outline: none; background: #fff; }
.form-submit {
    display: flex;
    justify-content: center;
    margin-top: 8px;
}
.btn-submit {
    background: #d43c18;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 14px 36px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
}
.form-alert { background: #fff4f4; color: #8a1f1f; border: 1px solid #f0c8c8; border-radius: 8px; padding: 12px 14px; margin-bottom: 18px; }
.sisterhood-fields .form-group { margin-bottom: 22px; }
.sisterhood-fields .choice-list { display: grid; gap: 8px; margin-top: 8px; }
.sisterhood-fields .choice-list label { display: flex; align-items: flex-start; gap: 10px; font-weight: 500; line-height: 1.45; cursor: pointer; }
.sisterhood-fields .choice-list input { margin-top: 3px; flex-shrink: 0; }
.sisterhood-fields .field-hint { display: block; margin-top: 6px; color: #666; font-size: 13px; }
.sisterhood-fields .field-error { display: block; margin-top: 6px; color: #b91c1c; font-size: 13px; }
.sisterhood-fields .declaration-box { background: #fdf6f4; border-left: 4px solid #d43c18; padding: 18px 18px 8px; border-radius: 0 8px 8px 0; }
.policy-box { background: #fdf6f4; border: 1px solid #ead3cb; border-left: 4px solid #d43c18; border-radius: 0 8px 8px 0; padding: 16px 18px; margin: 8px 0 22px; }
.policy-box h2 { margin: 0 0 8px; font-size: 16px; font-weight: 700; color: #1e1e1e; }
.policy-box p { margin: 0; color: #333; line-height: 1.6; text-align: justify; hyphens: auto; }
@media (max-width: 700px) {
    .form-card { padding-bottom: 24px; }
    .form-header,
    .fee-note,
    .form-alert,
    .sisterhood-fields {
        margin-left: 16px;
        margin-right: 16px;
    }
}
</style>

<div class="form-page-container">
    <div class="container">
        <div class="form-card">
            <img class="sisterhood-form-banner" src="{{ asset('welfare/img/sisterhood-collective-banner.jpg') }}?v=2" alt="The Sisterhood Collective. Connect. Empower. Rise Together. Organised by Women and Family Development Bureau.">
            <div class="form-header">
                <h1>The Sisterhood Collective 2026 | Participant Registration</h1>
                <p class="form-tagline">Connect. Empower. Rise Together.</p>
                <div class="form-copy">
                <p><strong>SHE LEADS. GENERATIONS RISE.</strong></p>
                <p>A celebration of sisterhood, empowerment and shared purpose.</p>
                <p class="form-body">Join us for the inaugural edition of The Sisterhood Collective, a meaningful gathering that brings together Indian Muslim women from all walks of life to connect, find inspiration, and grow together.</p>
                <p class="form-body">An opportunity to build new friendships, celebrate women's strengths, exchange ideas and explore how we can collectively make a difference for our families and communities.</p>
                <h2 class="event-details-title">Event Details</h2>
                <ul class="event-details">
                    <li><span aria-hidden="true">📅</span> Date: {{ \App\Support\SisterhoodCollective::EVENT_DATE }}</li>
                    <li><span aria-hidden="true">🕘</span> Time: {{ \App\Support\SisterhoodCollective::EVENT_TIME }}</li>
                    <li><span aria-hidden="true">📍</span> Venue: {{ \App\Support\SisterhoodCollective::EVENT_VENUE }}</li>
                    <li><span aria-hidden="true">🎟️</span> Registration Fee: RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} per person</li>
                    <li>
                        <span aria-hidden="true">👩</span>
                        <div>
                            Open to:
                            <ul class="event-details-sub">
                                <li>Registered Friends of MUKMIN, and</li>
                                <li>Indian Muslim women aged 18 and above</li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <p class="dress-theme-label">Dress Theme:</p>
                <ul class="event-details-sub dress-theme">
                    <li>{{ \App\Support\SisterhoodCollective::EVENT_DRESS }}.</li>
                    <li>Modest attire complemented by Indian Muslim traditional jewellery or cultural accessories.</li>
                </ul>
                <p>Registration closes on 17 November 2026.</p>
                <p class="form-body">Come as you are, bring your stories and leave with new connections, fresh inspiration and a shared sense of purpose.</p>
                <p>Because when women connect, communities grow and generations rise.</p>
                <p><strong>Organised by:<br>{{ \App\Support\SisterhoodCollective::ORGANISER }}</strong></p>
                </div>
            </div>

            @if(session('error'))
                <div class="form-alert">{{ session('error') }}</div>
            @endif

            @if(! $registrationOpen)
                <div class="fee-note">
                    <strong>Registration is closed.</strong>
                    <p style="margin: 8px 0 0;">Registration closed on 17 November 2026.</p>
                </div>
            @elseif($registrationFull)
                <div class="fee-note">
                    <strong>Registration is full.</strong>
                    <p style="margin: 8px 0 0;">{{ \App\Support\SisterhoodCollective::fullMessage() }}</p>
                </div>
            @else
                <div class="fee-note">
                    <p>Upon submitting this form, you will be redirected to the payment gateway to complete the RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} registration fee.</p>
                    <p>Your registration will only be confirmed once payment has been successfully received.</p>
                    <p>Places are limited to {{ \App\Support\SisterhoodCollective::CAPACITY }} participants.</p>
                </div>

                @if($errors->any())
                    <div class="form-alert">Please complete every required field before continuing to payment.</div>
                @endif

                <form method="POST" action="{{ route('welfare.sisterhood.store') }}" class="sisterhood-fields">
                    @csrf
                    @include('welfare.partials.sisterhood-fields', ['registration' => null])
                    <div class="policy-box">
                        <h2>Registration &amp; Cancellation Policy</h2>
                        <p>Registration fees of RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} are non-refundable upon cancellation or non-attendance. Participants are responsible for ensuring their registration details are accurate. Any changes or cancellations must be communicated to the organiser before the registration closing date. MUKMIN reserves the right to make changes to the event arrangements when necessary.</p>
                    </div>
                    @include('welfare.partials.recaptcha')
                    <div class="form-submit">
                        <button type="submit" class="btn-submit">Register</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
