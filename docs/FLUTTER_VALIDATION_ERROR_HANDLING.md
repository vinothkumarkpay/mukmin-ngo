# MUKMIN Flutter App — Validation & Error Handling Document

> **Version:** 1.0  
> **Backend source:** Laravel 8 — `FormSubmissionController`, `AdminAuthController`, `AdminDashboardController`  
> **Companion docs:** [`API_DOCUMENTATION.md`](./API_DOCUMENTATION.md), [`FLUTTER_PROJECT_FLOW.md`](./FLUTTER_PROJECT_FLOW.md)  
> **Generated:** June 2026

---

## Table of Contents

1. [Shared Validation Primitives](#1-shared-validation-primitives)
2. [Input Validation Rules by Screen](#2-input-validation-rules-by-screen)
3. [API Error Handling Matrix](#3-api-error-handling-matrix)
4. [Flutter Error Handling Strategy](#4-flutter-error-handling-strategy)
5. [Business Logic Validations](#5-business-logic-validations)
6. [Security Validations](#6-security-validations)

---

## 1. Shared Validation Primitives

These rules are defined once in PHP (`FormSubmissionController` private helpers) and must be mirrored in Flutter as reusable validators in `lib/core/utils/validators.dart`.

### PHP → Flutter mapping

| Rule name | PHP definition | Flutter constant / function |
|-----------|----------------|----------------------------|
| **Required email** | `required\|email:rfc,dns\|max:255` (prod) | `Validators.requiredEmail()` |
| **Optional email** | `nullable\|email:rfc,dns\|max:255` | `Validators.optionalEmail()` |
| **Required phone** | `required\|regex:/^\+?[0-9][0-9\s\-()]{7,19}$/` | `Validators.requiredPhone()` |
| **Optional phone** | `nullable\|regex:/^\+?[0-9][0-9\s\-()]{7,19}$/` | `Validators.optionalPhone()` |
| **NRIC** | `required\|regex:/^\d{1,12}$/` | `Validators.requiredNric()` |
| **NRIC or passport** | `required\|regex:/^(?:\d{1,12}\|(?=.*[A-Za-z])[A-Za-z0-9]{6,20})$/` | `Validators.requiredNricOrPassport()` |
| **Accepted checkbox** | `required\|accepted` | `Validators.accepted()` — must be `true` |
| **Required boolean** | `required\|boolean` | Non-null `bool` required |
| **Min array length 1** | `required\|array\|min:1` | `list.isNotEmpty` check |

### Shared Dart validator library

```dart
// lib/core/utils/validators.dart
class Validators {
  static final _phoneRegex = RegExp(r'^\+?[0-9][0-9\s\-()]{7,19}$');
  static final _nricRegex = RegExp(r'^\d{1,12}$');
  static final _nricOrPassportRegex =
      RegExp(r'^(?:\d{1,12}|(?=.*[A-Za-z])[A-Za-z0-9]{6,20})$');
  static final _emailRegex = RegExp(r'^[\w\.\-\+]+@[\w\.\-]+\.\w{2,}$');

  static String? required(String? value, {String field = 'This field'}) {
    if (value == null || value.trim().isEmpty) return '$field is required.';
    return null;
  }

  static String? maxLength(String? value, int max, {String field = 'This field'}) {
    if (value != null && value.length > max) {
      return '$field must not exceed $max characters.';
    }
    return null;
  }

  static String? requiredEmail(String? value) {
    if (value == null || value.trim().isEmpty) return 'Email is required.';
    if (value.length > 255) return 'Email must not exceed 255 characters.';
    if (!_emailRegex.hasMatch(value)) return 'Enter a valid email address.';
    // Note: PHP also checks DNS in production — optional client-side DNS is impractical;
    // rely on server 422 if domain invalid.
    return null;
  }

  static String? optionalEmail(String? value) {
    if (value == null || value.trim().isEmpty) return null;
    return requiredEmail(value);
  }

  static String? requiredPhone(String? value) {
    if (value == null || value.trim().isEmpty) return 'Phone number is required.';
    if (!_phoneRegex.hasMatch(value)) {
      return 'Enter a valid phone number (e.g. +60123456789).';
    }
    return null;
  }

  static String? optionalPhone(String? value) {
    if (value == null || value.trim().isEmpty) return null;
    return requiredPhone(value);
  }

  static String? requiredNric(String? value) {
    if (value == null || value.trim().isEmpty) return 'NRIC is required.';
    final digits = value.replaceAll(RegExp(r'[\s\-]'), '');
    if (!_nricRegex.hasMatch(digits)) {
      return 'NRIC must be 1–12 digits.';
    }
    return null;
  }

  static String? requiredNricOrPassport(String? value) {
    if (value == null || value.trim().isEmpty) return 'NRIC/Passport is required.';
    if (!_nricOrPassportRegex.hasMatch(value.trim())) {
      return 'Enter a valid NRIC (1–12 digits) or passport (6–20 alphanumeric).';
    }
    return null;
  }

  static String? accepted(bool? value) {
    if (value != true) return 'You must accept the declaration to proceed.';
    return null;
  }

  static String? minNumeric(num? value, num min, {String field = 'Value'}) {
    if (value == null) return '$field is required.';
    if (value < min) return '$field must be at least $min.';
    return null;
  }

  static String? requiredSelection<T>(List<T>? values) {
    if (values == null || values.isEmpty) return 'Select at least one option.';
    return null;
  }

  static String? inEnum(String? value, List<String> allowed, {String field = 'Selection'}) {
    if (value == null || !allowed.contains(value)) {
      return '$field must be one of: ${allowed.join(', ')}.';
    }
    return null;
  }
}
```

### TextFormField usage pattern

```dart
TextFormField(
  decoration: const InputDecoration(labelText: 'Email *'),
  keyboardType: TextInputType.emailAddress,
  autovalidateMode: AutovalidateMode.onUserInteraction,
  validator: Validators.requiredEmail,
)
```

---

## 2. Input Validation Rules by Screen

Legend: **R** = Required, **O** = Optional. Min/max apply to string length unless noted.

---

### 2.1 AdminLoginScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Email | `email` | string | R | — | — | Valid email format | `Validators.requiredEmail` |
| Password | `password` | string | R | — | — | — | `Validators.required` |
| Remember me | `remember` | boolean | O | — | — | `true` / `false` | No validator (Switch default `false`) |

```dart
// password — PHP only checks required|string (no min length)
TextFormField(
  obscureText: true,
  validator: (v) => Validators.required(v, field: 'Password'),
)
```

**Server auth failure (not validation):** HTTP `302` redirect with error on `email`: *"The provided credentials do not match our records."*

---

### 2.2 ContactScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Name | `name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Email | `email` | string | R | — | 255 | Email RFC+DNS (server) | `Validators.requiredEmail` |
| Phone | `phone` | string | R | — | — | Phone regex | `Validators.requiredPhone` |
| Message | `message` | string | R | 1 | — | — | `Validators.required` |

```dart
TextFormField(
  maxLines: 5,
  validator: (v) => Validators.required(v, field: 'Message'),
)
```

---

### 2.3 DonateScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Name | `name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Email | `email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| Preset amount | `amount` | number | O | 1 | — | numeric ≥ 1 | `minNumeric(1)` if set |
| Custom amount | `custom_amount` | number | O | 1 | — | numeric ≥ 1 | `minNumeric(1)` if set |

**Client business rule:** At least one of `amount` or `custom_amount` should be provided for a meaningful enquiry (not enforced by PHP).

```dart
String? validateDonateAmount(String? preset, String? custom) {
  final p = double.tryParse(preset ?? '');
  final c = double.tryParse(custom ?? '');
  if ((p == null || p < 1) && (c == null || c < 1)) {
    return 'Select or enter a donation amount of at least 1.';
  }
  return null;
}
```

---

### 2.4 FeedbackFormScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Full name | `full_name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| NRIC | `nric_number` | string | R | 1 | 12 | `^\d{1,12}$` | `Validators.requiredNric` |
| Organisation | `organisation` | string | O | — | 255 | — | `maxLength(255)` |
| Position | `position` | string | O | — | 255 | — | `maxLength(255)` |
| State | `state_residency` | string | R | 1 | 50 | — | `required` + `maxLength(50)` |
| Address | `full_address` | string | R | 1 | — | — | `Validators.required` |
| Email | `email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| Phone | `contact_number` | string | R | — | — | Phone regex | `Validators.requiredPhone` |
| Categories | `categories` | array | R | 1 item | — | From API `feedback_category` | `requiredSelection` |
| Other category | `other_category` | string | O | — | 255 | — | Required if "Other" selected (client UX) |
| Suggestion | `suggestion_description` | string | R | 1 | — | — | `Validators.required` |
| Benefits | `benefits_description` | string | R | 1 | — | — | `Validators.required` |
| Contact consent | `contact_consent` | boolean | R | — | — | `true`/`false` | Must not be null |
| Preferred contact | `preferred_contact_methods` | array | O | — | — | e.g. Email, Phone, WhatsApp | — |
| Declaration | `declaration_confirmed` | boolean | R | — | — | Must be accepted | `Validators.accepted` |

**Seeded category options:** Community Development, Education & Youth, Welfare & Humanitarian, Mosque / NGO Collaboration, Digital & Media, Volunteerism, Funding / Partnerships, Events & Programmes, Other

---

### 2.5 OrdinaryMemberFormScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Organisation name | `name_of_organisation` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Reg number | `org_reg_number` | string | R | 1 | 50 | — | `required` + `maxLength(50)` |
| Reg date | `org_reg_date` | date | R | — | — | `YYYY-MM-DD`, valid date | Date picker + not future |
| Registered state | `registered_state` | string | R | 1 | 50 | — | `required` + `maxLength(50)` |
| Address | `full_address` | string | R | 1 | — | — | `Validators.required` |
| Postcode | `postcode` | string | R | 1 | 10 | — | `required` + `maxLength(10)` |
| District/City | `district_city` | string | R | 1 | 100 | — | `required` + `maxLength(100)` |
| Year established | `year_established` | integer | R | 1800 | current year | — | Custom range validator |
| Members size | `total_members_size` | integer | R | 0 | — | ≥ 0 | `minNumeric(0)` |
| Email | `email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| Phone | `contact_number` | string | R | — | — | Phone regex | `Validators.requiredPhone` |
| Website | `website` | string | O | — | 255 | — | `maxLength(255)` |
| Org type | `org_type` | array | R | 1 | — | API `ordinary_org_type` | `requiredSelection` |
| Org type other | `org_type_other` | string | O | — | 255 | — | If "Others" selected |
| Primary activities | `primary_activities` | array | R | 1 | — | API `ordinary_activity` | `requiredSelection` |
| Activities other | `primary_activities_other` | string | O | — | 255 | — | If "Others" selected |
| President name | `key_office_bearers.president.name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| President email | `key_office_bearers.president.email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| President phone | `key_office_bearers.president.phone` | string | R | — | — | Phone | `Validators.requiredPhone` |
| Secretary name | `key_office_bearers.secretary.name` | string | O | — | 255 | — | `maxLength(255)` |
| Secretary email | `key_office_bearers.secretary.email` | string | O | — | 255 | Email | `Validators.optionalEmail` |
| Secretary phone | `key_office_bearers.secretary.phone` | string | O | — | — | Phone | `Validators.optionalPhone` |
| Treasurer name | `key_office_bearers.treasurer.name` | string | O | — | 255 | — | `maxLength(255)` |
| Treasurer email | `key_office_bearers.treasurer.email` | string | O | — | 255 | Email | `Validators.optionalEmail` |
| Treasurer phone | `key_office_bearers.treasurer.phone` | string | O | — | — | Phone | `Validators.optionalPhone` |
| Declaration | `declaration_confirmed` | boolean | R | — | — | Accepted | `Validators.accepted` |

```dart
String? validateYearEstablished(String? value) {
  final year = int.tryParse(value ?? '');
  final currentYear = DateTime.now().year;
  if (year == null) return 'Year established is required.';
  if (year < 1800 || year > currentYear) {
    return 'Year must be between 1800 and $currentYear.';
  }
  return null;
}
```

**Seeded org types:** NGO, Chambers of Commerce, Civil Society Organisation, Masjid, Surau, Madrasah, Others  
**Seeded activities:** Education / Classes, Dakwah / Outreach, Welfare / Charity, Youth Development, Women Programmes, Community Services, Others

---

### 2.6 FriendsMemberFormScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Condition | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-----------|-------------------|
| Entity type | `entity_type` | string | R | 1 | 50 | API `friends_category` | Always | `required` |
| Others specify | `others_specify` | string | O | — | 255 | — | If type is "Others" | Client UX required |
| Declaration | `declaration_confirmed` | boolean | R | — | — | Accepted | Always | `Validators.accepted` |

**When `entity_type == "Individual"`**

| Field | API key | Type | R/O | Max | Validator |
|-------|---------|------|-----|-----|-----------|
| Name | `ind_name` | string | R | 255 | `required` + `maxLength(255)` |
| NRIC | `ind_nric` | string | R | 12 | `Validators.requiredNric` |
| State | `ind_state` | string | R | 50 | `required` + `maxLength(50)` |
| Address | `ind_address` | string | R | — | `Validators.required` |
| Email | `ind_email` | string | R | 255 | `Validators.requiredEmail` |
| Phone | `ind_phone` | string | R | — | `Validators.requiredPhone` |

**When `entity_type != "Individual"`** (Organisation path)

| Field | API key | Type | R/O | Max | Validator |
|-------|---------|------|-----|-----|-----------|
| Org name | `org_name` | string | R | 255 | `required` + `maxLength(255)` |
| State | `org_state` | string | R | 50 | `required` + `maxLength(50)` |
| Address | `org_address` | string | R | — | `Validators.required` |
| Email | `org_email` | string | R | 255 | `Validators.requiredEmail` |
| Phone | `org_phone` | string | R | — | `Validators.requiredPhone` |
| Website | `org_website` | string | O | 255 | `maxLength(255)` |

**Seeded entity types:** Individual, Surau, Madrasah, Non-registered NGO, Others

```dart
String? validateFriendsForm(FriendsFormState state) {
  if (state.entityType == 'Individual') {
    return Validators.required(state.indName, field: 'Name');
    // ... other ind_* fields
  } else {
    return Validators.required(state.orgName, field: 'Organisation name');
    // ... other org_* fields
  }
}
```

---

### 2.7 MentorFormScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Full name | `full_name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| NRIC/Passport | `nric_passport` | string | R | — | — | NRIC or passport regex | `Validators.requiredNricOrPassport` |
| Gender | `gender` | string | R | — | — | `Male`, `Female` | `inEnum(['Male','Female'])` |
| Occupation | `occupation` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Organisation | `organisation` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Position | `position` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Experience years | `experience_years` | integer | R | 0 | — | ≥ 0 | `minNumeric(0)` |
| State | `state_residency` | string | R | 1 | 50 | — | `required` + `maxLength(50)` |
| Address | `full_address` | string | R | 1 | — | — | `Validators.required` |
| Email | `email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| Phone | `contact_number` | string | R | — | — | Phone | `Validators.requiredPhone` |
| LinkedIn | `linkedin` | string | O | — | 255 | URL (client UX) | `maxLength(255)` |
| Expertise areas | `expertise_areas` | array | R | 1 | — | API `mentor_expertise` | `requiredSelection` |
| Expertise other | `expertise_other` | string | O | — | 255 | — | If "Other" selected |
| Preferred format | `preferred_format` | array | R | 1 | — | API `mentor_format` | `requiredSelection` |
| Preferred commitment | `preferred_commitment` | array | R | 1 | — | API `mentor_commitment` | `requiredSelection` |
| Experience description | `experience_description` | string | R | 1 | — | — | `Validators.required` |
| Has served before | `has_served_before` | boolean | R | — | — | — | Non-null |
| Served before details | `served_before_details` | string | O* | — | — | — | *Client: required if `has_served_before == true` |
| Declaration | `declaration_confirmed` | boolean | R | — | — | Accepted | `Validators.accepted` |

---

### 2.8 PartnerFormScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Company name | `company_name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Contact person | `contact_person` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Position | `position` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Org reg number | `org_reg_number` | string | O | — | 50 | — | `maxLength(50)` |
| Email | `email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| Phone | `contact_number` | string | R | — | — | Phone | `Validators.requiredPhone` |
| Office address | `office_address` | string | R | 1 | — | — | `Validators.required` |
| State/Country | `state_country` | string | R | 1 | 50 | — | `required` + `maxLength(50)` |
| Org type | `org_type` | array | R | 1 | — | API `partner_org_type` | `requiredSelection` |
| Org type other | `org_type_other` | string | O | — | 255 | — | If "Other" |
| Collaboration areas | `collaboration_areas` | array | R | 1 | — | API `partner_collaboration` | `requiredSelection` |
| Collaboration other | `collaboration_other` | string | O | — | 255 | — | If "Other" |
| Partnership type | `partnership_type` | array | R | 1 | — | API `partner_type` | `requiredSelection` |
| Partnership other | `partnership_other` | string | O | — | 255 | — | If "Other" |
| Proposal | `proposal_description` | string | R | 1 | — | — | `Validators.required` |
| Expected outcomes | `expected_outcomes` | string | R | 1 | — | — | `Validators.required` |
| Has collaborated before | `has_collaborated_before` | boolean | R | — | — | — | Non-null |
| Previous collab details | `collaborated_before_details` | string | O* | — | — | — | *Client: if `has_collaborated_before` |
| Supporting files | `supporting_files[]` | file[] | O | — | 20 MB each | See [§6.4](#64-file-upload-validation) | Custom file validator |
| Declaration | `declaration_confirmed` | boolean | R | — | — | Accepted | `Validators.accepted` |

---

### 2.9 VolunteerFormScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Full name | `full_name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| NRIC/Passport | `nric_passport` | string | R | — | — | NRIC/passport regex | `Validators.requiredNricOrPassport` |
| Gender | `gender` | string | R | — | — | `Male`, `Female` | `inEnum` |
| Occupation/Study | `occupation_study` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Organisation | `organisation` | string | O | — | 255 | — | `maxLength(255)` |
| State | `state_residency` | string | R | 1 | 50 | — | `required` + `maxLength(50)` |
| Address | `full_address` | string | R | 1 | — | — | `Validators.required` |
| Email | `email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| Phone | `contact_number` | string | R | — | — | Phone | `Validators.requiredPhone` |
| Interest areas | `interest_areas` | array | R | 1 | — | API `volunteer_interest` | `requiredSelection` |
| Interest other | `interest_other` | string | O | — | 255 | — | If "Other" |
| Skills/Expertise | `skills_expertise` | string | R | 1 | — | — | `Validators.required` |
| Preferred mode | `preferred_mode` | string | R | — | — | `Physical / On-Ground`, `Virtual / Remote`, `Both` | `inEnum` |
| Availability | `availability` | array | R | 1 | — | API `volunteer_availability` | `requiredSelection` |
| Volunteered before | `has_volunteered_before` | boolean | R | — | — | — | Non-null |
| Volunteer details | `volunteered_before_details` | string | O* | — | — | — | *Client: if true |
| Emergency name | `emergency_contact_name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Emergency relationship | `emergency_contact_relationship` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Emergency phone | `emergency_contact_phone` | string | R | — | — | Phone | `Validators.requiredPhone` |
| Declaration | `declaration_confirmed` | boolean | R | — | — | Accepted | `Validators.accepted` |

---

### 2.10 CommunityAidFormScreen

| Field | API key | Type | R/O | Min | Max | Regex / Enum | Flutter validator |
|-------|---------|------|-----|-----|-----|--------------|-------------------|
| Full name | `full_name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| NRIC/Passport | `nric_passport` | string | R | — | — | NRIC/passport regex | `Validators.requiredNricOrPassport` |
| Gender | `gender` | string | R | — | — | `Male`, `Female` | `inEnum` |
| Date of birth | `dob` | date | R | — | — | `YYYY-MM-DD` | Date picker; not future |
| Nationality | `nationality` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Occupation | `occupation` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Monthly income | `monthly_income` | string | O | — | 255 | — | `maxLength(255)` |
| Phone | `contact_number` | string | R | — | — | Phone | `Validators.requiredPhone` |
| Email | `email` | string | R | — | 255 | Email | `Validators.requiredEmail` |
| Address | `full_address` | string | R | 1 | — | — | `Validators.required` |
| State | `state_residency` | string | R | 1 | 50 | — | `required` + `maxLength(50)` |
| Type of aid | `type_of_aid` | array | R | 1 | — | Client-defined + optional API | `requiredSelection` |
| Aid other | `type_of_aid_other` | string | O | — | 255 | — | If "Other" |
| Situation | `situation_description` | string | R | 1 | — | — | `Validators.required` |
| Who benefits | `who_benefits` | string | R | — | — | See enum below | `inEnum` |
| Beneficiaries count | `number_of_beneficiaries` | integer | O | 1 | — | ≥ 1 if set | `minNumeric(1)` |
| Received aid before | `received_aid_before` | boolean | R | — | — | — | Non-null |
| Previous aid details | `received_aid_before_details` | string | O* | — | — | — | *Client: if true |
| Supporting files | `supporting_files[]` | file[] | O | — | 20 MB each | See §6.4 | File validator |
| Emergency name | `emergency_contact_name` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Emergency relationship | `emergency_contact_relationship` | string | R | 1 | 255 | — | `required` + `maxLength(255)` |
| Emergency phone | `emergency_contact_phone` | string | R | — | — | Phone | `Validators.requiredPhone` |
| Declaration | `declaration_confirmed` | boolean | R | — | — | Accepted | `Validators.accepted` |

**`who_benefits` enum (exact strings):** `Individual`, `Family`, `Community / Group`, `Organisation / Institution`

---

### 2.11 AdminSubmissionDetailScreen — Status update

| Field | API key | Type | R/O | Enum values | Flutter validator |
|-------|---------|------|-----|-------------|-------------------|
| Status | `status` | string | R | `pending`, `approved`, `rejected`, `under_review` | `inEnum([...])` |

**Path params:** `type` must be one of `ordinary`, `friends`, `partner`, `aid` only.

---

### 2.12 AdminOptionsScreen

**Add option**

| Field | API key | Type | R/O | Max | Flutter validator |
|-------|---------|------|-----|-----|-------------------|
| Form type | `form_type` | string | R | 100 | `required` + `maxLength(100)` |
| Option value | `option_value` | string | R | 255 | `required` + `maxLength(255)` |
| Sort order | `sort_order` | integer | O | — | Defaults to `0` |

**Edit option**

| Field | API key | Type | R/O | Max | Flutter validator |
|-------|---------|------|-----|-----|-------------------|
| Option value | `option_value` | string | R | 255 | `required` + `maxLength(255)` |
| Sort order | `sort_order` | integer | R | — | `required` (integer) |

**Delete option:** Path param `id` only — no body validation.

---

## 3. API Error Handling Matrix

### How Laravel responds in this codebase

| Request type | Validation fail | Auth fail | Not found |
|--------------|-----------------|-----------|-----------|
| Web POST **without** `Accept: application/json` | **302** redirect + session errors | **302** redirect to login | **404** HTML |
| Web/AJAX **with** `Accept: application/json` | **422** JSON | **401** JSON (Sanctum) or **302** (admin middleware) | **404** JSON |
| `/api/*` Sanctum | **422** JSON | **401** JSON | **404** JSON |

> **403 Forbidden** and **409 Conflict** are **not implemented** in the current PHP codebase. Rows below include them for Flutter defensive handling and future API design.

---

### Master error matrix

| HTTP Status | Error Code / Key | Typical Message | Cause | Flutter Handling |
|-------------|------------------|-----------------|-------|------------------|
| **302** | — | (redirect) | Web form validation fail without JSON Accept; admin login success/fail; unauthenticated admin access | If using legacy web routes: treat as error unless following redirects. Prefer `Accept: application/json` to avoid 302 on validation. |
| **400** | `error` | `"Invalid submission type for status update."` | `POST /admin/submissions/{type}/{id}/status` where `type` ∉ `{ordinary,friends,partner,aid}` | Show snackbar with message; do not retry |
| **400** | — | `"Bad Request"` | Malformed JSON body | Show snackbar; fix request payload |
| **401** | `message` | `"Unauthenticated."` | Missing/expired/revoked Sanctum token on `/api/user` or future API | Clear token; navigate to `AdminLoginScreen`; snackbar *"Session expired"* |
| **401** | `errors.email` | `"The provided credentials do not match our records."` | Invalid admin login (JSON API target) | Inline error on email field on login screen |
| **403** | `message` | `"Forbidden."` | *Not implemented* — future role-based restriction | Show dialog *"You don't have permission"*; navigate back |
| **404** | `error` | `"Submission not found."` | `GET /admin/submissions/{type}/{id}` — record missing | Show empty state / *"Submission not found"*; pop screen |
| **404** | `message` | `"No query results for model [...]"` | `findOrFail` on invalid option/submission ID | Show snackbar; refresh list |
| **404** | — | HTML 404 page | Invalid public URL / slug | Show generic not-found screen |
| **409** | `message` | `"Conflict."` | *Not implemented* — future duplicate submission prevention | Show dialog explaining duplicate; offer to view existing |
| **419** | `message` | `"CSRF token mismatch."` | Missing/expired CSRF on web POST | Auto: refresh CSRF via `GET /sanctum/csrf-cookie`, retry **once**; else snackbar |
| **422** | `message` | `"The given data was invalid."` | Laravel validation failed | Map `errors` map to inline field errors; scroll to first error |
| **422** | `errors.{field}` | Field-specific (see below) | Individual rule failures | Inline under matching `TextFormField` |
| **429** | `message` | `"Too Many Requests."` | `/api/*` rate limit: 60/min per user/IP | Snackbar + disable submit briefly; exponential backoff on GET |
| **500** | `message` | `"Server Error"` | Unhandled PHP exception, mail/DB failure | Dialog *"Something went wrong"* + Retry button (GET only) |
| **502** | — | Bad Gateway | Reverse proxy/upstream failure | Snackbar + retry (GET) |
| **503** | — | Service Unavailable | Maintenance mode | Full-screen maintenance message |
| **504** | — | Gateway Timeout | Server timeout | Snackbar + retry |
| **—** | `DioExceptionType.connectionTimeout` | — | Connect timeout (>15s default) | Snackbar *"Connection timed out"* + Retry |
| **—** | `DioExceptionType.receiveTimeout` | — | Response timeout | Snackbar + Retry (GET) |
| **—** | `DioExceptionType.connectionError` | — | No internet, DNS failure, refused | Show offline banner; queue form draft locally |
| **—** | `DioExceptionType.cancel` | — | Request cancelled (user navigated away) | Silent — no UI |
| **—** | `DioExceptionType.badCertificate` | — | SSL pinning / cert failure | Dialog *"Secure connection failed"* — do not retry |

---

### 422 field error examples (from Laravel default messages)

| Field rule | Example `errors` value |
|------------|-------------------------|
| `required` | `["The email field is required."]` |
| `email` / `email:rfc,dns` | `["The email must be a valid email address."]` |
| `max:255` | `["The full name must not be greater than 255 characters."]` |
| `regex` (phone) | `["The contact number format is invalid."]` |
| `regex` (NRIC) | `["The nric number format is invalid."]` |
| `in:` (gender) | `["The selected gender is invalid."]` |
| `accepted` | `["The declaration confirmed must be accepted."]` |
| `array\|min:1` | `["The categories must have at least 1 item."]` |
| `integer\|min:1800` | `["The year established must be at least 1800."]` |
| `file\|mimes:...` | `["The supporting_files.0 must be a file of type: pdf, jpg, ..."]` |
| `file\|max:20480` | `["The supporting_files.0 must not be greater than 20480 kilobytes."]` |
| `date` | `["The dob is not a valid date."]` |
| `numeric\|min:1` | `["The amount must be at least 1."]` |

### Nested field keys in 422 responses

Laravel returns dot notation for nested fields:

```
"errors": {
  "key_office_bearers.president.email": ["The key_office_bearers.president.email field is required."]
}
```

Flutter mapping: split key by `.` and map to form section + field, or maintain a `fieldKeyAliases` map.

---

## 4. Flutter Error Handling Strategy

### 4.1 Error model classes

```dart
// lib/core/network/api_error.dart
class ApiError implements Exception {
  final String message;
  final int? statusCode;
  final Map<String, List<String>> fieldErrors;
  final ApiErrorType type;

  const ApiError({
    required this.message,
    this.statusCode,
    this.fieldErrors = const {},
    this.type = ApiErrorType.unknown,
  });

  factory ApiError.fromDioException(DioException e) {
    final response = e.response;
    final data = response?.data;

    if (e.type == DioExceptionType.connectionError ||
        e.type == DioExceptionType.unknown) {
      return ApiError(
        message: 'No internet connection. Check your network and try again.',
        type: ApiErrorType.offline,
      );
    }
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.receiveTimeout) {
      return ApiError(
        message: 'Request timed out. Please try again.',
        type: ApiErrorType.timeout,
      );
    }

    if (data is Map<String, dynamic>) {
      final errors = <String, List<String>>{};
      final raw = data['errors'];
      if (raw is Map) {
        raw.forEach((key, value) {
          if (value is List) {
            errors[key.toString()] = value.cast<String>();
          }
        });
      }
      return ApiError(
        message: data['message']?.toString() ??
            data['error']?.toString() ??
            'An error occurred.',
        statusCode: response?.statusCode,
        fieldErrors: errors,
        type: _typeFromStatus(response?.statusCode),
      );
    }

    return ApiError(
      message: 'An unexpected error occurred.',
      statusCode: response?.statusCode,
      type: _typeFromStatus(response?.statusCode),
    );
  }

  static ApiErrorType _typeFromStatus(int? code) {
    return switch (code) {
      401 => ApiErrorType.unauthorized,
      403 => ApiErrorType.forbidden,
      404 => ApiErrorType.notFound,
      409 => ApiErrorType.conflict,
      419 => ApiErrorType.csrfExpired,
      422 => ApiErrorType.validation,
      429 => ApiErrorType.rateLimited,
      500 || 502 || 503 || 504 => ApiErrorType.server,
      _ => ApiErrorType.unknown,
    };
  }

  String? firstErrorFor(String fieldKey) => fieldErrors[fieldKey]?.first;
}

enum ApiErrorType {
  offline,
  timeout,
  unauthorized,
  forbidden,
  notFound,
  conflict,
  csrfExpired,
  validation,
  rateLimited,
  server,
  unknown,
}
```

```dart
// lib/core/network/validation_result.dart
class ValidationResult {
  final Map<String, String> fieldErrors;

  const ValidationResult(this.fieldErrors);

  bool get hasErrors => fieldErrors.isNotEmpty;

  factory ValidationResult.fromApiError(ApiError error) {
    return ValidationResult({
      for (final e in error.fieldErrors.entries)
        e.key: e.value.first,
    });
  }
}
```

---

### 4.2 Global Dio interceptor stack

```dart
// lib/core/network/dio_client.dart
Dio createDio(Ref ref) {
  final dio = Dio(BaseOptions(
    connectTimeout: const Duration(seconds: 15),
    receiveTimeout: const Duration(seconds: 30),
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  ));

  dio.interceptors.addAll([
    AuthInterceptor(ref),
    CsrfInterceptor(ref),
    ErrorInterceptor(ref),
    if (kDebugMode) LogInterceptor(requestBody: true, responseBody: true),
  ]);

  return dio;
}
```

```dart
// lib/core/network/error_interceptor.dart
class ErrorInterceptor extends Interceptor {
  ErrorInterceptor(this.ref);
  final Ref ref;

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    final apiError = ApiError.fromDioException(err);

    // 401 — force logout
    if (apiError.type == ApiErrorType.unauthorized) {
      await ref.read(authNotifierProvider.notifier).logout(localOnly: true);
      ref.read(appRouterProvider).go('/admin/login');
    }

    // 419 — refresh CSRF once
    if (apiError.type == ApiErrorType.csrfExpired &&
        err.requestOptions.extra['csrfRetried'] != true) {
      await ref.read(csrfServiceProvider).refresh();
      final opts = err.requestOptions;
      opts.extra['csrfRetried'] = true;
      try {
        final clone = await ref.read(dioProvider).fetch(opts);
        return handler.resolve(clone);
      } catch (e) {
        // fall through
      }
    }

    handler.reject(err.copyWith(error: apiError));
  }
}
```

---

### 4.3 Snackbar vs Dialog vs inline — decision guide

| Scenario | UI pattern | Example |
|----------|------------|---------|
| **422 validation** on form submit | **Inline field errors** + scroll to first | Red text under `TextFormField`; call `formKey.currentState!.validate()` |
| Single field failed after blur | **Inline** | `autovalidateMode.onUserInteraction` |
| **401** session expired | **Snackbar** + redirect | *"Session expired. Please sign in again."* |
| **419** after retry failed | **Snackbar** | *"Security token expired. Please try again."* |
| **429** rate limit | **Snackbar** | *"Too many requests. Wait a moment."* |
| **Offline** / connection error | **Persistent banner** top of screen | Retry when connectivity restored |
| **500** on form submit | **Dialog** | *"Submission failed. Your data is saved locally."* with Retry / Cancel |
| **500** on content load | **Inline empty state** + Retry button | Blog/causes list |
| **404** submission not found | **Full-screen empty state** | Illustration + Back button |
| **403** forbidden (future) | **Dialog** | Non-dismissible action to go back |
| Login credential failure | **Inline** on email field | Server message on `email` |
| Success after form submit | **Navigate** to `FormSuccessScreen` | Not snackbar (dedicated screen) |
| Admin status updated | **Snackbar** | *"Status updated to Approved"* |
| Admin option saved | **Snackbar** | *"Option added successfully"* |
| File too large / wrong type | **Inline** under file picker | Before upload attempt |

**Rule of thumb:** If the user can fix it → **inline**. If auth/session/network blocks progress → **snackbar or banner**. If action is destructive or blocks the whole screen → **dialog**.

---

### 4.4 Applying server 422 errors to forms

```dart
void applyServerValidation(
  GlobalKey<FormState> formKey,
  Map<String, String> serverErrors,
  void Function(String key, String msg) setFieldError,
) {
  for (final entry in serverErrors.entries) {
    setFieldError(entry.key, entry.value);
  }
  formKey.currentState?.validate();
  // Scroll to first error field via Scrollable.ensureVisible
}
```

---

### 4.5 Retry mechanism

```dart
// lib/core/network/retry_policy.dart
class RetryPolicy {
  static bool isIdempotent(RequestOptions options) =>
      options.method.toUpperCase() == 'GET' ||
      options.method.toUpperCase() == 'HEAD';

  static bool isRetryable(DioException e) {
    final code = e.response?.statusCode;
    if (code == 429 || code == 502 || code == 503 || code == 504) return true;
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.receiveTimeout) return true;
    return false;
  }

  static Future<Response<T>> withRetry<T>(
    Future<Response<T>> Function() call, {
    int maxAttempts = 3,
    bool idempotent = true,
  }) async {
    var attempt = 0;
    while (true) {
      try {
        return await call();
      } on DioException catch (e) {
        attempt++;
        if (!idempotent || !isRetryable(e) || attempt >= maxAttempts) rethrow;
        await Future.delayed(Duration(seconds: attempt * 2));
      }
    }
  }
}
```

| Operation | Retry? | Max attempts | Notes |
|-----------|--------|--------------|-------|
| GET blog/causes/options | Yes | 3 | Exponential backoff 2s, 4s |
| GET admin submission | Yes | 2 | |
| POST form submit | **No** | 1 | Except 419 CSRF refresh once |
| POST admin status | Yes | 1 | Idempotent status set |
| POST login | **No** | 1 | |
| File upload | **No** | 1 | User must re-select files |
| CSV export | Yes | 2 | GET stream |

---

### 4.6 Offline mode handling

```dart
// lib/core/network/connectivity_service.dart
class ConnectivityService {
  Stream<bool> get onConnectivityChanged;

  Future<bool> get isOnline async {
    final result = await Connectivity().checkConnectivity();
    return result != ConnectivityResult.none;
  }
}
```

| Feature | Offline behavior |
|---------|------------------|
| **Content screens** (blog, causes, about) | Serve last cached JSON from Hive; show *"Showing cached content"* banner |
| **Form screens** | Allow filling; disable Submit; show *"You are offline"* banner |
| **Form draft auto-save** | Persist partial form to Hive every 30s keyed by form type |
| **Submit while offline** | Queue in local `PendingSubmission` table; sync when online |
| **Admin dashboard** | Block access; show offline screen (admin requires live data) |
| **Dropdown options** | Use cached options from last successful fetch (TTL 24h) |

```dart
// lib/core/models/pending_submission.dart
class PendingSubmission {
  final String formType;       // e.g. 'contact', 'volunteer'
  final Map<String, dynamic> payload;
  final DateTime createdAt;
  final List<String>? localFilePaths;
}
```

---

## 5. Business Logic Validations

Rules enforced in PHP controllers beyond simple field validation. Implement on the **Flutter client** for UX and mirror on **server** for security.

### 5.1 Role-based access

| Rule | PHP implementation | Flutter enforcement |
|------|-------------------|---------------------|
| Admin routes require login | `AdminAuth` middleware → redirect if `!Auth::check()` | Route guard: `/admin/*` requires `AuthState.authenticated` |
| Only admin users exist | No public user model/login | No public auth screens |
| Status update types restricted | Only `ordinary`, `friends`, `partner`, `aid` in `updateStatus` switch | Hide status dropdown for `feedback`, `mentor`, `volunteer`, `contact` |
| Submission detail types | 8 types in `showSubmission` | Pass correct `type` param from dashboard tab |

### 5.2 Conditional form logic

| Rule | Condition | Flutter action |
|------|-----------|----------------|
| Friends form branching | `entity_type === 'Individual'` | Show `ind_*` fields; hide `org_*` |
| Friends form branching | `entity_type !== 'Individual'` | Show `org_*` fields; hide `ind_*` |
| Other specify fields | Category/type contains `"Other"` or `"Others"` | Require corresponding `*_other` text field |
| Served before details | `has_served_before == true` | Require `served_before_details` (client UX; server allows nullable) |
| Collaborated before | `has_collaborated_before == true` | Require `collaborated_before_details` |
| Volunteered before | `has_volunteered_before == true` | Require `volunteered_before_details` |
| Received aid before | `received_aid_before == true` | Require `received_aid_before_details` |
| Contact consent | `contact_consent` required boolean | Must be explicitly set (toggle) |

### 5.3 Date and time constraints

| Rule | PHP rule | Flutter validation |
|------|----------|-------------------|
| Org registration date | `required\|date` | Valid date; warn if future |
| Date of birth | `required\|date` | Valid date; must be in past; optional min age UX |
| Year established | `min:1800`, `max:currentYear` | `1800 ≤ year ≤ DateTime.now().year` |
| Org reg date max | No explicit max in PHP | Client: should not be future date |

### 5.4 Submission lifecycle / status

| Rule | Detail |
|------|--------|
| Default status on create | `ordinary`, `friends`, `partner`, `aid` → `pending` (DB default) |
| Allowed status values | `pending`, `approved`, `rejected`, `under_review` |
| Status not on all types | `feedback`, `mentor`, `volunteer`, `contact` have **no status field** |
| No duplicate check | PHP does **not** prevent duplicate NRIC/email submissions → no 409 today |

### 5.5 Donation / payment

| Rule | Detail |
|------|--------|
| No payment processing | `submitDonate` sends email only — **no DB insert** |
| No payment gateway | Success page says *"Donation Portal - Coming Soon"* |
| Amount optional | Both `amount` and `custom_amount` nullable; at least one recommended client-side |
| Flutter | Do not show payment card fields; enquiry form only |

### 5.6 Email side effects (non-blocking)

| Rule | PHP behavior | Flutter impact |
|------|--------------|----------------|
| Mail failure does not rollback | `sendFormSubmissionEmails` catches exceptions, logs, continues | Submission still succeeds — show success even if email delayed |
| Applicant + support emails | Sent to applicant email (if any) + `support@mukmin.org` | No client action |

### 5.7 Server-assigned fields (do not send from client)

| Field | Set by PHP | Value |
|-------|-----------|-------|
| `is_registered_ros` | `submitOrdinary` | Always `false` |
| `registration_certificate` | `submitOrdinary` | Always `null` |
| `committee_members` | `submitOrdinary` | Always `null` |
| `supporting_documents` | Partner/CommunityAid | Built from uploaded file paths |
| `status` | DB default | `pending` on applicable types |

### 5.8 Dropdown option integrity

| Rule | Detail |
|------|--------|
| Options loaded from DB | `FormDropdownOption` table — admin can add/edit/delete |
| Multi-select values | PHP validates array min:1 but **not** that values exist in DB | Client: restrict to fetched options + "Other" text |
| Cache invalidation | After admin edits options | Refresh options provider on admin save; TTL cache for public |

### 5.9 Export rules

| Rule | Detail |
|------|--------|
| Full export | `exportCsv` exports **all** records — no date filter |
| Admin only | Protected by `admin.auth` |
| Empty type | Invalid `{type}` produces CSV with headers only |

### 5.10 Not applicable (e-commerce rules absent)

The PHP codebase has **no** stock, orders, products, cart, inventory, or payment status logic. Do not implement order/stock validations in Flutter.

---

## 6. Security Validations

### 6.1 Client-side input sanitization

| Input type | Sanitization rule | Flutter implementation |
|------------|-------------------|------------------------|
| All text fields | Trim leading/trailing whitespace before validate/submit | `value.trim()` |
| NRIC | Strip spaces/hyphens before regex check | `value.replaceAll(RegExp(r'[\s\-]'), '')` |
| Phone | Allow digits, `+`, spaces, `()`, `-` only | `FilteringTextInputFormatter.allow(RegExp(r'[0-9+\s\-()]'))` |
| Email | Lowercase optional (server is case-sensitive for auth) | Trim only; do not force lower for submissions |
| HTML in text areas | Strip/nullify `<>` to reduce XSS if displayed in WebView | `value.replaceAll('<', '').replaceAll('>', '')` or use plain Text widgets only |
| JSON payload | Never interpolate user input into URLs | Use Dio `queryParameters` / encoded body |
| Path params (`type`, `id`) | Whitelist `type` strings | Enum check before API call |

**Important:** Client sanitization is for UX only — **never trust it for security**. Server validation is authoritative.

---

### 6.2 Sensitive data handling

| Data | Classification | Client rules |
|------|----------------|--------------|
| **Admin password** | Highly sensitive | `obscureText: true`; never log; never cache in SharedPreferences; clear from memory on dispose |
| **NRIC / passport** | PII | Mask in admin list views: show last 4 chars (`****1234`); full value only on detail screen |
| **Email, phone, address** | PII | No logging in production; exclude from analytics events |
| **Emergency contact** | PII | Same as above |
| **Sanctum token** | Credential | Store in `flutter_secure_storage` only; never in logs |
| **CSRF token** | Session security | Secure storage or cookie jar; rotate on 419 |
| **Card numbers** | N/A | No payment fields — do not collect |
| **Uploaded documents** | Sensitive | Store temp copies in app sandbox; delete after successful upload |

**Password field example**

```dart
TextFormField(
  obscureText: true,
  enableSuggestions: false,
  autocorrect: false,
  autofillHints: const [AutofillHints.password],
  validator: (v) => Validators.required(v, field: 'Password'),
)
```

**NRIC masking for admin list**

```dart
String maskNric(String nric) {
  if (nric.length <= 4) return '****';
  return '${'*' * (nric.length - 4)}${nric.substring(nric.length - 4)}';
}
```

---

### 6.3 Secure storage requirements

| Item | Storage | Clear on logout |
|------|---------|-----------------|
| Sanctum access token | `flutter_secure_storage` | Yes |
| Remember-me flag | `shared_preferences` | Yes |
| CSRF / session cookies | `cookie_jar` persisted encrypted | Yes |
| Form drafts | Hive (encrypted box for PII forms) | Optional user clear |
| Admin submission cache | Hive | Yes on logout |

---

### 6.4 File upload validation

PHP rule: `'supporting_files.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,zip,ppt,pptx|max:20480'`

| Check | Limit | Flutter pre-upload validation |
|-------|-------|--------------------------------|
| **Allowed MIME/types** | pdf, jpg, jpeg, png, doc, docx, zip, ppt, pptx | Check extension **and** MIME via `file_picker` / `mime` package |
| **Max size per file** | 20480 KB (20 MB) | `file.lengthSync() <= 20 * 1024 * 1024` |
| **Max files** | No PHP limit | Recommend client cap: **5 files** per submission |
| **Applies to** | Partner, Community Aid forms | `PartnerFormScreen`, `CommunityAidFormScreen` |
| **Storage path** | Server: `storage/app/public/documents/` | Client: temp dir only until upload succeeds |

```dart
// lib/core/utils/file_validators.dart
class FileValidators {
  static const allowedExtensions = {
    'pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'zip', 'ppt', 'pptx',
  };
  static const maxBytes = 20 * 1024 * 1024; // 20 MB

  static String? validatePlatformFile(PlatformFile file) {
    final ext = file.extension?.toLowerCase();
    if (ext == null || !allowedExtensions.contains(ext)) {
      return 'File type not allowed. Use PDF, images, DOC, ZIP, or PPT.';
    }
    if (file.size > maxBytes) {
      return 'File must not exceed 20 MB.';
    }
    return null;
  }
}
```

**422 from server on bad file:** Map `supporting_files.0` key to file picker error display.

---

### 6.5 Token / session expiry handling

| Mechanism | PHP config | Flutter behavior |
|-----------|-----------|------------------|
| Sanctum token expiration | `null` (no expiry) | Token valid until revoked; still handle 401 from manual revoke |
| Session cookie | Laravel session lifetime (default 120 min) | If using legacy session auth: treat 401/redirect as expiry |
| CSRF token | Rotates on login/logout | Refresh via `/sanctum/csrf-cookie` on 419 |
| Remember me | Extends session via cookie | Persist preference; longer admin session |
| Password reset throttle | 60s in `config/auth.php` | N/A — no reset route in app |

**401 handling flow**

```
API returns 401
  → AuthInterceptor catches
  → SecureTokenStorage.clearToken()
  → AuthNotifier → Unauthenticated
  → Router → /admin/login
  → Snackbar: "Session expired. Please sign in again."
  → Clear any in-memory PII caches
```

**Proactive check (optional):** On app resume, call `GET /api/user` — if 401, force re-login before showing admin UI.

---

### 6.6 Transport security

| Rule | Implementation |
|------|----------------|
| HTTPS only in production | `EnvConfig.production` must use `https://` |
| Certificate pinning | Optional for production hardening |
| No secrets in source | Base URL via `--dart-define`; tokens never in git |
| Disable debug logging in release | Strip `LogInterceptor` body logs — they contain PII |

---

### 6.7 Admin auth security

| Rule | Detail |
|------|--------|
| No rate limit on login (PHP) | Flutter: implement client-side lockout after 5 failed attempts (60s cooldown) |
| Generic error message | *"The provided credentials do not match our records."* — do not reveal if email exists |
| Logout | PHP invalidates session + regenerates CSRF | Flutter clears all secure storage |

---

## Appendix — Quick reference: screens × validation count

| Screen | Required fields | Optional fields | File upload | Conditional logic |
|--------|----------------|-----------------|-------------|-------------------|
| AdminLoginScreen | 2 | 1 | No | — |
| ContactScreen | 4 | 0 | No | — |
| DonateScreen | 2 | 2 | No | Amount UX |
| FeedbackFormScreen | 11 | 4 | No | Other category |
| OrdinaryMemberFormScreen | 18+ | 8 | No | Others text |
| FriendsMemberFormScreen | 4–8 | 1–2 | No | Individual vs Org |
| MentorFormScreen | 16 | 3 | No | Served before |
| PartnerFormScreen | 14 | 5 | Yes | Collaborated before |
| VolunteerFormScreen | 16 | 3 | No | Volunteered before |
| CommunityAidFormScreen | 17 | 4 | Yes | Aid before |
| AdminStatusUpdate | 1 | 0 | No | Type whitelist |
| AdminOptionsScreen | 2–3 | 0–1 | No | — |

---

*Derived from PHP validation in `FormSubmissionController.php`, `AdminAuthController.php`, `AdminDashboardController.php`, migrations, and feature tests.*
