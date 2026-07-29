<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeMflsOfferLetterFieldsNullable extends Migration
{
    public function up()
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY applied_to_university TINYINT(1) NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY received_offer_letter TINYINT(1) NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY offer_letter VARCHAR(255) NULL');
        }
    }

    public function down()
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY applied_to_university TINYINT(1) NOT NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY received_offer_letter TINYINT(1) NULL');
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY offer_letter VARCHAR(255) NULL');
        }
    }
}
