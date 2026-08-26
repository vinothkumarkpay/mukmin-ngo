@extends('welfare.layouts.app')

@section('title', 'Community Aid & Assistance Request Form - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
@include('welfare.partials.form-controls-styles')
<style>
.form-page-container {
    background: #f7f9f8;
    padding: 60px 0;
    font-family: 'Roboto', sans-serif;
}
.form-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    border: 1px solid #eaeaea;
    max-width: 800px;
    margin: 0 auto;
}
.form-header {
    text-align: center;
    margin-bottom: 40px;
}
.form-header h2 {
    font-size: 26px;
    font-weight: 700;
    color: #1e1e1e;
    margin-bottom: 10px;
}
.form-header p {
    font-size: 14.5px;
    color: #666;
    line-height: 22px;
}
.form-section-title {
    font-size: 16px;
    font-weight: 700;
    color: #d43c18;
    margin-bottom: 25px;
    border-bottom: 2px solid #f2f2f2;
    padding-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 40px;
}
.form-section-title:first-of-type {
    margin-top: 0;
}
.form-group {
    margin-bottom: 22px;
}
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d2d8d5;
    border-radius: 6px;
    outline: none;
    font-size: 14px;
    color: #333;
    transition: all 0.3s ease;
    background: #fcfdfd;
}
.form-control:focus {
    border-color: #d43c18;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(212, 60, 24, 0.08);
}
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.declaration-box {
    background: #fdf6f4;
    border-left: 4px solid #d43c18;
    padding: 20px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 30px;
}
.declaration-box label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-weight: 500;
    font-size: 13.5px;
    color: #842b15;
    cursor: pointer;
    line-height: 20px;
    margin-bottom: 12px;
}
.declaration-box label:last-child {
    margin-bottom: 0;
}
.declaration-box input[type="checkbox"] {
    margin-top: 3px;
    accent-color: #d43c18;
    cursor: pointer;
}
.btn-submit {
    background: #d43c18;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 14px 28px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    transition: background 0.3s ease;
    text-align: center;
}
.btn-submit:hover {
    background: #b83210;
}

/* Custom Dropdown */
.custom-dropdown-container {
    position: relative;
    width: 100%;
}
.dropdown-trigger {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d2d8d5;
    border-radius: 6px;
    background: #fcfdfd;
    text-align: left;
    font-size: 14px;
    color: #555;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}
.dropdown-trigger:focus, .custom-dropdown-container.open .dropdown-trigger {
    border-color: #d43c18;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(212, 60, 24, 0.08);
}
.dropdown-trigger i {
    font-size: 12px;
    color: #888;
    transition: transform 0.3s ease;
}
.custom-dropdown-container.open .dropdown-trigger i {
    transform: rotate(180deg);
}
.dropdown-options-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-top: 5px;
    max-height: 250px;
    overflow-y: auto;
    z-index: 100;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    display: none;
    padding: 8px 0;
}
.custom-dropdown-container.open .dropdown-options-list {
    display: block;
}
.dropdown-option-item:hover {
    background: #fdf6f4;
}

