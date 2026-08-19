<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiblingInformationToMflsScholarshipSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->json('sibling_information')->nullable()->after('number_of_dependents');
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
            $table->dropColumn('sibling_information');
        });
    }
}
