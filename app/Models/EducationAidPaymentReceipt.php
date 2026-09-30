<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidPaymentReceipt extends Model
{
    protected $fillable = [
        'education_aid_payment_id',
        'path',
        'display_name',
        'uploaded_by',
    ];

    public function payment()
    {
        return $this->belongsTo(EducationAidPayment::class, 'education_aid_payment_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
