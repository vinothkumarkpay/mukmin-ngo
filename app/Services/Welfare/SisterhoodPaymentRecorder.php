<?php

namespace App\Services\Welfare;

use App\Models\SisterhoodRegistration;
use App\Services\KiplePayService;
use Illuminate\Support\Facades\Log;

class SisterhoodPaymentRecorder
{
    private SisterhoodRegistrationNotifier $notifier;

    public function __construct(SisterhoodRegistrationNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Confirm a registration when the gateway hash matches.
     * The browser return may still confirm a successful payment when the amount matches,
     * because the sandbox response hash does not use the request hash.
     * A paid registration is never moved back to failed by a later callback.
     */
    public function record(SisterhoodRegistration $registration, array $payload, bool $requireHash = true): bool
    {
        if ($registration->isPaid()) {
            return true;
        }

        $previousStatus = (string) $registration->payment_status;

        $kiplePay = KiplePayService::make('guest');
        $hashPresent = ! empty($payload['ord_key']) || ! empty($payload['merchant_hashvalue']);
        $hashValid = $hashPresent
            ? $kiplePay->validateCallback($payload, (float) $registration->amount)
            : false;
        $paidAmount = number_format((float) ($payload['ord_totalamt'] ?? 0), 2, '.', '');
        $expectedAmount = number_format((float) $registration->amount, 2, '.', '');
        $amountMatches = $paidAmount === $expectedAmount;
        $isSuccess = $kiplePay->isSuccessfulReturn($payload['returncode'] ?? null);

        if (! $hashValid) {
            if ($requireHash || ! $isSuccess || ! $amountMatches) {
                Log::warning("Sisterhood registration callback validation failed for {$registration->order_id}", $payload);

                return false;
            }

            Log::warning("Sisterhood registration return hash mismatch for {$registration->order_id}, using return code", $payload);
        } elseif (! $amountMatches) {
            Log::warning("Sisterhood registration amount mismatch for {$registration->order_id}", $payload);

            return false;
        }

        $attributes = [
            'payment_status' => $isSuccess ? 'paid' : 'failed',
            'paid_at' => $isSuccess ? now() : null,
            'payment_reference' => $payload['wcID'] ?? $registration->payment_reference,
            'payment_payload' => $payload,
        ];

        if (! $registration->isTest()) {
            $attributes['status'] = $isSuccess
                ? SisterhoodRegistration::STATUS_SUCCESS
                : SisterhoodRegistration::STATUS_FAIL;
        }

        $registration->update($attributes);

        Log::info('Sisterhood registration ' . $registration->order_id . ' payment status updated to ' . $registration->payment_status);

        $this->notifier->notifyIfStatusChanged($registration, $previousStatus);

        return true;
    }
}
