<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidAssessment extends Model
{
    protected $fillable = [
        'community_aid_submission_id',
        'assigned_to',
        'assessment_due_at',
        'urgency',
        'urgency_system_suggested',
        'urgency_reason',
        'urgency_confirmed',
        'aid_categories',
        'purpose_expense_types',
        'documents_verified',
        'university_balance_verified',
        'ptptn_funding_verified',
        'other_scholarship_verified',
        'interview_conducted',
        'financial_need',
        'other_confirmed_funding',
        'funding_breakdown',
        'funding_requires_review',
        'recommended_amount',
        'recommendation',
        'assessment_remarks',
        'recommendation_submitted_at',
        'recommendation_submitted_by',
        'interview_date',
        'interview_conducted_by',
        'interview_notes',
        'submitted_to_committee_at',
        'submitted_to_committee_by',
        'committee_decision',
        'approved_amount',
        'committee_remarks',
        'committee_decision_date',
        'committee_approved_by',
    ];

    protected $casts = [
        'assessment_due_at' => 'date',
        'urgency_confirmed' => 'boolean',
        'aid_categories' => 'array',
        'purpose_expense_types' => 'array',
        'documents_verified' => 'boolean',
        'university_balance_verified' => 'boolean',
        'ptptn_funding_verified' => 'boolean',
        'other_scholarship_verified' => 'boolean',
        'interview_conducted' => 'boolean',
        'other_confirmed_funding' => 'decimal:2',
        'funding_breakdown' => 'array',
        'funding_requires_review' => 'boolean',
        'recommended_amount' => 'decimal:2',
        'recommendation_submitted_at' => 'datetime',
        'interview_date' => 'date',
        'submitted_to_committee_at' => 'datetime',
        'approved_amount' => 'decimal:2',
        'committee_decision_date' => 'date',
    ];

    public function submission()
    {
        return $this->belongsTo(CommunityAidSubmission::class, 'community_aid_submission_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function recommendationSubmitter()
    {
        return $this->belongsTo(User::class, 'recommendation_submitted_by');
    }

    public function committeeSubmitter()
    {
        return $this->belongsTo(User::class, 'submitted_to_committee_by');
    }

    /** @return list<string> */
    public static function aidCategoryOptions(): array
    {
        return [
            'Accommodation & Food Aid',
            'Semester Fee < RM5,000',
            'Semester Fee > RM5,000',
            'Laptop',
        ];
    }

    /** @return array<string, string> */
    public static function recommendationOptions(): array
    {
        return [
            'full_assistance' => 'Recommend Full Assistance',
            'partial_assistance' => 'Recommend Partial Assistance',
            'alternative_assistance' => 'Recommend Alternative Assistance / Counselling',
            'not_recommended' => 'Not Recommended',
        ];
    }

    /** @return array<string, string> */
    public static function committeeDecisionOptions(): array
    {
        return [
            'approve_full' => 'Approve Full',
            'approve_partial' => 'Approve Partial',
            'defer' => 'Defer',
            'do_not_approve' => 'Do Not Approve',
        ];
    }
}
