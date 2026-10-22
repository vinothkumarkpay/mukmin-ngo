<?php

namespace Tests\Feature;

use App\Models\SisterhoodRegistration;
use App\Models\User;
use App\Support\SisterhoodCollective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SisterhoodRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_home_page_hides_the_registration_link_until_launch(): void
    {
        $response = $this->get(route('welfare.home'));

        $response->assertOk();
        $response->assertDontSee('The Sisterhood Collective 2026', false);
        $response->assertDontSee(route('welfare.sisterhood.create'), false);
    }

    public function test_registration_form_lists_the_programme_fields(): void
    {
        $response = $this->get(route('welfare.sisterhood.create'));

        $response->assertOk();
        $response->assertSee('Full Name (as per NRIC/Passport)', false);
        $response->assertSee('Salutation / Gelaran', false);
        $response->assertSee('Area of Interest', false);
        $response->assertSee('Participation Declaration', false);
        $response->assertSee('RM30', false);
        $response->assertSee('class="btn-submit">Register</button>', false);
    }

    public function test_incomplete_form_is_rejected_and_does_not_create_a_registration(): void
    {
        $response = $this->from(route('welfare.sisterhood.create'))
            ->post(route('welfare.sisterhood.store'), [
                'full_name' => 'Aisha Rahman',
                'age' => 17,
            ]);

        $response->assertRedirect(route('welfare.sisterhood.create'));
        $response->assertSessionHasErrors(['salutation', 'age', 'email', 'declaration_accurate']);
        $this->assertDatabaseCount('sisterhood_registrations', 0);
    }

    public function test_completed_form_starts_a_pending_rm30_payment(): void
    {
        $response = $this->post(route('welfare.sisterhood.store'), $this->payload());

        $response->assertOk();
        $response->assertSee('Redirecting to Payment Gateway', false);
        $response->assertSee('value="30.00"', false);
        $response->assertSee('https://sandbox.webcash.com.my/wcgatewayinit.php', false);

        $registration = SisterhoodRegistration::first();
        $this->assertNotNull($registration);
        $this->assertSame('pending', $registration->payment_status);
        $this->assertNull($registration->paid_at);
        $this->assertFalse($registration->isPaid());
        $this->assertSame('30.00', $registration->amount);
        $this->assertSame('900101011234', $registration->nric_normalized);
    }

    public function test_payment_callback_confirms_registration_only_when_payment_succeeds(): void
    {
        $registration = $this->makeRegistration('TSC-PAID');

        $this->post(route('welfare.sisterhood.payment.callback'), $this->gatewayPayload('TSC-PAID', '100'))
            ->assertOk();

        $registration->refresh();
        $this->assertTrue($registration->isPaid());
        $this->assertSame('paid', $registration->payment_status);
        $this->assertNotNull($registration->paid_at);
    }

    public function test_failed_or_unverified_payment_leaves_registration_incomplete(): void
    {
        $failed = $this->makeRegistration('TSC-FAIL');
        $this->post(route('welfare.sisterhood.payment.callback'), $this->gatewayPayload('TSC-FAIL', 'E1'))
            ->assertOk();
        $failed->refresh();
        $this->assertFalse($failed->isPaid());
        $this->assertSame('failed', $failed->payment_status);

        $unverified = $this->makeRegistration('TSC-BADHASH');
        $payload = $this->gatewayPayload('TSC-BADHASH', '100');
        $payload['ord_key'] = 'not-the-gateway-hash';
        $this->post(route('welfare.sisterhood.payment.callback'), $payload)->assertOk();
        $unverified->refresh();
        $this->assertSame('pending', $unverified->payment_status);
        $this->assertFalse($unverified->isPaid());
    }

    public function test_return_from_gateway_shows_the_matching_message(): void
    {
        $paid = $this->makeRegistration('TSC-RETURN-OK');
        $this->post(route('welfare.sisterhood.payment.return'), $this->gatewayPayload('TSC-RETURN-OK', '100'))
            ->assertRedirect(route('welfare.sisterhood.result'));

        $this->get(route('welfare.sisterhood.result'))
            ->assertOk()
            ->assertSee('Registration Successful!', false)
            ->assertSee('Your registration and payment of RM30 have been successfully received.', false)
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
            ->assertSee('payment of RM30 has not been successfully received', false)
            ->assertSee('014-3021800', false)
            ->assertDontSee('Registration Successful!', false);
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
            ->assertSee('Aisha Rahman', false)
            ->assertSee('Incomplete', false);

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
        $this->assertSame('Aish', $registration->preferred_name);
        $this->assertSame('MANUAL-1', $registration->payment_reference);
        $this->assertSame('Paid at the counter.', $registration->admin_notes);

        $this->get(route('welfare.admin.sisterhood.show', $registration))
            ->assertSee('Registration updated. Payment is recorded and the registration is confirmed.', false);
    }

    public function test_guest_cannot_open_the_admin_section(): void
    {
        $this->get(route('welfare.admin.sisterhood.index'))
            ->assertRedirect(route('welfare.admin.login'));
    }

    private function makeRegistration(string $orderId): SisterhoodRegistration
    {
        return SisterhoodRegistration::create(array_merge(
            SisterhoodCollective::attributesFrom($this->payload()),
            [
                'amount' => SisterhoodCollective::FEE,
                'payment_status' => 'pending',
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
            'full_address' => '12 Jalan Mawar, Kuala Lumpur',
            'state' => 'Selangor',
            'areas_of_interest' => ['Community Welfare'],
            'looking_forward' => ['All of the above'],
            'food_preference' => 'Vegetarian',
            'emergency_contact' => 'Farah Rahman 0198887766',
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
