<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidPayment extends Model
{
    protected $fillable = [
        'community_aid_submission_id',
        'payment_date',
        'amount',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function submission()
    {
        return $this->belongsTo(CommunityAidSubmission::class, 'community_aid_submission_id');
    }

    public function receipts()
    {
        return $this->hasMany(EducationAidPaymentReceipt::class, 'education_aid_payment_id')->orderBy('id');
    }
}
