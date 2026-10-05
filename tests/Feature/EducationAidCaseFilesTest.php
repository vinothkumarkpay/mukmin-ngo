<?php

namespace Tests\Feature;

use App\Models\CommunityAidSubmission;
use App\Models\EducationAidCaseFile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EducationAidCaseFilesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    private function makeSubmission(array $overrides = []): CommunityAidSubmission
    {
        Storage::disk('public')->put('documents/nric-front-abc.pdf', 'pdf');

        return CommunityAidSubmission::create(array_merge([
            'full_name' => 'Jane Smith',
            'nric_passport' => '950101105432',
            'gender' => 'Female',
            'dob' => '1995-01-01',
            'nationality' => 'Malaysian',
            'occupation' => 'Student',
            'contact_number' => '+60176543210',
            'email' => 'jane@example.com',
            'full_address' => '789 Hope Avenue, Kuala Lumpur',
            'state_residency' => 'Selangor',
            'type_of_aid' => ['Education Aid'],
            'situation_description' => 'Tuition assistance.',
            'who_benefits' => 'Individual',
            'received_aid_before' => false,
            'emergency_contact_name' => 'John Smith',
            'emergency_contact_relationship' => 'Brother',
            'emergency_contact_phone' => '+60112223334',
            'declaration_confirmed' => true,
            'status' => 'received',
            'nric_front' => 'documents/nric-front-abc.pdf',
        ], $overrides));
    }

    public function test_review_page_lists_application_files_and_upload_controls()
    {
        $submission = $this->makeSubmission();

        $response = $this->get(route('welfare.admin.education-aid.review', $submission->id));

        $response->assertOk();
        $response->assertSee('nric-front-abc.pdf');
        $response->assertSee('ea-doc-upload-input', false);
        $this->assertDatabaseHas('education_aid_case_files', [
            'community_aid_submission_id' => $submission->id,
            'document_key' => 'nric_front',
            'source' => 'application',
        ]);
    }

    public function test_admin_can_upload_file_to_any_document()
    {
        $submission = $this->makeSubmission();

        $response = $this->postJson(
            route('welfare.admin.education-aid.files.upload', ['id' => $submission->id, 'documentKey' => 'official_invoice']),
            ['files' => [UploadedFile::fake()->create('Invoice March.pdf', 200)]]
        );

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('document.submitted', true)
            ->assertJsonPath('document.files.0.name', 'Invoice March.pdf')
            ->assertJsonPath('document.files.0.source', 'admin');

        $file = EducationAidCaseFile::where('document_key', 'official_invoice')->firstOrFail();
        Storage::disk('public')->assertExists($file->path);
        $this->assertDatabaseHas('education_aid_case_events', [
            'community_aid_submission_id' => $submission->id,
            'event_type' => 'document_file_uploaded',
        ]);
    }

    public function test_upload_rejects_oversized_and_wrong_type_files_with_clear_message()
    {
        $submission = $this->makeSubmission();
        $url = route('welfare.admin.education-aid.files.upload', ['id' => $submission->id, 'documentKey' => 'official_invoice']);

        $this->postJson($url, ['files' => [UploadedFile::fake()->create('big.pdf', 11000)]])
            ->assertStatus(422)
            ->assertJsonPath('errors', ['files.0' => ['Each file must not exceed 10MB.']]);

        $this->postJson($url, ['files' => [UploadedFile::fake()->create('script.exe', 10)]])
            ->assertStatus(422)
            ->assertJsonPath('errors', ['files.0' => ['Files must be PDF, JPG, PNG, DOC or DOCX.']]);

        $this->postJson($url, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['files']);
    }

    public function test_rename_keeps_extension_and_records_audit()
    {
        $submission = $this->makeSubmission();
        $this->get(route('welfare.admin.education-aid.review', $submission->id));
        $file = EducationAidCaseFile::where('document_key', 'nric_front')->firstOrFail();

        $this->postJson(route('welfare.admin.education-aid.files.rename', ['id' => $submission->id, 'fileId' => $file->id]), [
            'display_name' => 'Jane NRIC / front',
        ])->assertOk()->assertJsonPath('document.files.0.name', 'Jane NRIC front.pdf');

        $this->assertSame('Jane NRIC front.pdf', $file->fresh()->display_name);
        $this->assertSame('documents/nric-front-abc.pdf', $file->fresh()->path);
        $this->assertDatabaseHas('education_aid_case_events', [
            'community_aid_submission_id' => $submission->id,
            'event_type' => 'document_file_renamed',
        ]);

        $this->postJson(route('welfare.admin.education-aid.files.rename', ['id' => $submission->id, 'fileId' => $file->id]), [
            'display_name' => '',
        ])->assertStatus(422)->assertJsonValidationErrors(['display_name']);
    }

    public function test_view_serves_file_with_renamed_filename()
    {
        $submission = $this->makeSubmission();
        $this->get(route('welfare.admin.education-aid.review', $submission->id));
        $file = EducationAidCaseFile::where('document_key', 'nric_front')->firstOrFail();
        $file->update(['display_name' => 'Renamed NRIC.pdf']);

        $response = $this->get(route('welfare.admin.education-aid.files.show', ['id' => $submission->id, 'fileId' => $file->id]));

        $response->assertOk();
        $this->assertStringContainsString('Renamed NRIC.pdf', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_only_admin_uploaded_files_can_be_deleted()
    {
        $submission = $this->makeSubmission();
        $this->get(route('welfare.admin.education-aid.review', $submission->id));
        $applicationFile = EducationAidCaseFile::where('source', 'application')->firstOrFail();

        $this->postJson(route('welfare.admin.education-aid.files.delete', ['id' => $submission->id, 'fileId' => $applicationFile->id]))
            ->assertStatus(422)
            ->assertJsonPath('message', 'Files submitted with the application cannot be deleted.');

        $this->postJson(
            route('welfare.admin.education-aid.files.upload', ['id' => $submission->id, 'documentKey' => 'official_invoice']),
            ['files' => [UploadedFile::fake()->create('extra.pdf', 50)]]
        );
        $adminFile = EducationAidCaseFile::where('source', 'admin')->firstOrFail();

        $this->postJson(route('welfare.admin.education-aid.files.delete', ['id' => $submission->id, 'fileId' => $adminFile->id]))
            ->assertOk()
            ->assertJsonPath('document.submitted', false);

        Storage::disk('public')->assertMissing($adminFile->path);
        $this->assertDatabaseMissing('education_aid_case_files', ['id' => $adminFile->id]);
    }
}
