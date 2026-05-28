@extends('welfare.layouts.app')

@section('title', 'Partnership & Collaboration - Pertubuhan Gabungan MUKMIN Nasional')

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
    margin-top: 35px;
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
    display: flex;
    flex-direction: column;
    gap: 15px;
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
    flex-shrink: 0;
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
    max-height: 200px;
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
                <h2>MUKMIN Partnership & Collaboration Form</h2>
                <p>We welcome strategic partnerships that strengthen communities, empower meaningful initiatives, and create sustainable impact together.</p>
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

            <form method="POST" action="{{ route('welfare.partner.submit') }}" enctype="multipart/form-data">
                @csrf

                <!-- ORGANISATION / PARTNER DETAILS -->
                <div class="form-section-title">Organisation / Partner Details</div>
                
                <div class="form-group">
                    <label for="company_name">Organisation / Company Name *</label>
                    <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="contact_person">Primary Contact Person *</label>
                        <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ old('contact_person') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position / Designation *</label>
                        <input type="text" id="position" name="position" class="form-control" value="{{ old('position') }}" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="org_reg_number">Organisation Registration Number (if applicable)</label>
                        <input type="text" id="org_reg_number" name="org_reg_number" class="form-control" value="{{ old('org_reg_number') }}">
                    </div>
                    <div class="form-group">
                        <label for="state_country">State / Country *</label>
                        <input type="text" id="state_country" name="state_country" class="form-control" placeholder="e.g. Selangor / Malaysia" value="{{ old('state_country') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="office_address">Office Address *</label>
                    <textarea id="office_address" name="office_address" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('office_address') }}</textarea>
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
                    <label>Organisation Type * (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="orgtype-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose organisation types...">
                            <span class="trigger-text">Choose organisation types...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($orgTypes as $type)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="org_type[]" value="{{ $type }}" id="orgtype-{{ $loop->index }}" {{ is_array(old('org_type')) && in_array($type, old('org_type')) ? 'checked' : '' }}>
                                    <span for="orgtype-{{ $loop->index }}">{{ $type }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-orgtype-group" style="display: none;">
                    <label for="org_type_other">Please specify "Other" Organisation Type *</label>
                    <input type="text" id="org_type_other" name="org_type_other" class="form-control" value="{{ old('org_type_other') }}">
                </div>

                <!-- PARTNERSHIP INFORMATION -->
                <div class="form-section-title">Partnership Information</div>

                <div class="form-group">
                    <label>Area(s) of Collaboration Interest * (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="collab-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose collaboration areas...">
                            <span class="trigger-text">Choose collaboration areas...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($collabs as $collab)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="collaboration_areas[]" value="{{ $collab }}" id="collab-{{ $loop->index }}" {{ is_array(old('collaboration_areas')) && in_array($collab, old('collaboration_areas')) ? 'checked' : '' }}>
                                    <span for="collab-{{ $loop->index }}">{{ $collab }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-collab-group" style="display: none;">
                    <label for="collaboration_other">Please specify "Other" Collaboration Area *</label>
                    <input type="text" id="collaboration_other" name="collaboration_other" class="form-control" value="{{ old('collaboration_other') }}">
                </div>

                <div class="form-group">
                    <label>Type of Partnership Proposed * (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="partner-type-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose partnership types...">
                            <span class="trigger-text">Choose partnership types...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($partnerTypes as $pt)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="partnership_type[]" value="{{ $pt }}" id="pt-{{ $loop->index }}" {{ is_array(old('partnership_type')) && in_array($pt, old('partnership_type')) ? 'checked' : '' }}>
                                    <span for="pt-{{ $loop->index }}">{{ $pt }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-partnertype-group" style="display: none;">
                    <label for="partnership_other">Please specify "Other" Partnership Type *</label>
                    <input type="text" id="partnership_other" name="partnership_other" class="form-control" value="{{ old('partnership_other') }}">
                </div>

                <div class="form-group">
                    <label for="proposal_description">Please briefly describe your proposed collaboration or partnership: *</label>
                    <textarea id="proposal_description" name="proposal_description" rows="5" class="form-control" style="font-family: inherit;" required>{{ old('proposal_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="expected_outcomes">Expected Outcomes / Impact of Collaboration: *</label>
                    <textarea id="expected_outcomes" name="expected_outcomes" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('expected_outcomes') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Has your organisation previously collaborated with NGOs or community initiatives? *</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="has_collaborated_before" value="1" {{ old('has_collaborated_before') === '1' ? 'checked' : '' }} required>
                            Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="has_collaborated_before" value="0" {{ old('has_collaborated_before') === '0' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group" id="collab-details-group" style="display: none;">
                    <label for="collaborated_before_details">If yes, please provide brief details: *</label>
                    <textarea id="collaborated_before_details" name="collaborated_before_details" rows="3" class="form-control" style="font-family: inherit;">{{ old('collaborated_before_details') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="supporting_files">Attach Supporting Documents (Optional)</label>
                    <input type="file" id="supporting_files" name="supporting_files[]" multiple class="form-control" style="font-size: 13px; padding: 10px;">
                    <span style="display: block; font-size: 11.5px; color: #666; margin-top: 5px;">* You can select and upload multiple files (PDF, Word, Images, Zip, PPT - Max 20MB total)</span>
                </div>

                <!-- DECLARATION -->
                <div class="form-section-title">Declaration</div>

                <div class="declaration-box">
                    <label>
                        <input type="checkbox" name="declaration_confirmed" value="1" required {{ old('declaration_confirmed') ? 'checked' : '' }}>
                        We confirm that the information provided above is accurate and submitted voluntarily for partnership consideration with MUKMIN.
                    </label>
                    <label>
                        <input type="checkbox" name="declaration_approval_confirmed" value="1" required>
                        We understand that all partnership proposals are subject to review and approval by MUKMIN.
                    </label>
                </div>

                <button type="submit" class="btn-submit">Submit Partnership Proposal</button>
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

            // OrgType "Other" conditional toggle
            if (dropdown.id === 'orgtype-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase() === 'other');
                const otherGroup = document.getElementById('other-orgtype-group');
                const otherInput = document.getElementById('org_type_other');
                if (hasOther) {
                    otherGroup.style.display = 'block';
                    otherInput.setAttribute('required', 'required');
                } else {
                    otherGroup.style.display = 'none';
                    otherInput.removeAttribute('required');
                }
            }

            // Collab "Other" conditional toggle
            if (dropdown.id === 'collab-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase() === 'other');
                const otherGroup = document.getElementById('other-collab-group');
                const otherInput = document.getElementById('collaboration_other');
                if (hasOther) {
                    otherGroup.style.display = 'block';
                    otherInput.setAttribute('required', 'required');
                } else {
                    otherGroup.style.display = 'none';
                    otherInput.removeAttribute('required');
                }
            }

            // PartnerType "Other" conditional toggle
            if (dropdown.id === 'partner-type-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase() === 'other');
                const otherGroup = document.getElementById('other-partnertype-group');
                const otherInput = document.getElementById('partnership_other');
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

    // Handle Collaborated Before conditional fields
    const collabRadios = document.querySelectorAll('input[name="has_collaborated_before"]');
    const collabDetailsGroup = document.getElementById('collab-details-group');
    const collabDetailsInput = document.getElementById('collaborated_before_details');

    function toggleCollabDetails() {
        const selectedRadio = document.querySelector('input[name="has_collaborated_before"]:checked');
        if (selectedRadio && selectedRadio.value === '1') {
            collabDetailsGroup.style.display = 'block';
            collabDetailsInput.setAttribute('required', 'required');
        } else {
            collabDetailsGroup.style.display = 'none';
            collabDetailsInput.removeAttribute('required');
        }
    }

    collabRadios.forEach(radio => {
        radio.addEventListener('change', toggleCollabDetails);
    });

    // Initial run
    toggleCollabDetails();
});
</script>
@endpush
@endsection
