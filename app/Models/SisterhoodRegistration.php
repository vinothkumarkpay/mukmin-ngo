<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SisterhoodRegistration extends Model
{
    public const STATUS_PENDING = 0;

    public const STATUS_SUCCESS = 1;

    public const STATUS_FAIL = 2;

    public const STATUS_TEST = 9;

    protected $fillable = [
        'full_name',
        'salutation',
        'preferred_name',
        'nric_passport',
        'email',
        'contact_number',
        'age',
        'profession',
        'profession_other',
        'education_level',
        'education_other',
        'unit_number',
        'address_line_1',
        'address_line_2',
        'postcode',
        'state',
        'areas_of_interest',
        'looking_forward',
        'food_preference',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_relationship',
        'heard_from',
        'heard_from_other',
        'declarations',
        'amount',
        'payment_status',
        'status',
        'payment_method',
        'order_id',
        'payment_reference',
        'payment_payload',
        'payment_attempts',
        'paid_at',
        'admin_notes',
    ];

    protected $casts = [
        'age' => 'integer',
        'amount' => 'decimal:2',
        'areas_of_interest' => 'array',
        'looking_forward' => 'array',
        'declarations' => 'array',
        'payment_payload' => 'array',
        'payment_attempts' => 'array',
        'paid_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (SisterhoodRegistration $registration) {
            if (! filled($registration->check_in_code)) {
                $registration->check_in_code = self::newCheckInCode();
            }
        });

        static::saving(function (SisterhoodRegistration $registration) {
            $registration->nric_normalized = self::normalizeNric((string) $registration->nric_passport);
        });
    }

    public static function newCheckInCode(): string
    {
        do {
            $code = strtoupper(bin2hex(random_bytes(6)));
        } while (self::query()->where('check_in_code', $code)->exists());

        return $code;
    }

    public static function normalizeNric(string $nric): string
    {
        return strtoupper((string) preg_replace('/[\s\-]+/', '', trim($nric)));
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' && $this->paid_at !== null;
    }

    public function isTest(): bool
    {
        return $this->status !== null && (int) $this->status === self::STATUS_TEST;
    }

    public static function statusForPayment(string $paymentStatus): int
    {
        return match ($paymentStatus) {
            'paid' => self::STATUS_SUCCESS,
            'failed' => self::STATUS_FAIL,
            default => self::STATUS_PENDING,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status === null ? null : (int) $this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_FAIL => 'Fail',
            self::STATUS_TEST => 'Test',
            default => '—',
        };
    }

    public function isPresent(): bool
    {
        return $this->attended_at !== null;
    }

    public function attendanceLabel(): string
    {
        return $this->isPresent() ? 'Present' : 'Not present';
    }

    public function professionLabel(): string
    {
        if ($this->profession_other) {
            return $this->profession . ': ' . $this->profession_other;
        }

        return (string) $this->profession;
    }

    public function educationLabel(): string
    {
        if ($this->education_other) {
            return $this->education_level . ': ' . $this->education_other;
        }

        return (string) $this->education_level;
    }

    public function heardFromLabel(): string
    {
        if ($this->heard_from_other) {
            return $this->heard_from . ': ' . $this->heard_from_other;
        }

        return (string) $this->heard_from;
    }

    public function paymentStatusLabel(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Paid',
            'failed' => 'Failed',
            default => 'Pending',
        };
    }

    public function registrationStatusLabel(): string
    {
        return $this->isPaid() ? 'Successful' : 'Incomplete';
    }
}
