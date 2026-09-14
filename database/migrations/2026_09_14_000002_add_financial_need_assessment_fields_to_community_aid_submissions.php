<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinancialNeedAssessmentFieldsToCommunityAidSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_aid_submissions', function (Blueprint $table) {
            $table->text('financial_situation_explanation')->nullable()->after('additional_supporting_documents');
            $table->text('family_education_financing_efforts')->nullable()->after('financial_situation_explanation');
            $table->text('family_financial_commitments')->nullable()->after('family_education_financing_efforts');
            $table->text('university_payment_arrangement_discussed')->nullable()->after('family_financial_commitments');
            $table->text('remaining_balance_funding_plan')->nullable()->after('university_payment_arrangement_discussed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_aid_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'financial_situation_explanation',
                'family_education_financing_efforts',
                'family_financial_commitments',
                'university_payment_arrangement_discussed',
                'remaining_balance_funding_plan',
            ]);
        });
    }
}
