<?php

namespace Tests\Feature;

use Tests\TestCase;

class MflsPageTest extends TestCase
{
    public function test_mfls_page_loads_successfully()
    {
        $response = $this->get('/impact-areas/mfls');

        $response->assertStatus(200);
        $response->assertSee('MUKMIN Future Leaders Scholarship (MFLS)', false);
        $response->assertSee('National Scholarship', false);
        $response->assertSee('Talent Development Programme', false);
        $response->assertSee('2025/2026 Intake Update', false);
        $response->assertSee('Applications are facilitated through a coordinated selection process', false);
        $response->assertSee('Application Criteria', false);
        $response->assertSee('Applicants must be of Indian Muslim heritage', false);
        $response->assertSee('How It Works', false);
        $response->assertSee('Application Submission', false);
        $response->assertSee('Begin Your Learning Journey', false);
        $response->assertSee('Partner Institutions', false);
        $response->assertSee('Programmes', false);
        $response->assertSee('SG Academy', false);
        $response->assertSee('Drone Training (Certified by Civil Aviation)', false);
        $response->assertSee('assess eligibility and shortlist qualified candidates', false);
        $response->assertSee('Apply Now', false);
        $response->assertSee(route('welfare.mfls-scholarship'), false);
        $response->assertSee('Frequently Asked Questions', false);
        $response->assertSee('Can I apply to more than one programme?', false);
        $response->assertSee('Malaysian Qualifications Agency (MQA)', false);
    }

    public function test_mfls_scholarship_form_matches_google_form_sections()
    {
        $response = $this->get(route('welfare.mfls-scholarship'));

        $response->assertStatus(200);
        $response->assertSee('dual pathway model', false);
        $response->assertSee('Facilitated by FIKRAH', false);
        $response->assertSee('Apply Now. Lead the Future.', false);
        $response->assertSee('Applications close on 15th July 2026', false);
        $response->assertSee('Section 1: Personal Information', false);
        $response->assertSee('Section 2: Academic Information', false);
        $response->assertSee('Section 3: Financial Background', false);
        $response->assertSee('Section 4: Leadership', false);
        $response->assertSee('Section 5: Personal Statement', false);
        $response->assertSee('Section 6: Supporting Documents', false);
        $response->assertSee('Section 7: Declaration', false);
        $response->assertSee('Current Qualification (Year 2025/2026)', false);
        $response->assertSee('MUKMIN-FIKRAH Scholar', false);
    }

    public function test_impact_page_read_more_links_to_mfls()
    {
        $response = $this->get('/impact-areas');

        $response->assertStatus(200);
        $response->assertSee(route('welfare.impact.mfls'), false);
    }
}
