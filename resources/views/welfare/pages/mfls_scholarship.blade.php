@extends('welfare.layouts.app')

@section('title', 'MUKMIN Future Leaders Scholarship (MFLS) Application Form')

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
    margin-bottom: 14px;
    text-align: justify;
}
.form-header .form-tagline {
    font-size: 16px;
    font-weight: 700;
    color: #0c5930;
    margin: 18px 0 0;
}
.form-partner-brand {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin: 4px auto 28px;
    padding: 12px 0 4px;
}
.form-partner-brand img {
    display: block;
    max-width: min(400px, 92%);
    width: auto;
    height: 132px;
    object-fit: contain;
    margin: 0 auto;
}
.form-programme-select {
    margin-bottom: 28px;
}
.form-programme-select .form-control {
    font-size: 15px;
    padding: 14px 16px;
}
.partner-required-notice {
    background: #fff8f0;
    border: 1px solid #f0d9b8;
    border-radius: 8px;
    padding: 18px 20px;
    margin-bottom: 25px;
    font-size: 14px;
    color: #8a4b12;
    line-height: 22px;
    text-align: center;
}
.partner-required-notice a {
    color: #d43c18;
    font-weight: 700;
    text-decoration: none;
}
.partner-required-notice a:hover {
    text-decoration: underline;
}
.deadline-banner {
    background: #fff8f0;
    border: 1px solid #f0d9b8;
    border-radius: 8px;
    padding: 14px 18px;
    margin-bottom: 25px;
    font-size: 14px;
    color: #8a4b12;
    text-align: center;
    font-weight: 600;
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
.eligibility-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.eligibility-modal-overlay.is-visible {
    display: flex;
}
.eligibility-modal {
    background: #ffffff;
    border-radius: 10px;
    padding: 28px 24px;
    max-width: 440px;
    width: 100%;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
    text-align: center;
}
.eligibility-modal h3 {
    font-size: 18px;
    color: #d43c18;
    margin: 0 0 12px;
}
.eligibility-modal p {
    font-size: 14px;
    color: #444;
    line-height: 22px;
    margin: 0 0 20px;
}
.eligibility-modal button {
    background: #0c5930;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 10px 24px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}
.declaration-box {
    background: #fdf6f4;
    border-left: 4px solid #d43c18;
    padding: 20px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 30px;
}
.declaration-box p {
    font-size: 13.5px;
    color: #842b15;
    line-height: 20px;
    margin-bottom: 14px;
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
.field-hint {
    color: #666;
    display: block;
    margin-top: 5px;
    font-size: 12.5px;
}
.field-error {
    color: #b83210;
    font-size: 12.5px;
    margin-top: 6px;
    display: block;
}
.form-control.is-invalid {
    border-color: #e57373;
    background: #fffafa;
}
.word-count {
    color: #666;
    font-size: 12px;
    margin-top: 6px;
}
.word-count.invalid {
    color: #b83210;
    font-weight: 600;
}
@media (max-width: 768px) {
    .grid-2 {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .form-card {
        padding: 25px 20px;
    }
    .form-partner-brand img {
        max-width: min(320px, 94%);
        height: 108px;
    }
}
</style>

<div class="form-page-container">
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h2>MUKMIN Future Leaders Scholarship (MFLS) Application Form</h2>
                <p>The MUKMIN Future Leaders Scholarship (MFLS) is a national talent development initiative by MUKMIN, designed to unlock the full potential of the Indian Muslim community through a dual pathway model—integrating TVET (skills and technical pathways) and academic education.</p>
                <p>The programme provides access to TVET, Foundation, Diploma, Degree and Master programmes in collaboration with leading universities and institutions—ensuring multiple pathways for talents to progress, excel and succeed.</p>
                <p>Facilitated by FIKRAH, MUKMIN's strategic think tank, MFLS goes beyond financial support by building a future-ready talent pipeline—developing individuals who are not only qualified, but skilled, adaptable and driven to contribute meaningfully to society and the nation.</p>
                <p class="form-tagline">Apply Now. Lead the Future.</p>
            </div>

            <div class="deadline-banner">
                Applications close on 15th July 2026.
            </div>

            @if (!$selectedPartner)
                <div class="partner-required-notice">
                    Please select a partner institution on the
                    <a href="{{ route('welfare.impact.mfls') }}">MFLS page</a>,
                    open <strong>More Info</strong>, then click <strong>Apply Now</strong> to start your application.
                </div>
            @endif

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

            <form method="POST" action="{{ route('welfare.mfls-scholarship.submit') }}" enctype="multipart/form-data" id="mfls-form" novalidate>
                @csrf
                @if ($selectedPartner)
                    <input type="hidden" name="partner_id" value="{{ $selectedPartner['id'] }}">
                @endif

                @if ($selectedPartner)
                    <div class="form-partner-brand">
                        <img src="{{ asset($selectedPartner['logo']) }}" alt="{{ $selectedPartner['name'] }}">
                    </div>

                    <div class="form-section-title">Select Programme</div>

                    <div class="form-group form-programme-select">
                        <select id="programme_course_applied" name="programme_course_applied" class="form-control @error('programme_course_applied') is-invalid @enderror" aria-label="Select programme" required>
                            <option value="">-- Choose Programme --</option>
                            @if (!empty($selectedPartner['programme_groups']))
                                @foreach ($selectedPartner['programme_groups'] as $group)
                                    <optgroup label="{{ $group['title'] }}">
                                        @foreach ($group['programmes'] as $programme)
                                            <option value="{{ $programme }}" {{ old('programme_course_applied') === $programme ? 'selected' : '' }}>{{ $programme }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @else
                                @foreach ($selectedPartner['programmes'] as $programme)
                                    <option value="{{ $programme }}" {{ old('programme_course_applied') === $programme ? 'selected' : '' }}>{{ $programme }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('programme_course_applied')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                @else
                    <div class="form-section-title">Select Programme</div>

                    <div class="form-group form-programme-select">
                        <select id="programme_course_applied" name="programme_course_applied" class="form-control" aria-label="Select programme" disabled>
                            <option value="">Select an institution from the MFLS page first</option>
                        </select>
                    </div>
                @endif

                <div class="form-section-title">Section 1: Personal Information</div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" maxlength="255" autocomplete="email" required>
                    <small class="field-hint">Use a valid email address you check regularly.</small>
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" placeholder="Name as per NRIC" value="{{ old('full_name') }}" minlength="2" maxlength="255" autocomplete="name" required>
                    @error('full_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="nric_passport">NRIC Number</label>
                        <input type="text" id="nric_passport" name="nric_passport" class="form-control @error('nric_passport') is-invalid @enderror" placeholder="e.g. 900101145555" value="{{ old('nric_passport') }}" inputmode="numeric" pattern="[0-9]{12}" minlength="12" maxlength="12" title="Enter 12-digit NRIC without dashes" required>
                        <small class="field-hint">12 digits only, without dashes.</small>
                        @error('nric_passport')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}" min="1950-01-01" max="{{ date('Y-m-d') }}" required>
                        @error('dob')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
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
                    @error('gender')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}" min="15" max="60" required>
                    @error('age')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Citizenship</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="citizenship" value="Malaysian" {{ old('citizenship') === 'Malaysian' ? 'checked' : '' }} required>
                            Malaysian
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="citizenship" value="Permanent Resident" {{ old('citizenship') === 'Permanent Resident' ? 'checked' : '' }}>
                            Permanent Resident
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="citizenship" value="Non-Malaysian" {{ old('citizenship') === 'Non-Malaysian' ? 'checked' : '' }}>
                            Non-Malaysian
                        </label>
                    </div>
                    @error('citizenship')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Marital Status</label>
                    <div class="radio-group">
                        @foreach(['Single', 'Married'] as $status)
                            <label class="radio-label">
                                <input type="radio" name="marital_status" value="{{ $status }}" {{ old('marital_status') === $status ? 'checked' : '' }} required>
                                {{ $status }}
                            </label>
                        @endforeach
                    </div>
                    @error('marital_status')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="contact_number">Phone Number</label>
                    <input type="tel" id="contact_number" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number') }}" pattern="(\+?6?01)[0-9][0-9\s\-()]{7,11}" minlength="10" maxlength="16" autocomplete="tel" title="Malaysian mobile number" required>
                    <small class="field-hint">Malaysian mobile number starting with 01.</small>
                    @error('contact_number')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="full_address">Current Residential Address</label>
                    <textarea id="full_address" name="full_address" rows="3" class="form-control @error('full_address') is-invalid @enderror" style="font-family: inherit;" minlength="10" maxlength="1000" required>{{ old('full_address') }}</textarea>
                    @error('full_address')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="state">State</label>
                        <select id="state" name="state" class="form-control @error('state') is-invalid @enderror" required>
                            <option value="">-- Choose State --</option>
                            @foreach($states as $stateOption)
                                <option value="{{ $stateOption }}" {{ old('state') == $stateOption ? 'selected' : '' }}>{{ $stateOption }}</option>
                            @endforeach
                        </select>
                        @error('state')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="postcode">Postcode</label>
                        <input type="text" id="postcode" name="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode') }}" maxlength="10" required>
                        @error('postcode')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-section-title">Section 2: Academic Information</div>

                <div class="form-group">
                    <label for="current_qualification">Current Qualification (Year 2025/2026)</label>
                    <select id="current_qualification" name="current_qualification" class="form-control" required>
                        <option value="">-- Choose Qualification --</option>
                        @foreach(['SPM', 'STPM', 'Foundation', 'Diploma', 'Degree'] as $qualification)
                            <option value="{{ $qualification }}" {{ old('current_qualification') === $qualification ? 'selected' : '' }}>{{ $qualification }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="institution_name">Institution Name</label>
                    <input type="text" id="institution_name" name="institution_name" class="form-control @error('institution_name') is-invalid @enderror" value="{{ old('institution_name') }}" @unless($selectedPartner) disabled @endunless required>
                    @error('institution_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="current_cgpa_result">Current CGPA / Final Result (SPM equivalent allowed)</label>
                    <input type="text" id="current_cgpa_result" name="current_cgpa_result" class="form-control" value="{{ old('current_cgpa_result') }}" required>
                </div>

                <div class="form-group">
                    <label for="academic_transcript">Please upload the Academic Transcript</label>
                    <input type="file" id="academic_transcript" name="academic_transcript" class="form-control" style="padding: 10px 16px;" required>
                    <small class="field-hint">PDF, JPG, PNG, DOC, DOCX. Max size: 20MB.</small>
                </div>

                <div class="form-group">
                    <label>Have you applied to a participating university?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="applied_to_university" value="1" {{ old('applied_to_university') === '1' ? 'checked' : '' }} required>
                            Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="applied_to_university" value="0" {{ old('applied_to_university') === '0' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Have you received an Offer Letter?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="received_offer_letter" value="1" {{ old('received_offer_letter') === '1' ? 'checked' : '' }}>
                            Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="received_offer_letter" value="0" {{ old('received_offer_letter') === '0' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="offer_letter">Upload Offer Letter (if available)</label>
                    <input type="file" id="offer_letter" name="offer_letter" class="form-control" style="padding: 10px 16px;">
                    <small class="field-hint">PDF, JPG, PNG, DOC, DOCX. Max size: 20MB.</small>
                </div>

                <div class="form-section-title">Section 3: Financial Background</div>

                <div class="form-group">
                    <label>Household Income</label>
                    <div class="radio-group" style="flex-direction: column; gap: 12px;">
                        @foreach(['< RM2,000', 'RM2,001 – RM4,000', 'RM4,001 – RM8,000', '> RM8,000'] as $income)
                            <label class="radio-label">
                                <input type="radio" name="household_income" value="{{ $income }}" {{ old('household_income') === $income ? 'checked' : '' }} required>
                                {{ $income }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="father_guardian_name">Father/Guardian Name</label>
                        <input type="text" id="father_guardian_name" name="father_guardian_name" class="form-control" value="{{ old('father_guardian_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="father_guardian_occupation">Father/Guardian Occupation</label>
                        <input type="text" id="father_guardian_occupation" name="father_guardian_occupation" class="form-control" value="{{ old('father_guardian_occupation') }}" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="mother_guardian_name">Mother/Guardian Name</label>
                        <input type="text" id="mother_guardian_name" name="mother_guardian_name" class="form-control" value="{{ old('mother_guardian_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mother_guardian_occupation">Mother/Guardian Occupation</label>
                        <input type="text" id="mother_guardian_occupation" name="mother_guardian_occupation" class="form-control" value="{{ old('mother_guardian_occupation') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="proof_of_income">Upload Proof of Income (Slip / statutory declaration)</label>
                    <input type="file" id="proof_of_income" name="proof_of_income" class="form-control" style="padding: 10px 16px;">
                    <small class="field-hint">PDF, JPG, PNG, DOC, DOCX. Max size: 20MB.</small>
                </div>

                <div class="form-group">
                    <label for="number_of_dependents">Number of Dependents in Household</label>
                    <input type="number" id="number_of_dependents" name="number_of_dependents" class="form-control" min="0" max="20" value="{{ old('number_of_dependents') }}" required>
                </div>

                <div class="form-group">
                    <label for="other_scholarship_details">Are you receiving any other scholarship? If yes, kindly specify.</label>
                    <textarea id="other_scholarship_details" name="other_scholarship_details" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('other_scholarship_details') }}</textarea>
                </div>

                <div class="form-section-title">Section 4: Leadership &amp; Involvement</div>

                <div class="form-group">
                    <label for="leadership_roles">List up to 3 leadership roles (e.g. school prefect, NGO volunteer, club president)</label>
                    <textarea id="leadership_roles" name="leadership_roles" rows="4" class="form-control" style="font-family: inherit;" required>{{ old('leadership_roles') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="involvement_level">Level of involvement (Leader / Active / Occasional / None)</label>
                    <select id="involvement_level" name="involvement_level" class="form-control" required>
                        <option value="">-- Choose Level --</option>
                        @foreach(['Leader', 'Active', 'Occasional', 'None'] as $level)
                            <option value="{{ $level }}" {{ old('involvement_level') === $level ? 'selected' : '' }}>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="community_service_involvement">Community service involvement</label>
                    <textarea id="community_service_involvement" name="community_service_involvement" rows="4" class="form-control" style="font-family: inherit;" required>{{ old('community_service_involvement') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="community_contribution">Describe your contribution to community (150–200 words)</label>
                    <textarea id="community_contribution" name="community_contribution" rows="6" class="form-control @error('community_contribution') is-invalid @enderror word-count-field" style="font-family: inherit;" data-min-words="150" data-max-words="200" maxlength="5000" required>{{ old('community_contribution') }}</textarea>
                    <div class="word-count" id="community_contribution_count">0 words (required: 150–200)</div>
                    @error('community_contribution')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-section-title">Section 5: Personal Statement</div>

                <div class="form-group">
                    <label for="leadership_experience_statement">Describe one leadership experience where you made an impact (150–200 words)</label>
                    <textarea id="leadership_experience_statement" name="leadership_experience_statement" rows="6" class="form-control @error('leadership_experience_statement') is-invalid @enderror word-count-field" style="font-family: inherit;" data-min-words="150" data-max-words="200" maxlength="5000" required>{{ old('leadership_experience_statement') }}</textarea>
                    <div class="word-count" id="leadership_experience_statement_count">0 words (required: 150–200)</div>
                    @error('leadership_experience_statement')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="scholar_selection_statement">Why should you be selected as a MUKMIN-FIKRAH Scholar, and how will you contribute to society? (150–200 words)</label>
                    <textarea id="scholar_selection_statement" name="scholar_selection_statement" rows="6" class="form-control @error('scholar_selection_statement') is-invalid @enderror word-count-field" style="font-family: inherit;" data-min-words="150" data-max-words="200" maxlength="5000" required>{{ old('scholar_selection_statement') }}</textarea>
                    <div class="word-count" id="scholar_selection_statement_count">0 words (required: 150–200)</div>
                    @error('scholar_selection_statement')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-section-title">Section 6: Supporting Documents</div>

                <div class="form-group">
                    <label for="recommendation_letter">Upload recommendation letter (optional)</label>
                    <input type="file" id="recommendation_letter" name="recommendation_letter" class="form-control" style="padding: 10px 16px;">
                    <small class="field-hint">PDF, JPG, PNG, DOC, DOCX. Max size: 20MB.</small>
                </div>

                <div class="form-group">
                    <label for="relevant_certificates">Upload relevant certificates (optional)</label>
                    <input type="file" id="relevant_certificates" name="relevant_certificates[]" class="form-control" multiple style="padding: 10px 16px;">
                    <small class="field-hint">You can upload multiple files. PDF, JPG, PNG, DOC, DOCX. Max size: 20MB per file.</small>
                </div>

                <div class="form-section-title">Section 7: Declaration</div>

                <div class="declaration-box">
                    <p>I declare that all information provided is true and accurate. I understand that any false information may result in disqualification.</p>
                    <label>
                        <input type="checkbox" name="declaration_confirmed" value="1" required {{ old('declaration_confirmed') ? 'checked' : '' }}>
                        Agree
                    </label>
                </div>

                @include('welfare.partials.important-notes')

                <button type="submit" class="btn-submit" id="mfls-submit-btn" @unless($selectedPartner) disabled data-partner-disabled="true" @endunless>Submit MFLS Application</button>
            </form>
        </div>
    </div>
</div>

<div class="eligibility-modal-overlay" id="citizenship-eligibility-modal" role="dialog" aria-modal="true" aria-labelledby="eligibility-modal-title">
    <div class="eligibility-modal">
        <h3 id="eligibility-modal-title">Not Eligible</h3>
        <p>The MFLS Scholarship is open to Malaysian citizens and Permanent Residents (PR) of Indian Muslim heritage only. You are not eligible to proceed with this application.</p>
        <button type="button" id="eligibility-modal-close">Understood</button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const citizenshipRadios = document.querySelectorAll('input[name="citizenship"]');
    const eligibilityModal = document.getElementById('citizenship-eligibility-modal');
    const eligibilityModalClose = document.getElementById('eligibility-modal-close');
    const mflsForm = document.getElementById('mfls-form');
    const submitBtn = document.getElementById('mfls-submit-btn');
    let citizenshipBlocked = false;

    function getSelectedCitizenship() {
        const selected = document.querySelector('input[name="citizenship"]:checked');
        return selected ? selected.value : '';
    }

    function showEligibilityModal() {
        citizenshipBlocked = true;
        if (submitBtn) {
            submitBtn.disabled = true;
        }
        if (eligibilityModal) {
            eligibilityModal.classList.add('is-visible');
        }
    }

    function hideEligibilityModal() {
        if (eligibilityModal) {
            eligibilityModal.classList.remove('is-visible');
        }
        citizenshipRadios.forEach(function (radio) {
            radio.checked = false;
        });
        citizenshipBlocked = false;
        if (submitBtn && !submitBtn.hasAttribute('data-partner-disabled')) {
            submitBtn.disabled = false;
        }
    }

    citizenshipRadios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (this.value === 'Non-Malaysian') {
                showEligibilityModal();
            } else {
                citizenshipBlocked = false;
                if (submitBtn && !submitBtn.hasAttribute('data-partner-disabled')) {
                    submitBtn.disabled = false;
                }
            }
        });
    });

    if (eligibilityModalClose) {
        eligibilityModalClose.addEventListener('click', hideEligibilityModal);
    }

    if (eligibilityModal) {
        eligibilityModal.addEventListener('click', function (event) {
            if (event.target === eligibilityModal) {
                hideEligibilityModal();
            }
        });
    }

    if (getSelectedCitizenship() === 'Non-Malaysian') {
        showEligibilityModal();
    }

    if (mflsForm) {
        mflsForm.addEventListener('submit', function (event) {
            if (citizenshipBlocked || getSelectedCitizenship() === 'Non-Malaysian') {
                event.preventDefault();
                showEligibilityModal();
            }
        });
    }

    const nricInput = document.getElementById('nric_passport');
    if (nricInput) {
        nricInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 12);
        });
    }

    function countWords(text) {
        return text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
    }

    document.querySelectorAll('.word-count-field').forEach(function (field) {
        const counter = document.getElementById(field.id + '_count');
        const minWords = parseInt(field.dataset.minWords, 10);
        const maxWords = parseInt(field.dataset.maxWords, 10);

        function updateCount() {
            const total = countWords(field.value);
            counter.textContent = total + ' words (required: ' + minWords + '–' + maxWords + ')';
            counter.classList.toggle('invalid', total < minWords || total > maxWords);
        }

        field.addEventListener('input', updateCount);
        updateCount();
    });
});
</script>
@endpush
@endsection
