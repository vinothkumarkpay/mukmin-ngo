<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSisterhoodCheckIn extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('sisterhood_registrations', 'check_in_code')) {
            return;
        }

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->string('check_in_code', 12)->nullable()->unique()->after('order_id');
            $table->timestamp('attended_at')->nullable()->after('paid_at');
        });

        foreach (DB::table('sisterhood_registrations')->whereNull('check_in_code')->pluck('id') as $id) {
            do {
                $code = strtoupper(bin2hex(random_bytes(6)));
            } while (DB::table('sisterhood_registrations')->where('check_in_code', $code)->exists());

            DB::table('sisterhood_registrations')->where('id', $id)->update([
                'check_in_code' => $code,
            ]);
        }
    }

    public function down()
    {
        if (! Schema::hasColumn('sisterhood_registrations', 'check_in_code')) {
            return;
        }

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->dropColumn(['check_in_code', 'attended_at']);
        });
    }
}
