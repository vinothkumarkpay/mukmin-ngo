<?php

namespace Tests\Feature;

use App\Mail\DonationDonorPaymentMail;
use App\Mail\DonationPaymentMail;
use App\Models\Donation;
use App\Services\Welfare\DonationPaymentCallbackHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DonationPaymentNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_payment_callback_sends_email_to_donate_inbox_when_paid(): void
    {
        $orderNo = 'MUKMIN-TESTPAID';
        $amount = 50.00;

        Donation::create([
            'name' => 'Ahmad Ali',
            'email' => 'ahmad@example.com',
            'phone' => '+60123456789',
            'amount' => $amount,
            'message' => 'For education fund',
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $payload = $this->buildCallbackPayload($orderNo, $amount, '100');

        $response = app(DonationPaymentCallbackHandler::class)->handle(
            request()->merge($payload),
            'Donation'
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('donations', [
            'order_id' => $orderNo,
            'status' => 'paid',
        ]);

        Mail::assertSent(DonationPaymentMail::class, function ($mail) use ($orderNo) {
            $mail->build();

            return $mail->hasTo('donate@mukmin.org')
                && $mail->donation->order_id === $orderNo
                && str_contains($mail->subject, 'Paid');
        });

        Mail::assertSent(DonationDonorPaymentMail::class, function ($mail) use ($orderNo) {
            $mail->build();

            return $mail->hasTo('ahmad@example.com')
                && $mail->donation->order_id === $orderNo
                && $mail->statusKey === 'success';
        });
    }

    public function test_payment_callback_sends_email_when_failed(): void
    {
        $orderNo = 'MUKMIN-TESTFAIL';
        $amount = 25.00;

        Donation::create([
            'name' => 'Siti Rahman',
            'email' => 'siti@example.com',
            'phone' => '+60198765432',
            'amount' => $amount,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $payload = $this->buildCallbackPayload($orderNo, $amount, 'E1');

        app(DonationPaymentCallbackHandler::class)->handle(
            request()->merge($payload),
            'Donation'
        );

        Mail::assertSent(DonationPaymentMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('donate@mukmin.org')
                && str_contains($mail->subject, 'Failed');
        });

        Mail::assertSent(DonationDonorPaymentMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('siti@example.com')
                && $mail->statusKey === 'failed';
        });
    }

    public function test_duplicate_callback_does_not_send_duplicate_email(): void
    {
        $orderNo = 'MUKMIN-TESTDUP';
        $amount = 30.00;

        Donation::create([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'phone' => '+60111111111',
            'amount' => $amount,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $payload = $this->buildCallbackPayload($orderNo, $amount, '100');
        $handler = app(DonationPaymentCallbackHandler::class);

        $handler->handle(request()->merge($payload), 'Donation');
        $handler->handle(request()->merge($payload), 'Donation');

        Mail::assertSent(DonationPaymentMail::class, 1);
        Mail::assertSent(DonationDonorPaymentMail::class, 1);
    }

    public function test_payment_return_sends_email_to_donate_inbox_when_paid(): void
    {
        $orderNo = 'MUKMIN-TESTRETURN';
        $amount = 75.00;

        Donation::create([
            'name' => 'Return Tester',
            'email' => 'return@example.com',
            'phone' => '+60122222222',
            'amount' => $amount,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $payload = $this->buildCallbackPayload($orderNo, $amount, '100');

        $response = app(\App\Services\Welfare\DonationPaymentReturnHandler::class)->handle(
            request()->merge($payload),
            'welfare.donate.thank-you',
            'welfare.donate',
            'Donation'
        );

        $this->assertEquals(route('welfare.donate.thank-you'), $response->getTargetUrl());
        $this->assertDatabaseHas('donations', [
            'order_id' => $orderNo,
            'status' => 'paid',
        ]);

        Mail::assertSent(DonationPaymentMail::class, function ($mail) use ($orderNo) {
            $mail->build();

            return $mail->hasTo('donate@mukmin.org')
                && $mail->donation->order_id === $orderNo;
        });

        Mail::assertSent(DonationDonorPaymentMail::class, function ($mail) use ($orderNo) {
            $mail->build();

            return $mail->hasTo('return@example.com')
                && $mail->donation->order_id === $orderNo
                && $mail->statusKey === 'success';
        });
    }

    public function test_return_marks_paid_even_when_hash_mismatch(): void
    {
        $orderNo = 'MUKMIN-TESTHASH';
        $amount = 15.00;

        Donation::create([
            'name' => 'Hash Tester',
            'email' => 'hash@example.com',
            'phone' => '+60144444444',
            'amount' => $amount,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $payload = $this->buildCallbackPayload($orderNo, $amount, '100');
        $payload['ord_key'] = 'invalid-hash';

        app(\App\Services\Welfare\DonationPaymentReturnHandler::class)->handle(
            request()->merge($payload),
            'welfare.donate.thank-you',
            'welfare.donate',
            'Donation'
        );

        $this->assertDatabaseHas('donations', [
            'order_id' => $orderNo,
            'status' => 'paid',
        ]);
    }

    public function test_return_uses_session_order_when_mercref_missing(): void
    {
        $orderNo = 'DEMO-TESTSESSION';
        $amount = 20.00;

        Donation::create([
            'name' => 'Session Tester',
            'email' => 'session@example.com',
            'phone' => '+60155555555',
            'amount' => $amount,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $payload = $this->buildCallbackPayload($orderNo, $amount, '100');
        unset($payload['ord_mercref']);

        session(['pending_donation_order_id' => $orderNo]);

        app(\App\Services\Welfare\DonationPaymentReturnHandler::class)->handle(
            request()->merge($payload),
            'welfare.donate-demo.thank-you',
            'welfare.donate-demo',
            'Donation Demo'
        );

        $this->assertDatabaseHas('donations', [
            'order_id' => $orderNo,
            'status' => 'paid',
        ]);
    }

    public function test_return_then_callback_sends_only_one_email(): void
    {
        $orderNo = 'MUKMIN-TESTBOTH';
        $amount = 40.00;

        Donation::create([
            'name' => 'Both Tester',
            'email' => 'both@example.com',
            'phone' => '+60133333333',
            'amount' => $amount,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $payload = $this->buildCallbackPayload($orderNo, $amount, '100');

        app(\App\Services\Welfare\DonationPaymentReturnHandler::class)->handle(
            request()->merge($payload),
            'welfare.donate.thank-you',
            'welfare.donate',
            'Donation'
        );

        app(DonationPaymentCallbackHandler::class)->handle(
            request()->merge($payload),
            'Donation'
        );

        Mail::assertSent(DonationPaymentMail::class, 1);
        Mail::assertSent(DonationDonorPaymentMail::class, 1);
    }

    public function test_donation_store_sends_pending_email_to_donor(): void
    {
        $response = $this->post(route('welfare.donate.store'), [
            'name' => 'Pending Donor',
            'email' => 'pending@example.com',
            'phone' => '+60123456789',
            'amount' => 100,
            'message' => 'Education fund',
        ]);

        $response->assertStatus(200);

        Mail::assertSent(DonationDonorPaymentMail::class, function ($mail) {
            $mail->build();

            return $mail->hasTo('pending@example.com')
                && $mail->statusKey === 'pending'
                && str_contains($mail->subject, 'Pending');
        });

        Mail::assertNotSent(DonationPaymentMail::class);
    }

    private function buildCallbackPayload(string $orderNo, float $amount, string $returncode): array
    {
        $merchantId = config('services.kiplepay.merchant_id_guest', config('services.kiplepay.merchant_id'));
        $secretKey = config('services.kiplepay.secret_key_guest', config('services.kiplepay.secret_key'));
        $amountFormatted = number_format($amount, 2, '.', '');
        $amountWithoutDecimal = number_format($amount, 2, '', '');
        $hash = sha1($secretKey . $merchantId . $orderNo . $amountWithoutDecimal);

        return [
            'ord_mercref' => $orderNo,
            'ord_totalamt' => $amountFormatted,
            'ord_key' => $hash,
            'returncode' => $returncode,
            'wcID' => 'WC-TEST-123',
        ];
    }
}
