<?php

namespace Tests\Feature;

use App\Services\Welfare\MflsPartnerDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MflsProgrammeRequirementsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(MflsPartnerDocumentService::class)->bootstrapDocumentsIfMissing();
    }

    public function test_scholarship_form_includes_programme_requirements_modal(): void
    {
        $response = $this->get(route('welfare.mfls-scholarship', ['partner' => 'bac']));

        $response->assertStatus(200);
        $response->assertSee('Programme Requirements', false);
        $response->assertSee('Do you fulfil these programme requirements?', false);
        $response->assertSee('Yes, I fulfil them', false);
        $response->assertSee('No, I do not', false);
        $response->assertSee('id="programme-appeal-modal"', false);
        $response->assertSee('Let’s Explore Other Options!', false);
        $response->assertSee('scholarships@mukmin.org', false);
        $response->assertSee('APPEAL-MFLS', false);
        $response->assertSee('programme-requirements', false);
        $response->assertDontSee('requirements-inquiry', false);
    }

    public function test_programme_requirements_endpoint_returns_excel_details(): void
    {
        $response = $this->postJson(route('welfare.mfls-scholarship.programme-requirements'), [
            'partner' => 'bac',
            'programme' => 'FIL (Foundation in Law)',
        ]);

        $response->assertOk();
        $response->assertJson([
            'found' => true,
            'programme' => 'FIL (Foundation in Law)',
            'academic_requirements' => 'SPM 3A',
            'financial_requirement' => 'B40',
        ]);
    }

    public function test_programme_requirements_endpoint_accepts_get_for_simple_names(): void
    {
        $response = $this->getJson(route('welfare.mfls-scholarship.programme-requirements', [
            'partner' => 'bac',
            'programme' => 'FIL (Foundation in Law)',
        ]));

        $response->assertOk();
        $response->assertJson([
            'found' => true,
            'programme' => 'FIL (Foundation in Law)',
        ]);
    }

    public function test_programme_requirements_endpoint_accepts_names_with_multiple_parentheses(): void
    {
        $response = $this->postJson(route('welfare.mfls-scholarship.programme-requirements'), [
            'partner' => 'bac',
            'programme' => 'University of London Bachelor of Laws (LLB) (Hons)',
        ]);

        $response->assertOk();
        $response->assertJson([
            'found' => true,
            'programme' => 'University of London Bachelor of Laws (LLB) (Hons)',
        ]);
        $response->assertJsonStructure([
            'academic_requirements',
            'financial_requirement',
            'scholarship_coverage',
        ]);
    }

    public function test_programme_requirements_endpoint_rejects_invalid_programme(): void
    {
        $response = $this->postJson(route('welfare.mfls-scholarship.programme-requirements'), [
            'partner' => 'bac',
            'programme' => 'Not A Real Programme',
        ]);

        $response->assertStatus(422);
    }
}
