<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentStudentStatusOtherToCommunityAidSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_aid_submissions', function (Blueprint $table) {
            $table->string('current_student_status_other')->nullable()->after('current_student_status');
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
            $table->dropColumn('current_student_status_other');
        });
    }
}
