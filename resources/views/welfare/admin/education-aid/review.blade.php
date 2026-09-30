@extends('welfare.layouts.admin')

@php
    use App\Support\EducationAidStatus;
    use App\Support\EducationAidUrgency;
    use App\Models\EducationAidAssessment;
    use App\Models\EducationAidDocumentCheck;
    use App\Models\EducationAidSectionComment;

    $statusSlug = EducationAidStatus::normalize($submission->status);
    $statusLabel = EducationAidStatus::label($submission->status);
    $urgencySlug = $assessment->urgency ?? EducationAidUrgency::MODERATE;
    $urgencyLabel = EducationAidUrgency::label($urgencySlug);
    $canStart = in_array($statusSlug, [
        EducationAidStatus::RECEIVED,
        EducationAidStatus::ASSESSMENT_PENDING,
        EducationAidStatus::INITIAL_SCREENING,
    ], true);

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

    $defaultPurposeOptions = [
        'Tuition / Programme Fees',
        'Registration / Admission Fees',
        'Examination Fees',
        'Accommodation',
        'Professional / Academic Fees',
    ];
    $submissionExpenses = collect($submission->education_expense_types ?? [])->filter()->values()->all();
    if (filled($submission->education_expense_other)) {
        $submissionExpenses[] = $submission->education_expense_other;
    }
    $purposeOptions = collect(array_merge(
        $defaultPurposeOptions,
        $submissionExpenses,
        $assessment->purpose_expense_types ?? []
    ))->map(fn ($v) => trim((string) $v))->filter()->unique()->values()->all();

    $selectedPurpose = old('purpose_expense_types', $assessment->purpose_expense_types ?? $submissionExpenses);
    $selectedCategories = old('aid_categories', $assessment->aid_categories ?? []);
    $fundingBreakdown = old('funding_breakdown', $assessment->funding_breakdown ?? []);
@endphp

