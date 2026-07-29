<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MflsScholarshipSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'full_name',
        'nric_passport',
        'dob',
        'gender',
        'age',
        'citizenship',
        'marital_status',
        'marital_status_other',
        'contact_number',
        'full_address',
        'state',
        'postcode',
        'partner_institution_id',
        'partner_institution_name',
        'current_qualification',
        'institution_name',
        'current_cgpa_result',
        'academic_transcript',
        'programme_course_applied',
        'applied_to_university',
        'received_offer_letter',
        'offer_letter',
        'household_income',
        'father_guardian_name',
        'father_guardian_occupation',
        'mother_guardian_name',
        'mother_guardian_occupation',
        'proof_of_income',
        'government_assistance_status',
        'proof_of_government_assistance',
        'number_of_dependents',
        'other_scholarship_details',
        'leadership_roles',
        'involvement_level',
        'community_service_involvement',
        'community_contribution',
        'leadership_experience_statement',
        'scholar_selection_statement',
        'recommendation_letter',
        'relevant_certificates',
        'declaration_confirmed',
        'status',
    ];

    protected $casts = [
        'applied_to_university' => 'boolean',
        'received_offer_letter' => 'boolean',
        'proof_of_income' => 'array',
        'relevant_certificates' => 'array',
        'declaration_confirmed' => 'boolean',
        'dob' => 'date',
    ];
}
