# MUKMIN Flutter Mobile App — Project Flow Document

> **Version:** 1.0  
> **Backend:** Laravel 8 (MUKMIN Welfare Application)  
> **Companion doc:** [`API_DOCUMENTATION.md`](./API_DOCUMENTATION.md)  
> **Generated:** June 2026

---

## Document purpose

This document defines how to build a Flutter mobile app on top of the existing MUKMIN PHP backend. It maps PHP entities, routes, and user flows to Flutter screens, models, navigation, and API integration.

**Backend reality check:** The PHP app is a server-rendered website. The mobile app should target a **planned `/api/v1` JSON layer** (recommended additions documented in `API_DOCUMENTATION.md`). Where only legacy web endpoints exist today, both **current** and **target** endpoints are noted.

---

## User roles

| Role | PHP support today | Mobile app scope |
|------|-------------------|------------------|
| **Public user** | No login; form submission only | Browse content, submit forms, contact, donate enquiry |
| **Admin user** | Session login at `/admin/login` | Review submissions, update status, export, manage dropdown options |

There is **no public user registration or login** in the PHP codebase. The app has two modes: **guest (public)** and **admin (authenticated)**.

---

## 1. App Architecture Overview

### Recommended pattern: **Clean Architecture + MVVM + Riverpod**

| Layer | Responsibility |
|-------|----------------|
| **Presentation** | Widgets, screens, ViewModels (Riverpod `Notifier` / `AsyncNotifier`) |
| **Domain** | Entities, repository interfaces, use cases |
| **Data** | API clients (Dio), DTOs, repository implementations, local cache |

**Why Riverpod over BLoC/Provider for this project**

- Many independent form features (9 submission types) benefit from scoped providers without boilerplate.
- `AsyncNotifier` fits loading dropdown options + submit states cleanly.
- Compile-safe dependency injection for environment config, Dio, and auth.
- BLoC is viable but adds more ceremony per form; Provider alone scales poorly across 30+ screens.

**Alternative:** BLoC + `flutter_bloc` if the team already standardizes on it — folder structure below stays the same; swap `providers/` for `blocs/`.

### Folder structure

```
lib/
├── main.dart
├── app.dart                          # MaterialApp, theme, router
├── bootstrap.dart                    # Env init, Dio setup, error hooks
│
├── core/
│   ├── config/
│   │   ├── env_config.dart           # dev/staging/prod base URLs
│   │   └── app_constants.dart
│   ├── network/
│   │   ├── dio_client.dart
│   │   ├── api_interceptors.dart
│   │   ├── api_exception.dart
│   │   └── retry_policy.dart
│   ├── storage/
│   │   ├── secure_token_storage.dart
│   │   └── prefs_storage.dart
│   ├── routing/
│   │   ├── app_router.dart           # go_router routes
│   │   └── route_paths.dart
│   ├── theme/
│   │   ├── app_theme.dart
│   │   └── app_colors.dart           # primary #d43c18 from welfare config
│   ├── utils/
│   │   ├── validators.dart           # NRIC, phone, email regex from PHP
│   │   └── date_formatters.dart
│   └── widgets/
│       ├── app_scaffold.dart
│       ├── loading_overlay.dart
│       ├── error_banner.dart
│       └── form_fields/              # reusable inputs
│
├── features/
│   ├── splash/
│   ├── onboarding/                   # optional first-run
│   ├── home/
│   ├── about/
│   ├── ecosystem/
│   ├── impact/
│   ├── serve/                        # hub for registration forms
│   ├── blog/
│   ├── causes/
│   ├── news_gallery/
│   ├── contact/
│   ├── donate/
│   ├── forms/                        # shared form infrastructure
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │       ├── feedback/
│   │       ├── membership_ordinary/
│   │       ├── membership_friends/
│   │       ├── mentor/
│   │       ├── partner/
│   │       ├── volunteer/
│   │       └── community_aid/
│   ├── legal/
│   └── admin/
│       ├── auth/
│       ├── dashboard/
│       ├── submission_detail/
│       ├── export/
│       └── options/
│
└── shared/
    ├── models/                       # cross-feature DTOs
    └── providers/                    # global providers (auth, dio)
```

### State management approach

| State type | Tool | Example |
|------------|------|---------|
| Global auth session | `StateNotifierProvider<AuthNotifier, AuthState>` | Admin logged in/out |
| Screen loading/data | `AsyncNotifierProvider` | Blog list, submission detail |
| Form field state | `flutter_hooks` + local `useState` or `FormBuilder` | Multi-step registration forms |
| One-off UI (snackbar, tab index) | `StateProvider` | Bottom nav index |
| Dropdown options cache | `FutureProvider.family` | `formOptionsProvider('mentor_expertise')` |

**Principles**

1. **Immutable state objects** (`freezed` recommended for `AuthState`, `SubmissionState`).
2. **Repository pattern** — ViewModels never call Dio directly.
3. **Result/Either errors** — map `422` validation to field-level errors on forms.
4. **Optimistic UI only for admin status update** — revert on failure.

