<?php

namespace Tests\Feature;

use App\Models\MflsScholarshipSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSubmissionFiltersTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_admin_submission_filter_ui_uses_dropdown_and_extra_fields(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('welfare.admin.dashboard', [
            'admin_tab' => 'panel-mfls',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Search &amp; Filter Submissions', false);
        $response->assertSee('id="filter_submission_status"', false);
        $response->assertSee('name="filter_q"', false);
        $response->assertSee('name="filter_partner"', false);
        $response->assertSee('name="filter_programme"', false);
        $response->assertSee('name="filter_qualification"', false);
        $response->assertSee('name="filter_household_income"', false);
        $response->assertSee('Apply filters', false);
        $response->assertDontSee('No status filter applied', false);

        $html = $response->getContent();
        $this->assertMatchesRegularExpression(
            '/data-filter-panels="panel-volunteer"[^>]*style="display:\s*none;"/',
            $html
        );
        $this->assertMatchesRegularExpression(
            '/id="filter_gender"[^>]*\bdisabled\b/',
            $html
        );
        $this->assertDoesNotMatchRegularExpression(
            '/data-filter-panels="panel-mfls"[^>]*style="display:\s*none;"/',
            $html
        );
    }

    public function test_admin_can_filter_mfls_by_partner_and_status(): void
    {
        MflsScholarshipSubmission::create([
            'email' => 'keep@example.com',
            'full_name' => 'Keep Applicant',
            'nric_passport' => '010101011234',
            'dob' => '2001-01-01',
            'gender' => 'Male',
            'age' => 22,
            'citizenship' => 'Malaysian',
            'marital_status' => 'Single',
            'contact_number' => '+60123456789',
            'full_address' => '123 Jalan Pendidikan',
            'state' => 'Selangor',
            'postcode' => '43000',
            'partner_institution_id' => 'bac',
            'partner_institution_name' => 'BAC',
            'current_qualification' => 'SPM',
            'institution_name' => 'SMK Contoh',
            'year_of_completion' => 2024,
            'current_cgpa_result' => '7A',
            'academic_transcript' => 'documents/transcript.pdf',
            'programme_course_applied' => 'FIL (Foundation in Law)',
            'household_income' => 'Below RM 2,000',
            'father_guardian_name' => 'Father',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Mother',
            'mother_guardian_occupation' => 'Homemaker',
            'number_of_dependents' => 2,
            'other_scholarship_details' => 'None',
            'leadership_experience_statement' => str_repeat('word ', 160),
            'scholar_selection_statement' => str_repeat('word ', 160),
            'declaration_confirmed' => true,
            'status' => 'approved',
        ]);

        MflsScholarshipSubmission::create([
            'email' => 'drop@example.com',
            'full_name' => 'Drop Applicant',
            'nric_passport' => '010101011235',
            'dob' => '2001-01-01',
            'gender' => 'Female',
            'age' => 21,
            'citizenship' => 'Malaysian',
            'marital_status' => 'Single',
            'contact_number' => '+60123456780',
            'full_address' => '456 Jalan Pendidikan',
            'state' => 'Johor',
            'postcode' => '80000',
            'partner_institution_id' => 'binary',
            'partner_institution_name' => 'BINARY COLLEGE',
            'current_qualification' => 'Diploma',
            'institution_name' => 'College Contoh',
            'year_of_completion' => 2023,
            'current_cgpa_result' => '3.2',
            'academic_transcript' => 'documents/transcript2.pdf',
            'programme_course_applied' => 'Diploma in Accounting',
            'household_income' => 'RM 2,001 to RM 5,000',
            'father_guardian_name' => 'Father Two',
            'father_guardian_occupation' => 'Manager',
            'mother_guardian_name' => 'Mother Two',
            'mother_guardian_occupation' => 'Teacher',
            'number_of_dependents' => 1,
            'other_scholarship_details' => 'None',
            'leadership_experience_statement' => str_repeat('word ', 160),
            'scholar_selection_statement' => str_repeat('word ', 160),
            'declaration_confirmed' => true,
            'status' => 'received',
        ]);

        $this->actingAsAdmin();

        $response = $this->get(route('welfare.admin.dashboard', [
            'admin_tab' => 'panel-mfls',
            'submission_status' => 'approved',
            'filter_partner' => 'bac',
            'filter_q' => 'Keep',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Keep Applicant', false);
        $response->assertDontSee('Drop Applicant', false);
        $response->assertSee('Status: Approved', false);
        $response->assertSee('Partner: BAC', false);
    }
}
