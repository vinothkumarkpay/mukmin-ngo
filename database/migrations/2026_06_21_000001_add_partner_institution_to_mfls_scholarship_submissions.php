<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartnerInstitutionToMflsScholarshipSubmissions extends Migration
{
    public function up()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->string('partner_institution_id')->nullable()->after('full_address');
            $table->string('partner_institution_name')->nullable()->after('partner_institution_id');
        });
    }

    public function down()
    {
        Schema::table('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->dropColumn(['partner_institution_id', 'partner_institution_name']);
        });
    }
}