---

## 2. User Journey Flows

### Role A: Public user (guest)

#### A1 — App launch → Explore content

```
SplashScreen
  → [first launch?] OnboardingScreen (optional)
  → HomeScreen
      → AboutScreen (tabs: Who We Are | President's Note | Leadership)
      → EcosystemScreen
      → ImpactAreasScreen → MflsScreen | SiratScreen
      → ServeTogetherScreen (action hub)
      → BlogListScreen → BlogDetailScreen
      → CausesListScreen → CauseDetailScreen
      → NewsGalleryScreen
      → ContactScreen
      → DonateScreen
      → LegalDisclaimerScreen
```

**Decision points**

| Point | Condition | Route |
|-------|-----------|-------|
| First launch | `prefs.getBool('onboarding_done') == false` | Onboarding → Home |
| Returning user | onboarding done | Splash → Home |
| Offline | no network on content fetch | Show cached content or OfflineScreen |

#### A2 — Submit contact message

```
ContactScreen
  → [tap Send] validate locally
      → [invalid] inline field errors
      → [valid] POST contact/submit
          → [422] show server field errors
          → [200/201] FormSuccessScreen
          → [419] refresh CSRF → retry once
          → [500] ErrorDialog → stay on ContactScreen
```

#### A3 — Donation enquiry (payment not live)

```
DonateScreen
  → select preset amount OR enter custom_amount
  → [tap Submit Enquiry] POST donate/submit
      → FormSuccessScreen ("Donation Portal - Coming Soon")
```

**Note:** PHP does not persist donate submissions to DB — email only.

#### A4 — Feedback & suggestion

```
ServeTogetherScreen → FeedbackFormScreen
  → load categories (GET /api/v1/forms/feedback_category/options)
  → fill form + declaration checkbox
  → POST feedback-suggestion/submit
      → FormSuccessScreen
```

#### A5 — Ordinary member registration

```
ServeTogetherScreen → OrdinaryMemberFormScreen (multi-section)
  → Section 1: Organisation details
  → Section 2: Org type & activities (multi-select from options API)
  → Section 3: Key office bearers (president required; secretary/treasurer optional)
  → Section 4: Declaration
  → POST membership-ordinary/submit
      → FormSuccessScreen ("Application Submitted Successfully")
```

#### A6 — Friends of MUKMIN registration

```
ServeTogetherScreen → FriendsMemberFormScreen
  → [entity_type == Individual]
      → show ind_* fields
  → [entity_type != Individual]
      → show org_* fields
  → POST membership-friends/submit
      → FormSuccessScreen
```

#### A7 — Mentor registration

```
ServeTogetherScreen → MentorFormScreen
  → load expertise/format/commitment options
  → [has_served_before == true] show served_before_details
  → POST mentor-registration/submit
      → FormSuccessScreen
```

#### A8 — Partnership proposal (with files)

```
ServeTogetherScreen → PartnerFormScreen
  → pick supporting_files (pdf/images/docs, max 20MB each)
  → POST multipart partnership-collaboration/submit
      → FormSuccessScreen
```

#### A9 — Volunteer registration

```
ServeTogetherScreen → VolunteerFormScreen
  → [has_volunteered_before == true] show details field
  → POST volunteer-registration/submit
      → FormSuccessScreen
```

#### A10 — Community aid request (with files)

```
ServeTogetherScreen → CommunityAidFormScreen
  → [received_aid_before == true] show details
  → optional supporting_files upload
  → POST multipart community-aid/submit
      → FormSuccessScreen
```

---

### Role B: Admin user

#### B1 — Admin login

```
[Settings / hidden gesture / Admin entry] AdminLoginScreen
  → enter email + password + remember me
  → POST /api/v1/admin/login (target) OR POST /admin/login/submit (legacy session)
      → [invalid credentials] inline error
      → [success] store Sanctum token → AdminDashboardScreen
```

#### B2 — Review submissions

```
AdminDashboardScreen
  → [tap submission row]
      → AdminSubmissionDetailScreen (type + id)
          → GET /admin/submissions/{type}/{id}
  → [change status] (ordinary|friends|partner|aid only)
      → POST /admin/submissions/{type}/{id}/status
          → [success] update local list + snackbar
          → [400] invalid type error
```

#### B3 — Export CSV

```
AdminDashboardScreen
  → [tap Export on tab]
      → GET /admin/export/{type}
      → save/share CSV via platform share sheet
```

#### B4 — Manage dropdown options

```
AdminDashboardScreen → AdminOptionsScreen
  → [add] POST /admin/options/add
  → [edit] POST /admin/options/edit/{id}
  → [delete] POST /admin/options/delete/{id}
```

#### B5 — Admin logout

```
AdminDashboardScreen → [Logout]
  → clear secure token + cookies
  → AdminLoginScreen (or HomeScreen)
```

---

### Role C: Session expiry (admin)

```
Any admin screen + API returns 401
  → AuthInterceptor clears token
  → navigate to AdminLoginScreen
  → show "Session expired. Please sign in again."
```

