@php
    use App\Support\SisterhoodCollective;

    $registration = $registration ?? null;
    $hasOld = session()->hasOldInput();

    $field = function (string $name, $default = '') use ($registration, $hasOld) {
        if ($hasOld) {
            return old($name, $default);
        }

        return $registration->{$name} ?? $default;
    };

    $selectedList = function (string $name) use ($registration, $hasOld) {
        if ($hasOld) {
            $value = old($name, []);
            return is_array($value) ? $value : [];
        }

        $value = $registration->{$name} ?? [];
        return is_array($value) ? $value : [];
    };

    $declarationChecked = function (string $key, string $label) use ($registration, $hasOld) {
        if ($hasOld) {
            return (string) old('declaration_' . $key) === '1';
        }

        if (! $registration) {
            return false;
        }

        return in_array($label, $registration->declarations ?? [], true);
    };

    $areas = $selectedList('areas_of_interest');
    $looking = $selectedList('looking_forward');
@endphp

<div class="form-group">
    <label for="full_name">Full Name (as per NRIC/Passport)</label>
    <input type="text" id="full_name" name="full_name" class="form-control autocap" value="{{ $field('full_name') }}" maxlength="255" required autocapitalize="characters">
    @error('full_name')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="salutation">Salutation</label>
    <select id="salutation" name="salutation" class="form-control" required>
        <option value="">Choose</option>
        @foreach(SisterhoodCollective::salutations() as $option)
            <option value="{{ $option }}" {{ $field('salutation') === $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @error('salutation')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="preferred_name">Preferred Name</label>
    <input type="text" id="preferred_name" name="preferred_name" class="form-control autocap" value="{{ $field('preferred_name') }}" maxlength="255" required autocapitalize="characters">
    @error('preferred_name')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="nric_passport">NRIC Number / Passport Number</label>
    <input type="text" id="nric_passport" name="nric_passport" class="form-control autocap" value="{{ $field('nric_passport') }}" maxlength="50" required autocapitalize="characters">
    @error('nric_passport')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" class="form-control" value="{{ $field('email') }}" maxlength="255" required>
    <span class="field-hint">Required for the payment receipt and event updates.</span>
    @error('email')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="contact_number">Contact Number (Whatsapp)</label>
    <input type="text" id="contact_number" name="contact_number" class="form-control" value="{{ $field('contact_number') }}" maxlength="30" required>
    @error('contact_number')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="age">Age</label>
    <input type="number" id="age" name="age" class="form-control" value="{{ $field('age') }}" min="18" max="120" required>
    <span class="field-hint">Open to Indian Muslim women aged 18 and above.</span>
    @error('age')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="profession">Profession / Occupation</label>
    <select id="profession" name="profession" class="form-control" required>
        <option value="">Choose</option>
        @foreach(SisterhoodCollective::professions() as $option)
            <option value="{{ $option }}" {{ $field('profession') === $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @error('profession')<span class="field-error">{{ $message }}</span>@enderror
    <label for="profession_other" class="field-hint">If you selected Other, please specify</label>
    <input type="text" id="profession_other" name="profession_other" class="form-control autocap" value="{{ $field('profession_other') }}" maxlength="255" autocapitalize="characters">
    @error('profession_other')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="education_level">Education Level</label>
    <select id="education_level" name="education_level" class="form-control" required>
        <option value="">Choose</option>
        @foreach(SisterhoodCollective::educationLevels() as $option)
            <option value="{{ $option }}" {{ $field('education_level') === $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @error('education_level')<span class="field-error">{{ $message }}</span>@enderror
    <label for="education_other" class="field-hint">If you selected Other, please specify</label>
    <input type="text" id="education_other" name="education_other" class="form-control autocap" value="{{ $field('education_other') }}" maxlength="255" autocapitalize="characters">
    @error('education_other')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="unit_number">Unit Number</label>
    <input type="text" id="unit_number" name="unit_number" class="form-control autocap" value="{{ $field('unit_number') }}" maxlength="50" required autocapitalize="characters">
    @error('unit_number')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group form-group-full">
    <label for="address_line_1">Address Line 1</label>
    <input type="text" id="address_line_1" name="address_line_1" class="form-control autocap" value="{{ $field('address_line_1') }}" maxlength="255" required autocapitalize="characters">
    @error('address_line_1')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group form-group-full">
    <label for="address_line_2">Address Line 2</label>
    <input type="text" id="address_line_2" name="address_line_2" class="form-control autocap" value="{{ $field('address_line_2') }}" maxlength="255" autocapitalize="characters">
    <span class="field-hint">Optional.</span>
    @error('address_line_2')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="postcode">Postcode</label>
    <input type="text" id="postcode" name="postcode" class="form-control autocap" value="{{ $field('postcode') }}" maxlength="10" required autocapitalize="characters">
    @error('postcode')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="state">State</label>
    <select id="state" name="state" class="form-control" required>
        <option value="">Choose</option>
        @foreach(SisterhoodCollective::states() as $option)
            <option value="{{ $option }}" {{ $field('state') === $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @error('state')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group form-group-full">
    <label id="areas-label" for="areas-trigger">Area of Interest</label>
    <div class="custom-dropdown-container" data-placeholder="Choose">
        <input class="dropdown-validity" type="text" required tabindex="-1" autocomplete="off" value="{{ count($areas) ? '1' : '' }}" aria-hidden="true">
        <button type="button" class="dropdown-trigger" id="areas-trigger" aria-labelledby="areas-label" aria-expanded="false">
            <span class="trigger-text">Choose</span>
        </button>
        <div class="dropdown-options-list">
            @foreach(SisterhoodCollective::areasOfInterest() as $option)
                <label class="dropdown-option-item">
                    <input type="checkbox" name="areas_of_interest[]" value="{{ $option }}" {{ in_array($option, $areas, true) ? 'checked' : '' }}>
                    <span>{{ $option }}</span>
                </label>
            @endforeach
        </div>
    </div>
    @error('areas_of_interest')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group form-group-full">
    <label id="looking-label" for="looking-trigger">What are you most looking forward to at The Sisterhood Collective?</label>
    <div class="custom-dropdown-container" data-placeholder="Choose">
        <input class="dropdown-validity" type="text" required tabindex="-1" autocomplete="off" value="{{ count($looking) ? '1' : '' }}" aria-hidden="true">
        <button type="button" class="dropdown-trigger" id="looking-trigger" aria-labelledby="looking-label" aria-expanded="false">
            <span class="trigger-text">Choose</span>
        </button>
        <div class="dropdown-options-list">
            @foreach(SisterhoodCollective::lookingForward() as $option)
                <label class="dropdown-option-item">
                    <input type="checkbox" name="looking_forward[]" value="{{ $option }}" {{ in_array($option, $looking, true) ? 'checked' : '' }}>
                    <span>{{ $option }}</span>
                </label>
            @endforeach
        </div>
    </div>
    @error('looking_forward')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="food_preference">Food Preference</label>
    <select id="food_preference" name="food_preference" class="form-control" required>
        <option value="">Choose</option>
        @foreach(SisterhoodCollective::foodPreferences() as $option)
            <option value="{{ $option }}" {{ $field('food_preference') === $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @error('food_preference')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="emergency_contact_name">Emergency Contact Name</label>
    <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-control autocap" value="{{ $field('emergency_contact_name') }}" maxlength="255" required autocapitalize="characters">
    @error('emergency_contact_name')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="emergency_contact_number">Emergency Contact Number</label>
    <input type="tel" id="emergency_contact_number" name="emergency_contact_number" class="form-control" value="{{ $field('emergency_contact_number') }}" maxlength="30" required>
    @error('emergency_contact_number')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="emergency_relationship">Relationship to Participant</label>
    <input type="text" id="emergency_relationship" name="emergency_relationship" class="form-control autocap" value="{{ $field('emergency_relationship') }}" maxlength="255" required autocapitalize="characters">
    @error('emergency_relationship')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label for="heard_from">How did you hear about this programme?</label>
    <select id="heard_from" name="heard_from" class="form-control" required>
        <option value="">Choose</option>
        @foreach(SisterhoodCollective::heardFrom() as $option)
            <option value="{{ $option }}" {{ $field('heard_from') === $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    @error('heard_from')<span class="field-error">{{ $message }}</span>@enderror
    <label for="heard_from_other" class="field-hint">If you selected Other, please specify</label>
    <input type="text" id="heard_from_other" name="heard_from_other" class="form-control autocap" value="{{ $field('heard_from_other') }}" maxlength="255" autocapitalize="characters">
    @error('heard_from_other')<span class="field-error">{{ $message }}</span>@enderror
</div>

@if(! empty($showRegistrationPolicy))
    <div class="policy-box">
        <h2>Registration &amp; Cancellation Policy</h2>
        <p>Registration fees of RM{{ number_format(SisterhoodCollective::FEE, 0) }} are non-refundable upon cancellation or non-attendance. Participants are responsible for ensuring their registration details are accurate. Any changes or cancellations must be communicated to the organiser before the registration closing date. MUKMIN reserves the right to make changes to the event arrangements when necessary.</p>
    </div>
@endif

<div class="form-group form-group-full declaration-box">
    <span id="declaration-label">Participation Declaration</span>
    <span class="field-hint">Every statement must be marked before you can register.</span>
    <div class="choice-list">
        @foreach(SisterhoodCollective::declarations() as $key => $label)
            <label>
                <input type="checkbox" name="declaration_{{ $key }}" value="1" {{ $declarationChecked($key, $label) ? 'checked' : '' }} required>
                <span>{{ $label }}</span>
            </label>
        @endforeach
    </div>
    @if($errors->hasAny(array_map(function ($key) { return 'declaration_' . $key; }, array_keys(SisterhoodCollective::declarations()))))
        <span class="field-error">Please mark every participation declaration.</span>
    @endif
</div>

@once
<style>
.sisterhood-fields .autocap { text-transform: uppercase; }
.sisterhood-fields .custom-dropdown-container { position: relative; width: 100%; }
.sisterhood-fields .dropdown-validity {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 48px;
    opacity: 0;
    pointer-events: none;
    border: 0;
    padding: 0;
}
.sisterhood-fields .dropdown-trigger {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d2d8d5;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    font-weight: 400;
    background: #fcfdfd;
    color: #333;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    text-align: left;
    box-sizing: border-box;
}
.sisterhood-fields .dropdown-trigger .trigger-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.sisterhood-fields .dropdown-trigger::after {
    content: "";
    width: 8px;
    height: 8px;
    border-right: 2px solid #666;
    border-bottom: 2px solid #666;
    transform: rotate(45deg) translateY(-3px);
    flex-shrink: 0;
}
.sisterhood-fields .custom-dropdown-container.open .dropdown-trigger {
    border-color: #d43c18;
    background: #fff;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.sisterhood-fields .custom-dropdown-container.open .dropdown-trigger::after {
    transform: rotate(-135deg) translateY(-1px);
}
.sisterhood-fields .dropdown-options-list {
    display: none;
    border: 1px solid #d43c18;
    border-top: 1px solid #eadfdc;
    border-radius: 0 0 6px 6px;
    background: #fff;
    max-height: 240px;
    overflow-y: auto;
}
.sisterhood-fields .custom-dropdown-container.open .dropdown-options-list { display: block; }
.sisterhood-fields .dropdown-option-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin: 0;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.45;
    color: #222;
    cursor: pointer;
}
.sisterhood-fields .dropdown-option-item:hover { background: #fdf6f4; }
.sisterhood-fields .dropdown-option-item input {
    width: 16px;
    height: 16px;
    margin: 2px 0 0;
    flex-shrink: 0;
    accent-color: #d43c18;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sisterhood-fields .autocap').forEach(function (input) {
        function capitalise() {
            var start = input.selectionStart;
            var end = input.selectionEnd;
            var upper = input.value.toLocaleUpperCase();
            if (upper !== input.value) {
                input.value = upper;
                if (typeof start === 'number' && typeof end === 'number') {
                    input.setSelectionRange(start, end);
                }
            }
        }
        input.addEventListener('input', capitalise);
        capitalise();
    });

    var declarationBoxes = document.querySelectorAll('.sisterhood-fields input[name^="declaration_"]');
    function markDeclarations() {
        var unchecked = Array.prototype.filter.call(declarationBoxes, function (box) { return !box.checked; });
        declarationBoxes.forEach(function (box) { box.setCustomValidity(''); });
        if (unchecked.length) {
            unchecked[0].setCustomValidity('Please mark every participation declaration.');
        }
    }
    declarationBoxes.forEach(function (box) {
        box.addEventListener('change', markDeclarations);
    });
    var declarationForm = declarationBoxes.length ? declarationBoxes[0].form : null;
    if (declarationForm) {
        declarationForm.addEventListener('click', function (event) {
            if (event.target && event.target.closest && event.target.closest('[type="submit"]')) {
                markDeclarations();
            }
        }, true);
    }

    var dropdowns = document.querySelectorAll('.sisterhood-fields .custom-dropdown-container');

    dropdowns.forEach(function (dropdown) {
        var trigger = dropdown.querySelector('.dropdown-trigger');
        var triggerText = dropdown.querySelector('.trigger-text');
        var validity = dropdown.querySelector('.dropdown-validity');
        var checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
        var placeholder = dropdown.getAttribute('data-placeholder') || 'Choose';

        function update() {
            var checked = Array.prototype.filter.call(checkboxes, function (box) { return box.checked; });
            triggerText.textContent = checked.length
                ? checked.map(function (box) { return box.value; }).join(', ')
                : placeholder;
            if (validity) {
                validity.value = checked.length ? '1' : '';
            }
        }

        trigger.addEventListener('click', function (event) {
            event.stopPropagation();
            var willOpen = !dropdown.classList.contains('open');
            dropdowns.forEach(function (other) {
                other.classList.remove('open');
                other.querySelector('.dropdown-trigger').setAttribute('aria-expanded', 'false');
            });
            if (willOpen) {
                dropdown.classList.add('open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });

        dropdown.addEventListener('change', update);
        dropdown.addEventListener('click', function (event) { event.stopPropagation(); });
        update();
    });

    document.addEventListener('click', function () {
        dropdowns.forEach(function (dropdown) {
            dropdown.classList.remove('open');
            dropdown.querySelector('.dropdown-trigger').setAttribute('aria-expanded', 'false');
        });
    });
});
</script>
@endonce
