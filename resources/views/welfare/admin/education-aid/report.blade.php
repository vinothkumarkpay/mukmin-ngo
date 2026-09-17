<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Aid Assessment Report #{{ $submission->id }} — MUKMIN</title>
    <style>
        :root {
            --ea-primary: #d43c18;
            --ea-text: #1e293b;
            --ea-muted: #64748b;
            --ea-border: #e2e8f0;
            --ea-bg: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 32px;
            font-family: Georgia, 'Times New Roman', serif;
            color: var(--ea-text);
            background: #fff;
            line-height: 1.45;
            font-size: 14px;
        }
        .ea-report-toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }
        .ea-report-print-btn {
            background: var(--ea-primary);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: system-ui, sans-serif;
        }
        .ea-report-print-btn:hover { background: #b83210; }
        .ea-report-header {
            border-bottom: 3px solid var(--ea-primary);
            padding-bottom: 16px;
            margin-bottom: 28px;
        }
        .ea-report-header h1 {
            margin: 0 0 8px;
            font-size: 22px;
            letter-spacing: 0.02em;
            color: var(--ea-primary);
        }
        .ea-report-meta {
            color: var(--ea-muted);
            font-size: 13px;
            font-family: system-ui, sans-serif;
        }
        .ea-report-section {
            margin-bottom: 28px;
            page-break-inside: avoid;
        }
        .ea-report-section h2 {
            margin: 0 0 12px;
            font-size: 16px;
            border-bottom: 1px solid var(--ea-border);
            padding-bottom: 6px;
        }
        .ea-report-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 24px;
        }
        .ea-report-item .label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--ea-muted);
            font-family: system-ui, sans-serif;
            margin-bottom: 2px;
        }
        .ea-report-item .value {
            font-size: 14px;
        }
        .ea-report-item.full { grid-column: 1 / -1; }
        table.ea-report-table {
            width: 100%;
            border-collapse: collapse;
            font-family: system-ui, sans-serif;
            font-size: 13px;
        }
        table.ea-report-table th,
        table.ea-report-table td {
            border: 1px solid var(--ea-border);
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        table.ea-report-table th {
            background: var(--ea-bg);
            width: 40%;
            font-weight: 600;
        }
        .ea-report-list {
            margin: 0;
            padding-left: 18px;
            font-family: system-ui, sans-serif;
            font-size: 13px;
        }
        .ea-report-list li { margin-bottom: 4px; }
        .ea-report-done { color: #15803d; }
        .ea-report-pending { color: #b45309; }
        .ea-report-banner {
            background: #fff7ed;
            border: 1px solid #fdba74;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-family: system-ui, sans-serif;
            font-size: 13px;
        }
        .ea-report-footer {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid var(--ea-border);
            color: var(--ea-muted);
            font-size: 12px;
            font-family: system-ui, sans-serif;
        }
        @media print {
            body { padding: 12mm; }
            .ea-report-toolbar,
            .ea-report-print-btn { display: none !important; }
        }
        @media (max-width: 640px) {
            .ea-report-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
@php
    use App\Support\EducationAidStatus;
    use App\Support\EducationAidUrgency;
    use App\Models\EducationAidAssessment;

    $fmtMoney = function ($value) {
        return 'RM ' . number_format((float) ($value ?? 0), 2);
    };
    $fmtDate = function ($value) {
        if (! $value) {
            return '—';
        }
        try {
            return \Carbon\Carbon::parse($value)->format('d M Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    };
    $assessment = $assessment ?? $submission->assessment;
@endphp

<div class="ea-report-toolbar">
    <button type="button" class="ea-report-print-btn" onclick="window.print()">Print / Save as PDF</button>
</div>

<header class="ea-report-header">
    <h1>MUKMIN — Education Aid Assessment Report</h1>
    <div class="ea-report-meta">
        Case #{{ $submission->id }}
        &nbsp;·&nbsp;
        Generated {{ now()->format('d M Y H:i') }}
        &nbsp;·&nbsp;
        Status: {{ EducationAidStatus::label($submission->status) }}
        &nbsp;·&nbsp;
        Urgency: {{ EducationAidUrgency::label(optional($assessment)->urgency) }}
    </div>
</header>

<section class="ea-report-section">
    <h2>Applicant</h2>
    <div class="ea-report-grid">
        <div class="ea-report-item">
            <span class="label">Full Name</span>
            <span class="value">{{ $submission->full_name ?: '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">NRIC / Passport</span>
            <span class="value">{{ $submission->nric_passport ?: '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Email</span>
            <span class="value">{{ $submission->email ?: '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Contact</span>
            <span class="value">{{ $submission->contact_number ?: '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">University</span>
            <span class="value">{{ $submission->university_institution ?: '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Programme</span>
            <span class="value">{{ $submission->programme_name ?: '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Year / Semester</span>
            <span class="value">{{ $submission->current_year_semester ?: ($submission->current_year_semester_other ?: '—') }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Household Income</span>
            <span class="value">{{ $submission->household_income ?: '—' }}</span>
        </div>
        <div class="ea-report-item full">
            <span class="label">Consequence if Payment Not Made</span>
            <span class="value">{{ $submission->payment_not_made_consequence ?: '—' }}</span>
        </div>
        <div class="ea-report-item full">
            <span class="label">Purpose of Request</span>
            <span class="value">{{ $submission->purpose_of_request ?: '—' }}</span>
        </div>
    </div>
</section>

<section class="ea-report-section">
    <h2>Case Snapshot</h2>
    @if(! empty($snapshot['funding_requires_review']))
        <div class="ea-report-banner">
            <strong>Funding reconciliation requires review.</strong>
            @if(! empty($snapshot['reconciliation_notes']))
                <ul style="margin: 8px 0 0; padding-left: 18px;">
                    @foreach((array) $snapshot['reconciliation_notes'] as $note)
                        <li>{{ $note }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
    <table class="ea-report-table">
        <tr>
            <th>Total Programme Fees</th>
            <td>{{ $fmtMoney($snapshot['total_programme_fees'] ?? 0) }}</td>
        </tr>
        <tr>
            <th>Amount Already Paid</th>
            <td>{{ $fmtMoney($snapshot['amount_already_paid'] ?? 0) }}</td>
        </tr>
        <tr>
            <th>Other Confirmed Funding</th>
            <td>{{ $fmtMoney($snapshot['other_confirmed_funding'] ?? 0) }}</td>
        </tr>
        <tr>
            <th>Outstanding Balance</th>
            <td>{{ $fmtMoney($snapshot['outstanding_balance'] ?? 0) }}</td>
        </tr>
        <tr>
            <th>Amount Requested</th>
            <td>{{ $fmtMoney($snapshot['amount_requested'] ?? 0) }}</td>
        </tr>
        <tr>
            <th>Payment Deadline</th>
            <td>{{ $fmtDate($snapshot['payment_deadline'] ?? null) }}</td>
        </tr>
    </table>
</section>

<section class="ea-report-section">
    <h2>Assessment Completeness</h2>
    <p class="ea-report-meta" style="margin-bottom: 10px;">{{ (int) ($completeness['percent'] ?? 0) }}% complete</p>
    <ul class="ea-report-list">
        @foreach(($completeness['items'] ?? []) as $item)
            <li class="{{ ($item['done'] ?? false) ? 'ea-report-done' : 'ea-report-pending' }}">
                {{ ($item['done'] ?? false) ? '✓' : '○' }}
                {{ $item['label'] ?? '' }}
                @if(! empty($item['mandatory']))
                    (required)
                @endif
            </li>
        @endforeach
    </ul>
</section>

<section class="ea-report-section">
    <h2>Assessment Summary</h2>
    <div class="ea-report-grid">
        <div class="ea-report-item">
            <span class="label">Aid Categories</span>
            <span class="value">
                @php $cats = $assessment->aid_categories ?? []; @endphp
                {{ is_array($cats) && count($cats) ? implode(', ', $cats) : '—' }}
            </span>
        </div>
        <div class="ea-report-item">
            <span class="label">Purpose / Expense Types</span>
            <span class="value">
                @php $purposes = $assessment->purpose_expense_types ?? []; @endphp
                {{ is_array($purposes) && count($purposes) ? implode(', ', $purposes) : '—' }}
            </span>
        </div>
        <div class="ea-report-item">
            <span class="label">Financial Need</span>
            <span class="value">{{ $assessment->financial_need ? ucfirst($assessment->financial_need) : '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Assigned To</span>
            <span class="value">{{ optional($assessment->assignee)->name ?: '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Documents Verified</span>
            <span class="value">{{ $assessment->documents_verified ? 'Yes' : 'No' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">University Balance Verified</span>
            <span class="value">{{ $assessment->university_balance_verified ? 'Yes' : 'No' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Interview Conducted</span>
            <span class="value">{{ $assessment->interview_conducted ? 'Yes' : 'No' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Interview Date</span>
            <span class="value">{{ $fmtDate($assessment->interview_date) }}</span>
        </div>
        @php $breakdown = $assessment->funding_breakdown ?? []; @endphp
        <div class="ea-report-item">
            <span class="label">Funding — PTPTN</span>
            <span class="value">{{ $fmtMoney($breakdown['ptptn'] ?? 0) }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Funding — MIED</span>
            <span class="value">{{ $fmtMoney($breakdown['mied'] ?? 0) }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Funding — Family</span>
            <span class="value">{{ $fmtMoney($breakdown['family'] ?? 0) }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Funding — Other</span>
            <span class="value">{{ $fmtMoney($breakdown['other'] ?? 0) }}</span>
        </div>
        @if(filled($assessment->interview_notes))
            <div class="ea-report-item full">
                <span class="label">Interview Notes</span>
                <span class="value">{{ $assessment->interview_notes }}</span>
            </div>
        @endif
    </div>
</section>

<section class="ea-report-section">
    <h2>Reviewer Recommendation</h2>
    <div class="ea-report-grid">
        <div class="ea-report-item">
            <span class="label">Recommendation</span>
            <span class="value">
                {{ EducationAidAssessment::recommendationOptions()[$assessment->recommendation] ?? ($assessment->recommendation ?: '—') }}
            </span>
        </div>
        <div class="ea-report-item">
            <span class="label">Recommended Amount</span>
            <span class="value">{{ $assessment->recommended_amount !== null ? $fmtMoney($assessment->recommended_amount) : '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Submitted</span>
            <span class="value">
                {{ $fmtDate($assessment->recommendation_submitted_at) }}
                @if($assessment->recommendationSubmitter)
                    by {{ $assessment->recommendationSubmitter->name }}
                @endif
            </span>
        </div>
        <div class="ea-report-item full">
            <span class="label">Remarks</span>
            <span class="value">{{ $assessment->assessment_remarks ?: '—' }}</span>
        </div>
    </div>
</section>

<section class="ea-report-section">
    <h2>Committee Decision</h2>
    <div class="ea-report-grid">
        <div class="ea-report-item">
            <span class="label">Decision</span>
            <span class="value">
                {{ EducationAidAssessment::committeeDecisionOptions()[$assessment->committee_decision] ?? ($assessment->committee_decision ?: '—') }}
            </span>
        </div>
        <div class="ea-report-item">
            <span class="label">Approved Amount</span>
            <span class="value">{{ $assessment->approved_amount !== null ? $fmtMoney($assessment->approved_amount) : '—' }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Decision Date</span>
            <span class="value">{{ $fmtDate($assessment->committee_decision_date) }}</span>
        </div>
        <div class="ea-report-item">
            <span class="label">Approved By</span>
            <span class="value">{{ $assessment->committee_approved_by ?: '—' }}</span>
        </div>
        <div class="ea-report-item full">
            <span class="label">Committee Remarks</span>
            <span class="value">{{ $assessment->committee_remarks ?: '—' }}</span>
        </div>
    </div>
</section>

<footer class="ea-report-footer">
    MUKMIN Welfare Administration — confidential case document. Generated {{ now()->format('d M Y H:i') }}.
</footer>
</body>
</html>