---

## 3. Screen Inventory

### Core / shell

| Screen | Purpose | Key UI components | API endpoint(s) | Navigation |
|--------|---------|-------------------|-----------------|------------|
| `SplashScreen` | Boot, load env, check auth | Logo, progress | — | → Home or AdminDashboard |
| `OnboardingScreen` | First-run intro (optional) | PageView, Skip/Next | — | → Home |
| `MainShellScreen` | Bottom nav container | BottomNavigationBar (4–5 tabs) | — | Hosts tab roots |

**Bottom nav tabs (public)**

| Tab | Root screen |
|-----|-------------|
| Home | `HomeScreen` |
| Serve | `ServeTogetherScreen` |
| Causes | `CausesListScreen` |
| News | `BlogListScreen` or `NewsGalleryScreen` |
| More | `MoreMenuScreen` (drawer-like list) |

---

### Public — content

| Screen | Purpose | Key UI components | API endpoint(s) | Navigation |
|--------|---------|-------------------|-----------------|------------|
| `HomeScreen` | Landing, highlights | Hero banner, quick actions (Donate, Serve, Contact), featured causes | `GET /api/v1/home` (target) | → all major sections |
| `AboutScreen` | Organisation info | TabBar: Who We Are, President's Note, Leadership; team cards | `GET /api/v1/about` (target) | ← More, Home |
| `EcosystemScreen` | FIKRAH, Gabungan, Yayasan | Section anchors, cards | `GET /api/v1/pages/ecosystem` (target) | ← More |
| `ImpactAreasScreen` | Impact overview | Cards → MFLS, Sirat | Static / target API | → Mfls, Sirat |
| `MflsScreen` | MFLS programme detail | Rich text, CTA to Serve | Static / target API | ← Impact |
| `SiratScreen` | Sirat Series detail | Rich text, CTA | Static / target API | ← Impact |
| `ServeTogetherScreen` | Registration hub | Grid/list of 7 form entry points | — | → form screens |
| `BlogListScreen` | Blog index | `ListView` cards (image, title, excerpt, date) | `GET /api/v1/blog` (target); legacy: hardcoded in PHP | → BlogDetail |
| `BlogDetailScreen` | Single post | Hero image, HTML content WebView/flutter_html | `GET /api/v1/blog/{slug}` (target) | ← BlogList |
| `CausesListScreen` | Campaigns list | Cards with progress bar (`raised`/`goal`) | `GET /api/v1/causes` (target) | → CauseDetail |
| `CauseDetailScreen` | Campaign detail | Progress, description, Donate CTA | `GET /api/v1/causes/{slug}` (target) | → Donate |
| `NewsGalleryScreen` | Moments gallery | Category chips + image grid | `GET /api/v1/gallery/moments` (target) | ← More |
| `ChangingLivesScreen` | Stories / testimonials | Story cards | Static / target API | ← More |
| `LegalDisclaimerScreen` | Legal text | Scrollable text | Static / target API | ← More |
| `MoreMenuScreen` | Secondary links | ListTile menu | — | → About, Ecosystem, Impact, News, Legal, Admin |

---

### Public — forms

| Screen | Purpose | Key UI components | API endpoint(s) | Navigation |
|--------|---------|-------------------|-----------------|------------|
| `ContactScreen` | Contact form | name, email, phone, message fields | `POST /contact/submit` | ← Home/More; → FormSuccess |
| `DonateScreen` | Donation enquiry | Preset chips, custom amount, name, email | `POST /donate/submit` | ← Home/CauseDetail; → FormSuccess |
| `FeedbackFormScreen` | Feedback submission | categories multi-select, NRIC, consent | Options: `GET /api/v1/forms/feedback_category/options`; Submit: `POST /feedback-suggestion/submit` | ← Serve; → FormSuccess |
| `OrdinaryMemberFormScreen` | Org membership | Multi-step form, office bearers sub-form | Options: `ordinary_org_type`, `ordinary_activity`; Submit: `POST /membership-ordinary/submit` | ← Serve; → FormSuccess |
| `FriendsMemberFormScreen` | Friends registration | Entity type toggle, conditional fields | Options: `friends_category`; Submit: `POST /membership-friends/submit` | ← Serve; → FormSuccess |
| `MentorFormScreen` | Mentor registration | Expertise/format/commitment multi-select | Options: `mentor_*`; Submit: `POST /mentor-registration/submit` | ← Serve; → FormSuccess |
| `PartnerFormScreen` | Partnership proposal | File picker, multi-selects | Options: `partner_*`; Submit: `POST /partnership-collaboration/submit` (multipart) | ← Serve; → FormSuccess |
| `VolunteerFormScreen` | Volunteer signup | Interest, availability, emergency contact | Options: `volunteer_*`; Submit: `POST /volunteer-registration/submit` | ← Serve; → FormSuccess |
| `CommunityAidFormScreen` | Aid request | Aid types, beneficiaries, file upload | Submit: `POST /community-aid/submit` (multipart) | ← Serve; → FormSuccess |
| `FormSuccessScreen` | Confirmation | Title, message, Back to Home CTA | — | ← any form; → Home |

