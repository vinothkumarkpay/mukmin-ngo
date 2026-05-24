<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerSubmission extends Model
{
    protected $fillable = [
        'company_name', 'contact_person', 'position', 'org_reg_number', 'email',
        'contact_number', 'office_address', 'state_country', 'org_type',
        'org_type_other', 'collaboration_areas', 'collaboration_other',
        'partnership_type', 'partnership_other', 'proposal_description',
        'expected_outcomes', 'has_collaborated_before', 'collaborated_before_details',
        'supporting_documents', 'declaration_confirmed', 'status'
    ];

    protected $casts = [
        'org_type' => 'array',
        'collaboration_areas' => 'array',
        'partnership_type' => 'array',
        'supporting_documents' => 'array',
        'has_collaborated_before' => 'boolean',
        'declaration_confirmed' => 'boolean'
    ];
}
