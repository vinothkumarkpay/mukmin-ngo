<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSisterhoodRegistrationStatus extends Migration
{
    public function up()
    {
        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('sisterhood_registrations', 'status')) {
                $table->unsignedTinyInteger('status')->nullable()->default(null)->after('payment_status')->index();
            }
        });

        DB::table('sisterhood_registrations')->update(['status' => 9]);
    }

    public function down()
    {
        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('sisterhood_registrations', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}
