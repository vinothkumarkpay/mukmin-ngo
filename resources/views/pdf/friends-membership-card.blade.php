@php
    $nameLength = mb_strlen($member->member_name);
    $nameSize = $nameLength <= 26 ? '8pt' : ($nameLength <= 34 ? '6.6pt' : '5.4pt');
    $font = fn (string $weight) => str_replace('\\', '/', resource_path("fonts/Poppins-{$weight}.ttf"));
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership E-Card - {{ $member->formatted_membership_number }}</title>
    <style>
        @font-face { font-family: 'Poppins'; font-weight: normal; src: url('{{ $font('Regular') }}') format('truetype'); }
        @font-face { font-family: 'Poppins'; font-weight: 500; src: url('{{ $font('Medium') }}') format('truetype'); }
        @font-face { font-family: 'Poppins'; font-weight: 600; src: url('{{ $font('SemiBold') }}') format('truetype'); }
        @font-face { font-family: 'Poppins'; font-weight: bold; src: url('{{ $font('Bold') }}') format('truetype'); }
        @font-face { font-family: 'Poppins'; font-weight: 600; font-style: italic; src: url('{{ $font('SemiBoldItalic') }}') format('truetype'); }

        @page { margin: 0; }
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Helvetica, Arial, sans-serif;
        }
        .side {
            position: relative;
            margin: 8pt;
            width: 242.65pt;
            height: 153.07pt;
        }
        .side + .side { page-break-before: always; }
        .bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 242.65pt;
            height: 153.07pt;
        }
        .outline {
            position: absolute;
            top: 0;
            left: 0;
            width: 241.65pt;
            height: 152.07pt;
            border: 0.5pt solid #d5dbe1;
            border-radius: 9pt;
        }

        .identity {
            position: absolute;
            top: 69pt;
            left: 10pt;
            width: 145pt;
        }
        .member-name {
            font-size: {{ $nameSize }};
            font-weight: 600;
            color: #111827;
            text-transform: uppercase;
            line-height: 1.15;
        }
        .member-nric {
            margin-top: 1pt;
            font-size: 5.4pt;
            color: #1f2937;
            letter-spacing: 0.3pt;
        }
        .validity {
            position: absolute;
            top: 99.5pt;
            left: 10pt;
            line-height: 1.25;
        }
        .validity-label {
            font-size: 4.2pt;
            color: #1f2937;
        }
        .validity-value {
            font-size: 4.8pt;
            font-weight: 600;
            color: #111827;
            text-transform: uppercase;
        }
        .member-number {
            position: absolute;
            top: 136.5pt;
            left: 10pt;
            font-size: 6.2pt;
            color: #d9b25f;
            letter-spacing: 0.2pt;
        }

        .terms-title {
            position: absolute;
            top: 13pt;
            left: 14pt;
            font-size: 6.2pt;
            font-weight: 600;
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
            font-size: 4.4pt;
            line-height: 1.15;
            color: #ffffff;
            vertical-align: top;
            padding: 0 0 2.8pt 0;
        }
        .terms td.num {
            width: 7pt;
        }
        .website {
            position: absolute;
            bottom: 12pt;
            left: 14pt;
            font-size: 4.8pt;
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
            font-size: 5pt;
            font-weight: 600;
            font-style: italic;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="side">
        <img class="bg" src="{{ $front }}" alt="">
        <div class="outline"></div>
        <div class="identity">
            <div class="member-name">{{ $member->member_name }}</div>
            @if($member->formatted_nric)
                <div class="member-nric">{{ $member->formatted_nric }}</div>
            @endif
        </div>
        <div class="validity">
            <div class="validity-label">Membership Validity</div>
            <div class="validity-value">{{ optional($member->membership_end_date)->format('d M Y') }}</div>
        </div>
        <div class="member-number">{{ $member->formatted_membership_number }}</div>
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
