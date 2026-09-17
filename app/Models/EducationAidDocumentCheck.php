<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationAidDocumentCheck extends Model
{
    protected $fillable = [
        'community_aid_submission_id',
        'document_key',
        'verification_status',
        'verified_by',
        'remarks',
    ];

    /** @return array<string, string> */
    public static function documentCatalog(): array
    {
        return [
            'nric_front' => 'NRIC Front',
            'nric_back' => 'NRIC Back',
            'academic_result' => 'Academic Result',
            'latest_academic_transcript' => 'Latest Transcript',
            'student_id_confirmation' => 'Student ID',
            'university_offer_letter' => 'Enrolment Confirmation',
            'applicant_photo' => 'Applicant Photo',
            'university_fee_statement' => 'Fee Statement',
            'official_invoice' => 'Invoice / Payment Notice',
            'outstanding_balance_statement' => 'Outstanding Statement',
            'payment_deadline_notice' => 'Demand Notice',
            'proof_of_income' => 'Proof of Income',
            'proof_of_government_assistance' => 'Proof of Government Assistance',
            'additional_supporting_documents' => 'Additional Supporting Documents',
        ];
    }

    /** @return array<string, string> */
    public static function statusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'verified' => 'Verified',
            'not_verified' => 'Not Verified',
            'not_applicable' => 'Not Applicable',
            'request_replacement' => 'Request Replacement',
        ];
    }

    public function submission()
    {
        return $this->belongsTo(CommunityAidSubmission::class, 'community_aid_submission_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
