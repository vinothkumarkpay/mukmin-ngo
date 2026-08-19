<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearOfCompletionToMflsScholarshipSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->unsignedSmallInteger('year_of_completion')->nullable()->after('institution_name');
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
            $table->dropColumn('year_of_completion');
        });
    }
}
