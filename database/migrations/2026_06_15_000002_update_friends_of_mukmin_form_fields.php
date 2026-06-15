<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->string('ind_salutation')->nullable()->after('ind_name');
            $table->string('ind_postcode', 10)->nullable()->after('ind_address');
            $table->string('org_postcode', 10)->nullable()->after('org_address');
            $table->string('org_contact_person_salutation')->nullable()->after('org_contact_person_name');
            $table->string('org_contact_person_nric', 20)->nullable()->after('org_contact_person_salutation');
        });
    }

    public function down(): void
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'ind_salutation',
                'ind_postcode',
                'org_postcode',
                'org_contact_person_salutation',
                'org_contact_person_nric',
            ]);
        });
    }
};
