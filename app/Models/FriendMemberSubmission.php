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
        'ind_email', 'ind_phone', 'ind_area_of_interest', 'declaration_confirmed', 'status',
        'membership_number', 'membership_start_date', 'membership_end_date', 'approved_at',
        'welcome_email_sent_at',
    ];

    protected $casts = [
        'declaration_confirmed' => 'boolean',
        'membership_number' => 'integer',
        'membership_start_date' => 'date',
        'membership_end_date' => 'date',
        'approved_at' => 'datetime',
        'welcome_email_sent_at' => 'datetime',
    ];

    public function isIndividual(): bool
    {
        return $this->entity_type === 'Individual';
    }

    public function getFormattedMembershipNumberAttribute(): ?string
    {
        if (! $this->membership_number) {
            return null;
        }

        return config('welfare.friends_membership.number_prefix', 'MUKMIN-')
            . str_pad((string) $this->membership_number, 4, '0', STR_PAD_LEFT);
    }

    public function getMemberNameAttribute(): string
    {
        if ($this->isIndividual()) {
            return trim(($this->ind_salutation ? $this->ind_salutation . ' ' : '') . (string) $this->ind_name);
        }

        return trim((string) ($this->org_name ?: $this->org_contact_person_name));
    }

    public function getGreetingNameAttribute(): string
    {
        if ($this->isIndividual()) {
            return trim((string) $this->ind_name) ?: 'Member';
        }

        return trim((string) ($this->org_contact_person_name ?: $this->org_name)) ?: 'Member';
    }

    public function getMemberEmailAttribute(): ?string
    {
        $email = $this->isIndividual()
            ? ($this->ind_email ?: $this->org_email)
            : ($this->org_email ?: $this->ind_email);

        return $email ?: null;
    }
}
