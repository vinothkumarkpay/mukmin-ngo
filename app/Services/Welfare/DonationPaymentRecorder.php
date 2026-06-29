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
     * @param  bool  $requireHash  When true (callback), a valid ord_key is required.
     */
    public function record(Donation $donation, array $payload, bool $requireHash = true, string $logContext = 'Donation'): bool
    {
        $kiplePay = KiplePayService::make('guest');

        if (! empty($payload['ord_key'])) {
            if (! $kiplePay->validateCallback($payload)) {
                Log::warning("{$logContext} hash validation failed for {$donation->order_id}");

                return false;
            }
        } elseif ($requireHash) {
            Log::warning("{$logContext} missing hash for {$donation->order_id}");

            return false;
        }

        $previousStatus = $donation->status;
        $isSuccess = (($payload['returncode'] ?? '') == '100');

        $donation->update([
            'status' => $isSuccess ? 'paid' : 'failed',
            'payment_payload' => $payload,
        ]);

        Log::info("{$logContext} {$donation->order_id} status updated to: " . ($isSuccess ? 'paid' : 'failed'));

        $this->notifier->notifyIfStatusChanged($donation, $previousStatus, $payload);

        return true;
    }
}
