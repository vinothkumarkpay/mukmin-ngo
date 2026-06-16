<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationDemosTable extends Migration
{
    public function up()
    {
        Schema::create('donation_demos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30);
            $table->decimal('amount', 10, 2);
            $table->text('message')->nullable();
            $table->string('payment_method')->default('KiplePay');
            $table->string('status')->default('pending');
            $table->string('order_id')->nullable()->unique();
            $table->json('payment_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_demos');
    }
}
