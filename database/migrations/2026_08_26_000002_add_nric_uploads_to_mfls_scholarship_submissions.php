<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNricUploadsToMflsScholarshipSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->string('nric_front')->nullable()->after('nric_passport');
            $table->string('nric_back')->nullable()->after('nric_front');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->dropColumn(['nric_front', 'nric_back']);
        });
    }
}