---

### Admin

| Screen | Purpose | Key UI components | API endpoint(s) | Navigation |
|--------|---------|-------------------|-----------------|------------|
| `AdminLoginScreen` | Admin auth | email, password, remember me | `POST /api/v1/admin/login` (target) or `POST /admin/login/submit` | → AdminDashboard |
| `AdminDashboardScreen` | Submission overview | TabBar per type, counts, DataTable, export btn | Dashboard data: `GET /api/v1/admin/dashboard` (target) | → Detail, Options |
| `AdminSubmissionDetailScreen` | Full record view | JSON fields rendered, status dropdown | `GET /admin/submissions/{type}/{id}` | ← Dashboard |
| `AdminOptionsScreen` | Dropdown CRUD | form_type picker, add/edit/delete | `POST /admin/options/add`, `edit/{id}`, `delete/{id}` | ← Dashboard |

---

### Shared / utility

| Screen | Purpose | Navigation |
|--------|---------|------------|
| `OfflineScreen` | No connectivity | Retry button → previous route |
| `ErrorScreen` | Unrecoverable error | → Home |

---

## 4. Data Models

Use `json_serializable` + `freezed` in production. Signatures below are illustrative.

### `User` (admin)

```dart
class User {
  final int id;
  final String name;
  final String email;
  final DateTime? emailVerifiedAt;
  final DateTime createdAt;
  final DateTime updatedAt;

  factory User.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

| Field | Dart type | Nullable |
|-------|-----------|----------|
| `id` | `int` | No |
| `name` | `String` | No |
| `email` | `String` | No |
| `emailVerifiedAt` | `DateTime?` | Yes |
| `createdAt` | `DateTime` | No |
| `updatedAt` | `DateTime` | No |

---

### `AuthToken` (client-side)

```dart
class AuthToken {
  final String accessToken;
  final String tokenType;   // "Bearer"
  final DateTime? expiresAt;

  factory AuthToken.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `FormDropdownOption`

```dart
class FormDropdownOption {
  final int id;
  final String formType;
  final String optionValue;
  final int sortOrder;

  factory FormDropdownOption.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `ContactSubmission`

```dart
class ContactSubmission {
  final int? id;
  final String name;
  final String email;
  final String phone;
  final String message;
  final DateTime? createdAt;

  factory ContactSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();       // for POST body (exclude id, timestamps)
}
```

---

### `DonateEnquiry` (not persisted in PHP DB)

```dart
class DonateEnquiry {
  final String name;
  final String email;
  final double? amount;
  final double? customAmount;

  factory DonateEnquiry.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `FeedbackSubmission`

```dart
class FeedbackSubmission {
  final int? id;
  final String fullName;
  final String nricNumber;
  final String? organisation;
  final String? position;
  final String stateResidency;
  final String fullAddress;
  final String email;
  final String contactNumber;
  final List<String> categories;
  final String? otherCategory;
  final String suggestionDescription;
  final String benefitsDescription;
  final bool contactConsent;
  final List<String>? preferredContactMethods;
  final bool declarationConfirmed;
  final DateTime? createdAt;

  factory FeedbackSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `KeyOfficeBearers` + `OfficeBearer`

```dart
class OfficeBearer {
  final String? name;
  final String? email;
  final String? phone;

  factory OfficeBearer.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}

class KeyOfficeBearers {
  final OfficeBearer president;
  final OfficeBearer? secretary;
  final OfficeBearer? treasurer;

  factory KeyOfficeBearers.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `OrdinaryMemberSubmission`

```dart
class OrdinaryMemberSubmission {
  final int? id;
  final String nameOfOrganisation;
  final String orgRegNumber;
  final DateTime orgRegDate;
  final String registeredState;
  final String fullAddress;
  final String postcode;
  final String districtCity;
  final int yearEstablished;
  final int totalMembersSize;
  final String email;
  final String contactNumber;
  final String? website;
  final List<String> orgType;
  final String? orgTypeOther;
  final List<String> primaryActivities;
  final String? primaryActivitiesOther;
  final KeyOfficeBearers keyOfficeBearers;
  final bool declarationConfirmed;
  final String? status;              // pending|approved|rejected|under_review
  final DateTime? createdAt;

  factory OrdinaryMemberSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `FriendMemberSubmission`

```dart
class FriendMemberSubmission {
  final int? id;
  final String entityType;
  final String? othersSpecify;
  final String? orgName;
  final String? orgState;
  final String? orgAddress;
  final String? orgEmail;
  final String? orgPhone;
  final String? orgWebsite;
  final String? indName;
  final String? indNric;
  final String? indState;
  final String? indAddress;
  final String? indEmail;
  final String? indPhone;
  final bool declarationConfirmed;
  final String? status;
  final DateTime? createdAt;

