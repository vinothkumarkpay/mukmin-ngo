<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityAidSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'nric_passport',
        'gender',
        'dob',
        'nationality',
        'occupation',
        'monthly_income',
        'contact_number',
        'email',
        'full_address',
        'state_residency',
        'type_of_aid',
        'type_of_aid_other',
        'situation_description',
        'who_benefits',
        'number_of_beneficiaries',
        'received_aid_before',
        'received_aid_before_details',
        'supporting_documents',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'declaration_confirmed',
        'status',
    ];

    protected $casts = [
        'type_of_aid' => 'array',
        'supporting_documents' => 'array',
        'received_aid_before' => 'boolean',
        'declaration_confirmed' => 'boolean',
        'dob' => 'date',
    ];
}
