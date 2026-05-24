<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormDropdownOption extends Model
{
    protected $fillable = ['form_type', 'option_value', 'sort_order'];
}
