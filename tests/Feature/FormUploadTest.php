<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\OrdinaryMemberSubmission;
use App\Models\PartnerSubmission;

class FormUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_ordinary_membership()
    {
        $formData = [
            'name_of_organisation' => 'Test Org Welfare',
            'org_reg_number' => 'REG-12345',
            'org_reg_date' => '2025-01-01',
            'registered_state' => 'Selangor',
            'full_address' => '123 Test Street, Petaling Jaya',
            'postcode' => '47300',
            'district_city' => 'Petaling Jaya',
            'year_established' => 2020,
            'total_members_size' => 50,
            'email' => 'testorg@example.com',
            'contact_number' => '+60123456789',
            'org_type' => ['NGO'],
            'primary_activities' => ['Welfare / Charity'],
            'key_office_bearers' => [
                'president' => ['name' => 'President Name', 'email' => 'pres@example.com', 'phone' => '+60123456789'],
                'secretary' => ['name' => 'Sec Name', 'email' => 'sec@example.com', 'phone' => '+60123456780'],
            ],
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.membership.ordinary.submit'), $formData);

        $response->assertStatus(200);
        $response->assertViewIs('welfare.pages.form_success');

        // Assert database has submission
        $this->assertDatabaseHas('ordinary_member_submissions', [
            'name_of_organisation' => 'Test Org Welfare',
            'org_reg_number' => 'REG-12345',
            'is_registered_ros' => 0,
            'registration_certificate' => null,
            'committee_members' => null,
        ]);
    }

    public function test_submit_partner_proposal_with_multiple_files()
    {
        Storage::fake('public');

        $proposal = UploadedFile::fake()->create('proposal.pdf', 500);
        $profile = UploadedFile::fake()->create('profile.jpg', 300);

        $formData = [
            'company_name' => 'Partner Corp',
            'contact_person' => 'John Doe',
            'position' => 'Manager',
            'org_reg_number' => '123456-X',
            'email' => 'partner@corp.com',
            'contact_number' => '+60129998888',
            'office_address' => '456 Corporate Ave, KL',
            'state_country' => 'Kuala Lumpur',
            'org_type' => ['Corporate / Private Sector'],
            'collaboration_areas' => ['Technology & Digital Innovation'],
            'partnership_type' => ['Sponsorship'],
            'proposal_description' => 'Collaboration proposal detail goes here.',
            'expected_outcomes' => 'Outcome expectations details.',
            'has_collaborated_before' => '0',
            'supporting_files' => [$proposal, $profile],
            'declaration_confirmed' => '1',
        ];

        $response = $this->post(route('welfare.partner.submit'), $formData);

        $response->assertStatus(200);
        $response->assertViewIs('welfare.pages.form_success');

        $this->assertDatabaseHas('partner_submissions', [
            'company_name' => 'Partner Corp',
        ]);

        $submission = PartnerSubmission::first();
        $this->assertNotNull($submission->supporting_documents);
        $this->assertCount(2, $submission->supporting_documents);

        foreach ($submission->supporting_documents as $filePath) {
            Storage::disk('public')->assertExists($filePath);
        }
    }

}
