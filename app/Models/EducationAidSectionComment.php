<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidSectionComment extends Model
{
    protected $fillable = [
        'community_aid_submission_id',
        'section',
        'comment',
        'user_id',
    ];

    /** @return array<string, string> */
    public static function sectionOptions(): array
    {
        return [
            'academic' => 'Academic Assessment',
            'financial' => 'Financial Assessment',
            'education_cost' => 'Education Cost Verification',
            'interview' => 'Interview Assessment',
            'general' => 'General Notes',
        ];
    }

    public function submission()
    {
        return $this->belongsTo(CommunityAidSubmission::class, 'community_aid_submission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