.checkbox-stack {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 8px;
}
.checkbox-stack label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-weight: 500;
    font-size: 14px;
    color: #444;
    cursor: pointer;
    margin-bottom: 0;
}
.checkbox-stack input[type="checkbox"] {
    margin-top: 3px;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    accent-color: #d43c18;
}
.rm-prefix {
    display: flex;
    align-items: stretch;
    width: 100%;
}
.rm-prefix span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
    padding: 0 12px;
    font-size: 14px;
    font-weight: 600;
    color: #555;
    background: #f0f3f1;
    border: 1px solid #d2d8d5;
    border-right: none;
    border-radius: 6px 0 0 6px;
    flex-shrink: 0;
}
.rm-prefix .form-control {
    flex: 1;
    width: auto;
    min-width: 0;
    border-radius: 0 6px 6px 0;
}
.rm-prefix .form-control:focus {
    position: relative;
    z-index: 1;
}
.doc-subsection-title {
    font-size: 14px;
    font-weight: 700;
    color: #333;
    margin: 28px 0 14px;
}
.field-hint {
    color: #666;
    display: block;
    margin-top: 5px;
    font-size: 12.5px;
}
.sibling-information-section > label {
    margin-bottom: 6px;
}
.sibling-entry {
    border: 1px solid #e6ece8;
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 14px;
    background: #fcfdfd;
}
.sibling-entry-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.sibling-entry-title {
    font-size: 14px;
    font-weight: 700;
    color: #0c5930;
}
.btn-remove-sibling {
    background: #ffffff;
    color: #b83210;
    border: 1px solid #f0c4b8;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}
.btn-remove-sibling:hover {
    background: #fff8f6;
}
.btn-add-sibling {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #0c5930;
    background: #ffffff;
    color: #0c5930;
    font-size: 22px;
    font-weight: 700;
    line-height: 1;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-add-sibling:hover {
    background: #0c5930;
    color: #ffffff;
}
.sibling-status-fields[hidden] {
    display: none !important;
}
@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .form-card {
        padding: 25px 20px;
    }
}
</style>

<div class="form-page-container">
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h2>MUKMIN Community Aid & Assistance Request Form</h2>
                <p>This form is intended for individuals, families, or communities seeking assistance and support through MUKMIN’s humanitarian, welfare, education, healthcare, and community aid initiatives.</p>
            </div>

            @if ($errors->any())
                <div style="background: #fdf2f2; border: 1px solid #f5baba; border-radius: 6px; padding: 15px; margin-bottom: 25px; color: #b83210; font-size: 14px;">
                    <strong>Please fix the errors below:</strong>
                    <ul style="margin: 5px 0 0 20px; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('welfare.community-aid.submit') }}" enctype="multipart/form-data">
                @csrf

                <!-- SECTION 1: APPLICANT DETAILS -->
                <div class="form-section-title">I. Applicant Details</div>
                
                <div class="form-group">
                    <label for="full_name">Full Name (as per NRIC / Passport)</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Name as per NRIC" value="{{ old('full_name') }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="nric_passport">NRIC / Passport Number</label>
                        <input type="text" id="nric_passport" name="nric_passport" class="form-control" placeholder="e.g. 900101145555" value="{{ old('nric_passport') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="gender" value="Male" {{ old('gender') === 'Male' ? 'checked' : '' }} required>
                                Male
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="gender" value="Female" {{ old('gender') === 'Female' ? 'checked' : '' }}>
                                Female
                            </label>
                        </div>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="form-control" value="{{ old('dob') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality" class="form-control" value="{{ old('nationality') }}" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="occupation">Occupation</label>
                        <input type="text" id="occupation" name="occupation" class="form-control" value="{{ old('occupation') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="monthly_income">Monthly Household Income (RM, if applicable)</label>
                        <input type="text" id="monthly_income" name="monthly_income" class="form-control" placeholder="e.g. 2500" value="{{ old('monthly_income') }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="e.g. +60123456789" value="{{ old('contact_number') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_address">Full Residential Address</label>
                    <textarea id="full_address" name="full_address" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('full_address') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="state_residency">State</label>
                    <select id="state_residency" name="state_residency" class="form-control" required>
                        <option value="">-- Choose State --</option>
                        @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya'] as $state)
                            <option value="{{ $state }}" {{ old('state_residency') == $state ? 'selected' : '' }}>{{ $state }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- SECTION 2: TYPE OF AID REQUIRED -->
                <div class="form-section-title">II. Type of Aid Required</div>

                <div class="form-group">
                    <label>Select Types of Aid Required (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="aid-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose types of aid...">
                            <span class="trigger-text">Choose types of aid...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach(['Education Aid', 'Social Aid', 'Healthcare Aid', 'Emergency / Crisis Support', 'Financial Assistance', 'Food & Basic Necessities', 'Community Support Programme', 'Others'] as $aidType)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="type_of_aid[]" value="{{ $aidType }}" id="aid-{{ $loop->index }}" {{ is_array(old('type_of_aid')) && in_array($aidType, old('type_of_aid')) ? 'checked' : '' }}>
                                    <span for="aid-{{ $loop->index }}">{{ $aidType }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-aid-group" style="display: none;">
                    <label for="type_of_aid_other">Please specify "Other" Type of Aid</label>
                    <input type="text" id="type_of_aid_other" name="type_of_aid_other" class="form-control" value="{{ old('type_of_aid_other') }}">
                </div>

                <!-- EDUCATION AID: SECTIONS 1–4 (shown when Education Aid selected) -->
                <div id="education-aid-sections" style="display: none;">
                    <div class="form-section-title">Section 1: Education Information</div>

                    <div class="form-group">
                        <label for="university_institution">University / Institution</label>
                        <input type="text" id="university_institution" name="university_institution" class="form-control" value="{{ old('university_institution') }}">
                    </div>

                    <div class="form-group">
                        <label for="programme_name">Programme Name</label>
                        <input type="text" id="programme_name" name="programme_name" class="form-control" value="{{ old('programme_name') }}">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="programme_level">Programme Level</label>
                            <select id="programme_level" name="programme_level" class="form-control">
                                <option value="">-- Choose Programme Level --</option>
                                @foreach(['Foundation', 'Certificate', 'Diploma', 'Degree', 'Postgraduate', 'Professional Qualification', 'TVET / Skills', 'Other'] as $level)
                                    <option value="{{ $level }}" {{ old('programme_level') === $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="faculty_school">Faculty / School</label>
                            <input type="text" id="faculty_school" name="faculty_school" class="form-control" value="{{ old('faculty_school') }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="current_year_semester">Current Year / Semester</label>
                            <select id="current_year_semester" name="current_year_semester" class="form-control">
                                <option value="">-- Choose Status --</option>
                                @foreach(['Newly Accepted', 'Currently Studying', 'Continuing Student', 'Final Year', 'Other'] as $yearStatus)
                                    <option value="{{ $yearStatus }}" {{ old('current_year_semester') === $yearStatus ? 'selected' : '' }}>{{ $yearStatus }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="current_student_status">Current Student Status</label>
                            <select id="current_student_status" name="current_student_status" class="form-control">
                                <option value="">-- Choose Status --</option>
                                @foreach(['Full-time', 'Part-time', 'Distance / Online Learning', 'Deferred', 'Other'] as $studentStatus)
                                    <option value="{{ $studentStatus }}" {{ old('current_student_status') === $studentStatus ? 'selected' : '' }}>{{ $studentStatus }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="intake_date">Intake Date</label>
                            <input type="date" id="intake_date" name="intake_date" class="form-control" value="{{ old('intake_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="expected_graduation_date">Expected Graduation Date</label>
                            <input type="date" id="expected_graduation_date" name="expected_graduation_date" class="form-control" value="{{ old('expected_graduation_date') }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="current_cgpa_result">Current CGPA / Latest Result</label>
                            <input type="text" id="current_cgpa_result" name="current_cgpa_result" class="form-control" placeholder="e.g. 3.50 or Pass" value="{{ old('current_cgpa_result') }}">
                        </div>
                        <div class="form-group">
                            <label for="student_id">Student ID</label>
                            <input type="text" id="student_id" name="student_id" class="form-control" value="{{ old('student_id') }}">
                        </div>
                    </div>

                    <div class="form-section-title">Section 2: Education Cost &amp; Aid Request</div>

                    <div class="form-group">
                        <label>What are you requesting financial assistance for?</label>
                        <small class="field-hint" style="margin-bottom: 8px;">Select all that apply.</small>
                        <div class="checkbox-stack">
                            @foreach([
                                'Tuition / Programme Fees',
                                'Registration / Admission Fees',
                                'Examination Fees',
                                'Accommodation',
                                'Professional / Academic Fees',
                                'Other compulsory education-related expense',
                            ] as $expenseType)
                                <label>
                                    <input type="checkbox" name="education_expense_types[]" value="{{ $expenseType }}" {{ is_array(old('education_expense_types')) && in_array($expenseType, old('education_expense_types')) ? 'checked' : '' }}>
                                    <span>{{ $expenseType }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group" id="education-expense-other-group" style="display: none;">
                        <label for="education_expense_other">Please give details of the other compulsory education-related expense</label>
                        <input type="text" id="education_expense_other" name="education_expense_other" class="form-control" value="{{ old('education_expense_other') }}">
                    </div>

                    <div class="form-group">
                        <label style="margin-bottom: 14px;">Financial Information</label>
                        @foreach([
                            'total_programme_tuition_fees' => 'Total Programme / Tuition Fees',
                            'total_amount_already_paid' => 'Total Amount Already Paid',
                            'current_outstanding_amount' => 'Current Outstanding Amount',
                            'amount_due_immediately' => 'Amount Due Immediately',
                            'amount_requested_from_mukmin' => 'Amount Requested from MUKMIN',
                        ] as $moneyField => $moneyLabel)
                            <div class="form-group">
                                <label for="{{ $moneyField }}">{{ $moneyLabel }}</label>
                                <div class="rm-prefix">
                                    <span>RM</span>
                                    <input type="number" id="{{ $moneyField }}" name="{{ $moneyField }}" class="form-control" min="0" step="0.01" value="{{ old($moneyField) }}">
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <label for="payment_deadline">Payment Deadline</label>
                            <input type="date" id="payment_deadline" name="payment_deadline" class="form-control" value="{{ old('payment_deadline') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="purpose_of_request">Purpose of Request</label>
                        <small class="field-hint" style="margin-bottom: 8px;">What is the specific education expense for which you are requesting assistance?</small>
                        <textarea id="purpose_of_request" name="purpose_of_request" rows="4" class="form-control" style="font-family: inherit;">{{ old('purpose_of_request') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="payment_not_made_consequence">What happens if this payment is not made?</label>
                        <textarea id="payment_not_made_consequence" name="payment_not_made_consequence" rows="4" class="form-control" style="font-family: inherit;">{{ old('payment_not_made_consequence') }}</textarea>
                    </div>

                    <div class="form-section-title">Section 3: Socioeconomic Background</div>

                    <div class="form-group">
                        <label>Household Income</label>
                        <div class="radio-group" style="flex-direction: column; gap: 12px;">
                            @foreach(['Below RM 2,000', 'RM 2,001 to RM 5,000'] as $income)
                                <label class="radio-label">
                                    <input type="radio" name="household_income" value="{{ $income }}" {{ old('household_income') === $income ? 'checked' : '' }}>
                                    {{ $income }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="father_guardian_name">Father/Guardian Name</label>
                            <input type="text" id="father_guardian_name" name="father_guardian_name" class="form-control" value="{{ old('father_guardian_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="father_guardian_occupation">Father/Guardian Occupation</label>
                            <input type="text" id="father_guardian_occupation" name="father_guardian_occupation" class="form-control" value="{{ old('father_guardian_occupation') }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="mother_guardian_name">Mother/Guardian Name</label>
                            <input type="text" id="mother_guardian_name" name="mother_guardian_name" class="form-control" value="{{ old('mother_guardian_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="mother_guardian_occupation">Mother/Guardian Occupation</label>
                            <input type="text" id="mother_guardian_occupation" name="mother_guardian_occupation" class="form-control" value="{{ old('mother_guardian_occupation') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="proof_of_income">Upload Proof of Income</label>
                        <small class="field-hint" style="margin-bottom: 8px;">Requirement: Please upload proof of income for both parents if both are currently working.</small>
                        <input type="file" id="proof_of_income" name="proof_of_income[]" class="form-control" multiple style="padding: 10px 16px;">
                        <small class="field-hint">You can upload multiple files. PDF, JPG, PNG, DOC, DOCX. Max size: 20MB per file.</small>
                    </div>

                    <div class="form-group">
                        <label for="government_assistance_status">Proof of Government Assistance / Welfare Status</label>
                        <select id="government_assistance_status" name="government_assistance_status" class="form-control">
                            <option value="">-- Choose Status --</option>
                            @foreach([
                                'Sumbangan Tunai Rahmah (STR)',
                                'Bantuan Sara Hidup (BSH)',
                                'Sumbangan Asas Rahmah (SARA)',
                                'Zakat / Baitulmal Assistance Recipient',
                            ] as $status)
                                <option value="{{ $status }}" {{ old('government_assistance_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="proof_of_government_assistance">Upload Proof of Government Assistance / Welfare</label>
                        <input type="file" id="proof_of_government_assistance" name="proof_of_government_assistance" class="form-control" style="padding: 10px 16px;">
                        <small class="field-hint">PDF, JPG, PNG, DOC, DOCX. Max size: 20MB.</small>
                    </div>

                    <div class="form-group">
                        <label for="number_of_dependents">Number of Dependents in Household</label>
                        <input type="number" id="number_of_dependents" name="number_of_dependents" class="form-control" min="0" max="20" value="{{ old('number_of_dependents') }}">
                    </div>

                    @php
                        $siblingRows = old('sibling_information');
                        if (!is_array($siblingRows) || count($siblingRows) === 0) {
                            $siblingRows = [[]];
                        }
                    @endphp

                    <div class="form-group sibling-information-section">
                        <label>Sibling Information</label>
                        <small class="field-hint">Add details for each sibling. Click + to add another.</small>
                        <div id="sibling-information-list">
                            @foreach ($siblingRows as $index => $sibling)
                                <div class="sibling-entry" data-sibling-entry>
                                    <div class="sibling-entry-header">
                                        <span class="sibling-entry-title">Sibling {{ $index + 1 }}</span>
                                        <button type="button" class="btn-remove-sibling" data-remove-sibling @if(count($siblingRows) === 1) hidden @endif aria-label="Remove sibling">Remove</button>
                                    </div>
                                    <div class="form-group">
                                        <label for="sibling_name_{{ $index }}">Name</label>
                                        <input type="text" id="sibling_name_{{ $index }}" name="sibling_information[{{ $index }}][name]" class="form-control" value="{{ $sibling['name'] ?? '' }}" maxlength="255">
                                    </div>
                                    <div class="grid-2">
                                        <div class="form-group">
                                            <label for="sibling_age_{{ $index }}">Age</label>
                                            <input type="number" id="sibling_age_{{ $index }}" name="sibling_information[{{ $index }}][age]" class="form-control" value="{{ $sibling['age'] ?? '' }}" min="0" max="100">
                                        </div>
                                        <div class="form-group">
                                            <label for="sibling_status_{{ $index }}">Status</label>
                                            <select id="sibling_status_{{ $index }}" name="sibling_information[{{ $index }}][status]" class="form-control sibling-status-select" data-sibling-status>
                                                <option value="">-- Choose Status --</option>
                                                <option value="Studying" {{ ($sibling['status'] ?? '') === 'Studying' ? 'selected' : '' }}>Studying</option>
                                                <option value="Working" {{ ($sibling['status'] ?? '') === 'Working' ? 'selected' : '' }}>Working</option>
                                                <option value="Not Working" {{ ($sibling['status'] ?? '') === 'Not Working' ? 'selected' : '' }}>Not Working</option>
                                                <option value="Not Yet in School" {{ ($sibling['status'] ?? '') === 'Not Yet in School' ? 'selected' : '' }}>Not Yet in School</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sibling-status-fields sibling-studying-fields" data-sibling-studying @unless(($sibling['status'] ?? '') === 'Studying') hidden @endunless>
                                        <div class="form-group">
                                            <label for="sibling_program_{{ $index }}">Programme</label>
                                            <input type="text" id="sibling_program_{{ $index }}" name="sibling_information[{{ $index }}][program]" class="form-control" value="{{ $sibling['program'] ?? '' }}" maxlength="255">
                                        </div>
                                        <div class="form-group">
                                            <label for="sibling_university_{{ $index }}">University</label>
                                            <input type="text" id="sibling_university_{{ $index }}" name="sibling_information[{{ $index }}][university]" class="form-control" value="{{ $sibling['university'] ?? '' }}" maxlength="255">
                                        </div>
                                    </div>
                                    <div class="sibling-status-fields sibling-working-fields" data-sibling-working @unless(($sibling['status'] ?? '') === 'Working') hidden @endunless>
                                        <div class="form-group">
                                            <label for="sibling_profession_{{ $index }}">Profession</label>
                                            <input type="text" id="sibling_profession_{{ $index }}" name="sibling_information[{{ $index }}][profession]" class="form-control" value="{{ $sibling['profession'] ?? '' }}" maxlength="255">
                                        </div>
                                    </div>
                                    <div class="sibling-status-fields sibling-not-working-fields" data-sibling-not-working @unless(($sibling['status'] ?? '') === 'Not Working') hidden @endunless>
                                        <div class="form-group">
                                            <label for="sibling_reason_{{ $index }}">Reason</label>
                                            <input type="text" id="sibling_reason_{{ $index }}" name="sibling_information[{{ $index }}][reason]" class="form-control" value="{{ $sibling['reason'] ?? '' }}" maxlength="255" placeholder="Please state why they are not currently working">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-sibling-btn" class="btn-add-sibling" aria-label="Add sibling">+</button>
                    </div>

                    <template id="sibling-entry-template">
                        <div class="sibling-entry" data-sibling-entry>
                            <div class="sibling-entry-header">
                                <span class="sibling-entry-title">Sibling __NUMBER__</span>
                                <button type="button" class="btn-remove-sibling" data-remove-sibling aria-label="Remove sibling">Remove</button>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="sibling_information[__INDEX__][name]" class="form-control" maxlength="255">
                            </div>
                            <div class="grid-2">
                                <div class="form-group">
                                    <label>Age</label>
                                    <input type="number" name="sibling_information[__INDEX__][age]" class="form-control" min="0" max="100">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="sibling_information[__INDEX__][status]" class="form-control sibling-status-select" data-sibling-status>
                                        <option value="">-- Choose Status --</option>
                                        <option value="Studying">Studying</option>
                                        <option value="Working">Working</option>
                                        <option value="Not Working">Not Working</option>
                                        <option value="Not Yet in School">Not Yet in School</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sibling-status-fields sibling-studying-fields" data-sibling-studying hidden>
                                <div class="form-group">
                                    <label>Programme</label>
                                    <input type="text" name="sibling_information[__INDEX__][program]" class="form-control" maxlength="255">
                                </div>
                                <div class="form-group">
                                    <label>University</label>
                                    <input type="text" name="sibling_information[__INDEX__][university]" class="form-control" maxlength="255">
                                </div>
                            </div>
                            <div class="sibling-status-fields sibling-working-fields" data-sibling-working hidden>
                                <div class="form-group">
                                    <label>Profession</label>
                                    <input type="text" name="sibling_information[__INDEX__][profession]" class="form-control" maxlength="255">
                                </div>
                            </div>
                            <div class="sibling-status-fields sibling-not-working-fields" data-sibling-not-working hidden>
                                <div class="form-group">
                                    <label>Reason</label>
                                    <input type="text" name="sibling_information[__INDEX__][reason]" class="form-control" maxlength="255" placeholder="Please state why they are not currently working">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="form-group">
                        <label for="other_scholarship_details">Are you receiving any other scholarship? If yes, kindly specify.</label>
                        <textarea id="other_scholarship_details" name="other_scholarship_details" rows="3" class="form-control" style="font-family: inherit;">{{ old('other_scholarship_details') }}</textarea>
                    </div>

                    <div class="form-section-title">Section 4: Document Upload</div>

                    <div class="doc-subsection-title">Applicant Documents</div>
                    @foreach([
                        'nric_front' => 'NRIC — Front',
                        'nric_back' => 'NRIC — Back',
                        'academic_result' => 'SPM / STPM / Diploma / Relevant Academic Result',
                        'latest_academic_transcript' => 'Latest Academic Transcript / Result',
                        'university_offer_letter' => 'University Offer Letter / Confirmation of Enrolment',
                        'student_id_confirmation' => 'Student ID / Current Student Confirmation',
                    ] as $docField => $docLabel)
                        <div class="form-group">
                            <label for="{{ $docField }}">{{ $docLabel }}</label>
                            <input type="file" id="{{ $docField }}" name="{{ $docField }}" class="form-control" style="padding: 10px 16px;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <small class="field-hint">PDF, JPG, PNG, DOC, DOCX. Max size: 2MB.</small>
                        </div>
                    @endforeach

                    <div class="doc-subsection-title">Education Cost Documents</div>
                    @foreach([
                        'university_fee_statement' => 'Official University Fee Statement',
                        'official_invoice' => 'Official Invoice / Payment Notice',
                        'outstanding_balance_statement' => 'Statement Showing Outstanding Balance',
                        'payment_deadline_notice' => 'Payment Deadline / Demand Notice (where applicable)',
                    ] as $docField => $docLabel)
                        <div class="form-group">
                            <label for="{{ $docField }}">{{ $docLabel }}</label>
                            <input type="file" id="{{ $docField }}" name="{{ $docField }}" class="form-control" style="padding: 10px 16px;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <small class="field-hint">PDF, JPG, PNG, DOC, DOCX. Max size: 2MB.</small>
                        </div>
                    @endforeach

                    <div class="doc-subsection-title">Additional Supporting Documents</div>
                    <div class="form-group">
                        <label for="additional_supporting_documents">Upload any document supporting your request for assistance</label>
                        <small class="field-hint" style="margin-bottom: 8px;">Examples: medical circumstances, retrenchment letter, death certificate, disability documentation, university warning/suspension notice, etc.</small>
                        <input type="file" id="additional_supporting_documents" name="additional_supporting_documents[]" class="form-control" multiple style="padding: 10px 16px;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.zip">
                        <small class="field-hint">Optional. Multiple files allowed. PDF, JPG, PNG, DOC, DOCX, ZIP. Max size: 2MB per file.</small>
                    </div>
                </div>

                <!-- GENERAL AID: SECTIONS III–IV (hidden when Education Aid is the only selection) -->
                <div id="general-aid-sections">
                    <div class="form-section-title">III. Details of Assistance Required</div>

                    <div class="form-group">
                        <label for="situation_description">Please describe your current situation and the type of assistance required:</label>
                        <textarea id="situation_description" name="situation_description" rows="5" class="form-control" style="font-family: inherit;" required>{{ old('situation_description') }}</textarea>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="who_benefits">Who will benefit from this assistance?</label>
                            <select id="who_benefits" name="who_benefits" class="form-control" required>
                                <option value="">-- Select Beneficiary Type --</option>
                                @foreach(['Individual', 'Family', 'Community / Group', 'Organisation / Institution'] as $benefitType)
                                    <option value="{{ $benefitType }}" {{ old('who_benefits') == $benefitType ? 'selected' : '' }}>{{ $benefitType }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="number_of_beneficiaries">If applicable, number of beneficiaries</label>
                            <input type="number" id="number_of_beneficiaries" name="number_of_beneficiaries" class="form-control" min="1" value="{{ old('number_of_beneficiaries') }}">
                        </div>
                    </div>

                    <div class="form-section-title">IV. Supporting Information</div>

                    <div class="form-group">
                        <label>Have you previously received aid or assistance from any organisation?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="received_aid_before" value="1" {{ old('received_aid_before') === '1' ? 'checked' : '' }} required>
                                Yes
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="received_aid_before" value="0" {{ old('received_aid_before') === '0' ? 'checked' : '' }}>
                                No
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="received-aid-details-group" style="display: none;">
                        <label for="received_aid_before_details">If yes, please specify details (Organisation name, type of aid, date received)</label>
                        <textarea id="received_aid_before_details" name="received_aid_before_details" rows="3" class="form-control" style="font-family: inherit;">{{ old('received_aid_before_details') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="supporting_files">Upload Supporting Documents (Income Statements, Medical Bills, Police Reports, Situation Photos, etc.)</label>
                        <input type="file" id="supporting_files" name="supporting_files[]" class="form-control" multiple style="padding: 10px 16px;">
                        <small class="field-hint">You can upload multiple files (PDF, JPG, PNG, DOCX, ZIP). Max size: 20MB per file.</small>
                    </div>
                </div>

                <!-- EMERGENCY CONTACT (hidden when Education Aid is the only selection) -->
                <div id="emergency-contact-section">
                    <div class="form-section-title" id="emergency-contact-section-title">V. Emergency Contact</div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="emergency_contact_name">Full Name</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-control" placeholder="Name as per NRIC" value="{{ old('emergency_contact_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency_contact_relationship">Relationship</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-control" value="{{ old('emergency_contact_relationship') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact_phone">Contact Number</label>
                        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" class="form-control" placeholder="e.g. +60123456789" value="{{ old('emergency_contact_phone') }}" required>
                    </div>
                </div>

                <!-- DECLARATION & CONSENT -->
                <div class="form-section-title" id="declaration-section-title">VI. Declaration & Consent</div>

                <div class="declaration-box">
                    <label>
                        <input type="checkbox" name="declaration_confirmed" value="1" required {{ old('declaration_confirmed') ? 'checked' : '' }}>
                        I confirm that all information provided in this form is true, accurate, and submitted voluntarily.
                    </label>
                    <label>
                        <input type="checkbox" required>
                        I hereby consent to MUKMIN collecting, processing, storing, and using my personal data for the purpose of assessing, managing, and administering aid, assistance, welfare, and related community support initiatives.
                    </label>
                    <label>
                        <input type="checkbox" required>
                        I understand that any documents, photographs, videos, testimonials, or information submitted may be used by MUKMIN for internal assessment, reporting, audit, documentation, fundraising, awareness campaigns, publicity, or promotional purposes related to MUKMIN’s humanitarian and community initiatives.
                    </label>
                    <label>
                        <input type="checkbox" required>
                        I understand that submission of this form does not guarantee approval of assistance and is subject to MUKMIN’s review, verification, and available resources.
                    </label>
                </div>

                @include('welfare.partials.important-notes')

                <button type="submit" class="btn-submit">Submit Aid Request</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const generalAidSections = document.getElementById('general-aid-sections');
    const educationAidSections = document.getElementById('education-aid-sections');
    const receivedAidDetailsGroup = document.getElementById('received-aid-details-group');
    const receivedAidDetailsTextarea = document.getElementById('received_aid_before_details');
    const receivedAidRadios = document.querySelectorAll('input[name="received_aid_before"]');

    function setRequiredIn(container, enabled) {
        if (!container) return;

        container.querySelectorAll('input, select, textarea').forEach(function (el) {
            if (el.type === 'file' || el.type === 'checkbox' || el.type === 'button' || el.type === 'hidden') {
                return;
            }
            if (el.name === 'number_of_beneficiaries' || el.name === 'received_aid_before_details') {
                return;
            }
            if (el.name && el.name.indexOf('sibling_information') === 0) {
                return;
            }
            if (el.name === 'education_expense_other') {
                return;
            }
            if (enabled) {
                el.setAttribute('required', 'required');
            } else {
                el.removeAttribute('required');
            }
        });

        container.querySelectorAll('input[type="radio"][name="household_income"], input[type="radio"][name="received_aid_before"]').forEach(function (el) {
            if (enabled) {
                el.setAttribute('required', 'required');
            } else {
                el.removeAttribute('required');
            }
        });

        const optionalFiles = ['payment_deadline_notice', 'additional_supporting_documents'];
        container.querySelectorAll('input[type="file"]').forEach(function (el) {
            const name = el.name || '';
            const baseName = name.replace(/\[\]$/, '');
            if (optionalFiles.indexOf(baseName) !== -1 || name === 'supporting_files[]') {
                el.removeAttribute('required');
                return;
            }
            if (enabled && educationAidSections && educationAidSections.contains(el)) {
                el.setAttribute('required', 'required');
            } else {
                el.removeAttribute('required');
            }
        });
    }

    function toggleReceivedAidDetails() {
        if (!receivedAidDetailsGroup || !receivedAidDetailsTextarea) return;
        if (generalAidSections && generalAidSections.style.display === 'none') {
            receivedAidDetailsGroup.style.display = 'none';
            receivedAidDetailsTextarea.removeAttribute('required');
            return;
        }
        const selectedRadio = document.querySelector('input[name="received_aid_before"]:checked');
        if (selectedRadio && selectedRadio.value === '1') {
            receivedAidDetailsGroup.style.display = 'block';
            receivedAidDetailsTextarea.setAttribute('required', 'required');
        } else {
            receivedAidDetailsGroup.style.display = 'none';
            receivedAidDetailsTextarea.removeAttribute('required');
        }
    }

    function toggleEducationExpenseOther() {
        const otherGroup = document.getElementById('education-expense-other-group');
        const otherInput = document.getElementById('education_expense_other');
        if (!otherGroup || !otherInput) return;

        const checked = Array.from(document.querySelectorAll('input[name="education_expense_types[]"]:checked'));
        const hasOther = checked.some(cb => cb.value === 'Other compulsory education-related expense');
        const educationVisible = educationAidSections && educationAidSections.style.display !== 'none';

        if (educationVisible && hasOther) {
            otherGroup.style.display = 'block';
            otherInput.setAttribute('required', 'required');
        } else {
            otherGroup.style.display = 'none';
            otherInput.removeAttribute('required');
        }
    }

    function isEducationAidSelected() {
        return Array.from(document.querySelectorAll('#aid-dropdown input[name="type_of_aid[]"]:checked'))
            .some(cb => cb.value === 'Education Aid');
    }

    function hasNonEducationAidSelected() {
        return Array.from(document.querySelectorAll('#aid-dropdown input[name="type_of_aid[]"]:checked'))
            .some(cb => cb.value !== 'Education Aid');
    }

    function syncAidSections() {
        if (!generalAidSections || !educationAidSections) return;

        const showEducation = isEducationAidSelected();
        // Show III & IV for non-education aids, or when Education Aid is combined with other aid types
        const showGeneral = !showEducation || hasNonEducationAidSelected();
        // Emergency contact is hidden when Education Aid is the only selection
        const showEmergency = showGeneral || !showEducation;
        const emergencySection = document.getElementById('emergency-contact-section');

        educationAidSections.style.display = showEducation ? 'block' : 'none';
        generalAidSections.style.display = showGeneral ? 'block' : 'none';
        if (emergencySection) {
            emergencySection.style.display = showEmergency ? 'block' : 'none';
        }

        setRequiredIn(educationAidSections, showEducation);
        setRequiredIn(generalAidSections, showGeneral);
        setRequiredIn(emergencySection, showEmergency);

        // Education Aid only: Sections 1–4, then III. Declaration (Emergency hidden)
        // Otherwise keep V / VI when general III–IV are present
        const declarationTitle = document.getElementById('declaration-section-title');
        const emergencyTitle = document.getElementById('emergency-contact-section-title');
        if (declarationTitle) {
            if (showEducation && !showGeneral) {
                declarationTitle.textContent = 'III. Declaration & Consent';
            } else {
                declarationTitle.textContent = 'VI. Declaration & Consent';
            }
        }
        if (emergencyTitle) {
            emergencyTitle.textContent = 'V. Emergency Contact';
        }

        if (showGeneral) {
            toggleReceivedAidDetails();
        }

        toggleEducationExpenseOther();
    }

    function toggleEducationAidSections() {
        syncAidSections();
    }

    // Custom Dropdown triggers
    const dropdowns = document.querySelectorAll('.custom-dropdown-container');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
        const triggerText = trigger.querySelector('.trigger-text');

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdowns.forEach(other => {
                if (other !== dropdown) other.classList.remove('open');
            });
            dropdown.classList.toggle('open');
        });

        function updateText() {
            const checked = Array.from(checkboxes).filter(cb => cb.checked);
            if (checked.length === 0) {
                triggerText.textContent = trigger.getAttribute('data-placeholder') || 'Select options';
            } else if (checked.length <= 2) {
                triggerText.textContent = checked.map(cb => cb.parentNode.textContent.trim()).join(', ');
            } else {
                triggerText.textContent = checked.length + ' options selected';
            }

            if (dropdown.id === 'aid-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase() === 'others');
                const otherGroup = document.getElementById('other-aid-group');
                const otherInput = document.getElementById('type_of_aid_other');

                if (hasOther) {
                    otherGroup.style.display = 'block';
                    otherInput.setAttribute('required', 'required');
                } else {
                    otherGroup.style.display = 'none';
                    otherInput.removeAttribute('required');
                }

                toggleEducationAidSections();
            }
        }

        dropdown.addEventListener('change', updateText);

        dropdown.querySelectorAll('.dropdown-option-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.stopPropagation();
                if (e.target.type !== 'checkbox') {
                    const cb = item.querySelector('input[type="checkbox"]');
                    cb.checked = !cb.checked;
                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });

        updateText();
    });

    document.addEventListener('click', function () {
        dropdowns.forEach(d => d.classList.remove('open'));
    });

    // Direct listener as a reliable fallback for Education Aid toggle
    document.querySelectorAll('#aid-dropdown input[name="type_of_aid[]"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            toggleEducationAidSections();
        });
    });

    receivedAidRadios.forEach(radio => {
        radio.addEventListener('change', toggleReceivedAidDetails);
    });

    document.querySelectorAll('input[name="education_expense_types[]"]').forEach(function (cb) {
        cb.addEventListener('change', toggleEducationExpenseOther);
    });

    // Sibling information (Education Aid socioeconomic section)
    const siblingList = document.getElementById('sibling-information-list');
    const addSiblingBtn = document.getElementById('add-sibling-btn');
    const siblingTemplate = document.getElementById('sibling-entry-template');

    function updateSiblingStatusFields(entry) {
        const statusSelect = entry.querySelector('[data-sibling-status]');
        const studyingFields = entry.querySelector('[data-sibling-studying]');
        const workingFields = entry.querySelector('[data-sibling-working]');
        const notWorkingFields = entry.querySelector('[data-sibling-not-working]');
        if (!statusSelect || !studyingFields || !workingFields || !notWorkingFields) {
            return;
        }

        const status = statusSelect.value;
        studyingFields.hidden = status !== 'Studying';
        workingFields.hidden = status !== 'Working';
        notWorkingFields.hidden = status !== 'Not Working';
    }

    function reindexSiblingEntries() {
        if (!siblingList) {
            return;
        }

        const entries = siblingList.querySelectorAll('[data-sibling-entry]');
        entries.forEach(function (entry, index) {
            const title = entry.querySelector('.sibling-entry-title');
            if (title) {
                title.textContent = 'Sibling ' + (index + 1);
            }

            entry.querySelectorAll('[name^="sibling_information["]').forEach(function (field) {
                field.name = field.name.replace(/sibling_information\[\d+\]/, 'sibling_information[' + index + ']');
            });

            const removeBtn = entry.querySelector('[data-remove-sibling]');
            if (removeBtn) {
                removeBtn.hidden = entries.length === 1;
            }
        });
    }

    function bindSiblingEntry(entry) {
        const statusSelect = entry.querySelector('[data-sibling-status]');
        const removeBtn = entry.querySelector('[data-remove-sibling]');

        if (statusSelect) {
            statusSelect.addEventListener('change', function () {
                updateSiblingStatusFields(entry);
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                entry.remove();
                if (!siblingList.querySelector('[data-sibling-entry]')) {
                    addSiblingEntry();
                } else {
                    reindexSiblingEntries();
                }
            });
        }

        updateSiblingStatusFields(entry);
    }

    function addSiblingEntry() {
        if (!siblingList || !siblingTemplate) {
            return;
        }

        const index = siblingList.querySelectorAll('[data-sibling-entry]').length;
        const html = siblingTemplate.innerHTML
            .replace(/__INDEX__/g, String(index))
            .replace(/__NUMBER__/g, String(index + 1));

        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        const entry = wrapper.firstElementChild;
        siblingList.appendChild(entry);
        bindSiblingEntry(entry);
        reindexSiblingEntries();
    }

    if (siblingList) {
        siblingList.querySelectorAll('[data-sibling-entry]').forEach(bindSiblingEntry);
    }

    if (addSiblingBtn) {
        addSiblingBtn.addEventListener('click', addSiblingEntry);
    }

    toggleReceivedAidDetails();
    toggleEducationExpenseOther();
    toggleEducationAidSections();
});
</script>
@endpush
@endsection
