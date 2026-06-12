<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->string('org_contact_person_name')->nullable()->after('org_phone');
            $table->string('ind_profession')->nullable()->after('ind_state');
            $table->string('ind_profession_other')->nullable()->after('ind_profession');
        });
    }

    public function down(): void
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->dropColumn(['org_contact_person_name', 'ind_profession', 'ind_profession_other']);
        });
    }
};
