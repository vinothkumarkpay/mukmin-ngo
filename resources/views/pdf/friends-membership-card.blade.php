@php
    $nameLength = mb_strlen($member->member_name);
    $nameSize = $nameLength <= 26 ? '8.6pt' : ($nameLength <= 34 ? '7pt' : '5.6pt');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership E-Card - {{ $member->formatted_membership_number }}</title>
    <style>
        @page { margin: 0; }
        html, body {
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
        }
        .side {
            position: relative;
            width: 242.65pt;
            height: 153.07pt;
            overflow: hidden;
        }
        .side + .side { page-break-before: always; }
        .bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 242.65pt;
            height: 153.07pt;
        }

        .identity {
            position: absolute;
            top: 65pt;
            left: 10.8pt;
            width: 142pt;
        }
        .member-name {
            font-size: {{ $nameSize }};
            font-weight: bold;
            color: #0b3d2a;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .member-number {
            margin-top: 1.5pt;
            font-size: 7.4pt;
            font-weight: bold;
            color: #0c7a43;
        }
        .field {
            position: absolute;
            left: 10.8pt;
            border-left: 1.4pt solid #c9a54a;
            padding-left: 4pt;
            line-height: 1.15;
        }
        .field-category { top: 90.5pt; }
        .field-validity { top: 104.5pt; }
        .field-label {
            font-size: 4.8pt;
            color: #4a5568;
        }
        .field-value {
            font-size: 5.6pt;
            color: #1a202c;
        }
        .field-validity .field-value {
            font-weight: bold;
            text-transform: uppercase;
        }

        .terms-title {
            position: absolute;
            top: 13pt;
            left: 14pt;
            font-size: 6.6pt;
            font-weight: bold;
            color: #e0b84f;
            letter-spacing: 0.2pt;
        }
        .terms {
            position: absolute;
            top: 25pt;
            left: 14pt;
            width: 150pt;
            border-collapse: collapse;
        }
        .terms td {
            font-size: 4.7pt;
            line-height: 1.35;
            color: #ffffff;
            vertical-align: top;
            padding: 0 0 2.6pt 0;
        }
        .terms td.num {
            width: 7pt;
        }
        .website {
            position: absolute;
            bottom: 12pt;
            left: 14pt;
            font-size: 5pt;
            color: #ffffff;
        }
        .website img {
            width: 6pt;
            height: 6pt;
            vertical-align: middle;
        }
        .slogan-line {
            position: absolute;
            bottom: 21pt;
            right: 14pt;
            width: 22pt;
            border-top: 1pt solid #e0b84f;
        }
        .slogan {
            position: absolute;
            bottom: 12pt;
            right: 14pt;
            font-size: 5.4pt;
            font-weight: bold;
            font-style: italic;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="side">
        <img class="bg" src="{{ $front }}" alt="">
        <div class="identity">
            <div class="member-name">{{ $member->member_name }}</div>
            <div class="member-number">{{ $member->formatted_membership_number }}</div>
        </div>
        <div class="field field-category">
            <div class="field-label">Membership Category</div>
            <div class="field-value">{{ $settings['category'] }}</div>
        </div>
        <div class="field field-validity">
            <div class="field-label">Membership Validity</div>
            <div class="field-value">{{ optional($member->membership_end_date)->format('d M Y') }}</div>
        </div>
    </div>

    <div class="side">
        <img class="bg" src="{{ $back }}" alt="">
        <div class="terms-title">TERMS AND CONDITIONS</div>
        <table class="terms">
            <tr><td class="num">1.</td><td>This card is issued by Pertubuhan Gabungan MUKMIN Nasional (MUKMIN) and remains the property of MUKMIN.</td></tr>
            <tr><td class="num">2.</td><td>This card is valid for the period stated and is non-transferable.</td></tr>
            <tr><td class="num">3.</td><td>The holder is a registered Friend of MUKMIN.</td></tr>
            <tr><td class="num">4.</td><td>Use of this card is subject to the terms and conditions of MUKMIN.</td></tr>
            <tr><td class="num">5.</td><td>For verification or enquiries, please contact {{ $settings['enquiry_email'] }}.</td></tr>
        </table>
        <div class="website">
            <img src="data:image/svg+xml;base64,{{ base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><ellipse cx="12" cy="12" rx="4.5" ry="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg>') }}" alt="">
            &nbsp;{{ $settings['website'] }}
        </div>
        <div class="slogan-line"></div>
        <div class="slogan">One Identity. One Vision. One Community.</div>
    </div>
</body>
</html>
