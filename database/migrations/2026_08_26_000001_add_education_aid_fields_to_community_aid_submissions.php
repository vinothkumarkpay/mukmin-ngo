<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEducationAidFieldsToCommunityAidSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_aid_submissions', function (Blueprint $table) {
            // Section 1: Education Information
            $table->string('university_institution')->nullable()->after('type_of_aid_other');
            $table->string('programme_name')->nullable()->after('university_institution');
            $table->string('programme_level')->nullable()->after('programme_name');
            $table->string('faculty_school')->nullable()->after('programme_level');
            $table->string('current_year_semester')->nullable()->after('faculty_school');
            $table->date('intake_date')->nullable()->after('current_year_semester');
            $table->date('expected_graduation_date')->nullable()->after('intake_date');
            $table->string('current_cgpa_result')->nullable()->after('expected_graduation_date');
            $table->string('student_id')->nullable()->after('current_cgpa_result');
            $table->string('current_student_status')->nullable()->after('student_id');

            // Section 2: Education Cost & Aid Request
            $table->text('education_expense_types')->nullable()->after('current_student_status');
            $table->string('education_expense_other')->nullable()->after('education_expense_types');
            $table->decimal('total_programme_tuition_fees', 12, 2)->nullable()->after('education_expense_other');
            $table->decimal('total_amount_already_paid', 12, 2)->nullable()->after('total_programme_tuition_fees');
            $table->decimal('current_outstanding_amount', 12, 2)->nullable()->after('total_amount_already_paid');
            $table->decimal('amount_due_immediately', 12, 2)->nullable()->after('current_outstanding_amount');
            $table->decimal('amount_requested_from_mukmin', 12, 2)->nullable()->after('amount_due_immediately');
            $table->date('payment_deadline')->nullable()->after('amount_requested_from_mukmin');
            $table->text('purpose_of_request')->nullable()->after('payment_deadline');
            $table->text('payment_not_made_consequence')->nullable()->after('purpose_of_request');

            // Section 3: Socioeconomic Background (mirrored from MFLS)
            $table->string('household_income')->nullable()->after('payment_not_made_consequence');
            $table->string('father_guardian_name')->nullable()->after('household_income');
            $table->string('father_guardian_occupation')->nullable()->after('father_guardian_name');
            $table->string('mother_guardian_name')->nullable()->after('father_guardian_occupation');
            $table->string('mother_guardian_occupation')->nullable()->after('mother_guardian_name');
            $table->text('proof_of_income')->nullable()->after('mother_guardian_occupation');
            $table->string('government_assistance_status')->nullable()->after('proof_of_income');
            $table->string('proof_of_government_assistance')->nullable()->after('government_assistance_status');
            $table->unsignedTinyInteger('number_of_dependents')->nullable()->after('proof_of_government_assistance');
            $table->text('sibling_information')->nullable()->after('number_of_dependents');
            $table->text('other_scholarship_details')->nullable()->after('sibling_information');

            // Section 4: Document Upload
            $table->string('nric_front')->nullable()->after('other_scholarship_details');
            $table->string('nric_back')->nullable()->after('nric_front');
            $table->string('academic_result')->nullable()->after('nric_back');
            $table->string('latest_academic_transcript')->nullable()->after('academic_result');
            $table->string('university_offer_letter')->nullable()->after('latest_academic_transcript');
            $table->string('student_id_confirmation')->nullable()->after('university_offer_letter');
            $table->string('university_fee_statement')->nullable()->after('student_id_confirmation');
            $table->string('official_invoice')->nullable()->after('university_fee_statement');
            $table->string('outstanding_balance_statement')->nullable()->after('official_invoice');
            $table->string('payment_deadline_notice')->nullable()->after('outstanding_balance_statement');
            $table->text('additional_supporting_documents')->nullable()->after('payment_deadline_notice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_aid_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'university_institution',
                'programme_name',
                'programme_level',
                'faculty_school',
                'current_year_semester',
                'intake_date',
                'expected_graduation_date',
                'current_cgpa_result',
                'student_id',
                'current_student_status',
                'education_expense_types',
                'education_expense_other',
                'total_programme_tuition_fees',
                'total_amount_already_paid',
                'current_outstanding_amount',
                'amount_due_immediately',
                'amount_requested_from_mukmin',
                'payment_deadline',
                'purpose_of_request',
                'payment_not_made_consequence',
                'household_income',
                'father_guardian_name',
                'father_guardian_occupation',
                'mother_guardian_name',
                'mother_guardian_occupation',
                'proof_of_income',
                'government_assistance_status',
                'proof_of_government_assistance',
                'number_of_dependents',
                'sibling_information',
                'other_scholarship_details',
                'nric_front',
                'nric_back',
                'academic_result',
                'latest_academic_transcript',
                'university_offer_letter',
                'student_id_confirmation',
                'university_fee_statement',
                'official_invoice',
                'outstanding_balance_statement',
                'payment_deadline_notice',
                'additional_supporting_documents',
            ]);
        });
    }
}
