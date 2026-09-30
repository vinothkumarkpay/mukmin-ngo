<?php

namespace Tests\Feature;

use App\Models\CommunityAidSubmission;
use App\Models\EducationAidPayment;
use App\Models\EducationAidPaymentReceipt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EducationAidPaymentsTest extends TestCase
{
    use RefreshDatabase;

    private CommunityAidSubmission $submission;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $this->submission = CommunityAidSubmission::create([
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
            'status' => 'committee_review',
        ]);
    }

    private function decision(array $extra = []): array
    {
        return array_merge([
            'committee_decision' => 'approve_full',
            'approved_amount' => '3000',
            'committee_decision_date' => '2026-09-29',
            'committee_approved_by' => 'Committee Chair',
            'committee_remarks' => 'Approved.',
        ], $extra);
    }

    private function url(): string
    {
        return route('welfare.admin.education-aid.committee-decision', $this->submission->id);
    }

    public function test_review_page_shows_payment_controls()
    {
        $this->get(route('welfare.admin.education-aid.review', $this->submission->id))
            ->assertOk()
            ->assertSee('Payment Date(s)')
            ->assertSee('Add payment date')
            ->assertSee('ea-payment-template', false);
    }

    public function test_multiple_payments_with_receipts_are_saved()
    {
        $this->post($this->url(), $this->decision([
            'payments' => [
                0 => [
                    'payment_date' => '2026-10-01',
                    'amount' => '1500',
                    'receipts' => [UploadedFile::fake()->create('receipt-oct.pdf', 100)],
                ],
                // Row without receipts sits between rows with receipts to prove files map to the right payment.
                3 => ['payment_date' => '2026-11-01', 'amount' => '1000'],
                5 => [
                    'payment_date' => '2026-12-01',
                    'receipts' => [
                        UploadedFile::fake()->create('receipt-dec-a.pdf', 50),
                        UploadedFile::fake()->image('receipt-dec-b.jpg'),
                    ],
                ],
            ],
        ]))->assertRedirect()->assertSessionHas('success');

        $payments = EducationAidPayment::with('receipts')->orderBy('payment_date')->get();
        $this->assertCount(3, $payments);
        $this->assertSame(['receipt-oct.pdf'], $payments[0]->receipts->pluck('display_name')->all());
        $this->assertCount(0, $payments[1]->receipts);
        $this->assertSame(['receipt-dec-a.pdf', 'receipt-dec-b.jpg'], $payments[2]->receipts->pluck('display_name')->all());
        $this->assertNull($payments[2]->amount);
        Storage::disk('public')->assertExists($payments[0]->receipts[0]->path);

        $this->assertDatabaseHas('education_aid_case_events', [
            'community_aid_submission_id' => $this->submission->id,
            'event_type' => 'payments_updated',
        ]);
    }

    public function test_existing_payment_can_be_updated_receipt_added_removed_and_row_deleted()
    {
        $keep = EducationAidPayment::create(['community_aid_submission_id' => $this->submission->id, 'payment_date' => '2026-10-01', 'amount' => 1000]);
        Storage::disk('public')->put('documents/payment-receipts/old.pdf', 'x');
        $oldReceipt = EducationAidPaymentReceipt::create(['education_aid_payment_id' => $keep->id, 'path' => 'documents/payment-receipts/old.pdf', 'display_name' => 'old.pdf']);
        $drop = EducationAidPayment::create(['community_aid_submission_id' => $this->submission->id, 'payment_date' => '2026-11-01']);

        $this->post($this->url(), $this->decision([
            'payments' => [
                0 => [
                    'id' => $keep->id,
                    'payment_date' => '2026-10-05',
                    'amount' => '1200',
                    'receipts' => [UploadedFile::fake()->create('new.pdf', 20)],
                ],
            ],
            'remove_receipts' => [$oldReceipt->id],
        ]))->assertSessionHas('success');

        $keep->refresh();
        $this->assertSame('2026-10-05', $keep->payment_date->format('Y-m-d'));
        $this->assertSame('1200.00', (string) $keep->amount);
        $this->assertSame(['new.pdf'], $keep->receipts()->pluck('display_name')->all());
        Storage::disk('public')->assertMissing('documents/payment-receipts/old.pdf');
        $this->assertDatabaseMissing('education_aid_payments', ['id' => $drop->id]);
    }

    public function test_missing_payment_date_and_bad_receipt_show_clear_errors()
    {
        $this->post($this->url(), $this->decision([
            'payments' => [
                0 => ['payment_date' => '', 'amount' => '100'],
                1 => ['payment_date' => '2026-10-01', 'receipts' => [UploadedFile::fake()->create('virus.exe', 10)]],
                2 => ['payment_date' => '2026-10-02', 'receipts' => [UploadedFile::fake()->create('huge.pdf', 11000)]],
            ],
        ]))->assertSessionHasErrors([
            'payments.0.payment_date' => 'Please enter a date for every payment, or remove the empty payment row.',
            'payments.1.receipts.0' => 'Receipts must be PDF, JPG or PNG files.',
            'payments.2.receipts.0' => 'Each receipt must not exceed 10MB.',
        ]);

        $this->assertSame(0, EducationAidPayment::count());
    }

    public function test_total_payments_cannot_exceed_approved_amount()
    {
        $this->post($this->url(), $this->decision([
            'approved_amount' => '2000',
            'payments' => [
                ['payment_date' => '2026-10-01', 'amount' => '1500'],
                ['payment_date' => '2026-11-01', 'amount' => '1000'],
            ],
        ]))->assertSessionHas('error', 'Total payments (RM2,500.00) cannot exceed the approved amount (RM2,000.00).');

        $this->assertSame(0, EducationAidPayment::count());
    }

    public function test_decision_without_payments_still_saves()
    {
        $this->post($this->url(), $this->decision(['committee_decision' => 'defer', 'approved_amount' => null]))
            ->assertSessionHas('success');

        $this->assertSame(0, EducationAidPayment::count());
    }

    public function test_receipt_view_uses_display_name()
    {
        $payment = EducationAidPayment::create(['community_aid_submission_id' => $this->submission->id, 'payment_date' => '2026-10-01']);
        Storage::disk('public')->put('documents/payment-receipts/r.pdf', 'x');
        $receipt = EducationAidPaymentReceipt::create(['education_aid_payment_id' => $payment->id, 'path' => 'documents/payment-receipts/r.pdf', 'display_name' => 'Oct payout.pdf']);

        $response = $this->get(route('welfare.admin.education-aid.receipts.show', ['id' => $this->submission->id, 'receiptId' => $receipt->id]));

        $response->assertOk();
        $this->assertStringContainsString('Oct payout.pdf', (string) $response->headers->get('Content-Disposition'));
    }
}
