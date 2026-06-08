<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMflsScholarshipSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('mfls_scholarship_submissions', function (Blueprint $table) {
            $table->id();

            $table->string('full_name');
            $table->string('nric_passport');
            $table->string('gender');
            $table->date('dob');
            $table->string('nationality');
            $table->string('email');
            $table->string('contact_number');
            $table->text('full_address');
            $table->string('state_residency');

            $table->string('preferred_institution');
            $table->string('programme_of_interest');
            $table->string('qualification_level');
            $table->string('intake_year');

            $table->string('highest_qualification');
            $table->string('institution_school');
            $table->string('year_completed_or_expected');
            $table->text('academic_results');
            $table->boolean('currently_studying');
            $table->string('current_institution')->nullable();

            $table->string('household_monthly_income')->nullable();
            $table->unsignedTinyInteger('number_of_dependents')->nullable();
            $table->boolean('receiving_other_scholarship');
            $table->text('other_scholarship_details')->nullable();

            $table->string('parent_guardian_name');
            $table->string('parent_guardian_relationship');
            $table->string('parent_guardian_phone');
            $table->string('parent_guardian_occupation')->nullable();

            $table->text('motivation_statement');

            $table->text('supporting_documents')->nullable();

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
