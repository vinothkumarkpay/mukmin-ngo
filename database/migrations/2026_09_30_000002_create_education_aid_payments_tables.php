<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationAidPaymentsTables extends Migration
{
    public function up()
    {
        Schema::create('education_aid_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_aid_submission_id');
            $table->date('payment_date');
            $table->decimal('amount', 12, 2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('community_aid_submission_id', 'ea_payments_submission_idx');
            $table->foreign('community_aid_submission_id', 'ea_payments_submission_fk')
                ->references('id')->on('community_aid_submissions')->onDelete('cascade');
            $table->foreign('created_by', 'ea_payments_created_by_fk')
                ->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('education_aid_payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('education_aid_payment_id');
            $table->string('path', 500);
            $table->string('display_name', 255);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('education_aid_payment_id', 'ea_receipts_payment_fk')
                ->references('id')->on('education_aid_payments')->onDelete('cascade');
            $table->foreign('uploaded_by', 'ea_receipts_uploaded_by_fk')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_aid_payment_receipts');
        Schema::dropIfExists('education_aid_payments');
    }
}
