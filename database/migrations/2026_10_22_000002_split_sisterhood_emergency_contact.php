<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SplitSisterhoodEmergencyContact extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('sisterhood_registrations', 'emergency_contact')) {
            return;
        }

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->string('emergency_contact_name')->default('')->after('food_preference');
            $table->string('emergency_contact_number', 30)->default('')->after('emergency_contact_name');
        });

        DB::table('sisterhood_registrations')->update([
            'emergency_contact_name' => DB::raw('emergency_contact'),
        ]);

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->dropColumn('emergency_contact');
        });
    }

    public function down()
    {
        if (! Schema::hasColumn('sisterhood_registrations', 'emergency_contact_name')) {
            return;
        }

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->string('emergency_contact')->default('')->after('food_preference');
        });

        DB::table('sisterhood_registrations')->update([
            'emergency_contact' => DB::raw("TRIM(CONCAT(emergency_contact_name, ' ', emergency_contact_number))"),
        ]);

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->dropColumn(['emergency_contact_name', 'emergency_contact_number']);
        });
    }
}
