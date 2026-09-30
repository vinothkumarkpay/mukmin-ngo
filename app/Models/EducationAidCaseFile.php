<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidCaseFile extends Model
{
    public const SOURCE_APPLICATION = 'application';
    public const SOURCE_ADMIN = 'admin';

    protected $fillable = [
        'community_aid_submission_id',
        'document_key',
        'path',
        'display_name',
        'original_name',
        'source',
        'uploaded_by',
    ];

    public function submission()
    {
        return $this->belongsTo(CommunityAidSubmission::class, 'community_aid_submission_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isAdminUpload(): bool
    {
        return $this->source === self::SOURCE_ADMIN;
    }

    public function sourceLabel(): string
    {
        return $this->isAdminUpload() ? 'Uploaded by admin' : 'From application';
    }
}
