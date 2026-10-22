@extends('welfare.layouts.app')

@section('title', $successful ? 'Registration Successful' : 'Registration Incomplete')

@section('content')
<style>
.result-page { background: #f7f4f8; padding: 70px 0; font-family: 'Roboto', sans-serif; }
.result-card {
    max-width: 720px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    padding: 40px;
    border: 1px solid #eadff0;
    box-shadow: 0 10px 30px rgba(0,0,0,0.04);
}
.result-card h1 { margin: 0 0 18px; font-size: 32px; }
.result-card p { color: #333; line-height: 1.7; margin: 0 0 14px; }
.result-card.success { border-top: 6px solid #1f7a4d; }
.result-card.incomplete { border-top: 6px solid #b45309; }
.result-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 22px; }
.btn-result, .btn-result-secondary {
    display: inline-block;
    border-radius: 6px;
    padding: 12px 20px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 15px;
}
.btn-result { background: #d43c18; color: #fff; }
.btn-result-secondary { background: #fff; color: #333; border: 1px solid #d2d8d5; }
</style>

<div class="result-page">
    <div class="container">
        @if($successful)
            <div class="result-card success">
                <h1>Registration Successful!</h1>
                <p>Thank you for registering for The Sisterhood Collective 2026.</p>
                <p>Your registration and payment of RM30 have been successfully received. We look forward to welcoming you to the programme!</p>
                <p>Further details regarding the event will be shared with you in due course.</p>
                <p>See you there!</p>
                @if($registration)
                    <p><strong>Reference:</strong> {{ $registration->order_id }}</p>
                @endif
                <div class="result-actions">
                    <a href="{{ route('welfare.home') }}" class="btn-result-secondary">Back to home</a>
                </div>
            </div>
        @else
            <div class="result-card incomplete">
                <h1>Registration Incomplete</h1>
                <p>Your registration has not been completed as payment of RM30 has not been successfully received.</p>
                <p>Please try again to complete your payment. Your registration will only be confirmed once payment has been successfully processed.</p>
                <p>If you have already made the payment but are still seeing this message, please contact the <a href="tel:0143021800">014-3021800</a> for assistance.</p>
                <div class="result-actions">
                    @if($registration)
                        <form method="POST" action="{{ route('welfare.sisterhood.retry') }}">
                            @csrf
                            <button type="submit" class="btn-result">Try payment again</button>
                        </form>
                    @else
                        <a href="{{ route('welfare.sisterhood.create') }}" class="btn-result">Try again</a>
                    @endif
                    <a href="{{ route('welfare.home') }}" class="btn-result-secondary">Back to home</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
