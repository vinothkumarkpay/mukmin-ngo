<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerSubmission extends Model
{
    protected $fillable = [
        'full_name', 'nric_passport', 'gender', 'occupation_study', 'organisation',
        'state_residency', 'full_address', 'email', 'contact_number', 'interest_areas',
        'interest_other', 'skills_expertise', 'preferred_mode', 'availability',
        'has_volunteered_before', 'volunteered_before_details', 'emergency_contact_name',
        'emergency_contact_relationship', 'emergency_contact_phone', 'declaration_confirmed'
    ];

    protected $casts = [
        'interest_areas' => 'array',
        'availability' => 'array',
        'has_volunteered_before' => 'boolean',
        'declaration_confirmed' => 'boolean'
    ];
}
