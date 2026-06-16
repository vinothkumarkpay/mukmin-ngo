<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationDemo extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'amount',
        'message',
        'payment_method',
        'status',
        'order_id',
        'payment_payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_payload' => 'array',
    ];
}
