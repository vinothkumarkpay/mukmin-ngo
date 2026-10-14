<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership Confirmation - {{ $member->formatted_membership_number }}</title>
    <style>
        @page { margin: 38px 56px 60px 56px; }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #2d3748;
            line-height: 1.55;
        }
        .letterhead {
            border-bottom: 2px solid #b3913b;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .letterhead td { vertical-align: middle; }
        .letterhead img { width: 170px; }
        .org {
            text-align: right;
            font-size: 9.5px;
            color: #4a5568;
            line-height: 1.45;
        }
        .org strong {
            color: #0c5930;
            font-size: 11px;
        }
        .meta { margin-bottom: 20px; }
        .meta td { padding: 1px 0; font-weight: bold; }
        .addressee {
            font-weight: bold;
            margin-bottom: 12px;
        }
        .subject {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 14px;
        }
        p { margin: 0 0 11px 0; text-align: justify; }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0 16px 0;
        }
        table.details th,
        table.details td {
            border: 1px solid #cbd5e0;
            padding: 7px 10px;
            text-align: left;
        }
        table.details th {
            background: #0c5930;
            color: #ffffff;
        }
        table.details td.label {
            width: 38%;
            background: #f7fafc;
            font-weight: bold;
        }
        .tagline {
            color: #0c5930;
            font-weight: bold;
            margin: 16px 0;
        }
        .signoff { margin-top: 18px; }
        .footnote {
            position: fixed;
            bottom: -34px;
            left: 0;
            right: 0;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 9px;
            color: #718096;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="letterhead" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td><img src="{{ $logo }}" alt="MUKMIN"></td>
            <td class="org">
                <strong>PERTUBUHAN GABUNGAN MUKMIN NASIONAL (MUKMIN)</strong><br>
                (PPM-019-10-15042026)<br>
                {{ config('welfare.address') }}<br>
                {{ config('welfare.postal') }}
            </td>
        </tr>
    </table>

    <table class="meta" cellspacing="0" cellpadding="0">
        <tr><td>Ref. No.: {{ $referenceNumber }}</td></tr>
        <tr><td>Date: {{ $issuedDate }}</td></tr>
    </table>

    <div class="addressee">Dear {{ $member->greeting_name }}</div>

    <div class="subject">SUBJECT: OFFICIAL MEMBERSHIP CONFIRMATION - FRIENDS OF MUKMIN</div>

    <p>Assalamualaikum warahmatullahi wabarakatuh.</p>

    <p><strong>On behalf of Pertubuhan Gabungan MUKMIN Nasional (MUKMIN), we are pleased to confirm that {{ $member->member_name }}, bearing Membership Number {{ $member->formatted_membership_number }}, has been registered as a member under the Friends of MUKMIN membership category.</strong></p>

    <p>The membership details are as follows:</p>

    <table class="details">
        <tr>
            <th>Particulars</th>
            <th>Details</th>
        </tr>
        <tr>
            <td class="label">Member Name</td>
            <td>{{ $member->member_name }}</td>
        </tr>
        <tr>
            <td class="label">Membership Number</td>
            <td>{{ $member->formatted_membership_number }}</td>
        </tr>
        <tr>
            <td class="label">Membership Category</td>
            <td>{{ $settings['category'] }}</td>
        </tr>
        <tr>
            <td class="label">Membership Validity</td>
            <td>{{ optional($member->membership_start_date)->format('d F Y') }} to {{ optional($member->membership_end_date)->format('d F Y') }}</td>
        </tr>
    </table>

    <p>The Friends of MUKMIN network serves as a community and supporter ecosystem for individuals, informal groups and organisations seeking to contribute towards MUKMIN's mission and community initiatives.</p>

    <p>As a member, you may have opportunities to participate in selected programmes, contribute to community engagement activities, collaborate with other members of the MUKMIN ecosystem, and support relevant working groups, sub-committees and community-driven initiatives, subject to the applicable arrangements and requirements.</p>

    <p><strong>Please find enclosed your official MUKMIN Membership E-Card as a digital record of your membership.</strong></p>

    <p>Please note that Friends of MUKMIN membership does not confer voting rights or eligibility to hold positions within MUKMIN's Executive Committee. Participation in programmes and other activities is subject to the relevant requirements and arrangements established by MUKMIN.</p>

    <p>We sincerely appreciate your support and look forward to your continued engagement in advancing our shared aspirations for community empowerment, collaboration and sustainable social impact.</p>

    <div class="tagline">One Identity. One Vision. One Community.</div>

    <div class="signoff">
        Yours faithfully,<br><br>
        <strong>MUKMIN Secretariat</strong><br>
        <strong>Pertubuhan Gabungan MUKMIN Nasional</strong>
    </div>

    <div class="footnote">This letter is issued electronically by the MUKMIN Secretariat.</div>
</body>
</html>
