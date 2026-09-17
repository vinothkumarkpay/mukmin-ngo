<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationAidCaseManagementTables extends Migration
{
    public function up()
    {
        Schema::create('education_aid_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_aid_submission_id')->unique();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('assessment_due_at')->nullable();
            $table->string('urgency', 32)->nullable();
            $table->string('urgency_system_suggested', 32)->nullable();
            $table->text('urgency_reason')->nullable();
            $table->boolean('urgency_confirmed')->default(false);
            $table->json('aid_categories')->nullable();
            $table->json('purpose_expense_types')->nullable();
            $table->boolean('documents_verified')->nullable();
            $table->boolean('university_balance_verified')->nullable();
            $table->boolean('ptptn_funding_verified')->nullable();
            $table->boolean('other_scholarship_verified')->nullable();
            $table->boolean('interview_conducted')->nullable();
            $table->string('financial_need', 32)->nullable();
            $table->decimal('other_confirmed_funding', 12, 2)->nullable();
            $table->json('funding_breakdown')->nullable();
            $table->boolean('funding_requires_review')->default(false);
            $table->decimal('recommended_amount', 12, 2)->nullable();
            $table->string('recommendation', 64)->nullable();
            $table->text('assessment_remarks')->nullable();
            $table->timestamp('recommendation_submitted_at')->nullable();
            $table->unsignedBigInteger('recommendation_submitted_by')->nullable();
            $table->date('interview_date')->nullable();
            $table->string('interview_conducted_by')->nullable();
            $table->text('interview_notes')->nullable();
            $table->timestamp('submitted_to_committee_at')->nullable();
            $table->unsignedBigInteger('submitted_to_committee_by')->nullable();
            $table->string('committee_decision', 64)->nullable();
            $table->decimal('approved_amount', 12, 2)->nullable();
            $table->text('committee_remarks')->nullable();
            $table->date('committee_decision_date')->nullable();
            $table->string('committee_approved_by')->nullable();
            $table->timestamps();

            $table->foreign('community_aid_submission_id', 'ea_assess_submission_fk')
                ->references('id')->on('community_aid_submissions')->onDelete('cascade');
            $table->foreign('assigned_to', 'ea_assess_assigned_fk')
                ->references('id')->on('users')->onDelete('set null');
            $table->foreign('recommendation_submitted_by', 'ea_assess_rec_by_fk')
                ->references('id')->on('users')->onDelete('set null');
            $table->foreign('submitted_to_committee_by', 'ea_assess_committee_by_fk')
                ->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('education_aid_document_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_aid_submission_id');
            $table->string('document_key', 100);
            $table->string('verification_status', 40)->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['community_aid_submission_id', 'document_key'], 'ea_doc_unique');
            $table->foreign('community_aid_submission_id', 'ea_doc_submission_fk')
                ->references('id')->on('community_aid_submissions')->onDelete('cascade');
            $table->foreign('verified_by', 'ea_doc_verified_by_fk')
                ->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('education_aid_section_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_aid_submission_id');
            $table->string('section', 64);
            $table->text('comment');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('community_aid_submission_id', 'ea_comment_submission_fk')
                ->references('id')->on('community_aid_submissions')->onDelete('cascade');
            $table->foreign('user_id', 'ea_comment_user_fk')
                ->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('education_aid_case_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_aid_submission_id');
            $table->string('event_type', 64);
            $table->text('description');
            $table->json('meta')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('community_aid_submission_id', 'ea_event_submission_fk')
                ->references('id')->on('community_aid_submissions')->onDelete('cascade');
            $table->foreign('user_id', 'ea_event_user_fk')
                ->references('id')->on('users')->onDelete('set null');
            $table->index(['community_aid_submission_id', 'created_at'], 'ea_event_case_created_idx');
        });

        Schema::create('education_aid_interview_proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_aid_submission_id');
            $table->json('proposed_dates');
            $table->string('status', 32)->default('pending');
            $table->dateTime('confirmed_at')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();

            $table->foreign('community_aid_submission_id', 'ea_interview_submission_fk')
                ->references('id')->on('community_aid_submissions')->onDelete('cascade');
            $table->foreign('created_by', 'ea_interview_created_by_fk')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_aid_interview_proposals');
        Schema::dropIfExists('education_aid_case_events');
        Schema::dropIfExists('education_aid_section_comments');
        Schema::dropIfExists('education_aid_document_checks');
        Schema::dropIfExists('education_aid_assessments');
    }
}
