<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityAidSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'nric_passport',
        'gender',
        'dob',
        'nationality',
        'occupation',
        'monthly_income',
        'contact_number',
        'email',
        'full_address',
        'state_residency',
        'type_of_aid',
        'type_of_aid_other',
        // Education Aid — Section 1
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
        'current_student_status_other',
        // Education Aid — Section 2
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
        // Education Aid — Section 3 (socioeconomic)
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
        // Education Aid — Section 4 (documents)
        'nric_front',
        'nric_back',
        'academic_result',
        'latest_academic_transcript',
        'university_offer_letter',
        'student_id_confirmation',
        'applicant_photo',
        'university_fee_statement',
        'official_invoice',
        'outstanding_balance_statement',
        'payment_deadline_notice',
        'additional_supporting_documents',
        // General aid
        'situation_description',
        'who_benefits',
        'number_of_beneficiaries',
        'received_aid_before',
        'received_aid_before_details',
        'supporting_documents',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'declaration_confirmed',
        'status',
    ];

    protected $casts = [
        'type_of_aid' => 'array',
        'education_expense_types' => 'array',
        'proof_of_income' => 'array',
        'sibling_information' => 'array',
        'additional_supporting_documents' => 'array',
        'supporting_documents' => 'array',
        'received_aid_before' => 'boolean',
        'declaration_confirmed' => 'boolean',
        'dob' => 'date',
        'intake_date' => 'date',
        'expected_graduation_date' => 'date',
        'payment_deadline' => 'date',
    ];
}
