# MUKMIN Welfare Application — API Documentation

> **Version:** 1.0  
> **Framework:** Laravel 8.x  
> **Generated from codebase analysis:** June 2026  
> **Base URL:** `{APP_URL}` (e.g. `https://mukmin.org` or `http://localhost:8000`)

---

## Table of Contents

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Common Headers & Conventions](#common-headers--conventions)
4. [Rate Limiting](#rate-limiting)
5. [Global Error Handling](#global-error-handling)
6. [Sanctum / API Module](#sanctum--api-module)
7. [Admin Authentication](#admin-authentication)
8. [Admin Dashboard & Management](#admin-dashboard--management)
9. [Contact & Donation](#contact--donation)
10. [Feedback & Suggestions](#feedback--suggestions)
11. [Membership — Ordinary Member](#membership--ordinary-member)
12. [Membership — Friends of MUKMIN](#membership--friends-of-mukmin)
13. [Mentor Registration](#mentor-registration)
14. [Partnership & Collaboration](#partnership--collaboration)
15. [Volunteer Registration](#volunteer-registration)
16. [Community Aid & Assistance](#community-aid--assistance)
17. [Public Content Pages](#public-content-pages)
18. [Blog](#blog)
19. [Causes / Campaigns](#causes--campaigns)
20. [Flutter Integration Notes](#flutter-integration-notes)

---

## Overview

This application is a **Laravel 8 monolithic welfare NGO website**. It was built primarily as a **server-rendered web app** (Blade HTML views), not as a dedicated REST API.

| Route group | Prefix | Middleware | Response format |
|-------------|--------|------------|-----------------|
| Web routes | `/` (no prefix) | `web` (session, CSRF) | Mostly **HTML** |
| API routes | `/api` | `api` (throttle) | **JSON** |

**Endpoint summary**

| Category | Count | JSON-capable |
|----------|-------|--------------|
| Formal `/api/*` routes | 1 | Yes |
| Sanctum CSRF cookie (package) | 1 | Cookie only |
| Public form POST (write) | 9 | Success returns HTML; errors can be JSON |
| Admin JSON endpoints | 2 | Yes |
| Admin CSV export | 1 (8 type variants) | CSV stream |
| Public GET pages | ~27 | HTML only |
| Admin auth & dashboard | 7 | Mixed |

**Important for Flutter:** Most write endpoints persist data correctly but return an HTML success page instead of JSON. To build a native mobile app, you will likely need **new JSON API routes** or middleware changes. This document describes the **current backend behavior** as implemented.

---

## Authentication

### Mechanisms in use

| Mechanism | Used by | How it works |
|-----------|---------|--------------|
| **Session cookie** (`laravel_session`) | All `/admin/*` protected routes, Sanctum stateful domains | Browser/app must persist session cookie after login |
| **CSRF token** (`X-CSRF-TOKEN` header or `_token` field) | All `web` middleware POST/PUT/PATCH/DELETE | Required on every mutating web request |
| **Laravel Sanctum Bearer token** | `GET /api/user` only | `Authorization: Bearer {token}` — **no token issuance route exists in this app** |
| **Remember me cookie** | Admin login (optional) | Set when `remember=true` on login |

### Not implemented

- JWT
- API keys
- OAuth / social login
- Mobile token login/register endpoints
- Public user registration

### Obtaining a Sanctum token (manual / future)

Sanctum is installed (`User` model uses `HasApiTokens`) but there is **no HTTP endpoint** to create tokens. Tokens must currently be created via Artisan/Tinker:

```php
$user = User::find(1);
$token = $user->createToken('flutter-app')->plainTextToken;
```

---

## Common Headers & Conventions

### Recommended headers for Flutter (web routes)

| Header | Value | Required | Notes |
|--------|-------|----------|-------|
| `Accept` | `application/json` | Recommended | Forces Laravel to return **422 JSON** on validation failure instead of 302 redirect |
| `Content-Type` | `application/json` or `multipart/form-data` | On POST | JSON works for non-file forms; use `multipart/form-data` for file uploads |
| `X-CSRF-TOKEN` | Session CSRF token | Required on POST | Obtain from `GET /sanctum/csrf-cookie` + session, or parse from HTML |
| `X-Requested-With` | `XMLHttpRequest` | Optional | Also triggers JSON error responses |
| `Cookie` | `laravel_session=...` | Admin routes | Session persistence |

### Recommended headers for `/api/*` routes

| Header | Value | Required |
|--------|-------|----------|
| `Accept` | `application/json` | Yes |
| `Authorization` | `Bearer {sanctum_token}` | Yes (for `/api/user`) |

### Data type conventions

| Laravel rule | Meaning |
|--------------|---------|
| `accepted` | Checkbox must be `"1"`, `true`, `"true"`, `"on"`, or `"yes"` |
| `boolean` | `true`, `false`, `1`, `0`, `"1"`, `"0"` |
| `array` | JSON array or form field `key[]` repeated values |
| `date` | `YYYY-MM-DD` |
| Phone regex | `^\+?[0-9][0-9\s\-()]{7,19}$` e.g. `+60123456789` |
| NRIC regex | `^\d{1,12}$` e.g. `900101011234` |
| NRIC/Passport regex | Digits 1–12 **or** alphanumeric 6–20 with at least one letter |

### Email validation

- **Production:** `email:rfc,dns` (DNS lookup required)
- **Testing environment:** Relaxed to `email` only

---

## Rate Limiting

| Scope | Limit | Key | HTTP status when exceeded |
|-------|-------|-----|---------------------------|
| `/api/*` routes | **60 requests/minute** | Authenticated user ID, else client IP | **429 Too Many Requests** |
| All web routes (`/`, `/admin/*`, form submits) | **None** | — | — |
| Admin login | **None** | — | — |

**429 response example (`/api/*` only):**

```json
{
  "message": "Too Many Attempts."
}
```

---

## Global Error Handling

### Validation error — `422 Unprocessable Entity`

Returned when `Accept: application/json` or `X-Requested-With: XMLHttpRequest` is sent.

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "contact_number": [
      "The contact number format is invalid."
    ]
  }
}
```

### CSRF mismatch — `419 Page Expired`

```json
{
  "message": "CSRF token mismatch."
}
```

### Unauthenticated Sanctum — `401 Unauthorized`

```json
{
  "message": "Unauthenticated."
}
```

### Not found (Eloquent `findOrFail`) — `404 Not Found`

HTML page or JSON (if `Accept: application/json`):

```json
{
  "message": "No query results for model [App\\Models\\OrdinaryMemberSubmission] {id}"
}
```

### Server error — `500 Internal Server Error`

```json
{
  "message": "Server Error"
}
```

---

## Sanctum / API Module

### GET `/sanctum/csrf-cookie`

**Purpose:** Prime CSRF cookie for SPA/mobile clients using session-based Sanctum auth.

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/sanctum/csrf-cookie` |
| **Auth** | None |
| **Rate limit** | None (not under `/api` prefix) |

**Request headers**

| Header | Required | Value |
|--------|----------|-------|
| `Accept` | Optional | `application/json` |

**Response:** `204 No Content` with `Set-Cookie` headers for CSRF/session.

---

### GET `/api/user`

**Purpose:** Return the currently authenticated user (Sanctum scaffold).

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/api/user` |
| **Auth** | **Required** — `auth:sanctum` (Bearer token or session) |
| **Rate limit** | 60/min |

**Request headers**

| Header | Required | Value |
|--------|----------|-------|
| `Accept` | Yes | `application/json` |
| `Authorization` | Yes* | `Bearer {personal_access_token}` |

\* Or valid session cookie from stateful domain.

**Request parameters:** None

**Success response — `200 OK`**

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | User primary key |
| `name` | string | Display name |
| `email` | string | Email address |
| `email_verified_at` | string\|null | ISO 8601 datetime or null |
| `created_at` | string | ISO 8601 datetime |
| `updated_at` | string | ISO 8601 datetime |

```json
{
  "id": 1,
  "name": "Admin User",
  "email": "admin@mukmin.org",
  "email_verified_at": null,
  "created_at": "2026-01-15T08:00:00.000000Z",
  "updated_at": "2026-01-15T08:00:00.000000Z"
}
```

**Note:** `password` and `remember_token` are hidden from serialization.

| Status | Condition | Response |
|--------|-----------|----------|
| `200` | Valid token/session | User object |
| `401` | Missing/invalid auth | `{"message":"Unauthenticated."}` |
| `429` | Rate limit exceeded | `{"message":"Too Many Attempts."}` |

---

## Admin Authentication

All admin auth routes use `web` middleware (session + CSRF).

---

### GET `/admin`

**Purpose:** Redirect to admin login page.

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/admin` |
| **Auth** | None |

**Response:** `302 Found` → `/admin/login`

---

### GET `/admin/login`

**Purpose:** Display admin login form. Redirects to dashboard if already authenticated.

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/admin/login` |
| **Auth** | None |

| Status | Response |
|--------|----------|
| `200` | HTML login page |
| `302` | Redirect to `/admin/dashboard` if session exists |

---

### POST `/admin/login/submit`

**Purpose:** Authenticate admin user and create session.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/admin/login/submit` |
| **Auth** | None |
| **Content-Type** | `application/x-www-form-urlencoded`, `multipart/form-data`, or `application/json` |

**Request headers**

| Header | Required | Value |
|--------|----------|-------|
| `X-CSRF-TOKEN` | Yes | CSRF token |
| `Accept` | Recommended | `application/json` |

**Request parameters**

| Name | Location | Type | Required | Default | Description / Example |
|------|----------|------|----------|---------|----------------------|
| `email` | body | string | Yes | — | Admin email. e.g. `admin@mukmin.org` |
| `password` | body | string | Yes | — | Account password |
| `remember` | body | boolean | No | `false` | Persist login via remember cookie |

**Request body example**

```json
{
  "email": "admin@mukmin.org",
  "password": "secret-password",
  "remember": true
}
```

**Success response**

| Status | Format | Body |
|--------|--------|------|
| `302` | Redirect | → `/admin/dashboard` with flash `success` |
| `200`* | HTML | Dashboard page |

\* Default browser behavior after redirect.

**Error responses**

| Status | Condition | Example |
|--------|-----------|---------|
| `302` | Invalid credentials | Redirect back with `errors.email` |
| `422` | Validation failure (JSON accept) | See global 422 format |
| `419` | CSRF mismatch | See global 419 format |

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The provided credentials do not match our records."]
  }
}
```

---

### GET `/admin/logout`

**Purpose:** Destroy admin session and redirect to login.

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/admin/logout` |
| **Auth** | Session (no middleware, but logout is meaningful only when logged in) |

**Response:** `302 Found` → `/admin/login` with flash `success`

---

## Admin Dashboard & Management

**Middleware:** `web` + `admin.auth`  
**Auth:** Session required. Unauthenticated requests → `302` redirect to `/admin/login`.

---

### GET `/admin/dashboard`

**Purpose:** Admin dashboard with submission counts, full submission lists, and dropdown option management UI.

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/admin/dashboard` |
| **Auth** | Session (admin) |

**Response:** `200 OK` — HTML page containing:

| Data (server-side) | Type | Description |
|--------------------|------|-------------|
| `stats` | object | Counts: `feedback`, `ordinary`, `friends`, `mentor`, `partner`, `volunteer`, `contact`, `aid` |
| Submissions | collections | All records per type, ordered by `created_at` desc |
| `options` | object | `FormDropdownOption` grouped by `form_type` |
| `formTypesMap` | object | Human-readable labels for option types |

| Status | Condition |
|--------|-----------|
| `200` | Authenticated |
| `302` | Not authenticated → login |

---

### GET `/admin/submissions/{type}/{id}`

**Purpose:** Fetch a single submission record as JSON (used by admin dashboard AJAX).

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/admin/submissions/{type}/{id}` |
| **Auth** | Session (admin) |

**Request headers**

| Header | Required | Value |
|--------|----------|-------|
| `Accept` | Yes | `application/json` |
| `Cookie` | Yes | `laravel_session=...` |
| `X-CSRF-TOKEN` | Recommended | For subsequent POST from same session |

**Path parameters**

| Name | Type | Required | Allowed values |
|------|------|----------|----------------|
| `type` | string | Yes | `feedback`, `ordinary`, `friends`, `mentor`, `partner`, `volunteer`, `contact`, `aid` |
| `id` | integer | Yes | Submission primary key |

**Success — `200 OK`**

Response is the full Eloquent model serialized to JSON. Fields vary by `type` — see [Submission response schemas](#submission-response-schemas).

**Example — `GET /admin/submissions/feedback/1`**

```json
{
  "id": 1,
  "full_name": "Ahmad bin Ali",
  "nric_number": "900101011234",
  "organisation": "MASJID AL-IKHLAS",
  "position": "Committee Member",
  "state_residency": "Selangor",
  "full_address": "No 12, Jalan Merdeka, 43000 Kajang",
  "email": "ahmad@example.com",
  "contact_number": "+60123456789",
  "categories": ["Community Development", "Education"],
  "other_category": null,
  "suggestion_description": "Proposed youth mentorship programme.",
  "benefits_description": "Will benefit 200+ students annually.",
  "contact_consent": true,
  "preferred_contact_methods": ["Email", "Phone"],
  "declaration_confirmed": true,
  "created_at": "2026-05-20T10:30:00.000000Z",
  "updated_at": "2026-05-20T10:30:00.000000Z"
}
```

**Error responses**

| Status | Body |
|--------|------|
| `404` | `{"error":"Submission not found."}` |
| `302` | Redirect to login (unauthenticated) |

---

### POST `/admin/submissions/{type}/{id}/status`

**Purpose:** Update workflow status on eligible submission types.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/admin/submissions/{type}/{id}/status` |
| **Auth** | Session (admin) |
| **Content-Type** | `application/json` or form-encoded |

**Path parameters**

| Name | Type | Required | Allowed values |
|------|------|----------|----------------|
| `type` | string | Yes | `ordinary`, `friends`, `partner`, `aid` only |
| `id` | integer | Yes | Submission ID |

**Body parameters**

| Name | Location | Type | Required | Allowed values | Example |
|------|----------|------|----------|----------------|---------|
| `status` | body | string | Yes | `pending`, `approved`, `rejected`, `under_review` | `"approved"` |

**Request body example**

```json
{
  "status": "approved"
}
```

**Success — `200 OK`**

```json
{
  "success": true,
  "status": "approved"
}
```

**Error responses**

| Status | Condition | Body |
|--------|-----------|------|
| `400` | Invalid `type` for status update | `{"error":"Invalid submission type for status update."}` |
| `404` | ID not found (`findOrFail`) | Laravel 404 message |
| `422` | Invalid `status` value | Validation errors |
| `302` | Unauthenticated | Redirect to login |
| `419` | CSRF mismatch | CSRF error |

---

### GET `/admin/export/{type}`

**Purpose:** Download all submissions of a given type as a UTF-8 CSV file (Excel-compatible BOM).

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/admin/export/{type}` |
| **Auth** | Session (admin) |

**Path parameters**

| Name | Type | Required | Allowed values |
|------|------|----------|----------------|
| `type` | string | Yes | `feedback`, `ordinary`, `friends`, `mentor`, `partner`, `volunteer`, `contact`, `aid` |

**Response headers (success)**

```
Content-Type: text/csv; charset=UTF-8
Content-Disposition: attachment; filename=submissions_{type}_{Ymd_His}.csv
```

**Success — `200 OK`:** CSV stream (not JSON).

**CSV columns by type**

| `type` | Columns |
|--------|---------|
| `feedback` | ID, Date, Full Name, NRIC, Organisation, Position, State, Address, Email, Phone, Categories, Other Category, Suggestion, Benefits, Contact Consent, Preferred Methods |
| `ordinary` | ID, Date, Organisation Name, Reg Number, Reg Date, State, Address, Postcode, City, Established, Congregation Size, Email, Phone, Website, Org Types, Org Types Other, Activities, Activities Other, Registered ROS, President/Secretary names & contacts, Status |
| `friends` | ID, Date, Type, Others Specify, Org fields, Individual fields, Status |
| `mentor` | ID, Date, Full Name, NRIC/Passport, Gender, Occupation, Organisation, Position, Years Experience, State, Address, Email, Phone, LinkedIn, Expertise, Formats, Commitments, Experience, Has Served Before, Details |
| `partner` | ID, Date, Company, Contact, Position, Reg Number, Email, Phone, Address, State/Country, Org Types, Collaboration, Partnership, Proposal, Outcomes, Has Collaborated, Documents, Status |
| `volunteer` | ID, Date, Full Name, NRIC/Passport, Gender, Occupation/Study, Organisation, State, Address, Email, Phone, Interests, Skills, Mode, Availability, Prior volunteering, Emergency contact |
| `contact` | ID, Date, Name, Email, Phone, Message |
| `aid` | ID, Date, Full Name, NRIC/Passport, Gender, DOB, Nationality, Occupation, Income, Phone, Email, Address, State, Aid types, Situation, Beneficiaries, Prior aid, Status |

| Status | Condition |
|--------|-----------|
| `200` | CSV stream |
| `302` | Unauthenticated |

**Notes:** No row limit or pagination — exports **all** records.

---

### POST `/admin/options/add`

**Purpose:** Add a dropdown option for dynamic form fields.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/admin/options/add` |
| **Auth** | Session (admin) |

**Body parameters**

| Name | Location | Type | Required | Default | Description / Example |
|------|----------|------|----------|---------|----------------------|
| `form_type` | body | string | Yes | — | e.g. `feedback_category`, `volunteer_interest` |
| `option_value` | body | string | Yes | — | Display value. e.g. `"Education"` |
| `sort_order` | body | integer | No | `0` | Display order |

**Known `form_type` values**

| `form_type` | Used on form |
|-------------|--------------|
| `feedback_category` | Feedback & Suggestion |
| `ordinary_org_type` | Ordinary Member |
| `ordinary_activity` | Ordinary Member |
| `friends_category` | Friends of MUKMIN |
| `mentor_expertise` | Mentor Registration |
| `mentor_format` | Mentor Registration |
| `mentor_commitment` | Mentor Registration |
| `partner_org_type` | Partnership |
| `partner_collaboration` | Partnership |
| `partner_type` | Partnership |
| `volunteer_interest` | Volunteer |
| `volunteer_mode` | Volunteer |
| `volunteer_availability` | Volunteer |

**Request body example**

```json
{
  "form_type": "volunteer_interest",
  "option_value": "Community Outreach",
  "sort_order": 10
}
```

**Success:** `302` redirect back with flash `success: "Dropdown option added successfully!"`

**Errors:** `422` validation; `302` unauthenticated; `419` CSRF

---

### POST `/admin/options/edit/{id}`

**Purpose:** Update an existing dropdown option.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/admin/options/edit/{id}` |
| **Auth** | Session (admin) |

**Path parameters**

| Name | Type | Required |
|------|------|----------|
| `id` | integer | Yes — `form_dropdown_options.id` |

**Body parameters**

| Name | Location | Type | Required | Example |
|------|----------|------|----------|---------|
| `option_value` | body | string | Yes | `"Youth Development"` |
| `sort_order` | body | integer | Yes | `5` |

**Success:** `302` redirect with flash success  
**Errors:** `404` (invalid id), `422`, `419`

---

### POST `/admin/options/delete/{id}`

**Purpose:** Delete a dropdown option.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/admin/options/delete/{id}` |
| **Auth** | Session (admin) |

**Path parameters**

| Name | Type | Required |
|------|------|----------|
| `id` | integer | Yes |

**Body:** None required (CSRF token only).

**Success:** `302` redirect with flash success  
**Errors:** `404`, `419`

---

## Contact & Donation

---

### POST `/contact/submit`

**Purpose:** Submit a general contact/enquiry message. Persists to `contact_submissions` and sends acknowledgement emails.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/contact/submit` |
| **Auth** | None |
| **CSRF** | Required |

**Request parameters**

| Name | Location | Type | Required | Description / Example |
|------|----------|------|----------|---------------------|
| `name` | body | string | Yes | Full name. `"Siti Aminah"` |
| `email` | body | string | Yes | Valid email (RFC+DNS in prod). `"siti@example.com"` |
| `phone` | body | string | Yes | Phone matching regex. `"+60198765432"` |
| `message` | body | string | Yes | Enquiry text |

**Request body example**

```json
{
  "name": "Siti Aminah",
  "email": "siti@example.com",
  "phone": "+60198765432",
  "message": "I would like to know more about volunteering opportunities."
}
```

**Success — `200 OK`:** HTML success page (not JSON).

**Side effects:** DB insert; email to applicant + `support@mukmin.org`.

**Stored record schema (`contact_submissions`)**

| Field | Type |
|-------|------|
| `id` | integer |
| `name` | string |
| `email` | string |
| `phone` | string |
| `message` | string |
| `created_at` | datetime |
| `updated_at` | datetime |

| Status | Condition |
|--------|-----------|
| `200` | Success (HTML) |
| `302` | Validation fail without JSON accept |
| `422` | Validation fail with JSON accept |
| `419` | CSRF mismatch |
| `500` | Mail/DB failure |

---

### POST `/donate/submit`

**Purpose:** Capture donation interest/enquiry. **Does not process payment** — sends email only.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/donate/submit` |
| **Auth** | None |

**Request parameters**

| Name | Location | Type | Required | Description / Example |
|------|----------|------|----------|---------------------|
| `name` | body | string | Yes | `"John Doe"` |
| `email` | body | string | Yes | `"john@example.com"` |
| `amount` | body | number | No | Preset amount ≥ 1. `100` |
| `custom_amount` | body | number | No | Custom amount ≥ 1. `250` |

**Request body example**

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "amount": 100,
  "custom_amount": null
}
```

**Success — `200 OK`:** HTML page titled *"Donation Portal - Coming Soon"*.

**Note:** No database persistence — email notification only.

---

## Feedback & Suggestions

---

### GET `/feedback-suggestion`

**Purpose:** Render feedback form page with category dropdown options.

| Property | Value |
|----------|-------|
| **Method** | `GET` |
| **URL** | `/feedback-suggestion` |
| **Auth** | None |

**Response:** `200 OK` HTML. Server loads `categories` from `form_dropdown_options` where `form_type = feedback_category`.

---

### POST `/feedback-suggestion/submit`

**Purpose:** Submit feedback or improvement suggestion.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/feedback-suggestion/submit` |
| **Auth** | None |

**Request parameters**

| Name | Location | Type | Required | Description / Example |
|------|----------|------|----------|---------------------|
| `full_name` | body | string | Yes | `"Ahmad bin Ali"` |
| `nric_number` | body | string | Yes | 1–12 digits. `"900101011234"` |
| `organisation` | body | string | No | `"MASJID AL-IKHLAS"` |
| `position` | body | string | No | `"Committee Member"` |
| `state_residency` | body | string | Yes | `"Selangor"` |
| `full_address` | body | string | Yes | Full postal address |
| `email` | body | string | Yes | Valid email |
| `contact_number` | body | string | Yes | Phone regex |
| `categories` | body | array | Yes | Min 1 item. `["Education","Community Development"]` |
| `other_category` | body | string | No | Free text if "Other" selected |
| `suggestion_description` | body | string | Yes | Detailed suggestion |
| `benefits_description` | body | string | Yes | Expected benefits |
| `contact_consent` | body | boolean | Yes | `true` |
| `preferred_contact_methods` | body | array | No | e.g. `["Email","Phone"]` |
| `declaration_confirmed` | body | boolean | Yes | Must be accepted (`true`/`1`/`on`) |

**Request body example**

```json
{
  "full_name": "Ahmad bin Ali",
  "nric_number": "900101011234",
  "organisation": "MASJID AL-IKHLAS",
  "position": "Committee Member",
  "state_residency": "Selangor",
  "full_address": "No 12, Jalan Merdeka, 43000 Kajang",
  "email": "ahmad@example.com",
  "contact_number": "+60123456789",
  "categories": ["Community Development", "Education"],
  "other_category": null,
  "suggestion_description": "Proposed youth mentorship programme.",
  "benefits_description": "Will benefit 200+ students annually.",
  "contact_consent": true,
  "preferred_contact_methods": ["Email"],
  "declaration_confirmed": true
}
```

**Success — `200 OK`:** HTML success page.  
**Side effects:** DB insert + emails.

---

## Membership — Ordinary Member

---

### GET `/membership-ordinary`

**Purpose:** Render ordinary member registration form with org type and activity dropdowns.

| Method | `GET` | Auth | None |
| URL | `/membership-ordinary` | Response | HTML |

---

### POST `/membership-ordinary/submit`

**Purpose:** Submit ordinary (organisation) membership application.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/membership-ordinary/submit` |
| **Auth** | None |

**Request parameters**

| Name | Location | Type | Required | Description / Example |
|------|----------|------|----------|---------------------|
| `name_of_organisation` | body | string | Yes | `"PERSATUAN KEBAJIKAN AL-IKHLAS"` |
| `org_reg_number` | body | string | Yes | `"PPM-1234-567890"` |
| `org_reg_date` | body | string (date) | Yes | `"2020-05-15"` |
| `registered_state` | body | string | Yes | `"Selangor"` |
| `full_address` | body | string | Yes | Organisation address |
| `postcode` | body | string | Yes | `"43000"` |
| `district_city` | body | string | Yes | `"Kajang"` |
| `year_established` | body | integer | Yes | 1800–current year. `2010` |
| `total_members_size` | body | integer | Yes | ≥ 0. `150` |
| `email` | body | string | Yes | Org email |
| `contact_number` | body | string | Yes | Phone |
| `website` | body | string | No | `"https://example.org"` |
| `org_type` | body | array | Yes | Min 1. `["NGO","Surau"]` |
| `org_type_other` | body | string | No | If "Other" selected |
| `primary_activities` | body | array | Yes | Min 1 |
| `primary_activities_other` | body | string | No | |
| `key_office_bearers` | body | object | Yes | See nested table |
| `declaration_confirmed` | body | boolean | Yes | Accepted |

**Nested: `key_office_bearers`**

| Name | Type | Required |
|------|------|----------|
| `president.name` | string | Yes |
| `president.email` | string | Yes |
| `president.phone` | string | Yes |
| `secretary.name` | string | No |
| `secretary.email` | string | No |
| `secretary.phone` | string | No |
| `treasurer.name` | string | No |
| `treasurer.email` | string | No |
| `treasurer.phone` | string | No |

**Request body example**

```json
{
  "name_of_organisation": "PERSATUAN KEBAJIKAN AL-IKHLAS",
  "org_reg_number": "PPM-1234-567890",
  "org_reg_date": "2020-05-15",
  "registered_state": "Selangor",
  "full_address": "No 1, Jalan Harmoni, 43000 Kajang",
  "postcode": "43000",
  "district_city": "Kajang",
  "year_established": 2010,
  "total_members_size": 150,
  "email": "info@alikhlas.org",
  "contact_number": "+60387654321",
  "website": "https://alikhlas.org",
  "org_type": ["NGO"],
  "primary_activities": ["Community Aid", "Education"],
  "key_office_bearers": {
    "president": {
      "name": "Encik Rahman",
      "email": "rahman@alikhlas.org",
      "phone": "+60123456789"
    },
    "secretary": {
      "name": "Puan Aisyah",
      "email": "aisyah@alikhlas.org",
      "phone": "+60198765432"
    }
  },
  "declaration_confirmed": true
}
```

**Server-set fields (not in request):** `is_registered_ros=false`, `registration_certificate=null`, `committee_members=null`, `status="pending"`.

**Success — `200 OK`:** HTML success page.

---

## Membership — Friends of MUKMIN

---

### GET `/membership-friends`

**Purpose:** Render Friends of MUKMIN registration form.

| Method | `GET` | URL | `/membership-friends` | Response | HTML |

---

### POST `/membership-friends/submit`

**Purpose:** Register as Friend of MUKMIN (Individual or Organisation).

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/membership-friends/submit` |
| **Auth** | None |

**Request parameters (common)**

| Name | Location | Type | Required | Description |
|------|----------|------|----------|-------------|
| `entity_type` | body | string | Yes | `"Individual"` triggers individual rules; any other value triggers organisation rules |
| `others_specify` | body | string | No | Specify if type is "Others" |
| `declaration_confirmed` | body | boolean | Yes | Accepted |

**When `entity_type` = `"Individual"`**

| Name | Type | Required |
|------|------|----------|
| `ind_name` | string | Yes |
| `ind_nric` | string | Yes (1–12 digits) |
| `ind_state` | string | Yes |
| `ind_address` | string | Yes |
| `ind_email` | string | Yes |
| `ind_phone` | string | Yes |

**When `entity_type` ≠ `"Individual"` (Organisation)**

| Name | Type | Required |
|------|------|----------|
| `org_name` | string | Yes |
| `org_state` | string | Yes |
| `org_address` | string | Yes |
| `org_email` | string | Yes |
| `org_phone` | string | Yes |
| `org_website` | string | No |

**Request body example (Individual)**

```json
{
  "entity_type": "Individual",
  "ind_name": "Fatimah Zahra",
  "ind_nric": "880505055566",
  "ind_state": "Kuala Lumpur",
  "ind_address": "No 5, Jalan Ampang",
  "ind_email": "fatimah@example.com",
  "ind_phone": "+60111222333",
  "declaration_confirmed": true
}
```

**Default status:** `pending`

---

## Mentor Registration

---

### GET `/mentor-registration`

**Purpose:** Render mentor registration form with expertise/format/commitment options.

| Method | `GET` | URL | `/mentor-registration` |

---

### POST `/mentor-registration/submit`

**Purpose:** Register as a MUKMIN mentor.

**Request parameters**

| Name | Location | Type | Required | Constraints / Example |
|------|----------|------|----------|----------------------|
| `full_name` | body | string | Yes | |
| `nric_passport` | body | string | Yes | NRIC or passport format |
| `gender` | body | string | Yes | `Male` or `Female` |
| `occupation` | body | string | Yes | |
| `organisation` | body | string | Yes | |
| `position` | body | string | Yes | |
| `experience_years` | body | integer | Yes | ≥ 0 |
| `state_residency` | body | string | Yes | |
| `full_address` | body | string | Yes | |
| `email` | body | string | Yes | |
| `contact_number` | body | string | Yes | |
| `linkedin` | body | string | No | |
| `expertise_areas` | body | array | Yes | Min 1 |
| `expertise_other` | body | string | No | |
| `preferred_format` | body | array | Yes | Min 1 |
| `preferred_commitment` | body | array | Yes | Min 1 |
| `experience_description` | body | string | Yes | |
| `has_served_before` | body | boolean | Yes | |
| `served_before_details` | body | string | No | |
| `declaration_confirmed` | body | boolean | Yes | Accepted |

**Request body example**

```json
{
  "full_name": "Dr. Hassan Ibrahim",
  "nric_passport": "750101015555",
  "gender": "Male",
  "occupation": "University Lecturer",
  "organisation": "Universiti Malaya",
  "position": "Associate Professor",
  "experience_years": 15,
  "state_residency": "Kuala Lumpur",
  "full_address": "Jalan Universiti, 50603 KL",
  "email": "hassan@um.edu.my",
  "contact_number": "+60123456789",
  "linkedin": "https://linkedin.com/in/hassan",
  "expertise_areas": ["Leadership", "Education"],
  "preferred_format": ["One-on-one Mentoring"],
  "preferred_commitment": ["Monthly"],
  "experience_description": "15 years mentoring youth leaders.",
  "has_served_before": true,
  "served_before_details": "Mentored at local NGO 2018-2022",
  "declaration_confirmed": true
}
```

---

## Partnership & Collaboration

---

### GET `/partnership-collaboration`

**Purpose:** Render partnership proposal form.

| Method | `GET` | URL | `/partnership-collaboration` |

---

### POST `/partnership-collaboration/submit`

**Purpose:** Submit partnership/collaboration proposal with optional file attachments.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/partnership-collaboration/submit` |
| **Content-Type** | **`multipart/form-data`** (required if uploading files) |

**Request parameters**

| Name | Location | Type | Required | Description |
|------|----------|------|----------|-------------|
| `company_name` | body | string | Yes | |
| `contact_person` | body | string | Yes | |
| `position` | body | string | Yes | |
| `org_reg_number` | body | string | No | |
| `email` | body | string | Yes | |
| `contact_number` | body | string | Yes | |
| `office_address` | body | string | Yes | |
| `state_country` | body | string | Yes | |
| `org_type` | body | array | Yes | Min 1 |
| `org_type_other` | body | string | No | |
| `collaboration_areas` | body | array | Yes | Min 1 |
| `collaboration_other` | body | string | No | |
| `partnership_type` | body | array | Yes | Min 1 |
| `partnership_other` | body | string | No | |
| `proposal_description` | body | string | Yes | |
| `expected_outcomes` | body | string | Yes | |
| `has_collaborated_before` | body | boolean | Yes | |
| `collaborated_before_details` | body | string | No | |
| `supporting_files` | body | file[] | No | Max 20 MB each |
| `declaration_confirmed` | body | boolean | Yes | Accepted |

**File upload rules (`supporting_files.*`)**

| Rule | Value |
|------|-------|
| MIME types | `pdf`, `jpg`, `jpeg`, `png`, `doc`, `docx`, `zip`, `ppt`, `pptx` |
| Max size | 20480 KB (20 MB) per file |
| Storage path | `storage/app/public/documents/{hash}` |
| DB field | `supporting_documents` (JSON array of paths) |

**Multipart example (conceptual)**

```
POST /partnership-collaboration/submit
Content-Type: multipart/form-data

company_name=ABC Corp
contact_person=Jane Smith
...
supporting_files[]=@proposal.pdf
supporting_files[]=@company_profile.pdf
declaration_confirmed=1
_token={csrf}
```

**Default status:** `pending`

---

## Volunteer Registration

---

### GET `/volunteer-registration`

**Purpose:** Render volunteer registration form.

| Method | `GET` | URL | `/volunteer-registration` |

---

### POST `/volunteer-registration/submit`

**Purpose:** Register as a volunteer.

**Request parameters**

| Name | Location | Type | Required | Constraints |
|------|----------|------|----------|-------------|
| `full_name` | body | string | Yes | |
| `nric_passport` | body | string | Yes | |
| `gender` | body | string | Yes | `Male`, `Female` |
| `occupation_study` | body | string | Yes | |
| `organisation` | body | string | No | |
| `state_residency` | body | string | Yes | |
| `full_address` | body | string | Yes | |
| `email` | body | string | Yes | |
| `contact_number` | body | string | Yes | |
| `interest_areas` | body | array | Yes | Min 1 |
| `interest_other` | body | string | No | |
| `skills_expertise` | body | string | Yes | |
| `preferred_mode` | body | string | Yes | `Physical / On-Ground`, `Virtual / Remote`, or `Both` |
| `availability` | body | array | Yes | Min 1 |
| `has_volunteered_before` | body | boolean | Yes | |
| `volunteered_before_details` | body | string | No | |
| `emergency_contact_name` | body | string | Yes | |
| `emergency_contact_relationship` | body | string | Yes | |
| `emergency_contact_phone` | body | string | Yes | |
| `declaration_confirmed` | body | boolean | Yes | Accepted |

---

## Community Aid & Assistance

---

### GET `/community-aid`

**Purpose:** Render community aid request form.

| Method | `GET` | URL | `/community-aid` |

---

### POST `/community-aid/submit`

**Purpose:** Submit a community aid/assistance request with optional supporting documents.

| Property | Value |
|----------|-------|
| **Method** | `POST` |
| **URL** | `/community-aid/submit` |
| **Content-Type** | `multipart/form-data` if files attached |

**Request parameters**

| Name | Location | Type | Required | Constraints |
|------|----------|------|----------|-------------|
| `full_name` | body | string | Yes | |
| `nric_passport` | body | string | Yes | |
| `gender` | body | string | Yes | `Male`, `Female` |
| `dob` | body | string (date) | Yes | `YYYY-MM-DD` |
| `nationality` | body | string | Yes | |
| `occupation` | body | string | Yes | |
| `monthly_income` | body | string | No | |
| `contact_number` | body | string | Yes | |
| `email` | body | string | Yes | |
| `full_address` | body | string | Yes | |
| `state_residency` | body | string | Yes | |
| `type_of_aid` | body | array | Yes | Min 1 |
| `type_of_aid_other` | body | string | No | |
| `situation_description` | body | string | Yes | |
| `who_benefits` | body | string | Yes | `Individual`, `Family`, `Community / Group`, `Organisation / Institution` |
| `number_of_beneficiaries` | body | integer | No | ≥ 1 |
| `received_aid_before` | body | boolean | Yes | |
| `received_aid_before_details` | body | string | No | |
| `supporting_files` | body | file[] | No | Same rules as partnership |
| `emergency_contact_name` | body | string | Yes | |
| `emergency_contact_relationship` | body | string | Yes | |
| `emergency_contact_phone` | body | string | Yes | |
| `declaration_confirmed` | body | boolean | Yes | Accepted |

**Default status:** `pending`

**Request body example (JSON, no files)**

```json
{
  "full_name": "Razak bin Ismail",
  "nric_passport": "700101014444",
  "gender": "Male",
  "dob": "1970-01-01",
  "nationality": "Malaysian",
  "occupation": "Driver",
  "monthly_income": "RM 2000",
  "contact_number": "+60123456789",
  "email": "razak@example.com",
  "full_address": "No 8, Jalan Sentosa, 41000 Klang",
  "state_residency": "Selangor",
  "type_of_aid": ["Financial Assistance", "Food Aid"],
  "situation_description": "Loss of income due to illness.",
  "who_benefits": "Family",
  "number_of_beneficiaries": 4,
  "received_aid_before": false,
  "emergency_contact_name": "Aminah",
  "emergency_contact_relationship": "Spouse",
  "emergency_contact_phone": "+60198765432",
  "declaration_confirmed": true
}
```

---

## Public Content Pages

All return **`200 OK` HTML**. No JSON API exists for these resources. Data is either static Blade content or PHP config arrays.

| Method | URL | Purpose | Special behavior |
|--------|-----|---------|------------------|
| GET | `/coming-soon` | Coming soon landing page | |
| GET | `/` | Home page | |
| GET | `/about` | About MUKMIN (team from config) | |
| GET | `/about/who-we-are` | Who we are section | **302** redirect → `/about#who-we-are` |
| GET | `/about/president-note` | President's note | **302** redirect → `/about#president-note` |
| GET | `/about/leadership` | Leadership section | **302** redirect → `/about#leadership` |
| GET | `/contact` | Contact form page | |
| GET | `/legal-disclaimer` | Legal disclaimer | |
| GET | `/donate` | Donation page | Payment gateway not integrated |
| GET | `/ecosystem` | Ecosystem overview | |
| GET | `/serve-together` | Serve together page | |
| GET | `/impact-areas` | Impact areas overview | |
| GET | `/impact-areas/mfls` | MFLS programme | |
| GET | `/impact-areas/sirat-series` | Sirat Series programme | |
| GET | `/news` | News listing | |
| GET | `/changing-lives` | Changing lives stories | |

**Auth:** None  
**Rate limit:** None

---

## Blog

Content is **hardcoded** in `BlogController` — not database-driven.

### GET `/blog`

**Purpose:** List blog posts.

**Response:** `200 OK` HTML. Server-side posts array shape:

| Field | Type | Example |
|-------|------|---------|
| `title` | string | `"How Your Donation Changes Lives"` |
| `excerpt` | string | Short summary |
| `date` | string | `"2026-03-15"` |
| `author` | string | `"Sarah Mitchell"` |
| `image` | string | Image URL |
| `slug` | string | `"how-your-donation-changes-lives"` |

### GET `/blog/{slug}`

**Purpose:** Single blog post.

**Path parameters**

| Name | Type | Required | Example |
|------|------|----------|---------|
| `slug` | string | Yes | `how-your-donation-changes-lives` |

**Response:** `200 OK` HTML. Post object includes `title`, `content` (HTML), `date`, `author`, `image`, `slug`.

**Note:** `show()` currently returns the same static post regardless of `slug` value.

---

## Causes / Campaigns

Content is **hardcoded** in `CampaignController`.

### GET `/causes`

**Purpose:** List fundraising causes/campaigns.

**Response:** `200 OK` HTML. Campaign object shape:

| Field | Type | Example |
|-------|------|---------|
| `title` | string | `"Clean Water for Rural Schools"` |
| `excerpt` | string | |
| `raised` | integer | `18400` |
| `goal` | integer | `25000` |
| `image` | string | URL |
| `slug` | string | `"clean-water-schools"` |

### GET `/causes/{slug}`

**Purpose:** Single campaign detail.

**Path parameters**

| Name | Type | Required |
|------|------|----------|
| `slug` | string | Yes |

**Response:** `200 OK` HTML with `title`, `content`, `raised`, `goal`, `image`, `slug`.

**Note:** `show()` returns static campaign data regardless of `slug`.

---

## Submission Response Schemas

Reference for `GET /admin/submissions/{type}/{id}` responses.

### `feedback`

| Field | Type |
|-------|------|
| `id`, `full_name`, `nric_number`, `state_residency`, `full_address`, `email`, `contact_number` | string |
| `organisation`, `position`, `other_category` | string\|null |
| `categories`, `preferred_contact_methods` | array |
| `suggestion_description`, `benefits_description` | string |
| `contact_consent`, `declaration_confirmed` | boolean |
| `created_at`, `updated_at` | datetime |

### `ordinary`

Includes all ordinary member fields plus `status` (string), `key_office_bearers` (object), `org_reg_date` (date string), array fields for `org_type`, `primary_activities`.

### `friends`

Includes `entity_type`, `status`, and conditional `ind_*` or `org_*` fields.

### `mentor`

Array fields: `expertise_areas`, `preferred_format`, `preferred_commitment`. No `status` field.

### `partner`

Array fields: `org_type`, `collaboration_areas`, `partnership_type`, `supporting_documents`. Includes `status`.

### `volunteer`

Array fields: `interest_areas`, `availability`. No `status` field.

### `contact`

`id`, `name`, `email`, `phone`, `message`, timestamps.

### `aid`

Array: `type_of_aid`, `supporting_documents`. Includes `status`, `dob` (date string).

---

## Flutter Integration Notes

### What works today

| Use case | Endpoint | Notes |
|----------|----------|-------|
| Submit forms | `POST /*/submit` | Data is validated and saved; send `Accept: application/json` for 422 errors |
| Admin review (if building admin app) | `/admin/submissions/*` | Requires session cookie auth |
| Token user lookup | `GET /api/user` | Requires manually created Sanctum token |

### Gaps for a production Flutter app

1. **No JSON success responses** on form submissions — all return HTML views.
2. **No public JSON endpoints** for blog, campaigns, pages, or dropdown options.
3. **No mobile auth flow** — no register/login/token endpoints for app users.
4. **CSRF requirement** on all web POST routes complicates stateless mobile clients.
5. **Session-based admin auth** is not ideal for mobile; consider Sanctum tokens for admin API.
6. **File URLs** for uploaded documents are storage paths (`documents/...`) — need `Storage::url()` exposure or download endpoint.
7. **Donation endpoint** does not persist to database or process payments.

### Recommended backend additions for Flutter

```
GET  /api/v1/forms/{formType}/options     — dropdown values
POST /api/v1/forms/{formType}             — JSON submit with JSON response
GET  /api/v1/blog                         — JSON blog list
GET  /api/v1/blog/{slug}                  — JSON blog detail
GET  /api/v1/causes                       — JSON campaigns
POST /api/v1/auth/login                   — Sanctum token issuance
```

### CSRF workflow for current endpoints

```
1. GET /sanctum/csrf-cookie          → sets XSRF-TOKEN cookie
2. Read XSRF-TOKEN cookie value
3. POST with header X-CSRF-TOKEN: {decoded_token}
4. Include laravel_session cookie on all subsequent requests
```

### Suggested Flutter HTTP client headers

```dart
final headers = {
  'Accept': 'application/json',
  'Content-Type': 'application/json', // or multipart for files
  'X-CSRF-TOKEN': csrfToken,
  'X-Requested-With': 'XMLHttpRequest',
};
```

---

## Appendix: Route Index

| # | Method | Path | Auth | Response |
|---|--------|------|------|----------|
| 1 | GET | `/sanctum/csrf-cookie` | None | 204 + cookies |
| 2 | GET | `/api/user` | Sanctum | JSON |
| 3 | GET | `/admin` | None | 302 |
| 4 | GET | `/admin/login` | None | HTML |
| 5 | POST | `/admin/login/submit` | None | 302 |
| 6 | GET | `/admin/logout` | Session | 302 |
| 7 | GET | `/admin/dashboard` | Admin session | HTML |
| 8 | GET | `/admin/submissions/{type}/{id}` | Admin session | JSON |
| 9 | POST | `/admin/submissions/{type}/{id}/status` | Admin session | JSON |
| 10 | GET | `/admin/export/{type}` | Admin session | CSV |
| 11 | POST | `/admin/options/add` | Admin session | 302 |
| 12 | POST | `/admin/options/edit/{id}` | Admin session | 302 |
| 13 | POST | `/admin/options/delete/{id}` | Admin session | 302 |
| 14 | POST | `/contact/submit` | None | HTML |
| 15 | POST | `/donate/submit` | None | HTML |
| 16 | GET | `/feedback-suggestion` | None | HTML |
| 17 | POST | `/feedback-suggestion/submit` | None | HTML |
| 18 | GET | `/membership-ordinary` | None | HTML |
| 19 | POST | `/membership-ordinary/submit` | None | HTML |
| 20 | GET | `/membership-friends` | None | HTML |
| 21 | POST | `/membership-friends/submit` | None | HTML |
| 22 | GET | `/mentor-registration` | None | HTML |
| 23 | POST | `/mentor-registration/submit` | None | HTML |
| 24 | GET | `/partnership-collaboration` | None | HTML |
| 25 | POST | `/partnership-collaboration/submit` | None | HTML |
| 26 | GET | `/volunteer-registration` | None | HTML |
| 27 | POST | `/volunteer-registration/submit` | None | HTML |
| 28 | GET | `/community-aid` | None | HTML |
| 29 | POST | `/community-aid/submit` | None | HTML |
| 30 | GET | `/coming-soon` | None | HTML |
| 31 | GET | `/` | None | HTML |
| 32 | GET | `/about` | None | HTML |
| 33 | GET | `/about/who-we-are` | None | 302 |
| 34 | GET | `/about/president-note` | None | 302 |
| 35 | GET | `/about/leadership` | None | 302 |
| 36 | GET | `/contact` | None | HTML |
| 37 | GET | `/legal-disclaimer` | None | HTML |
| 38 | GET | `/donate` | None | HTML |
| 39 | GET | `/ecosystem` | None | HTML |
| 40 | GET | `/serve-together` | None | HTML |
| 41 | GET | `/impact-areas` | None | HTML |
| 42 | GET | `/impact-areas/mfls` | None | HTML |
| 43 | GET | `/impact-areas/sirat-series` | None | HTML |
| 44 | GET | `/news` | None | HTML |
| 45 | GET | `/changing-lives` | None | HTML |
| 46 | GET | `/blog` | None | HTML |
| 47 | GET | `/blog/{slug}` | None | HTML |
| 48 | GET | `/causes` | None | HTML |
| 49 | GET | `/causes/{slug}` | None | HTML |

---

*Document generated from `routes/welfare.php`, `routes/api.php`, and controller/model source code in the MUKMIN Laravel application.*
