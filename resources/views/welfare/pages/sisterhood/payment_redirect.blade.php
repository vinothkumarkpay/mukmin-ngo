<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment Gateway...</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f7f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', sans-serif;
            margin: 0;
        }
        .redirect-card {
            text-align: center;
            max-width: 450px;
            padding: 3rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top-color: #7a3e7a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 24px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        h1 { color: #1e1e1e; font-size: 22px; margin-bottom: 12px; }
        p { color: #64748b; margin: 0; line-height: 1.6; }
    </style>
</head>
<body onload="document.forms['payment_form'].submit();">
    <div class="redirect-card">
        <div class="spinner"></div>
        <h1>Redirecting to Payment Gateway</h1>
        <p>Please wait while we redirect you to pay the RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} registration fee. Do not close this window. Your registration is confirmed only after payment is received.</p>

        <form name="payment_form" action="{{ $paymentData['url'] }}" method="POST">
            @foreach($paymentData['params'] as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>
</body>
</html>
