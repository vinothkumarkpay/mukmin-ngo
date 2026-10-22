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
            background-color: #6b2d4a;
            padding: 0;
            text-align: left;
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
            color: #f8e8ef;
            margin: 0;
            font-size: 15px;
            font-weight: 500;
        }
        .email-body {
            padding: 40px 30px;
        }
        .intro-text {
            font-size: 16px;
            margin-bottom: 24px;
            color: #4a5568;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .status-success {
            background: #d1fae5;
            color: #059669;
        }
        .status-failed {
            background: #fee2e2;
            color: #dc2626;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin: 0 0 12px 0;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
            font-size: 15px;
        }
        .form-table th, .form-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
            vertical-align: top;
        }
        .form-table th {
            width: 38%;
            color: #718096;
            font-weight: 600;
            background-color: #f7fafc;
        }
        .form-table td {
            color: #2d3748;
        }
        .email-footer {
            background-color: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer a {
            color: #6b2d4a;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <div class="email-header-top">
                    <img class="email-header-logo" src="{{ asset('welfare/img/mukmin_logo.png') }}" alt="MUKMIN Logo">
                </div>
                <div class="email-header-main">
                    <h1>The Sisterhood Collective 2026</h1>
                    <p>{{ $statusKey === 'success' ? 'Registration Successful' : 'Registration Incomplete' }}</p>
                </div>
            </div>

            <div class="email-body">
                <div class="intro-text">
                    @if($statusKey === 'success')
                        <p>Assalamu alaikum {{ $registration->full_name }},</p>
                        <p>Thank you for registering for The Sisterhood Collective 2026.</p>
                        <p>Your registration and payment of RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} have been successfully received. We look forward to welcoming you to the programme.</p>
                        <p>Your registration details and the event timing are below. Further details regarding the event will be shared with you in due course.</p>
                        <p>See you there!</p>
                    @else
                        <p>Assalamu alaikum {{ $registration->full_name }},</p>
                        <p>Your registration has not been completed as payment of RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} has not been successfully received.</p>
                        <p>Please try again to complete your payment. Your registration will only be confirmed once payment has been successfully processed.</p>
                        <p>If you have already made the payment but are still seeing this message, please contact <a href="tel:0143021800">{{ \App\Support\SisterhoodCollective::SUPPORT_PHONE }}</a> for assistance.</p>
                    @endif
                    <div style="margin-top: 16px;">
                        <span class="status-badge status-{{ $statusKey }}">{{ $statusLabel }}</span>
                    </div>
                </div>

                <p class="section-title">Your registration</p>
                <table class="form-table">
                    <tbody>
                        @foreach($registrationRows as $row)
                            <tr>
                                <th>{{ $row['label'] }}</th>
                                <td>{{ $row['value'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p class="section-title">Event details</p>
                <table class="form-table">
                    <tbody>
                        @foreach($eventRows as $row)
                            <tr>
                                <th>{{ $row['label'] }}</th>
                                <td>{{ $row['value'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="email-footer">
                <p>&copy; 2026 Pertubuhan Gabungan MUKMIN Nasional. All rights reserved.</p>
                <p>Support: <a href="tel:0143021800">{{ \App\Support\SisterhoodCollective::SUPPORT_PHONE }}</a></p>
            </div>
        </div>
    </div>
</body>
</html>