@section('title', 'Education Aid #' . $submission->id . ' - MUKMIN Admin')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'dashboard',
        'activeTab' => 'panel-aid',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Education Aid Case Review'])

        <div class="content-body">
            @if(session('success'))
                <div class="alert-admin alert-admin-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert-admin alert-admin-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 8px 0 0 18px; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. Header bar --}}
            <div class="ea-case-header ea-section">
                <div class="ea-case-header-top">
                    <a href="{{ route('welfare.admin.dashboard', ['admin_tab' => 'panel-aid']) }}" class="ea-case-back">
                        <i class="fas fa-arrow-left"></i> Back to Aid Panel
                    </a>
                    <div class="ea-case-title-block">
                        <h1 class="ea-case-title">EDUCATION AID #{{ $submission->id }}</h1>
                        <p class="ea-case-applicant">{{ $submission->full_name }}</p>
                    </div>
                    <div class="ea-case-badges">
                        <span class="ea-badge {{ EducationAidStatus::badgeClass($statusSlug) }}">{{ $statusLabel }}</span>
                        <span class="ea-badge ea-badge-urgency-{{ $urgencySlug }}">{{ $urgencyLabel }}</span>
                        @if(in_array($statusSlug, [
                            EducationAidStatus::RECEIVED,
                            EducationAidStatus::ASSESSMENT_PENDING,
                            EducationAidStatus::INITIAL_SCREENING,
                        ], true))
                            <span class="ea-badge ea-badge-pending">ASSESSMENT PENDING</span>
                        @endif
                    </div>
                </div>
                <div class="ea-case-meta">
                    <span>Submitted {{ $fmtDate($submission->created_at) }}</span>
                    <span class="ea-meta-sep">|</span>
                    <span>Amount Requested {{ $fmtMoney($submission->amount_requested_from_mukmin) }}</span>
                    <span class="ea-meta-sep">|</span>
                    <span>Payment Deadline {{ $fmtDate($submission->payment_deadline) }}</span>
                    <span class="ea-meta-sep">|</span>
                    <span>{{ $submission->university_institution ?: '—' }}</span>
                    <span class="ea-meta-sep">|</span>
                    <span>{{ $submission->programme_name ?: '—' }}</span>
                    <span class="ea-meta-sep">|</span>
                    <span>{{ $submission->current_year_semester ?: ($submission->current_year_semester_other ?: '—') }}</span>
                </div>
            </div>

            {{-- 2. Quick Actions --}}
            @if($canManage)
                <div class="dashboard-card ea-section">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-body ea-quick-actions">
                        @if($canStart)
                            <form method="POST" action="{{ route('welfare.admin.education-aid.start', $submission->id) }}">
                                @csrf
                                <button type="submit" class="btn-admin btn-admin-primary">
                                    <i class="fas fa-play"></i> Start Assessment
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('welfare.admin.education-aid.status', $submission->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="{{ EducationAidStatus::DOCUMENTS_INCOMPLETE }}">
                            <button type="submit" class="btn-admin btn-admin-secondary">
                                <i class="fas fa-folder-open"></i> Incomplete Documentation
                            </button>
                        </form>

                        <button type="button" class="btn-admin btn-admin-secondary" id="ea-open-interview-modal">
                            <i class="fas fa-calendar-alt"></i> Schedule Interview
                        </button>

                        <a href="#ea-section-comments" class="btn-admin btn-admin-secondary">
                            <i class="fas fa-comment"></i> Add Committee Note
                        </a>

                        <a href="{{ route('welfare.admin.education-aid.report', $submission->id) }}" class="btn-admin btn-admin-secondary" target="_blank" rel="noopener">
                            <i class="fas fa-file-alt"></i> Generate Assessment Report
                        </a>

                        <button type="button" class="btn-admin btn-admin-secondary" id="ea-toggle-applicant-edit">
                            <i class="fas fa-user-edit"></i> Edit Applicant Information
                        </button>
                    </div>
                </div>
            @endif

            {{-- 3. Assignment --}}
            <div class="dashboard-card ea-section">
                <div class="card-header">
                    <h3>Assignment</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('welfare.admin.education-aid.assignment', $submission->id) }}" class="ea-form-grid">
                        @csrf
                        <div class="admin-input-group">
                            <label for="assigned_to">Assigned To</label>
                            <select name="assigned_to" id="assigned_to" class="admin-input" {{ (! $canManage) ? 'disabled' : '' }}>
                                <option value="">Unassigned</option>
                                @foreach($assignees as $user)
                                    <option value="{{ $user->id }}" {{ (old('assigned_to', $assessment->assigned_to) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="admin-input-group">
                            <label for="assessment_due_at">Assessment Due</label>
                            <input type="date" name="assessment_due_at" id="assessment_due_at" class="admin-input"
                                   value="{{ old('assessment_due_at', optional($assessment->assessment_due_at)->format('Y-m-d')) }}"
                                   {{ (! $canManage) ? 'disabled' : '' }}>
                        </div>
                        <div class="admin-input-group">
                            <label for="urgency">Priority / Urgency</label>
                            <select name="urgency" id="urgency" class="admin-input" required {{ (! $canManage) ? 'disabled' : '' }}>
                                @foreach(EducationAidUrgency::options() as $value => $label)
                                    <option value="{{ $value }}" {{ (old('urgency', $urgencySlug) === $value) ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($assessment->urgency_reason)
                                <small class="ea-field-hint">Suggested: {{ EducationAidUrgency::label($assessment->urgency_system_suggested) }} — {{ $assessment->urgency_reason }}</small>
                            @endif
                        </div>
                        @if($canManage)
                            <div class="admin-input-group ea-form-actions">
                                <button type="submit" class="btn-admin btn-admin-primary">Save Assignment</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- 4. Status workflow --}}
            <div class="dashboard-card ea-section">
                <div class="card-header">
                    <h3>Status Workflow</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('welfare.admin.education-aid.status', $submission->id) }}" class="ea-form-inline">
                        @csrf
                        <div class="admin-input-group" style="flex: 1;">
                            <label for="case_status">Status</label>
                            <select name="status" id="case_status" class="admin-input" required {{ (! $canManage) ? 'disabled' : '' }}>
                                @foreach(EducationAidStatus::options() as $value => $label)
                                    <option value="{{ $value }}" {{ (old('status', $statusSlug) === $value) ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($canManage)
                            <button type="submit" class="btn-admin btn-admin-primary">Save Status</button>
                        @endif
                    </form>
                </div>
            </div>

            {{-- 5. Assessment Completeness --}}
            <div class="dashboard-card ea-section ea-completeness" id="ea-completeness-card">
                <div class="card-header">
                    <h3>Assessment Completeness</h3>
                    <span class="ea-completeness-percent" id="ea-completeness-percent">{{ (int) ($completeness['percent'] ?? 0) }}%</span>
                </div>
                <div class="card-body">
                    <div class="ea-progress-track" aria-hidden="true">
                        <div class="ea-progress-fill" id="ea-progress-fill" style="width: {{ (int) ($completeness['percent'] ?? 0) }}%;"></div>
                    </div>
                    <ul class="ea-completeness-list" id="ea-completeness-list">
                        @foreach(($completeness['items'] ?? []) as $key => $item)
                            <li class="{{ ($item['done'] ?? false) ? 'is-done' : 'is-pending' }}" data-completeness-key="{{ $key }}">
                                <i class="fas {{ ($item['done'] ?? false) ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                <span>{{ $item['label'] ?? $key }}</span>
                                @if(! empty($item['mandatory']))
                                    <em class="ea-mandatory">required</em>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @if($canManage)
                        <form method="POST" action="{{ route('welfare.admin.education-aid.committee-submit', $submission->id) }}" class="ea-completeness-actions">
                            @csrf
                            <button type="submit"
                                    class="btn-admin btn-admin-primary"
                                    id="ea-submit-committee-btn"
                                    {{ (empty($completeness['can_submit_to_committee'])) ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane"></i> Submit to Committee
                            </button>
                            <small class="ea-field-hint" id="ea-submit-committee-hint" {{ ! empty($completeness['can_submit_to_committee']) ? 'hidden' : '' }}>
                                Complete all mandatory items before submitting.
                            </small>
                        </form>
                    @endif
                </div>
            </div>

            {{-- 6. Case Snapshot --}}
            <div class="dashboard-card ea-section">
                <div class="card-header">
                    <h3>Case Snapshot</h3>
                </div>
                <div class="card-body">
                    @if(! empty($snapshot['funding_requires_review']))
                        <div class="ea-warning-banner">
                            <strong><i class="fas fa-exclamation-triangle"></i> Funding reconciliation requires review</strong>
                            @if(! empty($snapshot['reconciliation_notes']))
                                <ul>
                                    @foreach((array) $snapshot['reconciliation_notes'] as $note)
                                        <li>{{ $note }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                    <table class="admin-table ea-snapshot-table">
                        <tbody>
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
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 7. Layer 1 — Applicant submission --}}
            <div class="dashboard-card ea-section">
                <div class="card-header">
                    <h3>Layer 1 — Applicant Submission</h3>
                </div>
                <div class="card-body">
                    <div class="ea-readonly-grid">
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Full Name</span>
                            <span class="ea-readonly-value">{{ $submission->full_name ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">NRIC / Passport</span>
                            <span class="ea-readonly-value">{{ $submission->nric_passport ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Email</span>
                            <span class="ea-readonly-value">{{ $submission->email ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Contact</span>
                            <span class="ea-readonly-value">{{ $submission->contact_number ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">University</span>
                            <span class="ea-readonly-value">{{ $submission->university_institution ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Programme</span>
                            <span class="ea-readonly-value">{{ $submission->programme_name ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Programme Level</span>
                            <span class="ea-readonly-value">{{ $submission->programme_level ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Year / Semester</span>
                            <span class="ea-readonly-value">{{ $submission->current_year_semester ?: ($submission->current_year_semester_other ?: '—') }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">CGPA / Result</span>
                            <span class="ea-readonly-value">{{ $submission->current_cgpa_result ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Student Status</span>
                            <span class="ea-readonly-value">{{ $submission->current_student_status ?: ($submission->current_student_status_other ?: '—') }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Household Income</span>
                            <span class="ea-readonly-value">{{ $submission->household_income ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Dependents</span>
                            <span class="ea-readonly-value">{{ $submission->number_of_dependents !== null ? $submission->number_of_dependents : '—' }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Outstanding Balance</span>
                            <span class="ea-readonly-value">{{ $fmtMoney($submission->current_outstanding_amount) }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Amount Requested</span>
                            <span class="ea-readonly-value">{{ $fmtMoney($submission->amount_requested_from_mukmin) }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Payment Deadline</span>
                            <span class="ea-readonly-value">{{ $fmtDate($submission->payment_deadline) }}</span>
                        </div>
                        <div class="ea-readonly-item">
                            <span class="ea-readonly-label">Education Expense Types</span>
                            <span class="ea-readonly-value">
                                @php $expenses = $submission->education_expense_types ?? []; @endphp
                                {{ is_array($expenses) && count($expenses) ? implode(', ', $expenses) : '—' }}
                                @if(filled($submission->education_expense_other))
                                    <br><em>Other: {{ $submission->education_expense_other }}</em>
                                @endif
                            </span>
                        </div>
                        <div class="ea-readonly-item ea-readonly-full">
                            <span class="ea-readonly-label">Consequence if Payment Not Made</span>
                            <span class="ea-readonly-value">{{ $submission->payment_not_made_consequence ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item ea-readonly-full">
                            <span class="ea-readonly-label">Purpose of Request</span>
                            <span class="ea-readonly-value">{{ $submission->purpose_of_request ?: '—' }}</span>
                        </div>
                        <div class="ea-readonly-item ea-readonly-full">
                            <span class="ea-readonly-label">Financial Situation</span>
                            <span class="ea-readonly-value">{{ $submission->financial_situation_explanation ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 8. Layer 2 — Assessment form --}}
            <div class="dashboard-card ea-section" id="ea-assessment-form">
                <div class="card-header">
                    <h3>Layer 2 — Assessment</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('welfare.admin.education-aid.assessment', $submission->id) }}">
                        @csrf

                        <fieldset class="ea-fieldset" {{ (! $canManage) ? 'disabled' : '' }}>
                            <legend>Aid Categories</legend>
                            <div class="ea-checkbox-grid">
                                @foreach(EducationAidAssessment::aidCategoryOptions() as $category)
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="aid_categories[]" value="{{ $category }}"
                                               {{ (in_array($category, (array) $selectedCategories, true)) ? 'checked' : '' }}>
                                        {{ $category }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <fieldset class="ea-fieldset" {{ (! $canManage) ? 'disabled' : '' }}>
                            <legend>Purpose of Education Aid</legend>
                            <div class="ea-checkbox-grid">
                                @foreach($purposeOptions as $purpose)
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="purpose_expense_types[]" value="{{ $purpose }}"
                                               {{ (in_array($purpose, (array) $selectedPurpose, true)) ? 'checked' : '' }}>
                                        {{ $purpose }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <fieldset class="ea-fieldset" {{ (! $canManage) ? 'disabled' : '' }}>
                            <legend>Verification</legend>
                            <div class="ea-checkbox-grid">
                                @foreach([
                                    'documents_verified' => 'Documents verified',
                                    'university_balance_verified' => 'University balance verified',
                                    'ptptn_funding_verified' => 'PTPTN funding verified',
                                    'other_scholarship_verified' => 'Other scholarship verified',
                                    'interview_conducted' => 'Interview conducted',
                                ] as $field => $label)
                                    <label class="checkbox-label">
                                        <input type="hidden" name="{{ $field }}" value="0">
                                        <input type="checkbox" name="{{ $field }}" value="1"
                                               {{ (old($field, $assessment->{$field})) ? 'checked' : '' }}>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="ea-form-grid">
                            <div class="admin-input-group">
                                <label for="financial_need">Financial Need</label>
                                <select name="financial_need" id="financial_need" class="admin-input" {{ (! $canManage) ? 'disabled' : '' }}>
                                    <option value="">Select…</option>
                                    @foreach(['high' => 'High', 'moderate' => 'Moderate', 'low' => 'Low'] as $value => $label)
                                        <option value="{{ $value }}" {{ (old('financial_need', $assessment->financial_need) === $value) ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <fieldset class="ea-fieldset" {{ (! $canManage) ? 'disabled' : '' }}>
                            <legend>Funding Breakdown (RM)</legend>
                            <div class="ea-form-grid">
                                @foreach(['ptptn' => 'PTPTN', 'mied' => 'MIED', 'family' => 'Family', 'other' => 'Other'] as $key => $label)
                                    <div class="admin-input-group">
                                        <label for="funding_{{ $key }}">{{ $label }}</label>
                                        <input type="number" step="0.01" min="0" name="funding_breakdown[{{ $key }}]"
                                               id="funding_{{ $key }}" class="admin-input"
                                               value="{{ old('funding_breakdown.'.$key, $fundingBreakdown[$key] ?? '') }}">
                                    </div>
                                @endforeach
                                <div class="admin-input-group">
                                    <label for="other_confirmed_funding">Other Confirmed Funding (total)</label>
                                    <input type="number" step="0.01" min="0" name="other_confirmed_funding"
                                           id="other_confirmed_funding" class="admin-input"
                                           value="{{ old('other_confirmed_funding', $assessment->other_confirmed_funding) }}">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="ea-fieldset" {{ (! $canManage) ? 'disabled' : '' }}>
                            <legend>Interview</legend>
                            <div class="ea-form-grid">
                                <div class="admin-input-group">
                                    <label for="interview_date">Interview Date</label>
                                    <input type="date" name="interview_date" id="interview_date" class="admin-input"
                                           value="{{ old('interview_date', optional($assessment->interview_date)->format('Y-m-d')) }}">
                                </div>
                                <div class="admin-input-group">
                                    <label for="interview_conducted_by">Conducted By</label>
                                    <input type="text" name="interview_conducted_by" id="interview_conducted_by" class="admin-input"
                                           value="{{ old('interview_conducted_by', $assessment->interview_conducted_by) }}">
                                </div>
                                <div class="admin-input-group ea-form-full">
                                    <label for="interview_notes">Interview Notes</label>
                                    <textarea name="interview_notes" id="interview_notes" rows="3" class="admin-input">{{ old('interview_notes', $assessment->interview_notes) }}</textarea>
                                </div>
                            </div>
                        </fieldset>

                        @if($canManage)
                            <div class="form-actions">
                                <button type="submit" class="btn-admin btn-admin-primary">
                                    <i class="fas fa-save"></i> Save Assessment
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- 9. Document Verification --}}
            <div class="dashboard-card ea-section" id="ea-document-verification">
                <div class="card-header">
                    <h3>Document Verification</h3>
                </div>
                <div class="card-body">
                    <div id="ea-doc-toast" class="ea-doc-toast" hidden role="status" aria-live="polite"></div>

                    @if($canManage)
                        <div class="ea-doc-toolbar">
                            <label class="ea-doc-select-all">
                                <input type="checkbox" id="ea-doc-select-all">
                                <span>Select all</span>
                            </label>
                            <div class="ea-doc-bulk-actions">
                                <select id="ea-doc-bulk-status" class="admin-input" aria-label="Bulk verification status">
                                    @foreach(EducationAidDocumentCheck::statusOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ $value === 'verified' ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-admin btn-admin-primary" id="ea-doc-verify-selected">
                                    <i class="fas fa-check-double"></i> Verify selected
                                </button>
                                <button type="button" class="btn-admin btn-admin-secondary" id="ea-doc-apply-selected">
                                    Apply status to selected
                                </button>
                            </div>
                            <span class="ea-doc-selected-count" id="ea-doc-selected-count">0 selected</span>
                        </div>
                        <p class="ea-doc-upload-hint">
                            Use <strong>Upload</strong> on any document to add files (PDF, JPG, PNG, DOC, DOCX, max 10MB each).
                            Click <i class="fas fa-pen"></i> to rename a file.
                        </p>
                    @endif

                    <div class="table-responsive">
                        <table class="admin-table ea-doc-table">
                            <thead>
                                <tr>
                                    @if($canManage)
                                        <th class="ea-doc-check-col"></th>
                                    @endif
                                    <th>Document</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Reviewer</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentRows as $row)
                                    @php $check = $row['check'] ?? null; @endphp
                                    <tr class="ea-doc-row" data-document-key="{{ $row['key'] }}"
                                        data-save-url="{{ route('welfare.admin.education-aid.document', ['id' => $submission->id, 'documentKey' => $row['key']]) }}">
                                        @if($canManage)
                                            <td class="ea-doc-check-col">
                                                <input type="checkbox" class="ea-doc-row-check" aria-label="Select {{ $row['label'] }}">
                                            </td>
                                        @endif
                                        <td class="ea-doc-name-cell">
                                            <strong>{{ $row['label'] }}</strong>
                                            <ul class="ea-doc-file-list">
                                                @foreach($row['files'] as $file)
                                                    <li class="ea-doc-file" data-file-id="{{ $file['id'] }}"
                                                        data-rename-url="{{ $file['rename_url'] }}"
                                                        data-delete-url="{{ $file['delete_url'] }}">
                                                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                                                        <a href="{{ $file['url'] }}" class="ea-doc-file-name" target="_blank" rel="noopener" title="{{ $file['source_label'] }}">{{ $file['name'] }}</a>
                                                        @if($file['source'] === 'admin')
                                                            <span class="ea-doc-file-tag">Admin</span>
                                                        @endif
                                                        @if($canManage)
                                                            <button type="button" class="ea-doc-file-btn ea-doc-file-rename" title="Rename file" aria-label="Rename {{ $file['name'] }}"><i class="fas fa-pen"></i></button>
                                                            @if($file['delete_url'])
                                                                <button type="button" class="ea-doc-file-btn ea-doc-file-delete" title="Remove file" aria-label="Remove {{ $file['name'] }}"><i class="fas fa-trash-alt"></i></button>
                                                            @endif
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="ea-doc-submitted-cell">
                                            @if(! empty($row['submitted']))
                                                <span class="badge-admin badge-admin-success">Yes</span>
                                            @else
                                                <span class="badge-admin badge-admin-muted">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <select class="admin-input ea-doc-status" {{ (! $canManage) ? 'disabled' : '' }} aria-label="Status for {{ $row['label'] }}">
                                                @foreach(EducationAidDocumentCheck::statusOptions() as $value => $label)
                                                    <option value="{{ $value }}" {{ (($check->verification_status ?? 'pending') === $value) ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="ea-doc-reviewer-cell">{{ optional(optional($check)->verifier)->name ?: '—' }}</td>
                                        <td>
                                            <input type="text" class="admin-input ea-doc-remarks"
                                                   value="{{ $check->remarks ?? '' }}"
                                                   placeholder="Remarks" {{ (! $canManage) ? 'disabled' : '' }}>
                                        </td>
                                        <td>
                                            <div class="ea-doc-actions">
                                                @if($canManage)
                                                    <label class="btn-admin btn-admin-secondary ea-doc-upload-btn" title="Upload file(s) for {{ $row['label'] }}">
                                                        <i class="fas fa-upload"></i> Upload
                                                        <input type="file" class="ea-doc-upload-input" data-upload-url="{{ $row['upload_url'] }}"
                                                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" multiple hidden>
                                                    </label>
                                                    <button type="button" class="btn-admin btn-admin-primary ea-doc-save-btn">Save</button>
                                                    <span class="ea-doc-row-status" hidden></span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 10. Section comments --}}
            <div class="dashboard-card ea-section" id="ea-section-comments">
                <div class="card-header">
                    <h3>Section Comments</h3>
                </div>
                <div class="card-body">
                    @foreach(EducationAidSectionComment::sectionOptions() as $sectionKey => $sectionLabel)
                        <div class="ea-comment-section">
                            <h4>{{ $sectionLabel }}</h4>
                            @php $sectionComments = $commentsBySection->get($sectionKey, collect()); @endphp
                            @forelse($sectionComments as $comment)
                                <div class="ea-comment-item">
                                    <div class="ea-comment-meta">
                                        <strong>{{ optional($comment->user)->name ?: 'Admin' }}</strong>
                                        <span>{{ optional($comment->created_at)->format('d M Y H:i') }}</span>
                                    </div>
                                    <p>{{ $comment->comment }}</p>
                                </div>
                            @empty
                                <p class="ea-empty-hint">No comments yet.</p>
                            @endforelse

                            @if($canManage)
                                <form method="POST" action="{{ route('welfare.admin.education-aid.comment', $submission->id) }}" class="ea-comment-form">
                                    @csrf
                                    <input type="hidden" name="section" value="{{ $sectionKey }}">
                                    <div class="admin-input-group">
                                        <label for="comment_{{ $sectionKey }}">Add note</label>
                                        <textarea name="comment" id="comment_{{ $sectionKey }}" rows="2" class="admin-input" required minlength="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn-admin btn-admin-secondary">Add Comment</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 11. Reviewer Recommendation --}}
            <div class="dashboard-card ea-section">
                <div class="card-header">
                    <h3>Reviewer Recommendation</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('welfare.admin.education-aid.recommendation', $submission->id) }}">
                        @csrf
                        <div class="ea-form-grid">
                            <div class="admin-input-group">
                                <label for="recommendation">Recommendation</label>
                                <select name="recommendation" id="recommendation" class="admin-input" required {{ (! $canManage) ? 'disabled' : '' }}>
                                    <option value="">Select…</option>
                                    @foreach(EducationAidAssessment::recommendationOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ (old('recommendation', $assessment->recommendation) === $value) ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="admin-input-group">
                                <label for="recommended_amount">Recommended Amount (RM)</label>
                                <input type="number" step="0.01" min="0" name="recommended_amount" id="recommended_amount"
                                       class="admin-input" required {{ (! $canManage) ? 'disabled' : '' }}
                                       value="{{ old('recommended_amount', $assessment->recommended_amount) }}">
                            </div>
                            <div class="admin-input-group ea-form-full">
                                <label for="assessment_remarks">Remarks</label>
                                <textarea name="assessment_remarks" id="assessment_remarks" rows="3" class="admin-input"
                                          {{ (! $canManage) ? 'disabled' : '' }}>{{ old('assessment_remarks', $assessment->assessment_remarks) }}</textarea>
                            </div>
                        </div>
                        @if($assessment->recommendation_submitted_at)
                            <p class="ea-field-hint">
                                Submitted {{ $fmtDate($assessment->recommendation_submitted_at) }}
                                @if($assessment->recommendationSubmitter)
                                    by {{ $assessment->recommendationSubmitter->name }}
                                @endif
                            </p>
                        @endif
                        @if($canManage)
                            <div class="form-actions">
                                <button type="submit" class="btn-admin btn-admin-primary">
                                    <i class="fas fa-check"></i> Submit Recommendation
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- 12. Committee Decision --}}
            <div class="dashboard-card ea-section">
                <div class="card-header">
                    <h3>Committee Decision</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('welfare.admin.education-aid.committee-decision', $submission->id) }}"
                          enctype="multipart/form-data" id="ea-committee-decision-form">
                        @csrf
                        <div class="ea-form-grid">
                            <div class="admin-input-group ea-form-full">
                                <label>Decision</label>
                                <div class="ea-radio-grid">
                                    @foreach(EducationAidAssessment::committeeDecisionOptions() as $value => $label)
                                        <label class="checkbox-label">
                                            <input type="radio" name="committee_decision" value="{{ $value }}"
                                                   {{ (old('committee_decision', $assessment->committee_decision) === $value) ? 'checked' : '' }}
                                                   {{ (! $canManage) ? 'disabled' : '' }} required>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="admin-input-group">
                                <label for="approved_amount">Approved Amount (RM)</label>
                                <input type="number" step="0.01" min="0" name="approved_amount" id="approved_amount"
                                       class="admin-input" {{ (! $canManage) ? 'disabled' : '' }}
                                       value="{{ old('approved_amount', $assessment->approved_amount) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="committee_decision_date">Decision Date</label>
                                <input type="date" name="committee_decision_date" id="committee_decision_date" class="admin-input"
                                       required {{ (! $canManage) ? 'disabled' : '' }}
                                       value="{{ old('committee_decision_date', optional($assessment->committee_decision_date)->format('Y-m-d') ?: now()->format('Y-m-d')) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="committee_approved_by">Approved By</label>
                                <input type="text" name="committee_approved_by" id="committee_approved_by" class="admin-input"
                                       required {{ (! $canManage) ? 'disabled' : '' }}
                                       value="{{ old('committee_approved_by', $assessment->committee_approved_by) }}">
                            </div>
                            <div class="admin-input-group ea-form-full">
                                <label for="committee_remarks">Remarks</label>
                                <textarea name="committee_remarks" id="committee_remarks" rows="3" class="admin-input"
                                          {{ (! $canManage) ? 'disabled' : '' }}>{{ old('committee_remarks', $assessment->committee_remarks) }}</textarea>
                            </div>

                            @php
                                $receiptsByPayment = $submission->payments->mapWithKeys(fn ($p) => [$p->id => $p->receipts]);
                                $removedReceipts = array_map('intval', (array) old('remove_receipts', []));
                                $paymentRows = is_array(old('payments'))
                                    ? collect(old('payments'))->values()->map(fn ($r) => [
                                        'id' => $r['id'] ?? null,
                                        'payment_date' => $r['payment_date'] ?? '',
                                        'amount' => $r['amount'] ?? '',
                                    ])
                                    : $submission->payments->map(fn ($p) => [
                                        'id' => $p->id,
                                        'payment_date' => optional($p->payment_date)->format('Y-m-d'),
                                        'amount' => $p->amount,
                                    ])->values();
                                $totalPaid = $submission->payments->sum(fn ($p) => (float) $p->amount);
                            @endphp
                            <div class="admin-input-group ea-form-full ea-payments-group">
                                <label>Payment Date(s)</label>
                                <p class="ea-payments-hint">
                                    Add one row per payout. Receipts: PDF, JPG or PNG, max 10MB each.
                                    @if($totalPaid > 0)
                                        <span class="ea-payments-total">Total paid: RM{{ number_format($totalPaid, 2) }}</span>
                                    @endif
                                </p>
                                <div class="ea-payment-list" id="ea-payment-list" data-next-index="{{ $paymentRows->count() }}">
                                    @foreach($paymentRows as $i => $row)
                                        @include('welfare.admin.education-aid.partials.payment-row', [
                                            'index' => $i,
                                            'row' => $row,
                                            'receipts' => ! empty($row['id']) ? ($receiptsByPayment[$row['id']] ?? collect()) : collect(),
                                        ])
                                    @endforeach
                                </div>
                                @if($paymentRows->isEmpty() && ! $canManage)
                                    <p class="ea-receipt-empty">No payments recorded.</p>
                                @endif
                                @if($canManage)
                                    <button type="button" class="btn-admin btn-admin-secondary ea-add-payment" id="ea-add-payment">
                                        <i class="fas fa-plus"></i> Add payment date
                                    </button>
                                    <template id="ea-payment-template">
                                        @include('welfare.admin.education-aid.partials.payment-row', [
                                            'index' => '__INDEX__',
                                            'row' => [],
                                            'receipts' => collect(),
                                        ])
                                    </template>
                                @endif
                            </div>
                        </div>
                        @if($canManage)
                            <div class="form-actions">
                                <button type="submit" class="btn-admin btn-admin-primary">
                                    <i class="fas fa-gavel"></i> Save Committee Decision
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- 13. Case History --}}
            <div class="dashboard-card ea-section">
                <div class="card-header">
                    <h3>Case History / Audit Trail</h3>
                </div>
                <div class="card-body">
                    <ul class="ea-history-list">
                        @forelse($submission->caseEvents as $event)
                            <li>
                                <div class="ea-history-time">{{ optional($event->created_at)->format('d M Y H:i') }}</div>
                                <div class="ea-history-body">
                                    <p>{{ $event->description }}</p>
                                    <small>{{ optional($event->user)->name ?: 'System' }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="ea-empty-hint">No events recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- 14. Applicant edit panel --}}
            <div class="dashboard-card ea-section" id="applicant-edit-panel" hidden>
                <div class="card-header">
                    <h3>Edit Applicant Information</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('welfare.admin.education-aid.applicant', $submission->id) }}">
                        @csrf
                        <div class="ea-form-grid">
                            <div class="admin-input-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="admin-input" required
                                       value="{{ old('full_name', $submission->full_name) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="nric_passport">NRIC / Passport</label>
                                <input type="text" name="nric_passport" id="nric_passport" class="admin-input" required
                                       value="{{ old('nric_passport', $submission->nric_passport) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" id="dob" class="admin-input"
                                       value="{{ old('dob', optional($submission->dob)->format('Y-m-d')) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="admin-input"
                                       value="{{ old('contact_number', $submission->contact_number) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="admin-input" required
                                       value="{{ old('email', $submission->email) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="university_institution">University</label>
                                <input type="text" name="university_institution" id="university_institution" class="admin-input"
                                       value="{{ old('university_institution', $submission->university_institution) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="programme_name">Programme</label>
                                <input type="text" name="programme_name" id="programme_name" class="admin-input"
                                       value="{{ old('programme_name', $submission->programme_name) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="programme_level">Programme Level</label>
                                <input type="text" name="programme_level" id="programme_level" class="admin-input"
                                       value="{{ old('programme_level', $submission->programme_level) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="current_year_semester">Year / Semester</label>
                                <input type="text" name="current_year_semester" id="current_year_semester" class="admin-input"
                                       value="{{ old('current_year_semester', $submission->current_year_semester) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="intake_date">Intake Date</label>
                                <input type="date" name="intake_date" id="intake_date" class="admin-input"
                                       value="{{ old('intake_date', optional($submission->intake_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="expected_graduation_date">Expected Graduation</label>
                                <input type="date" name="expected_graduation_date" id="expected_graduation_date" class="admin-input"
                                       value="{{ old('expected_graduation_date', optional($submission->expected_graduation_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="household_income">Household Income</label>
                                <input type="text" name="household_income" id="household_income" class="admin-input"
                                       value="{{ old('household_income', $submission->household_income) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="father_guardian_occupation">Father / Guardian Occupation</label>
                                <input type="text" name="father_guardian_occupation" id="father_guardian_occupation" class="admin-input"
                                       value="{{ old('father_guardian_occupation', $submission->father_guardian_occupation) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="mother_guardian_occupation">Mother / Guardian Occupation</label>
                                <input type="text" name="mother_guardian_occupation" id="mother_guardian_occupation" class="admin-input"
                                       value="{{ old('mother_guardian_occupation', $submission->mother_guardian_occupation) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="number_of_dependents">Number of Dependents</label>
                                <input type="number" min="0" name="number_of_dependents" id="number_of_dependents" class="admin-input"
                                       value="{{ old('number_of_dependents', $submission->number_of_dependents) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="amount_requested_from_mukmin">Amount Requested (RM)</label>
                                <input type="number" step="0.01" min="0" name="amount_requested_from_mukmin" id="amount_requested_from_mukmin" class="admin-input"
                                       value="{{ old('amount_requested_from_mukmin', $submission->amount_requested_from_mukmin) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="current_outstanding_amount">Outstanding Amount (RM)</label>
                                <input type="number" step="0.01" min="0" name="current_outstanding_amount" id="current_outstanding_amount" class="admin-input"
                                       value="{{ old('current_outstanding_amount', $submission->current_outstanding_amount) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="total_programme_tuition_fees">Total Programme Fees (RM)</label>
                                <input type="number" step="0.01" min="0" name="total_programme_tuition_fees" id="total_programme_tuition_fees" class="admin-input"
                                       value="{{ old('total_programme_tuition_fees', $submission->total_programme_tuition_fees) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="total_amount_already_paid">Amount Already Paid (RM)</label>
                                <input type="number" step="0.01" min="0" name="total_amount_already_paid" id="total_amount_already_paid" class="admin-input"
                                       value="{{ old('total_amount_already_paid', $submission->total_amount_already_paid) }}">
                            </div>
                            <div class="admin-input-group">
                                <label for="payment_deadline">Payment Deadline</label>
                                <input type="date" name="payment_deadline" id="payment_deadline" class="admin-input"
                                       value="{{ old('payment_deadline', optional($submission->payment_deadline)->format('Y-m-d')) }}">
                            </div>
                            <div class="admin-input-group ea-form-full">
                                <label for="payment_not_made_consequence">Consequence if Payment Not Made</label>
                                <textarea name="payment_not_made_consequence" id="payment_not_made_consequence" rows="3" class="admin-input">{{ old('payment_not_made_consequence', $submission->payment_not_made_consequence) }}</textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn-admin btn-admin-secondary" id="ea-cancel-applicant-edit">Cancel</button>
                            <button type="submit" class="btn-admin btn-admin-primary">Save Applicant Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- 15. Interview modal --}}
@if($canManage)
<div class="modal-backdrop" id="interview-modal" aria-hidden="true">
    <div class="modal-window" role="dialog" aria-labelledby="interview-modal-title">
        <div class="modal-header">
            <h3 id="interview-modal-title">Schedule Interview</h3>
            <button type="button" class="modal-close" id="ea-close-interview-modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('welfare.admin.education-aid.interview', $submission->id) }}" id="ea-interview-form">
                @csrf
                <p class="ea-field-hint" style="margin-bottom: 16px;">Propose three interview dates. Emails will be sent to the applicant and committee members.</p>
                @for($i = 0; $i < 3; $i++)
                    <div class="admin-input-group">
                        <label for="proposed_date_{{ $i }}">Proposed Date {{ $i + 1 }}</label>
                        <input type="date" name="proposed_dates[]" id="proposed_date_{{ $i }}" class="admin-input" required
                               min="{{ now()->format('Y-m-d') }}"
                               value="{{ old('proposed_dates.'.$i) }}">
                    </div>
                @endfor
                <div class="admin-input-group">
                    <label for="committee_emails">Committee Emails</label>
                    <textarea name="committee_emails" id="committee_emails" rows="3" class="admin-input" required
                              placeholder="email1@example.com, email2@example.com">{{ old('committee_emails') }}</textarea>
                    <small class="ea-field-hint">Separate multiple emails with commas or new lines.</small>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-admin btn-admin-secondary" id="ea-cancel-interview-modal">Cancel</button>
                    <button type="submit" class="btn-admin btn-admin-primary">Send Interview Proposal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
(function () {
    var applicantPanel = document.getElementById('applicant-edit-panel');
    var toggleApplicantBtn = document.getElementById('ea-toggle-applicant-edit');
    var cancelApplicantBtn = document.getElementById('ea-cancel-applicant-edit');

    function showApplicantPanel() {
        if (!applicantPanel) return;
        applicantPanel.hidden = false;
        applicantPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hideApplicantPanel() {
        if (!applicantPanel) return;
        applicantPanel.hidden = true;
    }

    if (toggleApplicantBtn) {
        toggleApplicantBtn.addEventListener('click', function () {
            if (applicantPanel && applicantPanel.hidden) {
                showApplicantPanel();
            } else {
                hideApplicantPanel();
            }
        });
    }
    if (cancelApplicantBtn) {
        cancelApplicantBtn.addEventListener('click', hideApplicantPanel);
    }

    var interviewModal = document.getElementById('interview-modal');
    var openInterviewBtn = document.getElementById('ea-open-interview-modal');
    var closeInterviewBtn = document.getElementById('ea-close-interview-modal');
    var cancelInterviewBtn = document.getElementById('ea-cancel-interview-modal');

    function openInterviewModal() {
        if (!interviewModal) return;
        interviewModal.classList.add('open');
        interviewModal.setAttribute('aria-hidden', 'false');
    }

    function closeInterviewModal() {
        if (!interviewModal) return;
        interviewModal.classList.remove('open');
        interviewModal.setAttribute('aria-hidden', 'true');
    }

    if (openInterviewBtn) {
        openInterviewBtn.addEventListener('click', openInterviewModal);
    }
    if (closeInterviewBtn) {
        closeInterviewBtn.addEventListener('click', closeInterviewModal);
    }
    if (cancelInterviewBtn) {
        cancelInterviewBtn.addEventListener('click', closeInterviewModal);
    }
    if (interviewModal) {
        interviewModal.addEventListener('click', function (e) {
            if (e.target === interviewModal) {
                closeInterviewModal();
            }
        });
    }

    @if($errors->any() && old('proposed_dates'))
        openInterviewModal();
    @endif
    @if($errors->any() && old('full_name'))
        showApplicantPanel();
    @endif

    // ---- Committee decision: payouts + receipts ----
    @if($canManage)
    (function initPayments() {
        var form = document.getElementById('ea-committee-decision-form');
        var list = document.getElementById('ea-payment-list');
        var addBtn = document.getElementById('ea-add-payment');
        var template = document.getElementById('ea-payment-template');
        if (!form || !list || !addBtn || !template) return;

        var MAX_RECEIPT_BYTES = 10 * 1024 * 1024;
        var nextIndex = parseInt(list.getAttribute('data-next-index') || '0', 10);

        function renumber() {
            list.querySelectorAll('.ea-payment-row').forEach(function (row, i) {
                var label = row.querySelector('.ea-payment-no-value');
                if (label) label.textContent = i + 1;
            });
        }

        function addReceiptInput(row) {
            var index = row.getAttribute('data-index');
            var wrap = document.createElement('div');
            wrap.className = 'ea-receipt-new';
            wrap.innerHTML = '<input type="file" name="payments[' + index + '][receipts][]" accept=".pdf,.jpg,.jpeg,.png" class="admin-input" required>'
                + '<button type="button" class="ea-receipt-new-remove" title="Cancel this receipt" aria-label="Cancel this receipt"><i class="fas fa-times"></i></button>';
            row.querySelector('.ea-receipt-new-list').appendChild(wrap);
            wrap.querySelector('input').click();
        }

        addBtn.addEventListener('click', function () {
            var html = template.innerHTML.replace(/__INDEX__/g, String(nextIndex));
            nextIndex++;
            var holder = document.createElement('div');
            holder.innerHTML = html.trim();
            var row = holder.firstElementChild;
            list.appendChild(row);
            renumber();
            var dateInput = row.querySelector('input[type="date"]');
            if (dateInput) dateInput.focus();
        });

        list.addEventListener('click', function (e) {
            var row = e.target.closest('.ea-payment-row');
            if (!row) return;

            if (e.target.closest('.ea-add-receipt')) {
                addReceiptInput(row);
                return;
            }

            if (e.target.closest('.ea-receipt-new-remove')) {
                e.target.closest('.ea-receipt-new').remove();
                return;
            }

            var toggle = e.target.closest('.ea-receipt-toggle');
            if (toggle) {
                var item = toggle.closest('.ea-receipt');
                var removed = item.classList.toggle('is-removed');
                var hidden = item.querySelector('input[name="remove_receipts[]"]');
                if (removed && !hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'remove_receipts[]';
                    hidden.value = item.getAttribute('data-receipt-id');
                    item.appendChild(hidden);
                } else if (!removed && hidden) {
                    hidden.remove();
                }
                toggle.title = removed ? 'Undo remove' : 'Remove receipt';
                toggle.querySelector('i').className = 'fas ' + (removed ? 'fa-undo' : 'fa-times');
                return;
            }

            if (e.target.closest('.ea-payment-remove')) {
                var hasReceipts = row.querySelectorAll('.ea-receipt').length > 0;
                var message = hasReceipts
                    ? 'Remove this payment and its receipts? This takes effect when you save the committee decision.'
                    : 'Remove this payment?';
                if (confirm(message)) {
                    row.remove();
                    renumber();
                }
            }
        });

        form.addEventListener('submit', function (e) {
            var tooLarge = [];
            form.querySelectorAll('.ea-receipt-new input[type="file"]').forEach(function (input) {
                Array.prototype.forEach.call(input.files || [], function (file) {
                    if (file.size > MAX_RECEIPT_BYTES) tooLarge.push(file.name);
                });
            });
            if (tooLarge.length) {
                e.preventDefault();
                alert('Each receipt must not exceed 10MB.\n\nToo large:\n- ' + tooLarge.join('\n- '));
            }
        });
    })();
    @endif

    // ---- Document verification (AJAX, no page refresh) ----
    @if($canManage)
    (function initDocumentVerification() {
        var csrf = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrf ? csrf.getAttribute('content') : '';
        var bulkUrl = @json(route('welfare.admin.education-aid.documents-bulk', $submission->id));
        var toastEl = document.getElementById('ea-doc-toast');
        var selectAll = document.getElementById('ea-doc-select-all');
        var selectedCountEl = document.getElementById('ea-doc-selected-count');
        var bulkStatus = document.getElementById('ea-doc-bulk-status');
        var verifySelectedBtn = document.getElementById('ea-doc-verify-selected');
        var applySelectedBtn = document.getElementById('ea-doc-apply-selected');
        var rows = Array.prototype.slice.call(document.querySelectorAll('.ea-doc-row'));
        var remarkTimers = {};

        function showToast(message, isError) {
            if (!toastEl) return;
            toastEl.hidden = false;
            toastEl.textContent = message;
            toastEl.classList.toggle('is-error', !!isError);
            toastEl.classList.add('is-visible');
            clearTimeout(showToast._timer);
            showToast._timer = setTimeout(function () {
                toastEl.classList.remove('is-visible');
                toastEl.hidden = true;
            }, 3200);
        }

        function updateSelectedCount() {
            var count = document.querySelectorAll('.ea-doc-row-check:checked').length;
            if (selectedCountEl) {
                selectedCountEl.textContent = count + ' selected';
            }
            if (selectAll && rows.length) {
                var allChecked = count === rows.length;
                var someChecked = count > 0 && !allChecked;
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked;
            }
        }

        function applyDocumentToRow(doc) {
            if (!doc || !doc.key) return;
            var row = document.querySelector('.ea-doc-row[data-document-key="' + doc.key + '"]');
            if (!row) return;
            var statusSelect = row.querySelector('.ea-doc-status');
            var remarksInput = row.querySelector('.ea-doc-remarks');
            var reviewerCell = row.querySelector('.ea-doc-reviewer-cell');
            if (statusSelect && doc.verification_status) {
                statusSelect.value = doc.verification_status;
            }
            if (remarksInput && typeof doc.remarks !== 'undefined') {
                remarksInput.value = doc.remarks || '';
            }
            if (reviewerCell) {
                reviewerCell.textContent = doc.verified_by || '—';
            }
            row.classList.add('ea-doc-row-saved');
            setTimeout(function () { row.classList.remove('ea-doc-row-saved'); }, 900);
        }

        function updateCompleteness(completeness) {
            if (!completeness) return;
            var percentEl = document.getElementById('ea-completeness-percent');
            var fillEl = document.getElementById('ea-progress-fill');
            var submitBtn = document.getElementById('ea-submit-committee-btn');
            var hintEl = document.getElementById('ea-submit-committee-hint');
            var percent = parseInt(completeness.percent || 0, 10);

            if (percentEl) percentEl.textContent = percent + '%';
            if (fillEl) fillEl.style.width = percent + '%';

            if (completeness.items) {
                Object.keys(completeness.items).forEach(function (key) {
                    var item = completeness.items[key];
                    var li = document.querySelector('[data-completeness-key="' + key + '"]');
                    if (!li) return;
                    var done = !!item.done;
                    li.classList.toggle('is-done', done);
                    li.classList.toggle('is-pending', !done);
                    var icon = li.querySelector('i');
                    if (icon) {
                        icon.className = 'fas ' + (done ? 'fa-check-circle' : 'fa-circle');
                    }
                });
            }

            if (submitBtn) {
                submitBtn.disabled = !completeness.can_submit_to_committee;
            }
            if (hintEl) {
                hintEl.hidden = !!completeness.can_submit_to_committee;
            }
        }

        function setRowBusy(row, busy) {
            row.classList.toggle('is-saving', !!busy);
            row.querySelectorAll('select, input, button').forEach(function (el) {
                if (el.classList.contains('ea-doc-row-check')) return;
                el.disabled = !!busy;
            });
        }

        function saveRow(row) {
            var url = row.getAttribute('data-save-url');
            var statusSelect = row.querySelector('.ea-doc-status');
            var remarksInput = row.querySelector('.ea-doc-remarks');
            if (!url || !statusSelect) return Promise.resolve();

            setRowBusy(row, true);
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    verification_status: statusSelect.value,
                    remarks: remarksInput ? remarksInput.value : ''
                })
            })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok || !result.data.success) {
                    throw new Error((result.data && result.data.message) || 'Failed to save document');
                }
                applyDocumentToRow(result.data.document);
                updateCompleteness(result.data.completeness);
                showToast(result.data.message || 'Saved');
            })
            .catch(function (err) {
                showToast(err.message || 'Save failed', true);
            })
            .finally(function () {
                setRowBusy(row, false);
            });
        }

        function bulkUpdate(status, keys) {
            if (!keys.length) {
                showToast('Select at least one document first', true);
                return;
            }

            var toolbarBtns = [verifySelectedBtn, applySelectedBtn];
            toolbarBtns.forEach(function (btn) { if (btn) btn.disabled = true; });

            fetch(bulkUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    document_keys: keys,
                    verification_status: status
                })
            })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok || !result.data.success) {
                    throw new Error((result.data && result.data.message) || 'Bulk update failed');
                }
                (result.data.documents || []).forEach(applyDocumentToRow);
                updateCompleteness(result.data.completeness);
                showToast(result.data.message || 'Documents updated');
                document.querySelectorAll('.ea-doc-row-check:checked').forEach(function (cb) {
                    cb.checked = false;
                });
                updateSelectedCount();
            })
            .catch(function (err) {
                showToast(err.message || 'Bulk update failed', true);
            })
            .finally(function () {
                toolbarBtns.forEach(function (btn) { if (btn) btn.disabled = false; });
            });
        }

        function selectedKeys() {
            return Array.prototype.slice.call(document.querySelectorAll('.ea-doc-row-check:checked'))
                .map(function (cb) {
                    var row = cb.closest('.ea-doc-row');
                    return row ? row.getAttribute('data-document-key') : null;
                })
                .filter(Boolean);
        }

        rows.forEach(function (row) {
            var key = row.getAttribute('data-document-key');
            var statusSelect = row.querySelector('.ea-doc-status');
            var remarksInput = row.querySelector('.ea-doc-remarks');
            var saveBtn = row.querySelector('.ea-doc-save-btn');
            var check = row.querySelector('.ea-doc-row-check');

            if (check) {
                check.addEventListener('change', updateSelectedCount);
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
                    saveRow(row);
                });
            }

            if (remarksInput) {
                remarksInput.addEventListener('input', function () {
                    clearTimeout(remarkTimers[key]);
                    remarkTimers[key] = setTimeout(function () {
                        saveRow(row);
                    }, 700);
                });
                remarksInput.addEventListener('blur', function () {
                    clearTimeout(remarkTimers[key]);
                    saveRow(row);
                });
            }

            if (saveBtn) {
                saveBtn.addEventListener('click', function () {
                    clearTimeout(remarkTimers[key]);
                    saveRow(row);
                });
            }
        });

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                var checked = selectAll.checked;
                document.querySelectorAll('.ea-doc-row-check').forEach(function (cb) {
                    cb.checked = checked;
                });
                updateSelectedCount();
            });
        }

        if (verifySelectedBtn) {
            verifySelectedBtn.addEventListener('click', function () {
                bulkUpdate('verified', selectedKeys());
            });
        }

        if (applySelectedBtn) {
            applySelectedBtn.addEventListener('click', function () {
                var status = bulkStatus ? bulkStatus.value : 'verified';
                bulkUpdate(status, selectedKeys());
            });
        }

        // ---- Document files: admin upload, rename, remove ----
        var MAX_UPLOAD_BYTES = 10 * 1024 * 1024;

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function errorMessageFrom(response, data) {
            if (response.status === 413) {
                return 'The file is too large for the server to accept. Please upload a smaller file.';
            }
            if (data && data.errors) {
                var first = Object.keys(data.errors)[0];
                if (first && data.errors[first] && data.errors[first][0]) {
                    return data.errors[first][0];
                }
            }
            if (data && data.message) {
                return data.message;
            }
            return 'Something went wrong. Please try again.';
        }

        function sendRequest(url, method, body) {
            var headers = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            };
            if (body && !(body instanceof FormData)) {
                headers['Content-Type'] = 'application/json';
                body = JSON.stringify(body);
            }

            return fetch(url, { method: method, headers: headers, body: body })
                .then(function (response) {
                    return response.json().catch(function () { return null; }).then(function (data) {
                        if (!response.ok || !data || !data.success) {
                            throw new Error(errorMessageFrom(response, data));
                        }
                        return data;
                    });
                });
        }

        function fileItemHtml(file) {
            var html = '<li class="ea-doc-file" data-file-id="' + file.id + '"'
                + ' data-rename-url="' + escapeHtml(file.rename_url) + '"'
                + ' data-delete-url="' + escapeHtml(file.delete_url || '') + '">'
                + '<i class="fas fa-paperclip" aria-hidden="true"></i>'
                + '<a href="' + escapeHtml(file.url) + '" class="ea-doc-file-name" target="_blank" rel="noopener" title="' + escapeHtml(file.source_label) + '">' + escapeHtml(file.name) + '</a>';
            if (file.source === 'admin') {
                html += '<span class="ea-doc-file-tag">Admin</span>';
            }
            html += '<button type="button" class="ea-doc-file-btn ea-doc-file-rename" title="Rename file" aria-label="Rename ' + escapeHtml(file.name) + '"><i class="fas fa-pen"></i></button>';
            if (file.delete_url) {
                html += '<button type="button" class="ea-doc-file-btn ea-doc-file-delete" title="Remove file" aria-label="Remove ' + escapeHtml(file.name) + '"><i class="fas fa-trash-alt"></i></button>';
            }
            return html + '</li>';
        }

        function renderDocumentFiles(doc) {
            if (!doc || !doc.key) return;
            var row = document.querySelector('.ea-doc-row[data-document-key="' + doc.key + '"]');
            if (!row) return;
            var list = row.querySelector('.ea-doc-file-list');
            if (list) {
                list.innerHTML = (doc.files || []).map(fileItemHtml).join('');
            }
            var submittedCell = row.querySelector('.ea-doc-submitted-cell');
            if (submittedCell) {
                submittedCell.innerHTML = doc.submitted
                    ? '<span class="badge-admin badge-admin-success">Yes</span>'
                    : '<span class="badge-admin badge-admin-muted">No</span>';
            }
            row.classList.add('ea-doc-row-saved');
            setTimeout(function () { row.classList.remove('ea-doc-row-saved'); }, 900);
        }

        document.querySelectorAll('.ea-doc-upload-input').forEach(function (input) {
            input.addEventListener('change', function () {
                var row = input.closest('.ea-doc-row');
                var files = Array.prototype.slice.call(input.files || []);
                if (!files.length || !row) return;

                var tooLarge = files.filter(function (f) { return f.size > MAX_UPLOAD_BYTES; });
                if (tooLarge.length) {
                    showToast('Each file must not exceed 10MB. Too large: ' + tooLarge.map(function (f) { return f.name; }).join(', '), true);
                    input.value = '';
                    return;
                }

                var formData = new FormData();
                files.forEach(function (f) { formData.append('files[]', f); });

                setRowBusy(row, true);
                sendRequest(input.getAttribute('data-upload-url'), 'POST', formData)
                    .then(function (data) {
                        renderDocumentFiles(data.document);
                        showToast(data.message || 'Uploaded');
                    })
                    .catch(function (err) { showToast(err.message, true); })
                    .finally(function () {
                        input.value = '';
                        setRowBusy(row, false);
                    });
            });
        });

        function splitName(name) {
            var dot = name.lastIndexOf('.');
            if (dot <= 0) return { base: name, ext: '' };
            return { base: name.slice(0, dot), ext: name.slice(dot) };
        }

        function startRename(item) {
            if (item.classList.contains('is-renaming')) return;
            var link = item.querySelector('.ea-doc-file-name');
            if (!link) return;
            var parts = splitName(link.textContent.trim());

            item.classList.add('is-renaming');
            var editor = document.createElement('span');
            editor.className = 'ea-doc-file-editor';
            editor.innerHTML = '<input type="text" class="admin-input ea-doc-file-input" maxlength="190" aria-label="New file name">'
                + (parts.ext ? '<span class="ea-doc-file-ext">' + escapeHtml(parts.ext) + '</span>' : '')
                + '<button type="button" class="ea-doc-file-btn ea-doc-file-rename-save" title="Save name"><i class="fas fa-check"></i></button>'
                + '<button type="button" class="ea-doc-file-btn ea-doc-file-rename-cancel" title="Cancel"><i class="fas fa-times"></i></button>';
            link.insertAdjacentElement('afterend', editor);

            var field = editor.querySelector('.ea-doc-file-input');
            field.value = parts.base;
            field.focus();
            field.select();

            function cancel() {
                editor.remove();
                item.classList.remove('is-renaming');
            }

            function save() {
                var base = field.value.trim();
                if (!base) {
                    showToast('Please enter a file name.', true);
                    field.focus();
                    return;
                }
                editor.querySelectorAll('input, button').forEach(function (el) { el.disabled = true; });
                sendRequest(item.getAttribute('data-rename-url'), 'POST', { display_name: base + parts.ext })
                    .then(function (data) {
                        renderDocumentFiles(data.document);
                        showToast(data.message || 'File renamed');
                    })
                    .catch(function (err) {
                        showToast(err.message, true);
                        editor.querySelectorAll('input, button').forEach(function (el) { el.disabled = false; });
                        field.focus();
                    });
            }

            field.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); save(); }
                if (e.key === 'Escape') { e.preventDefault(); cancel(); }
            });
            editor.querySelector('.ea-doc-file-rename-save').addEventListener('click', save);
            editor.querySelector('.ea-doc-file-rename-cancel').addEventListener('click', cancel);
        }

        function deleteFile(item) {
            var link = item.querySelector('.ea-doc-file-name');
            var name = link ? link.textContent.trim() : 'this file';
            if (!confirm('Remove "' + name + '"? This cannot be undone.')) return;

            var row = item.closest('.ea-doc-row');
            if (row) setRowBusy(row, true);
            sendRequest(item.getAttribute('data-delete-url'), 'DELETE')
                .then(function (data) {
                    renderDocumentFiles(data.document);
                    showToast(data.message || 'File removed');
                })
                .catch(function (err) { showToast(err.message, true); })
                .finally(function () { if (row) setRowBusy(row, false); });
        }

        document.querySelectorAll('.ea-doc-file-list').forEach(function (list) {
            list.addEventListener('click', function (e) {
                var item = e.target.closest('.ea-doc-file');
                if (!item) return;
                if (e.target.closest('.ea-doc-file-rename')) {
                    startRename(item);
                } else if (e.target.closest('.ea-doc-file-delete')) {
                    deleteFile(item);
                }
            });
        });

        updateSelectedCount();
    })();
    @endif
})();
</script>
@endpush
