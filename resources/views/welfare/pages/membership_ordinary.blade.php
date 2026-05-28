@extends('welfare.layouts.app')

@section('title', 'Ordinary Member Registration - Pertubuhan Gabungan MUKMIN Nasional')

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
.bearer-row {
    background: #fafafa;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.bearer-row h4 {
    margin: 0 0 15px 0;
    font-size: 14.5px;
    font-weight: 700;
    color: #444;
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
                <h2>MUKMIN Organisation Membership Registration</h2>
                <p><strong>Ordinary Member (Ahli Biasa)</strong><br><br>Open to legally registered organisations that align with the mission, vision, and values of MUKMIN, including NGOs, civil society organisations, chambers of commerce, industry associations, institutions, foundations, community-based organisations, as well as mosques, madrasah, and surau throughout Malaysia.</p>
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

            <form method="POST" action="{{ route('welfare.membership.ordinary.submit') }}" enctype="multipart/form-data">
                @csrf

                <!-- SECTION A: ORGANISATION CATEGORY -->
                <div class="form-section-title">Section A: Organisation Category</div>
                <div class="form-group">
                    <label>Select Category (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="org-type-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose category types...">
                            <span class="trigger-text">Choose category types...</span>
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
                    <label for="org_type_other">Please specify "Others" Category</label>
                    <input type="text" id="org_type_other" name="org_type_other" class="form-control" value="{{ old('org_type_other') }}">
                </div>

                <!-- SECTION B: ORGANISATION DETAILS -->
                <div class="form-section-title">Section B: Organisation Details</div>
                
                <div class="form-group">
                    <label for="name_of_organisation">Name of Organisation</label>
                    <input type="text" id="name_of_organisation" name="name_of_organisation" class="form-control" value="{{ old('name_of_organisation') }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="org_reg_number">Organisation Registration Number</label>
                        <input type="text" id="org_reg_number" name="org_reg_number" class="form-control" value="{{ old('org_reg_number') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="org_reg_date">Organisation Registration Date</label>
                        <input type="date" id="org_reg_date" name="org_reg_date" class="form-control" value="{{ old('org_reg_date') }}" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="registered_state">Registered State</label>
                        <select id="registered_state" name="registered_state" class="form-control" required>
                            <option value="">-- Choose State --</option>
                            @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya'] as $state)
                                <option value="{{ $state }}" {{ old('registered_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="district_city">District / City</label>
                        <input type="text" id="district_city" name="district_city" class="form-control" value="{{ old('district_city') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_address">Full Address of Organisation</label>
                    <textarea id="full_address" name="full_address" rows="3" class="form-control" style="font-family: inherit;" required>{{ old('full_address') }}</textarea>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label for="postcode">Postcode</label>
                        <input type="text" id="postcode" name="postcode" class="form-control" value="{{ old('postcode') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="year_established">Year Established</label>
                        <input type="number" id="year_established" name="year_established" class="form-control" value="{{ old('year_established') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="total_members_size">Members / Congregation Size</label>
                        <input type="number" id="total_members_size" name="total_members_size" class="form-control" value="{{ old('total_members_size') }}" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="email">Official Organisation Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Official Contact Number (WhatsApp preferred)</label>
                        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="e.g. +60123456789" value="{{ old('contact_number') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="website">Website / Social Media (if any)</label>
                    <input type="text" id="website" name="website" class="form-control" value="{{ old('website') }}">
                </div>

                <!-- SECTION C: ORGANISATION PROFILE -->
                <div class="form-section-title">Section C: Organisation Profile</div>
                
                <div class="form-group">
                    <label>Primary Activities (Dropdown List)</label>
                    <div class="custom-dropdown-container" id="activity-dropdown">
                        <div class="dropdown-trigger" data-placeholder="Choose activities...">
                            <span class="trigger-text">Choose activities...</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options-list">
                            @foreach($activities as $activity)
                                <div class="dropdown-option-item">
                                    <input type="checkbox" name="primary_activities[]" value="{{ $activity }}" id="activity-{{ $loop->index }}" {{ is_array(old('primary_activities')) && in_array($activity, old('primary_activities')) ? 'checked' : '' }}>
                                    <span for="activity-{{ $loop->index }}">{{ $activity }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" id="other-activity-group" style="display: none;">
                    <label for="primary_activities_other">Please specify "Others" Activity</label>
                    <input type="text" id="primary_activities_other" name="primary_activities_other" class="form-control" value="{{ old('primary_activities_other') }}">
                </div>

                <!-- SECTION D: GOVERNANCE & LEGAL -->
                <div class="form-section-title">Section D: Governance & Legal</div>
                
                <div class="form-group">
                    <label>Is your organisation registered with ROS / Religious Authority?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="is_registered_ros" value="1" {{ old('is_registered_ros') === '1' ? 'checked' : '' }} required>
                            Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="is_registered_ros" value="0" {{ old('is_registered_ros') === '0' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Attach Supporting Documents</label>
                    <div style="background: #fdf6f4; border: 1px dashed #ccc; padding: 20px; border-radius: 6px; display: flex; flex-direction: column; gap: 15px;">
                        <div>
                            <label style="font-weight: 500; font-size: 13px; margin-bottom: 5px; display: block;">Copy of Registration Certificate (ROS / JAIS / relevant authority)</label>
                            <input type="file" name="registration_certificate" class="form-control" required style="font-size: 13px;">
                        </div>
                        <div>
                            <label style="font-weight: 500; font-size: 13px; margin-bottom: 5px; display: block;">List of Committee Members (Optional)</label>
                            <input type="file" name="committee_members" class="form-control" style="font-size: 13px;">
                        </div>
                        <span style="display: block; font-size: 11.5px; color: #666;">* Accepted file types: PDF, Images (JPG, PNG), Word (DOC, DOCX) - Max 10MB per file</span>
                    </div>
                </div>

                <!-- SECTION E: KEY OFFICE BEARERS -->
                <div class="form-section-title">Section E: Key Office Bearers</div>
                
                <!-- President -->
                <div class="bearer-row">
                    <h4>President / Chairman</h4>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="key_office_bearers[president][name]" class="form-control" value="{{ old('key_office_bearers.president.name') }}" required>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="key_office_bearers[president][email]" class="form-control" value="{{ old('key_office_bearers.president.email') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="tel" name="key_office_bearers[president][phone]" class="form-control" placeholder="e.g. +60123456789" value="{{ old('key_office_bearers.president.phone') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Secretary -->
                <div class="bearer-row">
                    <h4>Secretary General (Optional)</h4>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="key_office_bearers[secretary][name]" class="form-control" value="{{ old('key_office_bearers.secretary.name') }}">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="key_office_bearers[secretary][email]" class="form-control" value="{{ old('key_office_bearers.secretary.email') }}">
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="tel" name="key_office_bearers[secretary][phone]" class="form-control" placeholder="e.g. +60123456789" value="{{ old('key_office_bearers.secretary.phone') }}">
                        </div>
                    </div>
                </div>

                <!-- Treasurer -->
                <div class="bearer-row">
                    <h4>Treasurer (Optional)</h4>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="key_office_bearers[treasurer][name]" class="form-control" value="{{ old('key_office_bearers.treasurer.name') }}">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="key_office_bearers[treasurer][email]" class="form-control" value="{{ old('key_office_bearers.treasurer.email') }}">
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="tel" name="key_office_bearers[treasurer][phone]" class="form-control" placeholder="e.g. +60123456789" value="{{ old('key_office_bearers.treasurer.phone') }}">
                        </div>
                    </div>
                </div>

                <!-- SECTION F: DECLARATION -->
                <div class="form-section-title">Section F: Declaration</div>

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
                        <li>MUKMIN reserves the right to approve or reject any application.</li>
                        <li>Additional supporting documents may be requested if necessary.</li>
                    </ul>
                </div>

                <button type="submit" class="btn-submit">Submit Membership Application</button>
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

            // Category "Others" conditional toggle
            if (dropdown.id === 'org-type-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase().includes('others'));
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

            // Activity "Others" conditional toggle
            if (dropdown.id === 'activity-dropdown') {
                const hasOther = checked.some(cb => cb.value.toLowerCase().includes('others'));
                const otherGroup = document.getElementById('other-activity-group');
                const otherInput = document.getElementById('primary_activities_other');
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
});
</script>
@endpush
@endsection
