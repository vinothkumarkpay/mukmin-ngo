@extends('welfare.layouts.app')

@section('title', 'Community Aid & Assistance Request Form - Pertubuhan Gabungan MUKMIN Nasional')

@section('content')
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
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
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
.important-notes {
    background: #fffcfb;
    border-left: 4px solid #d43c18;
    padding: 20px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 30px;
}
.important-notes h4 {
    color: #b83210;
    font-size: 14px;
    font-weight: 700;
    margin: 0 0 10px 0;
}
.important-notes ul {
    margin: 0;
    padding-left: 18px;
}
.important-notes li {
    font-size: 13px;
    color: #666;
    margin-bottom: 6px;
    line-height: 18px;
}
.important-notes li:last-child {
    margin-bottom: 0;
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
.dropdown-option-item {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    cursor: pointer;
    transition: background 0.2s ease;
    font-size: 14px;
    color: #444;
}
.dropdown-option-item:hover {
    background: #fdf6f4;
}
.dropdown-option-item input[type="checkbox"] {
    margin-right: 12px;
    width: 16px;
    height: 16px;
    accent-color: #d43c18;
    cursor: pointer;
}
.dropdown-option-item span {
    user-select: none;
}

/* Radio Button Styling */
.radio-group {
    display: flex;
    gap: 30px;
    margin-top: 8px;
}
.radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500 !important;
    font-size: 14px;
    color: #444;
    cursor: pointer;
}
.radio-label input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #d43c18;
    cursor: pointer;
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
                    <label for="full_name">Full Name (as per NRIC / Passport) *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="nric_passport">NRIC / Passport Number *</label>
                        <input type="text" id="nric_passport" name="nric_passport" class="form-control" placeholder="e.g. 900101145555" value="{{ old('nric_passport') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Gender *</label>
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
                        <label for="dob">Date of Birth *</label>
                        <input type="date" id="dob" name="dob" class="form-control" value="{{ old('dob') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nationality">Nationality *</label>
                        <input type="text" id="nationality" name="nationality" class="form-control" value="{{ old('nationality') }}" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="occupation">Occupation *</label>
                        <input type="text" id="occupation" name="occupation" class="form-control" value="{{ old('occupation') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="monthly_income">Monthly Household Income (RM, if applicable)</label>
                        <input type="text" id="monthly_income" name="monthly_income" class="form-control" placeholder="e.g. 2500" value="{{ old('monthly_income') }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number *</label>
                        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="e.g. +60123456789" value="{{ old('contact_number') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_address">Full Residential Address *</label>
                    <textarea id="full_address" name="full_address" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('full_address') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="state_residency">State *</label>
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
                    <label>Select Types of Aid Required * (Dropdown List)</label>
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
                    <label for="type_of_aid_other">Please specify "Other" Type of Aid *</label>
                    <input type="text" id="type_of_aid_other" name="type_of_aid_other" class="form-control" value="{{ old('type_of_aid_other') }}">
                </div>

                <!-- SECTION 3: DETAILS OF ASSISTANCE REQUIRED -->
                <div class="form-section-title">III. Details of Assistance Required</div>

                <div class="form-group">
                    <label for="situation_description">Please describe your current situation and the type of assistance required: *</label>
                    <textarea id="situation_description" name="situation_description" rows="5" class="form-control" style="font-family: inherit;" required>{{ old('situation_description') }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="who_benefits">Who will benefit from this assistance? *</label>
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

                <!-- SECTION 4: SUPPORTING INFORMATION -->
                <div class="form-section-title">IV. Supporting Information</div>

                <div class="form-group">
                    <label>Have you previously received aid or assistance from any organisation? *</label>
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
                    <label for="received_aid_before_details">If yes, please specify details (Organisation name, type of aid, date received) *</label>
                    <textarea id="received_aid_before_details" name="received_aid_before_details" rows="3" class="form-control" style="font-family: inherit;">{{ old('received_aid_before_details') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="supporting_files">Upload Supporting Documents (Income Statements, Medical Bills, Police Reports, Situation Photos, etc.)</label>
                    <input type="file" id="supporting_files" name="supporting_files[]" class="form-control" multiple style="padding: 10px 16px;">
                    <small style="color: #666; display: block; margin-top: 5px;">You can upload multiple files (PDF, JPG, PNG, DOCX, ZIP). Max size: 20MB per file.</small>
                </div>

                <!-- SECTION 5: EMERGENCY CONTACT -->
                <div class="form-section-title">V. Emergency Contact</div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="emergency_contact_name">Full Name *</label>
                        <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact_relationship">Relationship *</label>
                        <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-control" value="{{ old('emergency_contact_relationship') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="emergency_contact_phone">Contact Number *</label>
                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" class="form-control" placeholder="e.g. +60123456789" value="{{ old('emergency_contact_phone') }}" required>
                </div>

                <!-- SECTION 6: DECLARATION & CONSENT -->
                <div class="form-section-title">VI. Declaration & Consent</div>

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

                <div class="important-notes">
                    <h4>IMPORTANT NOTES</h4>
                    <ul>
                        <li>All information submitted is confidential and used solely for aid assessment.</li>
                        <li>Please make sure all supporting files are clearly legible.</li>
                        <li>Providing false information will lead to immediate rejection of the aid request.</li>
                    </ul>
                </div>

                <button type="submit" class="btn-submit">Submit Aid Request</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Custom Dropdown triggers
    const dropdowns = document.querySelectorAll('.custom-dropdown-container');
    
    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const list = dropdown.querySelector('.dropdown-options-list');
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

            // Specific validation toggle for Aid dropdown "Others" option
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
            }
        }

        dropdown.addEventListener('change', updateText);

        dropdown.querySelectorAll('.dropdown-option-item').forEach(item => {
            item.addEventListener('click', function (e) {
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

    // Handle Received Aid conditional fields
    const receivedAidRadios = document.querySelectorAll('input[name="received_aid_before"]');
    const receivedAidDetailsGroup = document.getElementById('received-aid-details-group');
    const receivedAidDetailsTextarea = document.getElementById('received_aid_before_details');

    function toggleReceivedAidDetails() {
        const selectedRadio = document.querySelector('input[name="received_aid_before"]:checked');
        if (selectedRadio && selectedRadio.value === '1') {
            receivedAidDetailsGroup.style.display = 'block';
            receivedAidDetailsTextarea.setAttribute('required', 'required');
        } else {
            receivedAidDetailsGroup.style.display = 'none';
            receivedAidDetailsTextarea.removeAttribute('required');
        }
    }

    receivedAidRadios.forEach(radio => {
        radio.addEventListener('change', toggleReceivedAidDetails);
    });

    // Run once on load
    toggleReceivedAidDetails();
});
</script>
@endpush
@endsection
