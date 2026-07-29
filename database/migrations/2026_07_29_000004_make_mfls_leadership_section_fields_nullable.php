<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeMflsLeadershipSectionFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY leadership_roles TEXT NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY involvement_level VARCHAR(255) NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY community_service_involvement TEXT NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY community_contribution TEXT NULL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY leadership_roles TEXT NOT NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY involvement_level VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY community_service_involvement TEXT NOT NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY community_contribution TEXT NOT NULL');
        }
    }
}
