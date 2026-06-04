<?php

use App\Models\FormDropdownOption;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        FormDropdownOption::firstOrCreate(
            ['form_type' => 'friends_category', 'option_value' => 'Non-registered NGO'],
            ['sort_order' => 30]
        );

        FormDropdownOption::where('form_type', 'friends_category')
            ->where('option_value', 'Others')
            ->update(['sort_order' => 40]);
    }

    public function down(): void
    {
        FormDropdownOption::where('form_type', 'friends_category')
            ->where('option_value', 'Non-registered NGO')
            ->delete();

        FormDropdownOption::where('form_type', 'friends_category')
            ->where('option_value', 'Others')
            ->update(['sort_order' => 30]);
    }
};
