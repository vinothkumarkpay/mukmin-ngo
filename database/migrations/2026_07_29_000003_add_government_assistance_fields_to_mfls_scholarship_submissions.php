<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGovernmentAssistanceFieldsToMflsScholarshipSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->string('government_assistance_status')->nullable()->after('proof_of_income');
            $table->string('proof_of_government_assistance')->nullable()->after('government_assistance_status');
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
            $table->dropColumn([
                'government_assistance_status',
                'proof_of_government_assistance',
            ]);
        });
    }
}
