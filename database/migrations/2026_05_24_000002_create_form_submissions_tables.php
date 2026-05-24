<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSubmissionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. feedback_submissions
        Schema::create('feedback_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nric_number');
            $table->string('organisation')->nullable();
            $table->string('position')->nullable();
            $table->string('state_residency');
            $table->text('full_address');
            $table->string('email');
            $table->string('contact_number');
            $table->text('categories'); // JSON stored as text
            $table->string('other_category')->nullable();
            $table->text('suggestion_description');
            $table->text('benefits_description');
            $table->boolean('contact_consent');
            $table->text('preferred_contact_methods')->nullable(); // JSON stored as text
            $table->boolean('declaration_confirmed');
            $table->timestamps();
        });

        // 2. ordinary_member_submissions
        Schema::create('ordinary_member_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name_of_organisation');
            $table->string('org_reg_number');
            $table->date('org_reg_date');
            $table->string('registered_state');
            $table->text('full_address');
            $table->string('postcode');
            $table->string('district_city');
            $table->integer('year_established');
            $table->integer('total_members_size');
            $table->string('email');
            $table->string('contact_number');
            $table->string('website')->nullable();
            $table->text('org_type'); // JSON stored as text
            $table->string('org_type_other')->nullable();
            $table->text('primary_activities'); // JSON stored as text
            $table->string('primary_activities_other')->nullable();
            $table->boolean('is_registered_ros');
            $table->string('registration_certificate')->nullable();
            $table->string('committee_members')->nullable();
            $table->text('key_office_bearers'); // JSON stored as text (President, Secretary, Treasurer)
            $table->boolean('declaration_confirmed');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        // 3. friend_member_submissions
        Schema::create('friend_member_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // individual, surau, madrasah, others
            $table->string('others_specify')->nullable();
            // Organisation fields (if applicable)
            $table->string('org_name')->nullable();
            $table->string('org_state')->nullable();
            $table->text('org_address')->nullable();
            $table->string('org_email')->nullable();
            $table->string('org_phone')->nullable();
            $table->string('org_website')->nullable();
            // Individual fields (if applicable)
            $table->string('ind_name')->nullable();
            $table->string('ind_nric')->nullable();
            $table->string('ind_state')->nullable();
            $table->text('ind_address')->nullable();
            $table->string('ind_email')->nullable();
            $table->string('ind_phone')->nullable();
            $table->boolean('declaration_confirmed');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        // 4. mentor_submissions
        Schema::create('mentor_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nric_passport');
            $table->string('gender'); // Male, Female
            $table->string('occupation');
            $table->string('organisation');
            $table->string('position');
            $table->integer('experience_years');
            $table->string('state_residency');
            $table->text('full_address');
            $table->string('email');
            $table->string('contact_number');
            $table->string('linkedin')->nullable();
            $table->text('expertise_areas'); // JSON stored as text
            $table->string('expertise_other')->nullable();
            $table->text('preferred_format'); // JSON stored as text
            $table->text('preferred_commitment'); // JSON stored as text
            $table->text('experience_description');
            $table->boolean('has_served_before');
            $table->text('served_before_details')->nullable();
            $table->boolean('declaration_confirmed');
            $table->timestamps();
        });

        // 5. partner_submissions
        Schema::create('partner_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('position');
            $table->string('org_reg_number')->nullable();
            $table->string('email');
            $table->string('contact_number');
            $table->text('office_address');
            $table->string('state_country');
            $table->text('org_type'); // JSON stored as text
            $table->string('org_type_other')->nullable();
            $table->text('collaboration_areas'); // JSON stored as text
            $table->string('collaboration_other')->nullable();
            $table->text('partnership_type'); // JSON stored as text
            $table->string('partnership_other')->nullable();
            $table->text('proposal_description');
            $table->text('expected_outcomes');
            $table->boolean('has_collaborated_before');
            $table->text('collaborated_before_details')->nullable();
            $table->text('supporting_documents')->nullable(); // JSON stored as text (ticked boxes)
            $table->boolean('declaration_confirmed');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        // 6. volunteer_submissions
        Schema::create('volunteer_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nric_passport');
            $table->string('gender'); // Male, Female
            $table->string('occupation_study');
            $table->string('organisation')->nullable();
            $table->string('state_residency');
            $table->text('full_address');
            $table->string('email');
            $table->string('contact_number');
            $table->text('interest_areas'); // JSON stored as text
            $table->string('interest_other')->nullable();
            $table->text('skills_expertise');
            $table->string('preferred_mode'); // Physical / On-Ground, Virtual / Remote, Both
            $table->text('availability'); // JSON stored as text
            $table->boolean('has_volunteered_before');
            $table->text('volunteered_before_details')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_phone');
            $table->boolean('declaration_confirmed');
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
        Schema::dropIfExists('volunteer_submissions');
        Schema::dropIfExists('partner_submissions');
        Schema::dropIfExists('mentor_submissions');
        Schema::dropIfExists('friend_member_submissions');
        Schema::dropIfExists('ordinary_member_submissions');
        Schema::dropIfExists('feedback_submissions');
    }
}
