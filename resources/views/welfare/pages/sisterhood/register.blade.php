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
.event-details li { display: flex; align-items: flex-start; gap: 8px; margin: 8px 0; color: #222; line-height: 1.5; }
.event-details li span { flex-shrink: 0; width: 1.4em; }
.fee-note { background: #fdf6f4; border-left: 4px solid #d43c18; padding: 14px 16px; margin: 20px 0 28px; }
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
            <img class="sisterhood-form-banner" src="{{ asset('welfare/img/sisterhood-collective-banner.jpg') }}" alt="MUKMIN Women and Family Development Bureau presents The Sisterhood Collective. Connect. Empower. Rise Together.">
            <div class="form-header">
                <h1>The Sisterhood Collective 2026 | Participant Registration</h1>
                <p class="form-tagline">Connect. Empower. Rise Together.</p>
                <div class="form-copy">
                <p><strong>SHE LEADS. GENERATIONS RISE.</strong></p>
                <p>A celebration of sisterhood, empowerment and shared purpose.</p>
                <p class="form-body">Join us for the inaugural The Sisterhood Collective, a meaningful gathering bringing together Indian Muslim women from all walks of life to connect, be inspired and grow together.</p>
                <p class="form-body">An opportunity to build new friendships, celebrate women's strengths, exchange ideas and explore how we can collectively make a difference in our families and communities.</p>
                <h2 class="event-details-title">Event Details</h2>
                <ul class="event-details">
                    <li><span aria-hidden="true">📅</span> Date: Sunday, 22 November 2026</li>
                    <li><span aria-hidden="true">🕘</span> Time: 9.30 AM – 5.00 PM</li>
                    <li><span aria-hidden="true">📍</span> Venue: Asiana Grand Hall 2, Kuala Lumpur</li>
                    <li><span aria-hidden="true">🎟️</span> Registration Fee: RM30 per person</li>
                    <li><span aria-hidden="true">👩</span> Open to: Registered Friends of Mukmin Indian Muslim women aged 18 and above</li>
                </ul>
                <p class="form-body">Dress Theme: Burgundy &amp; Olive Green. Modest attire complemented by Indian Muslim traditional jewellery or cultural accessories.</p>
                <p>Registration closes on 17 November 2026.</p>
                <p class="form-body">Come as you are, bring your stories and leave with new connections, fresh inspiration and a shared sense of purpose.</p>
                <p>Because when women connect, communities grow and generations rise.</p>
                <p><strong>Organised by MUKMIN Women &amp; Family Development Bureau</strong></p>
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
            @else
                <div class="fee-note">
                    <strong>Every question is required.</strong>
                    <p style="margin: 8px 0 0;">After you submit this form you will be taken to the payment gateway to pay the RM30 registration fee. Your place is confirmed only after that payment is received.</p>
                </div>

                @if($errors->any())
                    <div class="form-alert">Please complete every required field before continuing to payment.</div>
                @endif

                <form method="POST" action="{{ route('welfare.sisterhood.store') }}" class="sisterhood-fields">
                    @csrf
                    @include('welfare.partials.sisterhood-fields', ['registration' => null])
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
