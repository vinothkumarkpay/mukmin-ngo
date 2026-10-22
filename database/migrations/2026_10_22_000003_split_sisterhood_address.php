<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SplitSisterhoodAddress extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('sisterhood_registrations', 'full_address')) {
            return;
        }

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->string('unit_number', 50)->default('')->after('education_other');
            $table->string('address_line_1')->default('')->after('unit_number');
            $table->string('address_line_2')->nullable()->after('address_line_1');
            $table->string('postcode', 10)->default('')->after('address_line_2');
        });

        DB::table('sisterhood_registrations')->update([
            'address_line_1' => DB::raw('full_address'),
        ]);

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->dropColumn('full_address');
        });
    }

    public function down()
    {
        if (! Schema::hasColumn('sisterhood_registrations', 'address_line_1')) {
            return;
        }

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->text('full_address')->nullable()->after('education_other');
        });

        DB::table('sisterhood_registrations')->update([
            'full_address' => DB::raw("TRIM(CONCAT(unit_number, ' ', address_line_1, ' ', IFNULL(address_line_2, ''), ' ', postcode))"),
        ]);

        Schema::table('sisterhood_registrations', function (Blueprint $table) {
            $table->dropColumn(['unit_number', 'address_line_1', 'address_line_2', 'postcode']);
        });
    }
}
