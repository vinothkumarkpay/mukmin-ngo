<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdinaryMemberSubmission extends Model
{
    protected $fillable = [
        'name_of_organisation', 'org_reg_number', 'org_reg_date', 'registered_state',
        'full_address', 'postcode', 'district_city', 'year_established', 'total_members_size',
        'email', 'contact_number', 'website', 'org_type', 'org_type_other',
        'primary_activities', 'primary_activities_other', 'is_registered_ros',
        'registration_certificate', 'committee_members',
        'key_office_bearers', 'declaration_confirmed', 'status'
    ];

    protected $casts = [
        'org_type' => 'array',
        'primary_activities' => 'array',
        'key_office_bearers' => 'array',
        'is_registered_ros' => 'boolean',
        'declaration_confirmed' => 'boolean',
        'org_reg_date' => 'date'
    ];
}
