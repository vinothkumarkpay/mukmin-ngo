<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackSubmission extends Model
{
    protected $fillable = [
        'full_name', 'nric_number', 'organisation', 'position',
        'state_residency', 'full_address', 'email', 'contact_number',
        'categories', 'other_category', 'suggestion_description',
        'benefits_description', 'contact_consent', 'preferred_contact_methods',
        'declaration_confirmed'
    ];

    protected $casts = [
        'categories' => 'array',
        'preferred_contact_methods' => 'array',
        'contact_consent' => 'boolean',
        'declaration_confirmed' => 'boolean'
    ];
}
