<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SisterhoodRegistration extends Model
{
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
    ];

    protected static function booted(): void
    {
        static::saving(function (SisterhoodRegistration $registration) {
            $registration->nric_normalized = self::normalizeNric((string) $registration->nric_passport);
        });
    }

    public static function normalizeNric(string $nric): string
    {
        return strtoupper((string) preg_replace('/[\s\-]+/', '', trim($nric)));
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' && $this->paid_at !== null;
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
