<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityAidSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_aid_submissions', function (Blueprint $table) {
            $table->id();
            // Applicant Details
            $table->string('full_name');
            $table->string('nric_passport');
            $table->string('gender'); // Male, Female
            $table->date('dob');
            $table->string('nationality');
            $table->string('occupation');
            $table->string('monthly_income')->nullable();
            $table->string('contact_number');
            $table->string('email');
            $table->text('full_address');
            $table->string('state_residency');

            // Type of Aid Required
            $table->text('type_of_aid'); // JSON stored as text
            $table->string('type_of_aid_other')->nullable();

            // Details of Assistance Required
            $table->text('situation_description');
            $table->string('who_benefits'); // Individual, Family, Community / Group, Organisation / Institution
            $table->string('number_of_beneficiaries')->nullable();

            // Supporting Information
            $table->boolean('received_aid_before');
            $table->text('received_aid_before_details')->nullable();
            $table->text('supporting_documents')->nullable(); // JSON stored as text

            // Emergency Contact
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_phone');

            // Declaration & Consent
            $table->boolean('declaration_confirmed');
            
            // Status
            $table->string('status')->default('pending'); // pending, approved, rejected, under_review

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_aid_submissions');
    }
}
