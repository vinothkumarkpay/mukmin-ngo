<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f6f8;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e4e8;
        }
        .email-header {
            background-color: #0c5930;
            padding: 0;
            text-align: left;
            border-bottom: 3px solid #b3913b;
        }
        .email-header-top {
            background: #ffffff;
            padding: 16px 30px 12px;
            border-bottom: 1px solid #e6e6e6;
        }
        .email-header-main {
            padding: 22px 30px 24px;
        }
        .email-header-logo {
            max-width: 165px;
            height: auto;
            display: block;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0 0 6px 0;
            font-size: 20px;
            font-weight: 500;
        }
        .email-header p {
            color: #d1ffd6;
            margin: 0;
            font-size: 15px;
            font-weight: 500;
        }
        .email-body {
            padding: 40px 30px;
        }
        .intro-text {
            font-size: 16px;
            margin-bottom: 20px;
            color: #4a5568;
        }
        .summary-box {
            background: #f7fafc;
            border: 1px solid #edf2f7;
            border-radius: 8px;
            padding: 18px 20px;
            margin-bottom: 24px;
        }
        .summary-box p {
            margin: 0 0 10px 0;
            color: #2d3748;
            font-size: 15px;
        }
        .summary-box p:last-child {
            margin-bottom: 0;
        }
        .summary-label {
            color: #718096;
            font-weight: 600;
        }
        .email-footer {
            background-color: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #edf2f7;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <div class="email-header-top">
                    <img src="{{ asset('welfare/img/mukmin_logo.png') }}" alt="MUKMIN" class="email-header-logo">
                </div>
                <div class="email-header-main">
                    <h1>MUKMIN Future Leaders Scholarship</h1>
                    <p>{{ $isForSupport ? 'Requirements inquiry received' : 'Programme requirements update' }}</p>
                </div>
            </div>
            <div class="email-body">
                @if ($isForSupport)
                    <p class="intro-text">An applicant indicated they do not currently fulfil the listed programme requirements and requested a feasibility check.</p>
                    <div class="summary-box">
                        <p><span class="summary-label">Applicant email:</span> {{ $applicantEmail }}</p>
                        <p><span class="summary-label">Partner institution:</span> {{ $partnerName }}</p>
                        <p><span class="summary-label">Programme:</span> {{ $programmeName }}</p>
                    </div>
                    <p class="intro-text">Please follow up with the applicant for personal information and assess feasibility.</p>
                @else
                    <p class="intro-text">Thank you for your interest in the MUKMIN Future Leaders Scholarship (MFLS).</p>
                    <p class="intro-text">Based on your response, you do not currently fulfil the listed requirements for the programme below. Please contact us with your personal information so we can check and revert to you on the feasibility of your application.</p>
                    <div class="summary-box">
                        <p><span class="summary-label">Partner institution:</span> {{ $partnerName }}</p>
                        <p><span class="summary-label">Programme:</span> {{ $programmeName }}</p>
                        <p><span class="summary-label">Contact:</span> {{ config('welfare.form_submission_recipients.mfls-scholarship', config('welfare.email')) }}</p>
                    </div>
                    <p class="intro-text">You may also revisit the scholarship page for other programmes and partner institutions:</p>
                    <p class="intro-text"><a href="{{ $redirectUrl }}">{{ $redirectUrl }}</a></p>
                @endif
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} MUKMIN. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