  factory FriendMemberSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `MentorSubmission`

```dart
class MentorSubmission {
  final int? id;
  final String fullName;
  final String nricPassport;
  final String gender;               // Male | Female
  final String occupation;
  final String organisation;
  final String position;
  final int experienceYears;
  final String stateResidency;
  final String fullAddress;
  final String email;
  final String contactNumber;
  final String? linkedin;
  final List<String> expertiseAreas;
  final String? expertiseOther;
  final List<String> preferredFormat;
  final List<String> preferredCommitment;
  final String experienceDescription;
  final bool hasServedBefore;
  final String? servedBeforeDetails;
  final bool declarationConfirmed;
  final DateTime? createdAt;

  factory MentorSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `PartnerSubmission`

```dart
class PartnerSubmission {
  final int? id;
  final String companyName;
  final String contactPerson;
  final String position;
  final String? orgRegNumber;
  final String email;
  final String contactNumber;
  final String officeAddress;
  final String stateCountry;
  final List<String> orgType;
  final String? orgTypeOther;
  final List<String> collaborationAreas;
  final String? collaborationOther;
  final List<String> partnershipType;
  final String? partnershipOther;
  final String proposalDescription;
  final String expectedOutcomes;
  final bool hasCollaboratedBefore;
  final String? collaboratedBeforeDetails;
  final List<String>? supportingDocuments;  // server paths after upload
  final bool declarationConfirmed;
  final String? status;
  final DateTime? createdAt;

  factory PartnerSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `VolunteerSubmission`

```dart
class VolunteerSubmission {
  final int? id;
  final String fullName;
  final String nricPassport;
  final String gender;
  final String occupationStudy;
  final String? organisation;
  final String stateResidency;
  final String fullAddress;
  final String email;
  final String contactNumber;
  final List<String> interestAreas;
  final String? interestOther;
  final String skillsExpertise;
  final String preferredMode;        // Physical / On-Ground | Virtual / Remote | Both
  final List<String> availability;
  final bool hasVolunteeredBefore;
  final String? volunteeredBeforeDetails;
  final String emergencyContactName;
  final String emergencyContactRelationship;
  final String emergencyContactPhone;
  final bool declarationConfirmed;
  final DateTime? createdAt;

  factory VolunteerSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `CommunityAidSubmission`

```dart
class CommunityAidSubmission {
  final int? id;
  final String fullName;
  final String nricPassport;
  final String gender;
  final DateTime dob;
  final String nationality;
  final String occupation;
  final String? monthlyIncome;
  final String contactNumber;
  final String email;
  final String fullAddress;
  final String stateResidency;
  final List<String> typeOfAid;
  final String? typeOfAidOther;
  final String situationDescription;
  final String whoBenefits;
  final int? numberOfBeneficiaries;
  final bool receivedAidBefore;
  final String? receivedAidBeforeDetails;
  final List<String>? supportingDocuments;
  final String emergencyContactName;
  final String emergencyContactRelationship;
  final String emergencyContactPhone;
  final bool declarationConfirmed;
  final String? status;
  final DateTime? createdAt;

  factory CommunityAidSubmission.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `BlogPost`

```dart
class BlogPost {
  final String title;
  final String? excerpt;
  final String? content;             // HTML
  final String date;                 // or DateTime
  final String author;
  final String image;
  final String slug;

  factory BlogPost.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `Campaign` (Causes)

```dart
class Campaign {
  final String title;
  final String excerpt;
  final String? content;
  final int raised;
  final int goal;
  final String image;
  final String slug;

  double get progress => goal > 0 ? raised / goal : 0;

  factory Campaign.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `TeamMember` (About page)

```dart
class TeamMember {
  final String name;
  final String? role;
  final String? category;            // coa | cec | exco | bureau
  final String image;

  factory TeamMember.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `GalleryImage` + `GalleryCategory` (News / Moments)

```dart
class GalleryCategory {
  final String folder;
  final String slug;
  final String label;

  factory GalleryCategory.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}

class GalleryImage {
  final String src;
  final String title;
  final String file;
  final String category;
  final String categoryLabel;

  factory GalleryImage.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `AdminDashboardStats`

```dart
class AdminDashboardStats {
  final int feedback;
  final int ordinary;
  final int friends;
  final int mentor;
  final int partner;
  final int volunteer;
  final int contact;
  final int aid;

  factory AdminDashboardStats.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `SubmissionStatusUpdate`

```dart
class SubmissionStatusUpdate {
  final String status;   // pending | approved | rejected | under_review

