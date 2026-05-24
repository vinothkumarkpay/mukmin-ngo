@extends('welfare.layouts.app')

@section('title', 'Volunteer Registration - Pertubuhan Gabungan MUKMIN Nasional')

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
.grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}
.emergency-row {
    background: #fafafa;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
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
.dropdown-option-item input[type="checkbox"],
.dropdown-option-item input[type="radio"] {
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
    .grid-2, .grid-3 {
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
                <h2>MUKMIN Volunteer Registration Form</h2>
                <p>Join us in serving communities, strengthening the ummah, and creating meaningful impact through volunteerism and collective action.</p>
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

            <form method="POST" action="{{ route('welfare.volunteer.submit') }}">
                @csrf

                <!-- VOLUNTEER DETAILS -->
                <div class="form-section-title">Volunteer Details</div>
                
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label for="nric_passport">NRIC / Passport Number *</label>
                        <input type="text" id="nric_passport" name="nric_passport" class="form-control" value="{{ old('nric_passport') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Gender *</label>
                        <div class="radio-group" style="margin-top: 12px;">
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
                    <div class="form-group">
                        <label for="state_residency">State of Residency *</label>
                        <select id="state_residency" name="state_residency" class="form-control" required>
                            <option value="">-- Choose State --</option>
                            @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya'] as $state)
                                <option value="{{ $state }}" {{ old('state_residency') == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="occupation_study">Occupation / Field of Study *</label>
                        <input type="text" id="occupation_study" name="occupation_study" class="form-control" value="{{ old('occupation_study') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="organisation">Organisation / Institution (if applicable)</label>
                        <input type="text" id="organisation" name="organisation" class="form-control" value="{{ old('organisation') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_address">Full Address *</label>
                    <textarea id="full_address" name="full_address" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('full_address') }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number *</label>
                        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="e.g. +60123456789" value="{{ old('contact_number') }}" required>
                    </div>
                </div>

                <!-- VOLUNTEER ENGAGEMENT -->
                <div class="form-section-title">Volunteer Engagement</div>
                
                <div class="form-group">
                    <label>Area(s) of Interest * (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="interest-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose areas of interest...">
                            <span class="trigger-text">Choose areas of interest...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($interests as $interest)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="interest_areas[]" value="{{ $interest }}" id="int-{{ $loop->index }}" {{ is_array(old('interest_areas')) && in_array($interest, old('interest_areas')) ? 'checked' : '' }}>
                                    <span for="int-{{ $loop->index }}">{{ $interest }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-interest-group" style="display: none;">
                    <label for="interest_other">Please specify "Other" Interest *</label>
                    <input type="text" id="interest_other" name="interest_other" class="form-control" value="{{ old('interest_other') }}">
                </div>

                <div class="form-group">
                    <label for="skills_expertise">Skills / Expertise You Can Contribute *</label>
                    <textarea id="skills_expertise" name="skills_expertise" rows="3" class="form-control" placeholder="e.g. Graphic design, photography, medical, counseling, event management, first aid" style="font-family: inherit;" required>{{ old('skills_expertise') }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Preferred Mode of Volunteering * (Dropdown List)</label>
                        <div class="custom-dropdown-container" id="mode-dropdown">
                            <div class="dropdown-trigger" data-placeholder="Choose mode...">
                                <span class="trigger-text">Choose mode...</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="dropdown-options-list">
                                @foreach($modes as $mode)
                                    <div class="dropdown-option-item">
                                        <input type="radio" name="preferred_mode" value="{{ $mode }}" id="mode-{{ $loop->index }}" {{ old('preferred_mode') == $mode ? 'checked' : '' }} required>
                                        <span for="mode-{{ $loop->index }}">{{ $mode }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Availability * (Dropdown List)</label>
                        <div class="custom-dropdown-container" id="availability-dropdown">
                            <div class="dropdown-trigger" data-placeholder="Choose availability...">
                                <span class="trigger-text">Choose availability...</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="dropdown-options-list">
                                @foreach($availabilities as $av)
                                    <div class="dropdown-option-item">
                                        <input type="checkbox" name="availability[]" value="{{ $av }}" id="av-{{ $loop->index }}" {{ is_array(old('availability')) && in_array($av, old('availability')) ? 'checked' : '' }}>
                                        <span for="av-{{ $loop->index }}">{{ $av }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Have you volunteered with any organisation before? *</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="has_volunteered_before" value="1" {{ old('has_volunteered_before') === '1' ? 'checked' : '' }} required>
                            Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="has_volunteered_before" value="0" {{ old('has_volunteered_before') === '0' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group" id="volunteered-details-group" style="display: none;">
                    <label for="volunteered_before_details">If yes, please specify: *</label>
                    <textarea id="volunteered_before_details" name="volunteered_before_details" rows="3" class="form-control" placeholder="Which organisation and what activities did you do?" style="font-family: inherit;">{{ old('volunteered_before_details') }}</textarea>
                </div>

                <!-- EMERGENCY CONTACT -->
                <div class="form-section-title">Emergency Contact</div>
                
                <div class="emergency-row">
                    <div class="form-group">
                        <label for="emergency_contact_name">Full Name *</label>
                        <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}" required>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="emergency_contact_relationship">Relationship *</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-control" placeholder="e.g. Spouse / Parent / Sibling" value="{{ old('emergency_contact_relationship') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency_contact_phone">Contact Number *</label>
                            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" class="form-control" placeholder="e.g. +60123456789" value="{{ old('emergency_contact_phone') }}" required>
                        </div>
                    </div>
                </div>

                <!-- DECLARATION -->
                <div class="form-section-title">Declaration</div>

                <div class="declaration-box">
                    <label>
                        <input type="checkbox" name="declaration_confirmed" value="1" required {{ old('declaration_confirmed') ? 'checked' : '' }}>
                        I confirm that the information provided is true and accurate.
                    </label>
                    <label>
                        <input type="checkbox" name="declaration_placement_confirmed" value="1" required>
                        I understand that submission of this form does not guarantee placement and is subject to MUKMIN’s volunteer requirements and availability.
                    </label>
                </div>

                <button type="submit" class="btn-submit">Submit Volunteer Registration</button>
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
        const radios = dropdown.querySelectorAll('input[type="radio"]');
        const triggerText = trigger.querySelector('.trigger-text');
        const isMulti = checkboxes.length > 0;

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdowns.forEach(other => {
                if (other !== dropdown) other.classList.remove('open');
            });
            dropdown.classList.toggle('open');
        });

        function updateText() {
            if (isMulti) {
                const checked = Array.from(checkboxes).filter(cb => cb.checked);
                if (checked.length === 0) {
                    triggerText.textContent = trigger.getAttribute('data-placeholder') || 'Select options';
                } else if (checked.length <= 2) {
                    triggerText.textContent = checked.map(cb => cb.parentNode.textContent.trim()).join(', ');
                } else {
                    triggerText.textContent = checked.length + ' options selected';
                }

                // Interest "Other" conditional toggle
                if (dropdown.id === 'interest-dropdown') {
                    const hasOther = checked.some(cb => cb.value.toLowerCase() === 'other');
                    const otherGroup = document.getElementById('other-interest-group');
                    const otherInput = document.getElementById('interest_other');
                    if (hasOther) {
                        otherGroup.style.display = 'block';
                        otherInput.setAttribute('required', 'required');
                    } else {
                        otherGroup.style.display = 'none';
                        otherInput.removeAttribute('required');
                    }
                }
            } else {
                const checked = dropdown.querySelector('input[type="radio"]:checked');
                if (checked) {
                    triggerText.textContent = checked.parentNode.textContent.trim();
                    dropdown.classList.remove('open');
                } else {
                    triggerText.textContent = trigger.getAttribute('data-placeholder') || 'Select option';
                }
            }
        }

        dropdown.addEventListener('change', updateText);

        dropdown.querySelectorAll('.dropdown-option-item').forEach(item => {
            item.addEventListener('click', function (e) {
                if (e.target.type !== 'checkbox' && e.target.type !== 'radio') {
                    const input = item.querySelector('input');
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = !input.checked;
                        } else {
                            input.checked = true;
                        }
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        });

        updateText();
    });

    document.addEventListener('click', function () {
        dropdowns.forEach(d => d.classList.remove('open'));
    });

    // Handle Volunteered Before conditional fields
    const volunteeredBeforeRadios = document.querySelectorAll('input[name="has_volunteered_before"]');
    const volunteeredDetailsGroup = document.getElementById('volunteered-details-group');
    const volunteeredDetailsInput = document.getElementById('volunteered_before_details');

    function toggleVolunteeredDetails() {
        const selectedRadio = document.querySelector('input[name="has_volunteered_before"]:checked');
        if (selectedRadio && selectedRadio.value === '1') {
            volunteeredDetailsGroup.style.display = 'block';
            volunteeredDetailsInput.setAttribute('required', 'required');
        } else {
            volunteeredDetailsGroup.style.display = 'none';
            volunteeredDetailsInput.removeAttribute('required');
        }
    }

    volunteeredBeforeRadios.forEach(radio => {
        radio.addEventListener('change', toggleVolunteeredDetails);
    });

    // Initial run
    toggleVolunteeredDetails();
});
</script>
@endpush
@endsection
