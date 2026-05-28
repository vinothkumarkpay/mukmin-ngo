@extends('welfare.layouts.app')

@section('title', 'Friends of MUKMIN Registration - Pertubuhan Gabungan MUKMIN Nasional')

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

/* Custom Dropdown Single-Select */
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
                <h2>Friends of MUKMIN Registration Form</h2>
                <p><strong>Community & Supporter Network</strong> - Open to individuals, informal groups, suraus, madrasahs, and organizations that wish to support MUKMIN's grassroots community and volunteer initiatives.</p>
            </div>

            <!-- ELIGIBILITY SECTION -->
            <div class="eligibility-box" style="background: #fffbeb; border: 1px solid #fde68a; border-left: 4px solid #d43c18; padding: 20px; border-radius: 8px; margin-bottom: 30px; font-size: 13.5px; line-height: 20px; color: #78350f;">
                <h4 style="margin: 0 0 8px 0; color: #d43c18; font-weight: 700; font-size: 14.5px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-info-circle"></i> MUKMIN Membership Eligibility
                </h4>
                <p style="margin: 0 0 8px 0;">All members must demonstrate active involvement in community, educational, or welfare initiatives and commit to collaborative participation in MUKMIN programmes.</p>
                <p style="margin: 0; font-weight: 600;">The MUKMIN central committee reserves the right to approve or reject applications.</p>
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

            <form method="POST" action="{{ route('welfare.membership.friends.submit') }}">
                @csrf

                <!-- SECTION A: CATEGORY -->
                <div class="form-section-title">Section A: Category Selection</div>
                <div class="form-group">
                    <label>Select Friends of MUKMIN Category * (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="entity-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose category...">
                            <span class="trigger-text">Choose category...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($categories as $category)
                                <div class="dropdown-option-item">
                                    <input type="radio" name="entity_type" value="{{ $category }}" id="ent-{{ $loop->index }}" {{ old('entity_type') == $category ? 'checked' : '' }} required>
                                    <span for="ent-{{ $loop->index }}">{{ $category }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="others-specify-group" style="display: none;">
                    <label for="others_specify">Please specify "Others" Category *</label>
                    <input type="text" id="others_specify" name="others_specify" class="form-control" value="{{ old('others_specify') }}">
                </div>

                <!-- SECTION B: INDIVIDUAL DETAILS (IF INDIVIDUAL) -->
                <div id="individual-details-section" style="display: none;">
                    <div class="form-section-title">Section B: Individual Profile Details</div>
                    
                    <div class="form-group">
                        <label for="ind_name">Full Name *</label>
                        <input type="text" id="ind_name" name="ind_name" class="form-control" value="{{ old('ind_name') }}">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="ind_nric">NRIC Number *</label>
                            <input type="text" id="ind_nric" name="ind_nric" class="form-control" placeholder="e.g. 900101-14-5555" value="{{ old('ind_nric') }}">
                        </div>
                        <div class="form-group">
                            <label for="ind_state">State of Residency *</label>
                            <select id="ind_state" name="ind_state" class="form-control">
                                <option value="">-- Choose State --</option>
                                @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya'] as $state)
                                    <option value="{{ $state }}" {{ old('ind_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ind_address">Full Address *</label>
                        <textarea id="ind_address" name="ind_address" rows="3" class="form-control" style="font-family: inherit;">{{ old('ind_address') }}</textarea>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="ind_email">Email Address *</label>
                            <input type="email" id="ind_email" name="ind_email" class="form-control" value="{{ old('ind_email') }}">
                        </div>
                        <div class="form-group">
                            <label for="ind_phone">Contact Number *</label>
                            <input type="tel" id="ind_phone" name="ind_phone" class="form-control" placeholder="e.g. +60123456789" value="{{ old('ind_phone') }}">
                        </div>
                    </div>
                </div>

                <!-- SECTION C: ORGANISATION DETAILS (IF ORGANISATION) -->
                <div id="org-details-section" style="display: none;">
                    <div class="form-section-title">Section B: Organisation Profile Details</div>

                    <div class="form-group">
                        <label for="org_name">Name of Organisation *</label>
                        <input type="text" id="org_name" name="org_name" class="form-control" value="{{ old('org_name') }}">
                    </div>

                    <div class="form-group">
                        <label for="org_state">State *</label>
                        <select id="org_state" name="org_state" class="form-control">
                            <option value="">-- Choose State --</option>
                            @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya'] as $state)
                                <option value="{{ $state }}" {{ old('org_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="org_address">Full Address of Organisation *</label>
                        <textarea id="org_address" name="org_address" rows="3" class="form-control" style="font-family: inherit;">{{ old('org_address') }}</textarea>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="org_email">Official Organisation Email Address *</label>
                            <input type="email" id="org_email" name="org_email" class="form-control" value="{{ old('org_email') }}">
                        </div>
                        <div class="form-group">
                            <label for="org_phone">Official Contact Number (WhatsApp preferred) *</label>
                            <input type="tel" id="org_phone" name="org_phone" class="form-control" placeholder="e.g. +60123456789" value="{{ old('org_phone') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="org_website">Website / Social Media (if any)</label>
                        <input type="text" id="org_website" name="org_website" class="form-control" value="{{ old('org_website') }}">
                    </div>
                </div>

                <!-- DECLARATION -->
                <div class="form-section-title">Declaration</div>

                <div class="declaration-box">
                    <label>
                        <input type="checkbox" name="declaration_confirmed" value="1" required {{ old('declaration_confirmed') ? 'checked' : '' }}>
                        I/We hereby confirm that all information provided in this form is true and accurate. I/We agree to comply with the constitution, policies, and guidelines of MUKMIN.
                    </label>
                </div>

                <div class="important-notes">
                    <h4>IMPORTANT NOTES</h4>
                    <ul>
                        <li>Incomplete forms may not be processed.</li>
                        <li>MUKMIN reserves the right to approve or reject any application.</li>
                        <li>Friends of MUKMIN do not possess voting rights and are not eligible to hold positions within the Executive Committee.</li>
                    </ul>
                </div>

                <button type="submit" class="btn-submit">Submit Registration</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('entity-dropdown');
    const trigger = dropdown.querySelector('.dropdown-trigger');
    const triggerText = trigger.querySelector('.trigger-text');
    const list = dropdown.querySelector('.dropdown-options-list');
    const radios = dropdown.querySelectorAll('input[type="radio"]');

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('open');
    });

    function toggleFormSections() {
        const checkedRadio = dropdown.querySelector('input[type="radio"]:checked');
        const indSection = document.getElementById('individual-details-section');
        const orgSection = document.getElementById('org-details-section');
        const othersSpecifyGroup = document.getElementById('others-specify-group');
        const othersSpecifyInput = document.getElementById('others_specify');

        // Reset required attributes
        indSection.querySelectorAll('input, select, textarea').forEach(el => el.removeAttribute('required'));
        orgSection.querySelectorAll('input, select, textarea').forEach(el => el.removeAttribute('required'));
        othersSpecifyInput.removeAttribute('required');

        if (checkedRadio) {
            triggerText.textContent = checkedRadio.parentNode.textContent.trim();
            dropdown.classList.remove('open');
            
            const selectedVal = checkedRadio.value;
            
            // Toggle Specify Other input
            if (selectedVal.toLowerCase() === 'others') {
                othersSpecifyGroup.style.display = 'block';
                othersSpecifyInput.setAttribute('required', 'required');
            } else {
                othersSpecifyGroup.style.display = 'none';
            }

            // Toggle sections
            if (selectedVal.toLowerCase() === 'individual') {
                indSection.style.display = 'block';
                orgSection.style.display = 'none';
                
                // Add required inputs
                document.getElementById('ind_name').setAttribute('required', 'required');
                document.getElementById('ind_nric').setAttribute('required', 'required');
                document.getElementById('ind_state').setAttribute('required', 'required');
                document.getElementById('ind_address').setAttribute('required', 'required');
                document.getElementById('ind_email').setAttribute('required', 'required');
                document.getElementById('ind_phone').setAttribute('required', 'required');
            } else {
                indSection.style.display = 'none';
                orgSection.style.display = 'block';
                
                // Add required inputs
                document.getElementById('org_name').setAttribute('required', 'required');
                document.getElementById('org_state').setAttribute('required', 'required');
                document.getElementById('org_address').setAttribute('required', 'required');
                document.getElementById('org_email').setAttribute('required', 'required');
                document.getElementById('org_phone').setAttribute('required', 'required');
            }
        } else {
            triggerText.textContent = trigger.getAttribute('data-placeholder') || 'Choose category...';
            indSection.style.display = 'none';
            orgSection.style.display = 'none';
            othersSpecifyGroup.style.display = 'none';
        }
    }

    dropdown.addEventListener('change', toggleFormSections);

    dropdown.querySelectorAll('.dropdown-option-item').forEach(item => {
        item.addEventListener('click', function (e) {
            if (e.target.type !== 'radio') {
                const radio = item.querySelector('input[type="radio"]');
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });

    document.addEventListener('click', function () {
        dropdown.classList.remove('open');
    });

    // Run on load to restore state
    toggleFormSections();
});
</script>
@endpush
@endsection
