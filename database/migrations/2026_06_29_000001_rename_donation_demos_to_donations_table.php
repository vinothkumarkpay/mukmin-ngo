<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenameDonationDemosToDonationsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('donation_demos') && ! Schema::hasTable('donations')) {
            Schema::rename('donation_demos', 'donations');
        }
    }

    public function down()
    {
        if (Schema::hasTable('donations') && ! Schema::hasTable('donation_demos')) {
            Schema::rename('donations', 'donation_demos');
        }
    }
}
