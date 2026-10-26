<?php

namespace Tests\Feature;

use App\Mail\SisterhoodRegistrationMail;
use App\Models\SisterhoodRegistration;
use App\Models\User;
use App\Support\SisterhoodCollective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SisterhoodRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        config([
            'services.kiplepay.merchant_id' => 'MID',
            'services.kiplepay.secret_key' => 'SECRET',
            'services.kiplepay.merchant_id_guest' => 'MID',
            'services.kiplepay.secret_key_guest' => 'SECRET',
            'services.kiplepay.url' => 'https://sandbox.webcash.com.my',
            'services.recaptcha.site_key' => null,
            'services.recaptcha.secret_key' => null,
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_home_page_links_to_the_registration_form(): void
    {
        $response = $this->get(route('welfare.home'));

        $response->assertOk();
        $response->assertSee('btn-sisterhood-rounded', false);
        $response->assertSee(route('welfare.sisterhood.create'), false);
        $response->assertDontSee('sisterhood-home-banner', false);
    }

    public function test_registration_form_lists_the_programme_fields(): void
    {
        $response = $this->get(route('welfare.sisterhood.create'));

        $response->assertOk();
        $response->assertSee('Full Name (as per NRIC/Passport)', false);
        $response->assertSee('Salutation', false);
        $response->assertSee('Preferred Name', false);
        $response->assertSee('Organised by:', false);
        $response->assertSee('Upon submitting this form, you will be redirected to the payment gateway to complete the RM30 registration fee.', false);
        $response->assertSee('Your registration will only be confirmed once payment has been successfully received.', false);
        $response->assertDontSee('Every question is required, except address line 2.', false);
        $response->assertSee('Registered Friends of MUKMIN, and', false);
        $response->assertSee('Indian Muslim women aged 18 and above', false);
        $response->assertDontSee('Salutation / Gelaran', false);
        $response->assertDontSee('Nama Panggilan', false);
        $response->assertDontSee('Friends of Mukmin', false);
        $response->assertSee('Area of Interest', false);
        $response->assertSee('Participation Declaration', false);
        $response->assertSee('Emergency Contact Name', false);
        $response->assertSee('Emergency Contact Number', false);
        $response->assertSee('Unit Number', false);
        $response->assertSee('Address Line 1', false);
        $response->assertSee('Address Line 2', false);
        $response->assertSee('Postcode', false);
        $response->assertSee('Every statement must be marked before you can register.', false);
        $response->assertSee('Registration &amp; Cancellation Policy', false);
        $response->assertSee('Registration fees of RM30 are non-refundable upon cancellation or non-attendance.', false);
        $response->assertDontSee('Emergency Contact Name &amp; Number', false);
        $response->assertDontSee('<span aria-hidden="true">*</span>', false);
        $response->assertSee('RM' . number_format(SisterhoodCollective::FEE, 0), false);
        $response->assertSee('Places are limited to ' . SisterhoodCollective::CAPACITY . ' participants.', false);
        $response->assertSee('class="btn-submit">Register</button>', false);
    }

    public function test_registration_stops_when_all_places_are_taken(): void
    {
        foreach (range(1, SisterhoodCollective::CAPACITY) as $number) {
            $registration = $this->makeRegistration('TSC-FULL-' . $number);
            $registration->status = SisterhoodRegistration::STATUS_SUCCESS;
            $registration->payment_status = 'paid';
            $registration->paid_at = now();
            $registration->save();
        }

        $this->get(route('welfare.sisterhood.create'))
            ->assertOk()
            ->assertSee(SisterhoodCollective::fullMessage(), false)
            ->assertDontSee('class="btn-submit">Register</button>', false);

        $this->from(route('welfare.sisterhood.create'))
            ->post(route('welfare.sisterhood.store'), $this->payload([
                'nric_passport' => '910101-01-9999',
                'email' => 'another@example.com',
            ]))
            ->assertRedirect(route('welfare.sisterhood.create'))
            ->assertSessionHas('error', SisterhoodCollective::fullMessage());

        $this->assertSame(SisterhoodCollective::CAPACITY, SisterhoodRegistration::count());
    }

    public function test_test_records_are_excluded_from_the_place_limit(): void
    {
        foreach (range(1, SisterhoodCollective::CAPACITY) as $number) {
            $registration = $this->makeRegistration('TSC-TEST-' . $number);
            $registration->status = SisterhoodRegistration::STATUS_TEST;
            $registration->payment_status = 'paid';
            $registration->paid_at = now();
            $registration->save();
        }

        $this->assertSame(0, SisterhoodCollective::placesTaken());

        $this->get(route('welfare.sisterhood.create'))
            ->assertOk()
            ->assertSee('class="btn-submit">Register</button>', false);
    }

    public function test_a_test_registration_stays_status_9_when_payment_is_recorded(): void
    {
        $registration = $this->makeRegistration('TSC-KEEP-TEST');
        $registration->status = SisterhoodRegistration::STATUS_TEST;
        $registration->save();

        app(\App\Services\Welfare\SisterhoodPaymentRecorder::class)->record(
            $registration,
            $this->gatewayPayload('TSC-KEEP-TEST', '100')
        );

        $registration->refresh();
        $this->assertTrue($registration->isPaid());
        $this->assertSame(SisterhoodRegistration::STATUS_TEST, (int) $registration->status);
    }

    public function test_a_failed_payment_does_not_keep_a_place(): void
    {
        foreach (range(1, SisterhoodCollective::CAPACITY) as $number) {
            $registration = $this->makeRegistration('TSC-OPEN-' . $number);
            $registration->payment_status = 'failed';
            $registration->save();
        }

        $this->get(route('welfare.sisterhood.create'))
            ->assertOk()
            ->assertSee('class="btn-submit">Register</button>', false);
    }

    public function test_incomplete_form_is_rejected_and_does_not_create_a_registration(): void
    {
        $response = $this->from(route('welfare.sisterhood.create'))
            ->post(route('welfare.sisterhood.store'), [
                'full_name' => 'Aisha Rahman',
                'age' => 17,
            ]);

        $response->assertRedirect(route('welfare.sisterhood.create'));
        $response->assertSessionHasErrors(['salutation', 'age', 'email', 'unit_number', 'declaration_accurate']);
        $this->assertDatabaseCount('sisterhood_registrations', 0);
    }

    public function test_invalid_submission_keeps_the_selected_dropdowns(): void
    {
        $payload = $this->payload([
            'salutation' => "Dato'",
            'profession' => SisterhoodCollective::PROFESSION_OTHER,
            'profession_other' => '',
        ]);

        $response = $this->from(route('welfare.sisterhood.create'))
            ->post(route('welfare.sisterhood.store'), $payload);

        $response->assertRedirect(route('welfare.sisterhood.create'));
        $response->assertSessionHasErrors('profession_other');

        $this->get(route('welfare.sisterhood.create'))
            ->assertOk()
            ->assertSee('value="Dato&#039;" selected', false)
            ->assertSee('value="' . e(SisterhoodCollective::PROFESSION_OTHER) . '" selected', false)
            ->assertSee('value="Bachelor&#039;s Degree" selected', false)
            ->assertSee('value="Selangor" selected', false)
            ->assertSee('value="Vegetarian" selected', false)
            ->assertSee('value="WhatsApp" selected', false)
            ->assertSee('value="Community Welfare" checked', false)
            ->assertSee('value="All of the above" checked', false)
            ->assertSee('name="declaration_accurate" value="1" checked', false);
    }

    public function test_every_declaration_must_be_marked(): void
    {
        $payload = $this->payload();
        unset($payload['declaration_media']);

        $response = $this->from(route('welfare.sisterhood.create'))
            ->post(route('welfare.sisterhood.store'), $payload);

        $response->assertRedirect(route('welfare.sisterhood.create'));
        $response->assertSessionHasErrors('declaration_media');
        $this->assertDatabaseCount('sisterhood_registrations', 0);

        $this->get(route('welfare.sisterhood.create'))
            ->assertSee('Please mark every participation declaration.', false)
            ->assertSee('value="AISHA RAHMAN"', false)
            ->assertSee('value="A-12-3"', false);
    }

    public function test_completed_form_starts_a_pending_rm30_payment(): void
    {
        $response = $this->post(route('welfare.sisterhood.store'), $this->payload());

        $response->assertOk();
        $response->assertSee('Redirecting to Payment Gateway', false);
        $response->assertSee('value="' . number_format(SisterhoodCollective::FEE, 2, '.', '') . '"', false);
        $response->assertSee('https://sandbox.webcash.com.my/wcgatewayinit.php', false);

        $registration = SisterhoodRegistration::first();
        $this->assertNotNull($registration);
        $this->assertSame('pending', $registration->payment_status);
        $this->assertSame(SisterhoodRegistration::STATUS_PENDING, (int) $registration->status);
        $this->assertNull($registration->paid_at);
        $this->assertFalse($registration->isPaid());
        $this->assertSame(number_format(SisterhoodCollective::FEE, 2, '.', ''), $registration->amount);
        $this->assertSame('900101011234', $registration->nric_normalized);
        $this->assertSame('FARAH RAHMAN', $registration->emergency_contact_name);
        $this->assertSame('AISHA RAHMAN', $registration->full_name);
        $this->assertSame('aisha@example.com', $registration->email);
        $this->assertSame('A-12-3', $registration->unit_number);
        $this->assertSame('12 JALAN MAWAR', $registration->address_line_1);
        $this->assertNull($registration->address_line_2);
        $this->assertSame('55100', $registration->postcode);
        $this->assertSame('0198887766', $registration->emergency_contact_number);
        Mail::assertNothingSent();
    }

    public function test_payment_callback_confirms_registration_only_when_payment_succeeds(): void
    {
        $registration = $this->makeRegistration('TSC-PAID');

        $this->post(route('welfare.sisterhood.payment.callback'), $this->gatewayPayload('TSC-PAID', '100'))
            ->assertOk();

        $registration->refresh();
        $this->assertTrue($registration->isPaid());
        $this->assertSame('paid', $registration->payment_status);
        $this->assertSame(SisterhoodRegistration::STATUS_SUCCESS, (int) $registration->status);
        $this->assertNotNull($registration->paid_at);

        Mail::assertSent(SisterhoodRegistrationMail::class, function (SisterhoodRegistrationMail $mail) {
            $html = $this->renderMail($mail);

            return $mail->hasTo('aisha@example.com')
                && $mail->statusLabel === 'Paid'
                && str_contains($html, 'Ms. AISHA RAHMAN')
                && str_contains($html, '12 JALAN MAWAR')
                && str_contains($html, SisterhoodCollective::EVENT_DATE)
                && str_contains($html, SisterhoodCollective::EVENT_TIME)
                && str_contains($html, SisterhoodCollective::EVENT_VENUE)
                && str_contains($html, 'RM' . number_format(SisterhoodCollective::FEE, 2))
                && str_contains($html, 'Your entry QR code')
                && str_contains($html, 'Check-in code: ' . $mail->registration->check_in_code)
                && str_starts_with($mail->qrPng, "\x89PNG")
                && str_contains($this->renderMailText($mail), 'Check-in code: ' . $mail->registration->check_in_code);
        });

        $this->post(route('welfare.sisterhood.payment.callback'), $this->gatewayPayload('TSC-PAID', '100'))
            ->assertOk();

        Mail::assertSent(SisterhoodRegistrationMail::class, 1);
    }

    public function test_failed_or_unverified_payment_leaves_registration_incomplete(): void
    {
        $failed = $this->makeRegistration('TSC-FAIL');
        $this->post(route('welfare.sisterhood.payment.callback'), $this->gatewayPayload('TSC-FAIL', 'E1'))
            ->assertOk();
        $failed->refresh();
        $this->assertFalse($failed->isPaid());
        $this->assertSame('failed', $failed->payment_status);
        Mail::assertSent(SisterhoodRegistrationMail::class, function (SisterhoodRegistrationMail $mail) {
            $html = $this->renderMail($mail);

            return $mail->hasTo('aisha@example.com')
                && $mail->statusKey === 'failed'
                && str_contains($html, 'Registration Incomplete')
                && str_contains($html, SisterhoodCollective::EVENT_TIME)
                && str_contains($html, SisterhoodCollective::SUPPORT_PHONE)
                && str_contains($html, 'has not been successfully received')
                && ! str_contains($html, 'have been successfully received')
                && ! str_contains($html, 'Your entry QR code')
                && $mail->qrPng === '';
        });

        $unverified = $this->makeRegistration('TSC-BADHASH');
        $payload = $this->gatewayPayload('TSC-BADHASH', '100');
        $payload['ord_key'] = 'not-the-gateway-hash';
        $this->post(route('welfare.sisterhood.payment.callback'), $payload)->assertOk();
        $unverified->refresh();
        $this->assertSame('pending', $unverified->payment_status);
        $this->assertFalse($unverified->isPaid());
        Mail::assertSent(SisterhoodRegistrationMail::class, 1);
    }

    public function test_return_from_gateway_shows_the_matching_message(): void
    {
        $paid = $this->makeRegistration('TSC-RETURN-OK');
        $this->post(route('welfare.sisterhood.payment.return'), $this->gatewayPayload('TSC-RETURN-OK', '100'))
            ->assertRedirect(route('welfare.sisterhood.result'));

        $this->get(route('welfare.sisterhood.result'))
            ->assertOk()
            ->assertSee('Registration Successful!', false)
            ->assertSee('Your registration and payment of RM' . number_format(SisterhoodCollective::FEE, 0) . ' have been successfully received.', false)
            ->assertDontSee('Registration Incomplete', false);

        $paid->refresh();
        $this->assertTrue($paid->isPaid());

        $this->flushSession();
        $this->makeRegistration('TSC-RETURN-NO');
        $this->post(route('welfare.sisterhood.payment.return'), $this->gatewayPayload('TSC-RETURN-NO', 'E2'))
            ->assertRedirect(route('welfare.sisterhood.result'));

        $this->get(route('welfare.sisterhood.result'))
            ->assertOk()
            ->assertSee('Registration Incomplete', false)
            ->assertSee('payment of RM' . number_format(SisterhoodCollective::FEE, 0) . ' has not been successfully received', false)
            ->assertSee('014-3021800', false)
            ->assertDontSee('Registration Successful!', false);
    }

    public function test_browser_return_confirms_a_successful_gateway_code_when_the_hash_does_not_match(): void
    {
        $registration = $this->makeRegistration('TSC-LIVE');
        $payload = $this->gatewayPayload('TSC-LIVE', '100');
        $payload['ord_key'] = 'gateway-response-hash';
        $payload['wcID'] = '50540856';

        $this->post(route('welfare.sisterhood.payment.return'), $payload)
            ->assertRedirect(route('welfare.sisterhood.result'));

        $registration->refresh();
        $this->assertTrue($registration->isPaid());
        $this->assertSame('50540856', $registration->payment_reference);

        $this->get(route('welfare.sisterhood.result'))
            ->assertOk()
            ->assertSee('Registration Successful!', false);

        $wrongAmount = $this->makeRegistration('TSC-LIVE-AMT');
        $mismatch = $this->gatewayPayload('TSC-LIVE-AMT', '100');
        $mismatch['ord_key'] = 'gateway-response-hash';
        $mismatch['ord_totalamt'] = '99.00';

        $this->post(route('welfare.sisterhood.payment.return'), $mismatch)
            ->assertRedirect(route('welfare.sisterhood.result'));

        $wrongAmount->refresh();
        $this->assertFalse($wrongAmount->isPaid());
        $this->assertSame('pending', $wrongAmount->payment_status);
    }

    public function test_direct_result_without_payment_is_incomplete(): void
    {
        $this->get(route('welfare.sisterhood.result'))
            ->assertOk()
            ->assertSee('Registration Incomplete', false);
    }

    public function test_a_confirmed_nric_cannot_register_again(): void
    {
        $existing = $this->makeRegistration('TSC-EXISTING');
        $existing->update([
            'payment_status' => 'paid',
            'status' => SisterhoodRegistration::STATUS_SUCCESS,
            'paid_at' => now(),
            'nric_passport' => '900101-01-1234',
        ]);

        $response = $this->from(route('welfare.sisterhood.create'))
            ->post(route('welfare.sisterhood.store'), $this->payload(['nric_passport' => '900101011234']));

        $response->assertRedirect(route('welfare.sisterhood.create'));
        $response->assertSessionHasErrors('nric_passport');
        $this->assertDatabaseCount('sisterhood_registrations', 1);
    }

    public function test_registration_closes_after_17_november_2026(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-18 00:30:00', 'Asia/Kuala_Lumpur'));

        $this->get(route('welfare.sisterhood.create'))
            ->assertOk()
            ->assertSee('Registration is closed.', false);

        $this->post(route('welfare.sisterhood.store'), $this->payload())
            ->assertRedirect(route('welfare.sisterhood.create'));

        $this->assertDatabaseCount('sisterhood_registrations', 0);
    }

    public function test_admin_can_view_and_edit_registration_and_payment(): void
    {
        $registration = $this->makeRegistration('TSC-ADMIN');
        $this->actingAs(User::factory()->create());

        $this->get(route('welfare.admin.sisterhood.index'))
            ->assertOk()
            ->assertSee('AISHA RAHMAN', false)
            ->assertSee('Pending', false);

        $this->get(route('welfare.admin.sisterhood.show', $registration))
            ->assertOk()
            ->assertSee('TSC-ADMIN', false)
            ->assertSee('aisha@example.com', false);

        $this->put(route('welfare.admin.sisterhood.update', $registration), $this->payload([
            'preferred_name' => 'Aish',
            'payment_status' => 'paid',
            'payment_reference' => 'MANUAL-1',
            'admin_notes' => 'Paid at the counter.',
        ]))->assertRedirect(route('welfare.admin.sisterhood.show', $registration));

        $registration->refresh();
        $this->assertTrue($registration->isPaid());
        $this->assertSame(SisterhoodRegistration::STATUS_SUCCESS, (int) $registration->status);
        $this->assertSame('AISH', $registration->preferred_name);
        $this->assertSame('MANUAL-1', $registration->payment_reference);
        $this->assertSame('Paid at the counter.', $registration->admin_notes);

        $this->get(route('welfare.admin.sisterhood.show', $registration))
            ->assertSee('Registration updated. Payment is recorded and the registration is confirmed.', false);

        Mail::assertSent(SisterhoodRegistrationMail::class, function (SisterhoodRegistrationMail $mail) {
            return $mail->hasTo('aisha@example.com') && $mail->statusKey === 'success';
        });

        $this->put(route('welfare.admin.sisterhood.update', $registration), $this->payload([
            'preferred_name' => 'Aish',
            'payment_status' => 'paid',
            'payment_reference' => 'MANUAL-1',
            'admin_notes' => 'Paid at the counter.',
        ]));

        Mail::assertSent(SisterhoodRegistrationMail::class, 1);
    }

    public function test_admin_can_show_the_qr_code_and_mark_attendance(): void
    {
        $registration = $this->makeRegistration('TSC-QR');
        $registration->forceFill([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ])->save();

        $this->actingAs(User::factory()->create());

        $this->get(route('welfare.admin.sisterhood.show', $registration))
            ->assertOk()
            ->assertSee('Not present', false)
            ->assertSee($registration->check_in_code, false)
            ->assertSee(route('welfare.admin.sisterhood.qr', $registration), false);

        $this->get(route('welfare.admin.sisterhood.qr', $registration))
            ->assertOk()
            ->assertHeader('content-type', 'image/png');

        $scan = "The Sisterhood Collective 2026\nName: {$registration->full_name}\nCheck-in: {$registration->check_in_code}";

        $this->postJson(route('welfare.admin.sisterhood.attendance.store'), [
            'code' => $scan,
            'present' => true,
        ])->assertOk()
            ->assertJsonPath('registration.attendance', 'Present')
            ->assertJsonPath('registration.name', $registration->full_name);

        $registration->refresh();
        $this->assertTrue($registration->isPresent());

        $this->postJson(route('welfare.admin.sisterhood.attendance.store'), [
            'code' => $registration->check_in_code,
            'present' => false,
        ])->assertOk()
            ->assertJsonPath('registration.attendance', 'Not present');

        $registration->refresh();
        $this->assertFalse($registration->isPresent());
    }

    public function test_unpaid_registration_cannot_be_marked_present(): void
    {
        $registration = $this->makeRegistration('TSC-UNPAID-QR');
        $this->actingAs(User::factory()->create());

        $this->postJson(route('welfare.admin.sisterhood.attendance.store'), [
            'code' => $registration->check_in_code,
            'present' => true,
        ])->assertStatus(422);

        $registration->refresh();
        $this->assertFalse($registration->isPresent());
    }

    public function test_guest_cannot_open_the_admin_section(): void
    {
        $this->get(route('welfare.admin.sisterhood.index'))
            ->assertRedirect(route('welfare.admin.login'));
    }

    private function renderMail(SisterhoodRegistrationMail $mail): string
    {
        return view('emails.sisterhood_registration', [
            'subject' => $mail->subject,
            'registration' => $mail->registration,
            'statusKey' => $mail->statusKey,
            'statusLabel' => $mail->statusLabel,
            'registrationRows' => $mail->registrationRows,
            'eventRows' => $mail->eventRows,
            'qrPng' => $mail->qrPng,
            'qrDataUri' => $mail->qrDataUri,
        ])->render();
    }

    private function renderMailText(SisterhoodRegistrationMail $mail): string
    {
        return view('emails.sisterhood_registration_text', [
            'registration' => $mail->registration,
            'statusKey' => $mail->statusKey,
            'statusLabel' => $mail->statusLabel,
            'registrationRows' => $mail->registrationRows,
            'eventRows' => $mail->eventRows,
        ])->render();
    }

    private function makeRegistration(string $orderId): SisterhoodRegistration
    {
        return SisterhoodRegistration::create(array_merge(
            SisterhoodCollective::attributesFrom($this->payload()),
            [
                'amount' => SisterhoodCollective::FEE,
                'payment_status' => 'pending',
                'status' => SisterhoodRegistration::STATUS_PENDING,
                'payment_method' => 'KiplePay',
                'order_id' => $orderId,
            ]
        ));
    }

    private function payload(array $overrides = []): array
    {
        $payload = [
            'full_name' => 'Aisha Rahman',
            'salutation' => 'Ms.',
            'preferred_name' => 'Aisha Rahman',
            'nric_passport' => '900101-01-1234',
            'email' => 'aisha@example.com',
            'contact_number' => '0123456789',
            'age' => 32,
            'profession' => 'Academic / Educator',
            'education_level' => "Bachelor's Degree",
            'unit_number' => 'A-12-3',
            'address_line_1' => '12 Jalan Mawar',
            'address_line_2' => '',
            'postcode' => '55100',
            'state' => 'Selangor',
            'areas_of_interest' => ['Community Welfare'],
            'looking_forward' => ['All of the above'],
            'food_preference' => 'Vegetarian',
            'emergency_contact_name' => 'Farah Rahman',
            'emergency_contact_number' => '0198887766',
            'emergency_relationship' => 'Sister',
            'heard_from' => 'WhatsApp',
        ];

        foreach (array_keys(SisterhoodCollective::declarations()) as $key) {
            $payload['declaration_' . $key] = '1';
        }

        return array_merge($payload, $overrides);
    }

    private function gatewayPayload(string $orderNo, string $returncode): array
    {
        $amount = SisterhoodCollective::FEE;
        $amountFormatted = number_format($amount, 2, '.', '');
        $amountWithoutDecimal = number_format($amount, 2, '', '');
        $hash = sha1('SECRET' . 'MID' . $orderNo . $amountWithoutDecimal);

        return [
            'ord_mercref' => $orderNo,
            'ord_totalamt' => $amountFormatted,
            'ord_key' => $hash,
            'returncode' => $returncode,
            'wcID' => 'WC-TSC-1',
        ];
    }
}
