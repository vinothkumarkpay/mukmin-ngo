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
            padding: 36px 30px;
            font-size: 15px;
            color: #4a5568;
        }
        .email-body p {
            margin: 0 0 16px 0;
        }
        .email-body h2 {
            color: #0c5930;
            font-size: 16px;
            margin: 26px 0 10px 0;
        }
        .email-body ul {
            margin: 0 0 16px 0;
            padding-left: 20px;
        }
        .email-body li {
            margin-bottom: 8px;
        }
        .email-body strong {
            color: #2d3748;
        }
        .tagline {
            color: #0c5930;
            font-weight: 700;
            margin-top: 22px;
        }
        .signature {
            margin-top: 24px;
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
                    <p>Welcome to Friends of MUKMIN</p>
                </div>
            </div>

            <div class="email-body">
                <p>Dear {{ $greetingName }},</p>

                <p><strong>Assalamualaikum warahmatullahi wabarakatuh and Salam MUKMIN.</strong></p>

                <p><strong>On behalf of Pertubuhan Gabungan MUKMIN Nasional (MUKMIN), we are pleased to welcome you as an official member of our Friends of MUKMIN community.</strong></p>

                <p>Thank you for your support and for being part of a growing national movement committed to empowering our community through collective action, meaningful partnerships and sustainable impact.</p>

                <h2>What Does Being a Friend of MUKMIN Mean?</h2>

                <p>Friends of MUKMIN form an important part of our community and supporter network, bringing together individuals, groups and organisations who share our vision and wish to contribute towards our mission.</p>

                <p>As a Friend of MUKMIN, you have opportunities to:</p>
                <ul>
                    <li><strong>Participate in Community Initiatives</strong> - Contribute to selected community engagement programmes and activities.</li>
                    <li><strong>Connect and Collaborate</strong> - Engage with like-minded individuals, groups and organisations within the MUKMIN ecosystem.</li>
                    <li><strong>Contribute Your Skills and Ideas</strong> - Participate in relevant working groups and sub-committees, where invited or appointed.</li>
                    <li><strong>Support Community-Driven Impact</strong> - Take part in collaborative activities and initiatives that advance MUKMIN's mission.</li>
                </ul>

                <p>Your involvement helps strengthen our collective efforts to build a connected, empowered and forward-looking community.</p>

                <h2>Your Membership Documents</h2>

                <p>Attached to this email are:</p>
                <ul>
                    <li><strong>MUKMIN Membership E-Card</strong> - Your digital membership card for identification and reference.</li>
                    <li><strong>Membership Confirmation Letter</strong> - Your official record of registration as a Friend of MUKMIN.</li>
                </ul>

                <p>We encourage you to download and retain both documents for your records.</p>

                <h2>Stay Connected with MUKMIN</h2>

                <p>Stay informed about our programmes, community initiatives, engagement opportunities and upcoming activities through our official channels:</p>
                <ul>
                    <li><strong>Website:</strong> <a href="https://{{ $settings['website'] }}" style="color: #0c5930; text-decoration: none;">{{ $settings['website'] }}</a></li>
                    <li><strong>Social Media:</strong> {{ $settings['social_handle'] }}</li>
                </ul>

                <p>Your membership represents an opportunity to be part of a wider community working towards shared aspirations and meaningful progress.</p>

                <p>Once again, welcome to MUKMIN. We look forward to your continued support as we work together under our shared commitment:</p>

                <p class="tagline">One Identity. One Vision. One Community.</p>

                <div class="signature">
                    <p style="margin-bottom: 4px;">Warm regards,</p>
                    <p style="margin: 0;"><strong>The MUKMIN Secretariat</strong><br>
                    <strong>Pertubuhan Gabungan MUKMIN Nasional</strong><br>
                    <strong><a href="https://{{ $settings['website'] }}" style="color: #0c5930; text-decoration: none;">{{ $settings['website'] }}</a></strong></p>
                </div>
            </div>

            <div class="email-footer">
                <p>&copy; {{ date('Y') }} Pertubuhan Gabungan MUKMIN Nasional. All rights reserved.</p>
                <p>Website: <a href="https://{{ $settings['website'] }}" target="_blank">{{ $settings['website'] }}</a> | Email: <a href="mailto:{{ $settings['enquiry_email'] }}">{{ $settings['enquiry_email'] }}</a></p>
            </div>
        </div>
    </div>
</body>
</html>
