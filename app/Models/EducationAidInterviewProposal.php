<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidInterviewProposal extends Model
{
    protected $fillable = [
        'community_aid_submission_id',
        'proposed_dates',
        'status',
        'confirmed_at',
        'confirmed_date',
        'created_by',
        'email_sent_at',
    ];

    protected $casts = [
        'proposed_dates' => 'array',
        'confirmed_at' => 'datetime',
        'confirmed_date' => 'date',
        'email_sent_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(CommunityAidSubmission::class, 'community_aid_submission_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
