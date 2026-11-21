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
        .intro-text p {
            margin: 0 0 14px 0;
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
        .reminders {
            margin: 0 0 24px 0;
            padding-left: 20px;
            color: #4a5568;
            font-size: 15px;
        }
        .reminders li {
            margin-bottom: 8px;
        }
        .sign-off {
            font-size: 16px;
            color: #4a5568;
            margin: 0 0 8px 0;
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
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <div class="email-header-top">
                    <img class="email-header-logo" src="{{ isset($message) ? $message->embed(public_path('welfare/img/mukmin_logo.png')) : asset('welfare/img/mukmin_logo.png') }}" alt="MUKMIN Logo">
                </div>
                <div class="email-header-main">
                    <h1>The Sisterhood Collective 2026</h1>
                    <p>Programme agenda reminder</p>
                </div>
            </div>

            <div class="email-body">
                <div class="intro-text">
                    <p>Assalamualaikum@if($recipientName !== ''), {{ $recipientName }}@endif,</p>
                    <p>We are just days away from The Sisterhood Collective 2026, and we can't wait to welcome you!</p>
                    <p>As we get closer to the event, we are pleased to share the programme agenda for your reference. Please find the agenda attached and take a moment to familiarise yourself with the day's sessions and activities.</p>
                </div>

                <p class="section-title">Event details</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th>Date</th>
                            <td>Sunday, 22 November 2026</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>9.00 AM – 5.00 PM</td>
                        </tr>
                        <tr>
                            <th>Venue</th>
                            <td>{{ \App\Support\SisterhoodCollective::EVENT_VENUE }}</td>
                        </tr>
                        <tr>
                            <th>Dress theme</th>
                            <td>{{ \App\Support\SisterhoodCollective::EVENT_DRESS }}</td>
                        </tr>
                    </tbody>
                </table>

                <p class="section-title">A few reminders before the event</p>
                <ul class="reminders">
                    <li>Please arrive early to allow sufficient time for registration and check-in.</li>
                    <li>Have your registration QR code ready for entry. You may retrieve it from your registration confirmation email.</li>
                    <li>We encourage you to follow the dress theme and come dressed in Burgundy and Olive Green.</li>
                </ul>

                <p class="sign-off">We look forward to a meaningful day of conversations, connections and shared experiences as we celebrate the strength and contributions of women in our community.</p>
                <p class="sign-off">See you this Sunday!</p>
                <p class="sign-off" style="margin-top: 20px;">Warm regards,<br>
                    {{ \App\Support\SisterhoodCollective::ORGANISER }}<br>
                    Pertubuhan Gabungan MUKMIN Nasional (MUKMIN)</p>
            </div>

            <div class="email-footer">
                <p>&copy; 2026 Pertubuhan Gabungan MUKMIN Nasional. All rights reserved.</p>
                <p>Support: <a href="tel:0143021800">{{ \App\Support\SisterhoodCollective::SUPPORT_PHONE }}</a></p>
            </div>
        </div>
    </div>
</body>
</html>
