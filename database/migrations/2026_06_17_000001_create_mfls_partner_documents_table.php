<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMflsPartnerDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('mfls_partner_documents', function (Blueprint $table) {
            $table->id();
            $table->string('partner_id')->unique();
            $table->string('original_filename');
            $table->string('stored_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mfls_partner_documents');
    }
}
