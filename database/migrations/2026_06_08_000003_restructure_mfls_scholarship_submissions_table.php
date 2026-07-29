<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RestructureMflsScholarshipSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('mfls_scholarship_submissions');

        Schema::create('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->id();

            // Section 1: Personal Information
            $table->string('email');
            $table->string('full_name');
            $table->string('nric_passport');
            $table->date('dob');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('marital_status_other')->nullable();
            $table->string('contact_number');
            $table->text('full_address');

            // Section 2: Academic Information
            $table->string('current_qualification');
            $table->string('institution_name');
            $table->string('current_cgpa_result');
            $table->string('academic_transcript')->nullable();
            $table->string('programme_course_applied');
            $table->boolean('applied_to_university')->nullable();
            $table->boolean('received_offer_letter')->nullable();
            $table->string('offer_letter')->nullable();

            // Section 3: Financial Background
            $table->string('household_income');
            $table->string('father_guardian_name');
            $table->string('father_guardian_occupation');
            $table->string('mother_guardian_name');
            $table->string('mother_guardian_occupation');
            $table->string('proof_of_income')->nullable();
            $table->unsignedTinyInteger('number_of_dependents');
            $table->text('other_scholarship_details');

            // Section 4: Leadership & Involvement
                    $table->text('leadership_roles')->nullable();
                    $table->string('involvement_level')->nullable();
                    $table->text('community_service_involvement')->nullable();
                    $table->text('community_contribution')->nullable();

            // Section 5: Personal Statement
            $table->text('leadership_experience_statement');
            $table->text('scholar_selection_statement');

            // Section 6: Supporting Documents
            $table->string('recommendation_letter')->nullable();
            $table->text('relevant_certificates')->nullable();

            // Section 7: Declaration
            $table->boolean('declaration_confirmed');
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mfls_scholarship_submissions');
    }
}
