<?php

use App\Models\FormDropdownOption;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            ['option_value' => 'Individual', 'sort_order' => 10],
            ['option_value' => 'Non-registered NGO', 'sort_order' => 20],
            ['option_value' => 'Non-registered Surau', 'sort_order' => 30],
            ['option_value' => 'Non-registered Madrasah', 'sort_order' => 40],
            ['option_value' => 'Others', 'sort_order' => 50],
        ];

        FormDropdownOption::where('form_type', 'friends_category')
            ->whereIn('option_value', ['Surau', 'Madrasah'])
            ->delete();

        foreach ($categories as $category) {
            FormDropdownOption::updateOrCreate(
                [
                    'form_type' => 'friends_category',
                    'option_value' => $category['option_value'],
                ],
                ['sort_order' => $category['sort_order']]
            );
        }
    }

    public function down(): void
    {
        FormDropdownOption::where('form_type', 'friends_category')
            ->whereIn('option_value', ['Non-registered Surau', 'Non-registered Madrasah'])
            ->delete();

        foreach ([
            ['option_value' => 'Surau', 'sort_order' => 20],
            ['option_value' => 'Madrasah', 'sort_order' => 30],
        ] as $category) {
            FormDropdownOption::updateOrCreate(
                [
                    'form_type' => 'friends_category',
                    'option_value' => $category['option_value'],
                ],
                ['sort_order' => $category['sort_order']]
            );
        }
    }
};
