<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorSubmission extends Model
{
    protected $fillable = [
        'full_name', 'nric_passport', 'gender', 'occupation', 'organisation',
        'position', 'experience_years', 'state_residency', 'full_address', 'email',
        'contact_number', 'linkedin', 'expertise_areas', 'expertise_other',
        'preferred_format', 'preferred_commitment', 'experience_description',
        'has_served_before', 'served_before_details', 'declaration_confirmed'
    ];

    protected $casts = [
        'expertise_areas' => 'array',
        'preferred_format' => 'array',
        'preferred_commitment' => 'array',
        'has_served_before' => 'boolean',
        'declaration_confirmed' => 'boolean'
    ];
}
