@extends('welfare.layouts.app')

@section('title', 'Community Feedback & Suggestion Form - Pertubuhan Gabungan MUKMIN Nasional')

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
    margin-top: 30px;
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
}
.declaration-box input[type="checkbox"] {
    margin-top: 3px;
    accent-color: #d43c18;
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
                <h2>MUKMIN Community Feedback & Suggestion Form</h2>
                <p>A platform for ideas, feedback, collaborations, and recommendations to help strengthen the MUKMIN ecosystem and community impact initiatives.</p>
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

            <form method="POST" action="{{ route('welfare.feedback.submit') }}">
                @csrf

                <!-- SECTION 1: INDIVIDUAL / ORGANISATION DETAILS -->
                <div class="form-section-title">Individual / Organisation Details</div>
                
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="nric_number">NRIC Number *</label>
                        <input type="text" id="nric_number" name="nric_number" class="form-control" placeholder="e.g. 900101-14-5555" value="{{ old('nric_number') }}" required>
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

                <div class="grid-2">
                    <div class="form-group">
                        <label for="organisation">Organisation / Institution (if applicable)</label>
                        <input type="text" id="organisation" name="organisation" class="form-control" value="{{ old('organisation') }}">
                    </div>
                    <div class="form-group">
                        <label for="position">Position / Designation (if applicable)</label>
                        <input type="text" id="position" name="position" class="form-control" value="{{ old('position') }}">
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

                <!-- SECTION 2: SUGGESTION / FEEDBACK CATEGORY -->
                <div class="form-section-title">Suggestion / Feedback Category</div>

                <div class="form-group">
                    <label>Select Category * (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="category-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose categories...">
                            <span class="trigger-text">Choose categories...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($categories as $category)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="categories[]" value="{{ $category }}" id="cat-{{ $loop->index }}" {{ is_array(old('categories')) && in_array($category, old('categories')) ? 'checked' : '' }}>
                                    <span for="cat-{{ $loop->index }}">{{ $category }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-category-group" style="display: none;">
                    <label for="other_category">Please specify "Other" Category *</label>
                    <input type="text" id="other_category" name="other_category" class="form-control" value="{{ old('other_category') }}">
                </div>

                <div class="form-group">
                    <label for="suggestion_description">Please describe your suggestion, feedback, concern, or proposed initiative: *</label>
                    <textarea id="suggestion_description" name="suggestion_description" rows="5" class="form-control" style="font-family: inherit;" required>{{ old('suggestion_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="benefits_description">How do you believe this suggestion can benefit the MUKMIN ecosystem or community? *</label>
                    <textarea id="benefits_description" name="benefits_description" rows="4" class="form-control" style="font-family: inherit;" required>{{ old('benefits_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Would you like to be contacted for further discussion or collaboration? *</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="contact_consent" value="1" {{ old('contact_consent') === '1' ? 'checked' : '' }} required>
                            Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="contact_consent" value="0" {{ old('contact_consent') === '0' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group" id="contact-methods-group" style="display: none;">
                    <label>If yes, preferred method of contact: (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="contact-methods-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose methods...">
                            <span class="trigger-text">Choose methods...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach(['Email', 'Phone Call', 'WhatsApp'] as $method)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="preferred_contact_methods[]" value="{{ $method }}" id="method-{{ $loop->index }}" {{ is_array(old('preferred_contact_methods')) && in_array($method, old('preferred_contact_methods')) ? 'checked' : '' }}>
                                    <span for="method-{{ $loop->index }}">{{ $method }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: DECLARATION -->
                <div class="form-section-title">Declaration</div>

                <div class="declaration-box">
                    <label>
                        <input type="checkbox" name="declaration_confirmed" value="1" required {{ old('declaration_confirmed') ? 'checked' : '' }}>
                        I hereby confirm that all information provided in this form is true and accurate. I agree to comply with the constitution, policies, and guidelines of MUKMIN.
                    </label>
                </div>

                <div class="important-notes">
                    <h4>IMPORTANT NOTES</h4>
                    <ul>
                        <li>Incomplete forms may not be processed.</li>
                        <li>MUKMIN reserves the right to approve or reject any suggestions.</li>
                        <li>Additional supporting documents may be requested if necessary.</li>
                    </ul>
                </div>

                <button type="submit" class="btn-submit">Submit Feedback & Suggestion</button>
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

            // Specific validation toggle for Category dropdown "Other" option
            if (dropdown.id === 'category-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase() === 'other');
                const otherGroup = document.getElementById('other-category-group');
                const otherInput = document.getElementById('other_category');
                
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

    // Handle Contact Consent conditional fields
    const contactConsentRadios = document.querySelectorAll('input[name="contact_consent"]');
    const contactMethodsGroup = document.getElementById('contact-methods-group');

    function toggleContactMethods() {
        const selectedRadio = document.querySelector('input[name="contact_consent"]:checked');
        if (selectedRadio && selectedRadio.value === '1') {
            contactMethodsGroup.style.display = 'block';
        } else {
            contactMethodsGroup.style.display = 'none';
        }
    }

    contactConsentRadios.forEach(radio => {
        radio.addEventListener('change', toggleContactMethods);
    });

    // Run once on load
    toggleContactMethods();
});
</script>
@endpush
@endsection
