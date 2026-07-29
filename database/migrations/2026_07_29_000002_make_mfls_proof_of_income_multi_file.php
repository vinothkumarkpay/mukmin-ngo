<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeMflsProofOfIncomeMultiFile extends Migration
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
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY proof_of_income TEXT NULL');
        }

        $rows = DB::table('mfls_scholarship_submissions')
            ->whereNotNull('proof_of_income')
            ->where('proof_of_income', '!=', '')
            ->get(['id', 'proof_of_income']);

        foreach ($rows as $row) {
            $value = trim((string) $row->proof_of_income);
            if ($value === '' || strpos($value, '[') === 0) {
                continue;
            }

            DB::table('mfls_scholarship_submissions')
                ->where('id', $row->id)
                ->update([
                    'proof_of_income' => json_encode([$value]),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $rows = DB::table('mfls_scholarship_submissions')
            ->whereNotNull('proof_of_income')
            ->where('proof_of_income', '!=', '')
            ->get(['id', 'proof_of_income']);

        foreach ($rows as $row) {
            $decoded = json_decode((string) $row->proof_of_income, true);
            $path = is_array($decoded) ? (string) ($decoded[0] ?? '') : (string) $row->proof_of_income;

            DB::table('mfls_scholarship_submissions')
                ->where('id', $row->id)
                ->update([
                    'proof_of_income' => $path !== '' ? $path : null,
                ]);
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE mfls_scholarship_submissions MODIFY proof_of_income VARCHAR(255) NULL');
        }
    }
}
