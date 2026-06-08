<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->string('ind_area_of_interest')->nullable()->after('ind_phone');
        });
    }

    public function down(): void
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->dropColumn('ind_area_of_interest');
        });
    }
};
