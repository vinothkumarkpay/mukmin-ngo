<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPersonalFieldsToMflsScholarshipSubmissions extends Migration
{
    public function up()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->unsignedTinyInteger('age')->nullable()->after('gender');
            $table->string('citizenship')->nullable()->after('age');
            $table->string('state')->nullable()->after('full_address');
            $table->string('postcode', 10)->nullable()->after('state');
        });
    }

    public function down()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->dropColumn(['age', 'citizenship', 'state', 'postcode']);
        });
    }
}
