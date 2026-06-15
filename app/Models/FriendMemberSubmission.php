<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendMemberSubmission extends Model
{
    protected $fillable = [
        'entity_type', 'others_specify', 'org_name', 'org_state', 'org_address', 'org_postcode',
        'org_email', 'org_phone', 'org_contact_person_salutation', 'org_contact_person_name',
        'org_contact_person_nric', 'org_website', 'ind_salutation', 'ind_name', 'ind_nric', 'ind_state',
        'ind_postcode', 'ind_profession', 'ind_profession_other', 'ind_address',
        'ind_email', 'ind_phone', 'ind_area_of_interest', 'declaration_confirmed', 'status'
    ];

    protected $casts = [
        'declaration_confirmed' => 'boolean'
    ];
}
