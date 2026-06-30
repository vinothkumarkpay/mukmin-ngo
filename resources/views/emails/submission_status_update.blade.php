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
            background: #f7fafc;
            color: #0c5930;
            border: 1px solid #d1fae5;
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
            font-size: 13px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer a {
            color: #0c5930;
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
                    <h1>Pertubuhan Gabungan MUKMIN Nasional</h1>
                    <p>Application Status Update</p>
                </div>
            </div>

            <div class="email-body">
                <div class="intro-text">
                    Assalamu alaikum {{ $recipientName ?: 'there' }},<br><br>
                    This is to inform you that the status of your <strong>{{ $formTitle }}</strong> submission has been updated.
                </div>

                <div class="summary-box">
                    <p><span class="summary-label">Application:</span> {{ $formTitle }}</p>
                    <p><span class="summary-label">Updated Status:</span> <span class="status-badge">{{ $statusLabel }}</span></p>
                    <p>{{ $statusMessage }}</p>
                </div>

                <div class="intro-text" style="margin-bottom: 0;">
                    If you have any questions, please contact us at <a href="mailto:support@mukmin.org" style="color: #0c5930; text-decoration: none; font-weight: 500;">support@mukmin.org</a>.
                </div>
            </div>

            <div class="email-footer">
                <p>&copy; 2026 Pertubuhan Gabungan MUKMIN Nasional. All rights reserved.</p>
                <p>Website: <a href="http://mukmin.org" target="_blank">mukmin.org</a> | Email: <a href="mailto:support@mukmin.org">support@mukmin.org</a></p>
            </div>
        </div>
    </div>
</body>
</html>
