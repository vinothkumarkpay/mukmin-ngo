<?php

namespace App\Support;

use App\Models\SisterhoodRegistration;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class SisterhoodCollective
{
    public const FEE = 30.00;

    public const CAPACITY = 155;

    public const SUPPORT_PHONE = '014-3021800';

    public const EVENT_DATE = 'Sunday, 22 November 2026';

    public const EVENT_TIME = '9.30 AM – 5.00 PM';

    public const EVENT_VENUE = 'Asiana Grand Hall 2, Kuala Lumpur';

    public const EVENT_DRESS = 'Burgundy & Olive Green';

    public const ORGANISER = 'MUKMIN Women & Family Development Bureau';

    public const PROFESSION_OTHER = 'Other (Please Specify)';

    public const EDUCATION_OTHER = 'Other';

    public const HEARD_OTHER = 'Other';

    public static function closesAt(): CarbonInterface
    {
        return Carbon::parse('2026-11-17 23:59:59', 'Asia/Kuala_Lumpur');
    }

    public static function isOpen(): bool
    {
        return now()->lt(self::closesAt());
    }

    public static function placesTaken(): int
    {
        return SisterhoodRegistration::query()
            ->where('status', SisterhoodRegistration::STATUS_SUCCESS)
            ->count();
    }

    public static function isFull(): bool
    {
        return self::placesTaken() >= self::CAPACITY;
    }

    public static function fullMessage(): string
    {
        return 'Registration is full. All ' . self::CAPACITY . ' places have been taken.';
    }

    public static function newOrderId(): string
    {
        return 'TSC-' . strtoupper(uniqid());
    }

    public static function salutations(): array
    {
        return [
            'Tan Sri',
            'Puan Sri',
            'Datuk Seri',
            "Dato' Seri",
            'Datin Deri',
            "Dato' Sri",
            'Datin Sri',
            'Datuk',
            'Datin',
            "Dato'",
            'Professor',
            'Dr.',
            'Mrs.',
            'Ms.',
        ];
    }

    public static function professions(): array
    {
        return [
            'Student',
            'Academic / Educator',
            'Doctor / Medical Professional',
            'Lawyer / Legal Professional',
            'Accountant / Finance Professional',
            'Engineer / Technical Professional',
            'IT / Technology Professional',
            'Business Owner / Entrepreneur',
            'Corporate Executive / Management',
            'Civil Servant / Government Officer',
            'NGO / Community Leader',
            'Shariah / Islamic Affairs',
            'Creative / Media / Communications',
            'Self-Employed / Freelancer',
            'Unemployed',
            'Retired',
            'Homemaker',
            self::PROFESSION_OTHER,
        ];
    }

    public static function educationLevels(): array
    {
        return [
            'No Formal Education',
            'Primary School',
            'Secondary School',
            'Certificate',
            'Diploma',
            "Bachelor's Degree",
            "Master's Degree",
            'Doctoral Degree (PhD)',
            self::EDUCATION_OTHER,
        ];
    }

    public static function states(): array
    {
        return [
            'Johor',
            'Kedah',
            'Kelantan',
            'Melaka',
            'Negeri Sembilan',
            'Pahang',
            'Perak',
            'Perlis',
            'Pulau Pinang',
            'Sabah',
            'Sarawak',
            'Selangor',
            'Terengganu',
            'Wilayah Persekutuan Kuala Lumpur',
            'Wilayah Persekutuan Labuan',
            'Wilayah Persekutuan Putrajaya',
            'Outside Malaysia',
        ];
    }

    public static function areasOfInterest(): array
    {
        return [
            'Economic Empowerment',
            'Education and Talent Development',
            'Leadership and Representation',
            'Community Welfare',
            'Faith, Identity and Ukhwah',
        ];
    }

    public static function lookingForward(): array
    {
        return [
            'Meeting new friends and building meaningful connections',
            'Learning from inspiring women and their experiences',
            'Discovering opportunities for personal growth',
            'Exploring ways to contribute to the community',
            'All of the above',
        ];
    }

    public static function foodPreferences(): array
    {
        return ['Vegetarian', 'Non-Vegetarian'];
    }

    public static function heardFrom(): array
    {
        return [
            'WhatsApp',
            'Facebook',
            'Instagram / Tik Tok',
            'Friends or Family',
            'MUKMIN representative',
            self::HEARD_OTHER,
        ];
    }

    /** @return array<string, string> */
    public static function declarations(): array
    {
        return [
            'accurate' => 'I confirm that the information provided is accurate.',
            'updates' => 'I agree to receive event-related updates from MUKMIN.',
            'information_use' => 'I understand that my information will be used for registration, event coordination and participant grouping.',
            'policy' => 'I have read and understood the registration and cancellation policy.',
            'media' => 'I consent to the use of photos and videos taken during the event for social media and promotional purposes.',
        ];
    }

    public static function hasPaidRegistration(string $nric, ?int $ignoreId = null): bool
    {
        $normalized = SisterhoodRegistration::normalizeNric($nric);

        if ($normalized === '') {
            return false;
        }

        return SisterhoodRegistration::query()
            ->where('nric_normalized', $normalized)
            ->where('status', SisterhoodRegistration::STATUS_SUCCESS)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists();
    }

    public static function rules(): array
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'salutation' => ['required', Rule::in(self::salutations())],
            'preferred_name' => ['required', 'string', 'max:255'],
            'nric_passport' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'contact_number' => ['required', 'string', 'max:30'],
            'age' => ['required', 'integer', 'min:18', 'max:120'],
            'profession' => ['required', Rule::in(self::professions())],
            'profession_other' => ['nullable', 'string', 'max:255', 'required_if:profession,' . self::PROFESSION_OTHER],
            'education_level' => ['required', Rule::in(self::educationLevels())],
            'education_other' => ['nullable', 'string', 'max:255', 'required_if:education_level,' . self::EDUCATION_OTHER],
            'unit_number' => ['required', 'string', 'max:50'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:10'],
            'state' => ['required', Rule::in(self::states())],
            'areas_of_interest' => ['required', 'array', 'min:1'],
            'areas_of_interest.*' => ['required', Rule::in(self::areasOfInterest())],
            'looking_forward' => ['required', 'array', 'min:1'],
            'looking_forward.*' => ['required', Rule::in(self::lookingForward())],
            'food_preference' => ['required', Rule::in(self::foodPreferences())],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_number' => ['required', 'string', 'max:30'],
            'emergency_relationship' => ['required', 'string', 'max:255'],
            'heard_from' => ['required', Rule::in(self::heardFrom())],
            'heard_from_other' => ['nullable', 'string', 'max:255', 'required_if:heard_from,' . self::HEARD_OTHER],
        ];

        foreach (array_keys(self::declarations()) as $key) {
            $rules['declaration_' . $key] = ['accepted'];
        }

        return $rules;
    }

    public static function validationMessages(): array
    {
        $messages = [];

        foreach (array_keys(self::declarations()) as $key) {
            $messages['declaration_' . $key . '.accepted'] = 'Please mark every participation declaration.';
        }

        return $messages;
    }

    public static function capitaliseInput(array $input): array
    {
        foreach ([
            'full_name',
            'preferred_name',
            'nric_passport',
            'profession_other',
            'education_other',
            'unit_number',
            'address_line_1',
            'address_line_2',
            'postcode',
            'emergency_contact_name',
            'emergency_relationship',
            'heard_from_other',
        ] as $key) {
            if (isset($input[$key]) && is_string($input[$key])) {
                $input[$key] = self::capitalise($input[$key]);
            }
        }

        if (isset($input['email']) && is_string($input['email'])) {
            $input['email'] = trim($input['email']);
        }

        return $input;
    }

    public static function capitalise(?string $value): string
    {
        return mb_strtoupper(trim((string) $value), 'UTF-8');
    }

    public static function adminRules(): array
    {
        return array_merge(self::rules(), [
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failed'])],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    public static function attributeNames(): array
    {
        $names = [
            'full_name' => 'full name',
            'salutation' => 'salutation',
            'preferred_name' => 'preferred name',
            'nric_passport' => 'NRIC / passport number',
            'email' => 'email address',
            'contact_number' => 'contact number',
            'age' => 'age',
            'profession' => 'profession / occupation',
            'profession_other' => 'profession details',
            'education_level' => 'education level',
            'education_other' => 'education details',
            'unit_number' => 'unit number',
            'address_line_1' => 'address line 1',
            'address_line_2' => 'address line 2',
            'postcode' => 'postcode',
            'state' => 'state',
            'areas_of_interest' => 'area of interest',
            'looking_forward' => 'what you are looking forward to',
            'food_preference' => 'food preference',
            'emergency_contact_name' => 'emergency contact name',
            'emergency_contact_number' => 'emergency contact number',
            'emergency_relationship' => 'relationship to participant',
            'heard_from' => 'how you heard about this programme',
            'heard_from_other' => 'how you heard about this programme',
            'payment_status' => 'payment status',
            'payment_reference' => 'payment reference',
            'admin_notes' => 'admin notes',
        ];

        foreach (self::declarations() as $key => $label) {
            $names['declaration_' . $key] = $label;
        }

        return $names;
    }

    public static function attributesFrom(array $input): array
    {
        $profession = (string) ($input['profession'] ?? '');
        $education = (string) ($input['education_level'] ?? '');
        $heard = (string) ($input['heard_from'] ?? '');

        return [
            'full_name' => self::capitalise($input['full_name'] ?? ''),
            'salutation' => (string) ($input['salutation'] ?? ''),
            'preferred_name' => self::capitalise($input['preferred_name'] ?? ''),
            'nric_passport' => self::capitalise($input['nric_passport'] ?? ''),
            'email' => trim((string) ($input['email'] ?? '')),
            'contact_number' => trim((string) ($input['contact_number'] ?? '')),
            'age' => (int) ($input['age'] ?? 0),
            'profession' => $profession,
            'profession_other' => $profession === self::PROFESSION_OTHER ? self::capitalise($input['profession_other'] ?? '') : null,
            'education_level' => $education,
            'education_other' => $education === self::EDUCATION_OTHER ? self::capitalise($input['education_other'] ?? '') : null,
            'unit_number' => self::capitalise($input['unit_number'] ?? ''),
            'address_line_1' => self::capitalise($input['address_line_1'] ?? ''),
            'address_line_2' => self::capitalise($input['address_line_2'] ?? '') ?: null,
            'postcode' => self::capitalise($input['postcode'] ?? ''),
            'state' => (string) ($input['state'] ?? ''),
            'areas_of_interest' => array_values($input['areas_of_interest'] ?? []),
            'looking_forward' => array_values($input['looking_forward'] ?? []),
            'food_preference' => (string) ($input['food_preference'] ?? ''),
            'emergency_contact_name' => self::capitalise($input['emergency_contact_name'] ?? ''),
            'emergency_contact_number' => trim((string) ($input['emergency_contact_number'] ?? '')),
            'emergency_relationship' => self::capitalise($input['emergency_relationship'] ?? ''),
            'heard_from' => $heard,
            'heard_from_other' => $heard === self::HEARD_OTHER ? self::capitalise($input['heard_from_other'] ?? '') : null,
            'declarations' => array_values(self::declarations()),
        ];
    }
}
