<?php

namespace Tests\Feature;

use App\Mail\MflsRequirementsInquiryMail;
use App\Services\Welfare\MflsPartnerDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
        $response->assertSee('programme-requirements', false);
        $response->assertSee('requirements-inquiry', false);
    }

    public function test_programme_requirements_endpoint_returns_excel_details(): void
    {
        $response = $this->getJson(route('welfare.mfls-scholarship.programme-requirements', [
            'partner' => 'bac',
            'programme' => 'FIL (Foundation in Law)',
        ]));

        $response->assertOk();
        $response->assertJson([
            'found' => true,
            'programme' => 'FIL (Foundation in Law)',
            'academic_requirements' => 'SPM 3A',
            'financial_requirement' => 'B40',
        ]);
    }

    public function test_programme_requirements_endpoint_rejects_invalid_programme(): void
    {
        $response = $this->getJson(route('welfare.mfls-scholarship.programme-requirements', [
            'partner' => 'bac',
            'programme' => 'Not A Real Programme',
        ]));

        $response->assertStatus(422);
    }

    public function test_requirements_inquiry_sends_emails_and_returns_redirect(): void
    {
        Mail::fake();

        $response = $this->postJson(route('welfare.mfls-scholarship.requirements-inquiry'), [
            'partner_id' => 'bac',
            'programme' => 'FIL (Foundation in Law)',
            'email' => 'applicant@example.com',
        ]);

        $response->assertOk();
        $response->assertJsonPath('ok', true);
        $response->assertJsonPath('redirect_url', route('welfare.impact.mfls'));

        Mail::assertSent(MflsRequirementsInquiryMail::class, function (MflsRequirementsInquiryMail $mail) {
            return $mail->applicantEmail === 'applicant@example.com'
                && $mail->programmeName === 'FIL (Foundation in Law)'
                && $mail->isForSupport === false;
        });

        Mail::assertSent(MflsRequirementsInquiryMail::class, function (MflsRequirementsInquiryMail $mail) {
            return $mail->isForSupport === true
                && $mail->applicantEmail === 'applicant@example.com';
        });
    }
}
