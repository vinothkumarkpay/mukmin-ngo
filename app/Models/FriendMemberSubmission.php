<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendMemberSubmission extends Model
{
    protected $fillable = [
        'entity_type', 'others_specify', 'org_name', 'org_state', 'org_address',
        'org_email', 'org_phone', 'org_website', 'ind_name', 'ind_nric', 'ind_state',
        'ind_address', 'ind_email', 'ind_phone', 'declaration_confirmed', 'status'
    ];

    protected $casts = [
        'declaration_confirmed' => 'boolean'
    ];
}
