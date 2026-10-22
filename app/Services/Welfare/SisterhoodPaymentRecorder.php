<?php

namespace App\Services\Welfare;

use App\Models\SisterhoodRegistration;
use App\Services\KiplePayService;
use Illuminate\Support\Facades\Log;

class SisterhoodPaymentRecorder
{
    /**
     * Confirm a registration only when the gateway hash matches and the return code is successful.
     * A paid registration is never moved back to failed by a later callback.
     */
    public function record(SisterhoodRegistration $registration, array $payload): bool
    {
        if ($registration->isPaid()) {
            return true;
        }

        $kiplePay = KiplePayService::make('guest');
        $hashPresent = ! empty($payload['ord_key']) || ! empty($payload['merchant_hashvalue']);
        $hashValid = $hashPresent
            ? $kiplePay->validateCallback($payload, (float) $registration->amount)
            : false;

        if (! $hashValid) {
            Log::warning("Sisterhood registration callback validation failed for {$registration->order_id}", $payload);

            return false;
        }

        $paidAmount = number_format((float) ($payload['ord_totalamt'] ?? 0), 2, '.', '');
        $expectedAmount = number_format((float) $registration->amount, 2, '.', '');

        if ($paidAmount !== $expectedAmount) {
            Log::warning("Sisterhood registration amount mismatch for {$registration->order_id}", $payload);

            return false;
        }

        $isSuccess = $kiplePay->isSuccessfulReturn($payload['returncode'] ?? null);

        $registration->update([
            'payment_status' => $isSuccess ? 'paid' : 'failed',
            'paid_at' => $isSuccess ? now() : null,
            'payment_reference' => $payload['wcID'] ?? $registration->payment_reference,
            'payment_payload' => $payload,
        ]);

        Log::info('Sisterhood registration ' . $registration->order_id . ' payment status updated to ' . $registration->payment_status);

        return true;
    }
}
