<?php

namespace Tests\Feature;

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
