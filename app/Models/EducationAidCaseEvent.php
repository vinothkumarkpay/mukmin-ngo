<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidCaseEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'community_aid_submission_id',
        'event_type',
        'description',
        'meta',
        'user_id',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(CommunityAidSubmission::class, 'community_aid_submission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
