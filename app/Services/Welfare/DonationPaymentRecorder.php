<?php

namespace App\Services\Welfare;

use App\Models\Donation;
use App\Services\KiplePayService;
use Illuminate\Support\Facades\Log;

class DonationPaymentRecorder
{
    public function __construct(
        protected DonationPaymentNotifier $notifier
    ) {}

    /**
     * @param  bool  $requireHash  When true (callback), hash validation must pass.
     */
    public function record(Donation $donation, array $payload, bool $requireHash = true, string $logContext = 'Donation'): bool
    {
        $kiplePay = KiplePayService::make('guest');
        $hashPresent = ! empty($payload['ord_key']) || ! empty($payload['merchant_hashvalue']);
        $hashValid = $hashPresent
            ? $kiplePay->validateCallback($payload, (float) $donation->amount)
            : false;

        if ($requireHash) {
            if (! $hashValid) {
                Log::warning("{$logContext} callback validation failed for {$donation->order_id}", $payload);

                return false;
            }
        } elseif ($hashPresent && ! $hashValid) {
            Log::warning("{$logContext} return hash mismatch for {$donation->order_id}, using returncode instead", $payload);
        }

        $previousStatus = $donation->status;
        $isSuccess = $kiplePay->isSuccessfulReturn($payload['returncode'] ?? null);

        if ($requireHash || $hashValid || $isSuccess) {
            $donation->update([
                'status' => $isSuccess ? 'paid' : 'failed',
                'payment_payload' => $payload,
            ]);

            Log::info("{$logContext} {$donation->order_id} status updated to: " . ($isSuccess ? 'paid' : 'failed'));

            $this->notifier->notifyIfStatusChanged($donation, $previousStatus, $payload);

            return true;
        }

        Log::warning("{$logContext} could not confirm payment for {$donation->order_id}", $payload);

        return false;
    }
}
