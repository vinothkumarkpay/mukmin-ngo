@extends('welfare.layouts.app')

@section('title', 'Mentor Registration - Pertubuhan Gabungan MUKMIN Nasional')

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
                <h2>MUKMIN Mentor Registration Form</h2>
                <p>Share your knowledge, experience, and leadership to help nurture future changemakers, strengthen communities, and build a sustainable ecosystem of impact.</p>
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

            <form method="POST" action="{{ route('welfare.mentor.submit') }}">
                @csrf

                <!-- MENTOR DETAILS -->
                <div class="form-section-title">Mentor Details</div>
                
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
                        <label for="state_residency">State *</label>
                        <select id="state_residency" name="state_residency" class="form-control" required>
                            <option value="">-- Choose State --</option>
                            @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya'] as $state)
                                <option value="{{ $state }}" {{ old('state_residency') == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label for="occupation">Occupation / Profession *</label>
                        <input type="text" id="occupation" name="occupation" class="form-control" value="{{ old('occupation') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="organisation">Organisation / Institution *</label>
                        <input type="text" id="organisation" name="organisation" class="form-control" value="{{ old('organisation') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position / Designation *</label>
                        <input type="text" id="position" name="position" class="form-control" value="{{ old('position') }}" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="experience_years">Years of Professional Experience *</label>
                        <input type="number" id="experience_years" name="experience_years" class="form-control" min="0" value="{{ old('experience_years') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="linkedin">LinkedIn / Professional Profile (if applicable)</label>
                        <input type="text" id="linkedin" name="linkedin" class="form-control" placeholder="LinkedIn or professional profile link" value="{{ old('linkedin') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_address">Full Address *</label>
                    <textarea id="full_address" name="full_address" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('full_address') }}</textarea>
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

                <!-- MENTORSHIP INFORMATION -->
                <div class="form-section-title">Mentorship Information</div>
                
                <div class="form-group">
                    <label>Area(s) of Expertise * (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="expertise-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose expertise areas...">
                            <span class="trigger-text">Choose expertise areas...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($expertises as $exp)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="expertise_areas[]" value="{{ $exp }}" id="exp-{{ $loop->index }}" {{ is_array(old('expertise_areas')) && in_array($exp, old('expertise_areas')) ? 'checked' : '' }}>
                                    <span for="exp-{{ $loop->index }}">{{ $exp }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-expertise-group" style="display: none;">
                    <label for="expertise_other">Please specify "Other" Expertise *</label>
                    <input type="text" id="expertise_other" name="expertise_other" class="form-control" value="{{ old('expertise_other') }}">
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Preferred Mentorship Format * (Dropdown List)</label>
                        <div class="custom-dropdown-container" id="format-dropdown">
                            <div class="dropdown-trigger" data-placeholder="Choose formats...">
                                <span class="trigger-text">Choose formats...</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="dropdown-options-list">
                                @foreach($formats as $fmt)
                                    <div class="dropdown-option-item">
                                        <input type="checkbox" name="preferred_format[]" value="{{ $fmt }}" id="fmt-{{ $loop->index }}" {{ is_array(old('preferred_format')) && in_array($fmt, old('preferred_format')) ? 'checked' : '' }}>
                                        <span for="fmt-{{ $loop->index }}">{{ $fmt }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Preferred Commitment Level * (Dropdown List)</label>
                        <div class="custom-dropdown-container" id="commitment-dropdown">
                            <div class="dropdown-trigger" data-placeholder="Choose commitment...">
                                <span class="trigger-text">Choose commitment...</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="dropdown-options-list">
                                @foreach($commitments as $com)
                                    <div class="dropdown-option-item">
                                        <input type="checkbox" name="preferred_commitment[]" value="{{ $com }}" id="com-{{ $loop->index }}" {{ is_array(old('preferred_commitment')) && in_array($com, old('preferred_commitment')) ? 'checked' : '' }}>
                                        <span for="com-{{ $loop->index }}">{{ $com }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="experience_description">Please briefly describe your experience and how you hope to contribute as a mentor: *</label>
                    <textarea id="experience_description" name="experience_description" rows="4" class="form-control" style="font-family: inherit;" required>{{ old('experience_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Have you previously served as a mentor, trainer, advisor, or coach? *</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="has_served_before" value="1" {{ old('has_served_before') === '1' ? 'checked' : '' }} required>
                            Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="has_served_before" value="0" {{ old('has_served_before') === '0' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group" id="served-details-group" style="display: none;">
                    <label for="served_before_details">If yes, please provide brief details: *</label>
                    <textarea id="served_before_details" name="served_before_details" rows="3" class="form-control" style="font-family: inherit;">{{ old('served_before_details') }}</textarea>
                </div>

                <!-- DECLARATION -->
                <div class="form-section-title">Declaration</div>

                <div class="declaration-box">
                    <label>
                        <input type="checkbox" name="declaration_confirmed" value="1" required {{ old('declaration_confirmed') ? 'checked' : '' }}>
                        I confirm that the information provided above is accurate and submitted voluntarily for mentorship engagement with MUKMIN.
                    </label>
                    <label>
                        <input type="checkbox" name="declaration_review_confirmed" value="1" required>
                        I understand that mentor participation is subject to MUKMIN’s review and programme requirements.
                    </label>
                </div>

                @include('welfare.partials.important-notes')

                <button type="submit" class="btn-submit">Submit Mentor Registration</button>
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

            // Expertise "Other" conditional toggle
            if (dropdown.id === 'expertise-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase() === 'other');
                const otherGroup = document.getElementById('other-expertise-group');
                const otherInput = document.getElementById('expertise_other');
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

    // Handle Served Before conditional fields
    const servedBeforeRadios = document.querySelectorAll('input[name="has_served_before"]');
    const servedDetailsGroup = document.getElementById('served-details-group');
    const servedDetailsInput = document.getElementById('served_before_details');

    function toggleServedDetails() {
        const selectedRadio = document.querySelector('input[name="has_served_before"]:checked');
        if (selectedRadio && selectedRadio.value === '1') {
            servedDetailsGroup.style.display = 'block';
            servedDetailsInput.setAttribute('required', 'required');
        } else {
            servedDetailsGroup.style.display = 'none';
            servedDetailsInput.removeAttribute('required');
        }
    }

    servedBeforeRadios.forEach(radio => {
        radio.addEventListener('change', toggleServedDetails);
    });

    // Initial run
    toggleServedDetails();
});
</script>
@endpush
@endsection
