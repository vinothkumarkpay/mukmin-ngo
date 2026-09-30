<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationAidCaseFilesTable extends Migration
{
    public function up()
    {
        Schema::create('education_aid_case_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_aid_submission_id');
            $table->string('document_key', 100);
            $table->string('path', 500);
            $table->string('display_name', 255);
            $table->string('original_name', 255)->nullable();
            $table->string('source', 20)->default('application');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index(['community_aid_submission_id', 'document_key'], 'ea_case_files_doc_idx');
            $table->foreign('community_aid_submission_id', 'ea_case_files_submission_fk')
                ->references('id')->on('community_aid_submissions')->onDelete('cascade');
            $table->foreign('uploaded_by', 'ea_case_files_uploaded_by_fk')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_aid_case_files');
    }
}
