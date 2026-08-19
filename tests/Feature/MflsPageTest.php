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
        $response->assertSee('New student intake only', false);
        $response->assertSee('Malaysian or Permanent Resident (PR) of Indian Muslim heritage', false);
        $response->assertSee('Applicants must not be receiving another full scholarship or equivalent funding support.', false);
        $response->assertDontSee('Applicants must meet the entry requirements of the chosen programme.', false);
        $response->assertSee('How It Works', false);
        $response->assertSee('Application Submission', false);
        $response->assertSee('Begin Your Learning Journey', false);
        $response->assertSee('Partner Institutions', false);
        $response->assertSee('Programmes', false);
        $response->assertSee('Looking for any other university?', false);
        $response->assertSee('Worry not! We may still be able to help you.', false);
        $response->assertSee('Submit an Education Aid Application', false);
        $response->assertSee(route('welfare.community-aid'), false);
        $response->assertSee('ASIA DRONE TECHNICAL ACADEMY', false);
        $response->assertSee('SKM LEVEL 2 H512-001-2:2019 (Drone Handling) + SKM LEVEL 3 H512-0013:2019 (Drone Commanding)', false);
        $response->assertSee('AUTOTRONICS CENTER OF EXCELLENCE', false);
        $response->assertSee('Certified SEDA PV Technician', false);
        $response->assertSee('SKM LEVEL 3 G452-007-3:2019 (Electric And Hybrid Car Servicing)', false);
        $response->assertSee('assess eligibility and shortlist qualified candidates', false);
        $response->assertSee('Apply Now', false);
        $response->assertSee('More Info', false);
        $response->assertSee('data-apply-url', false);
        $response->assertSee('Frequently Asked Questions', false);
        $response->assertSee('Can I apply to more than one programme?', false);
        $response->assertSee('Malaysian Qualifications Agency (MQA)', false);
    }

    public function test_mfls_scholarship_form_matches_google_form_sections()
    {
        $response = $this->get(route('welfare.mfls-scholarship', ['partner' => 'bac']));

        $response->assertStatus(200);
        $response->assertSee('dual pathway model', false);
        $response->assertSee('Facilitated by FIKRAH', false);
        $response->assertSee('Apply Now. Lead the Future.', false);
        $response->assertSee('Select Programme', false);
        $response->assertSee('FIL (Foundation in Law)', false);
        $response->assertSee('Section 1: Personal Information', false);
        $response->assertSee('Age', false);
        $response->assertSee('Citizenship', false);
        $response->assertSee('Postcode', false);
        $response->assertSee('Permanent Resident', false);
        $response->assertDontSee('Non-Malaysian', false);
        $response->assertSee('IGCSE', false);
        $response->assertDontSee('Divorced', false);
        $response->assertDontSee('e.g. 0123456789', false);
        $response->assertSee('Section 2: Academic Information', false);
        $response->assertSee('Section 3: Socioeconomic Background', false);
        $response->assertSee('Section 4: Personal Statement', false);
        $response->assertSee('Section 5: Declaration', false);
        $response->assertDontSee('Section 4: Leadership', false);
        $response->assertDontSee('Section 5: Supporting Documents', false);
        $response->assertDontSee('Section 6: Declaration', false);
        $response->assertDontSee('Section 7: Declaration', false);
        $response->assertSee('Current Qualification (Year 2025/2026)', false);
        $response->assertSee('Matriculation', false);
        $response->assertSee('Year of Completion', false);
        $response->assertSee('Sibling Information', false);
        $response->assertSee('Not Working', false);
        $response->assertSee('Not Yet in School', false);
        $response->assertSee('Reason', false);
        $response->assertSee('MUKMIN-FIKRAH Scholar', false);
    }

    public function test_impact_page_read_more_links_to_mfls()
    {
        $response = $this->get('/impact-areas');

        $response->assertStatus(200);
        $response->assertSee(route('welfare.impact.mfls'), false);
    }
}