  factory SubmissionStatusUpdate.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

---

### `ApiError` / `ValidationError`

```dart
class ApiError {
  final String message;
  final int? statusCode;
  final Map<String, List<String>>? fieldErrors;

  factory ApiError.fromJson(Map<String, dynamic> json);
}
```

---

### JSON key mapping note

PHP uses `snake_case`; Dart uses `camelCase`. Use `@JsonKey(name: 'full_name')` or a global `fieldRename: FieldRename.snake` in `build.yaml`.

---

## 5. Authentication Flow

### Public user

No authentication. Optional anonymous device ID for analytics only.

### Admin authentication sequence

```
┌─────────────┐     POST /api/v1/admin/login      ┌──────────────┐
│ LoginScreen │ ─────────────────────────────────► │ Laravel API  │
└─────────────┘     { email, password, remember }  └──────┬───────┘
       ▲                                                   │
       │                     200 { token, user }           │
       │◄──────────────────────────────────────────────────┘
       │
       ▼
 SecureStorage.write('access_token', token)
 AuthNotifier.state = Authenticated(user)
 navigate → AdminDashboardScreen
```

**Target login request**

```json
{
  "email": "admin@mukmin.org",
  "password": "secret",
  "remember": true
}
```

**Target login response**

```json
{
  "token": "1|abcdef...",
  "token_type": "Bearer",
  "user": { "id": 1, "name": "Admin", "email": "admin@mukmin.org" }
}
```

> **Current PHP gap:** No token login route exists. Implement `POST /api/v1/admin/login` using Sanctum `createToken()` on the backend before shipping admin mobile features.

### Logout sequence

```
1. POST /api/v1/admin/logout (target) — revoke current token
2. SecureStorage.delete('access_token')
3. Clear CSRF/session cookies if used for legacy endpoints
4. AuthNotifier.state = Unauthenticated
5. navigate → AdminLoginScreen
```

### Token storage strategy

| Data | Storage | Package |
|------|---------|---------|
| Sanctum access token | **Flutter Secure Storage** (Keychain / EncryptedSharedPreferences) | `flutter_secure_storage` |
| Remember-me preference | SharedPreferences | `shared_preferences` |
| CSRF token (legacy web routes) | Secure Storage or cookie jar | `dio_cookie_manager` |
| Cached dropdown options | Hive or SharedPreferences | `hive` |

```dart
abstract class SecureTokenStorage {
  Future<void> saveToken(String token);
  Future<String?> readToken();
  Future<void> clearToken();
}
```

### Token refresh flow

**Not applicable today.** Sanctum personal access tokens in this PHP app have **no expiration** (`sanctum.expiration` = null). No refresh token is issued.

**Future recommendation:** Set `expiration` in `config/sanctum.php` and add `POST /api/v1/auth/refresh` if long-lived mobile sessions are required.

### Session expiry handling

| Scenario | Detection | App behavior |
|----------|-----------|--------------|
| Sanctum token revoked/invalid | HTTP `401` on any admin call | Clear token → AdminLoginScreen + snackbar |
| CSRF `419` on legacy POST | Interceptor | Fetch new CSRF via `GET /sanctum/csrf-cookie`, retry once |
| Rate limit `429` | Interceptor | Exponential backoff, show "Try again shortly" |
| Network timeout | Dio timeout | Show retry on screen |

```dart
// AuthInterceptor pseudocode
onError(DioException e) {
  if (e.response?.statusCode == 401) {
    ref.read(authNotifierProvider.notifier).logout(localOnly: true);
    appRouter.go('/admin/login');
  }
}
```

---

## 6. Navigation Flow

### App entry point

```dart
// main.dart
void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await bootstrap(); // env, storage, dio
  runApp(ProviderScope(child: MukminApp()));
}

// app.dart — initial route
initialLocation: '/splash'
```

### Route map (`go_router`)

| Path | Name | Access | Screen |
|------|------|--------|--------|
| `/splash` | splash | Public | SplashScreen |
| `/onboarding` | onboarding | Public | OnboardingScreen |
| `/` | home | Public | MainShellScreen (tab: home) |
| `/serve` | serve | Public | MainShellScreen (tab: serve) |
| `/causes` | causes | Public | MainShellScreen (tab: causes) |
| `/blog` | blog | Public | MainShellScreen (tab: blog) |
| `/more` | more | Public | MoreMenuScreen |
| `/about` | about | Public | AboutScreen |
| `/ecosystem` | ecosystem | Public | EcosystemScreen |
| `/impact` | impact | Public | ImpactAreasScreen |
| `/impact/mfls` | mfls | Public | MflsScreen |
| `/impact/sirat` | sirat | Public | SiratScreen |
| `/blog/:slug` | blogDetail | Public | BlogDetailScreen |
| `/causes/:slug` | causeDetail | Public | CauseDetailScreen |
| `/news` | newsGallery | Public | NewsGalleryScreen |
| `/contact` | contact | Public | ContactScreen |
| `/donate` | donate | Public | DonateScreen |
| `/legal` | legal | Public | LegalDisclaimerScreen |
| `/forms/feedback` | feedbackForm | Public | FeedbackFormScreen |
| `/forms/ordinary` | ordinaryForm | Public | OrdinaryMemberFormScreen |
| `/forms/friends` | friendsForm | Public | FriendsMemberFormScreen |
| `/forms/mentor` | mentorForm | Public | MentorFormScreen |
| `/forms/partner` | partnerForm | Public | PartnerFormScreen |
| `/forms/volunteer` | volunteerForm | Public | VolunteerFormScreen |
| `/forms/community-aid` | communityAidForm | Public | CommunityAidFormScreen |
| `/forms/success` | formSuccess | Public | FormSuccessScreen |
| `/admin/login` | adminLogin | Public | AdminLoginScreen |
| `/admin` | adminDashboard | **Protected** | AdminDashboardScreen |
| `/admin/submissions/:type/:id` | adminSubmission | **Protected** | AdminSubmissionDetailScreen |
| `/admin/options` | adminOptions | **Protected** | AdminOptionsScreen |

### Public vs protected routes

```dart
redirect: (context, state) {
  final isAdminRoute = state.matchedLocation.startsWith('/admin')
      && state.matchedLocation != '/admin/login';
  final isLoggedIn = authState is Authenticated;

  if (isAdminRoute && !isLoggedIn) return '/admin/login';
  if (state.matchedLocation == '/admin/login' && isLoggedIn) return '/admin';
  return null;
}
```

### Bottom navigation structure

```
┌────────────────────────────────────────────────────────┐
│  🏠 Home  |  🤝 Serve  |  ❤️ Causes  |  📰 News  |  ☰ More │
└────────────────────────────────────────────────────────┘
```

| Tab | Stack root | Deep link examples |
|-----|------------|-------------------|
| Home | `/` | — |
| Serve | `/serve` | `/forms/volunteer` |
| Causes | `/causes` | `/causes/clean-water-schools` |
| News | `/blog` | `/blog/volunteer-spotlight` |
| More | `/more` | `/about`, `/contact`, `/admin/login` |

### Drawer / More menu items

Aligned with PHP `config/welfare.php` nav:

- About Us → Who We Are, President's Note, Leadership
- Our Ecosystem
- Impact Areas → MFLS, Sirat
- Serve Together (shortcut to Serve tab)
- Contact Us
- Donate
- Legal Disclaimer
- Admin Login (bottom, subtle)

### Deep linking

| URI scheme | Maps to | Use case |
|------------|---------|----------|
| `mukmin://causes/{slug}` | `/causes/{slug}` | Share campaign |
| `mukmin://blog/{slug}` | `/blog/{slug}` | Share blog post |
| `mukmin://serve/volunteer` | `/forms/volunteer` | Campaign QR codes |
| `mukmin://serve/community-aid` | `/forms/community-aid` | Aid programme link |
| `https://mukmin.org/causes/{slug}` | `/causes/{slug}` | Universal links (iOS/Android) |

Configure `android:intent-filters` and iOS Associated Domains to match production web URLs from the PHP site.

---

## 7. API Integration Layer

### HTTP client: **Dio** (recommended)

| Package | Purpose |
|---------|---------|
| `dio` | HTTP client, interceptors, multipart |
| `dio_cookie_manager` + `cookie_jar` | Session/CSRF cookie persistence (legacy web routes) |
| `pretty_dio_logger` | Debug logging (dev only) |
| `connectivity_plus` | Network reachability checks |

Use raw `http` package only for trivial prototypes — Dio is better suited for interceptors, retries, and file uploads.

### Base URL and environment config

```dart
enum Environment { dev, staging, production }

class EnvConfig {
  static const _urls = {
    Environment.dev:        'http://10.0.2.2:8000',      // Android emulator → localhost
    Environment.staging:    'https://staging.mukmin.org',
    Environment.production: 'https://mukmin.org',
  };

  static String get baseUrl => _urls[current]!;
  static String get apiV1 => '$baseUrl/api/v1';
  static String get legacyWeb => baseUrl;  // /contact/submit etc.
}
```

**Build flavors:** `--dart-define=ENV=production` or separate `main_dev.dart` / `main_prod.dart`.

### API service layout

```dart
abstract class FormApi {
  Future<void> submitContact(ContactSubmission body);
  Future<void> submitFeedback(FeedbackSubmission body);
  // ...
}

abstract class AdminApi {
  Future<AuthToken> login(String email, String password, {bool remember});
  Future<dynamic> getSubmission(String type, int id);
  Future<void> updateStatus(String type, int id, String status);
  Future<List<int>> exportCsv(String type);  // raw bytes
}

abstract class ContentApi {
  Future<List<BlogPost>> getBlogPosts();
  Future<BlogPost> getBlogPost(String slug);
  Future<List<Campaign>> getCampaigns();
  Future<List<FormDropdownOption>> getFormOptions(String formType);
}
```

### Interceptors

| # | Interceptor | Responsibility |
|---|-------------|----------------|
| 1 | `AuthInterceptor` | Inject `Authorization: Bearer {token}` on admin + `/api/*` calls |
| 2 | `CsrfInterceptor` | Attach `X-CSRF-TOKEN` on legacy `web` POST; refresh on `419` |
| 3 | `HeaderInterceptor` | `Accept: application/json`, `X-Requested-With: XMLHttpRequest` |
| 4 | `LoggingInterceptor` | Log request/response in debug builds only |
| 5 | `ErrorInterceptor` | Map DioException → `ApiError` with field errors from `422` |
| 6 | `ConnectivityInterceptor` | Short-circuit if offline (optional) |

```dart
class AuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    final token = await secureStorage.readToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    handler.next(options);
  }
}
```

### Error handling matrix

| HTTP | Meaning | Flutter action |
|------|---------|----------------|
| `200` / `201` | Success | Parse body or show success screen |
| `302` | Web redirect (legacy) | Treat as success only if following redirects is disabled for API |
| `400` | Bad request | Show `error` message from JSON |
| `401` | Unauthenticated | Force logout (admin) |
| `404` | Not found | Show empty/error state |
| `419` | CSRF expired | Refresh CSRF, retry once |
| `422` | Validation failed | Map `errors` to form fields |
| `429` | Rate limited | Backoff + user message |
| `500` | Server error | Generic error + optional retry |

### Retry logic

```dart
class RetryPolicy {
  static const maxRetries = 2;
  static const retryableStatuses = {408, 429, 500, 502, 503, 504};

  static Future<Response> execute(Future<Response> Function() call) async {
    int attempt = 0;
    while (true) {
      try {
        return await call();
      } on DioException catch (e) {
        attempt++;
        final status = e.response?.statusCode;
        if (attempt > maxRetries || !retryableStatuses.contains(status)) rethrow;
        await Future.delayed(Duration(seconds: attempt * 2)); // 2s, 4s
      }
    }
  }
}
```

**Retry rules**

| Operation | Retry? | Notes |
|-----------|--------|-------|
| GET content | Yes | Safe, idempotent |
| Form POST | **No** (except `419` CSRF once) | Avoid duplicate submissions |
| Admin status update | Yes (max 1) | Idempotent |
| File upload | No | User must re-submit |
| Login | No | Security |

### CSRF bootstrap (legacy web endpoints)

Required until all endpoints move to `/api/v1`:

```
1. GET /sanctum/csrf-cookie
2. CookieManager persists XSRF-TOKEN + laravel_session
3. Decode XSRF-TOKEN → set header X-CSRF-TOKEN on POST
```

### Recommended backend endpoints to implement first

Priority order for PHP team to unblock Flutter:

| Priority | Endpoint | Unblocks |
|----------|----------|----------|
| P0 | `POST /api/v1/admin/login` | Admin mobile auth |
| P0 | `GET /api/v1/forms/{formType}/options` | All registration forms |
| P0 | `POST /api/v1/forms/{type}` + JSON `201` response | Clean form submit UX |
| P1 | `GET /api/v1/blog`, `/causes`, `/about`, `/gallery/moments` | Content screens |
| P1 | `GET /api/v1/admin/dashboard` | Admin dashboard without HTML scrape |
| P2 | `POST /api/v1/admin/logout` | Token revocation |

---

## Appendix A — Feature ↔ PHP route mapping

| Flutter feature | PHP controller | PHP route |
|-----------------|----------------|-----------|
| Contact | `FormSubmissionController@submitContact` | `POST /contact/submit` |
| Donate | `FormSubmissionController@submitDonate` | `POST /donate/submit` |
| Feedback | `FormSubmissionController@submitFeedback` | `POST /feedback-suggestion/submit` |
| Ordinary member | `FormSubmissionController@submitOrdinary` | `POST /membership-ordinary/submit` |
| Friends | `FormSubmissionController@submitFriends` | `POST /membership-friends/submit` |
| Mentor | `FormSubmissionController@submitMentor` | `POST /mentor-registration/submit` |
| Partner | `FormSubmissionController@submitPartner` | `POST /partnership-collaboration/submit` |
| Volunteer | `FormSubmissionController@submitVolunteer` | `POST /volunteer-registration/submit` |
| Community aid | `FormSubmissionController@submitCommunityAid` | `POST /community-aid/submit` |
| Admin detail | `AdminDashboardController@showSubmission` | `GET /admin/submissions/{type}/{id}` |
| Admin status | `AdminDashboardController@updateStatus` | `POST /admin/submissions/{type}/{id}/status` |
| Admin export | `AdminDashboardController@exportCsv` | `GET /admin/export/{type}` |
| Blog | `BlogController` | `GET /blog`, `/blog/{slug}` |
| Causes | `CampaignController` | `GET /causes`, `/causes/{slug}` |

---

## Appendix B — Suggested implementation phases

| Phase | Scope | Screens |
|-------|-------|---------|
| **Phase 1 — MVP** | Content + contact + serve forms | Home, Serve hub, 2–3 forms, Contact, Success |
| **Phase 2 — Full public** | All forms + causes + blog | Remaining forms, Causes, Blog, Donate |
| **Phase 3 — Admin** | Backend v1 API + admin module | Login, Dashboard, Detail, Export |
| **Phase 4 — Polish** | Deep links, offline cache, push notifications | Universal links, Hive cache |

---

*This document is derived from the MUKMIN Laravel application source: `routes/welfare.php`, controllers under `app/Http/Controllers/Welfare/`, models, and migrations.*
