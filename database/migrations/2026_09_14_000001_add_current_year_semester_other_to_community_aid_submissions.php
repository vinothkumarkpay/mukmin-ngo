<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentYearSemesterOtherToCommunityAidSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_aid_submissions', function (Blueprint $table) {
            $table->string('current_year_semester_other')->nullable()->after('current_year_semester');
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
            $table->dropColumn('current_year_semester_other');
        });
    }
}
