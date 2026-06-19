<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MflsPartnerDocument extends Model
{
    protected $fillable = [
        'partner_id',
        'original_filename',
        'stored_path',
    ];
}
