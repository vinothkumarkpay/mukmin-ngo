<?php

namespace App\Services\Welfare;

use App\Models\Donation;
use App\Services\KiplePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationPaymentCallbackHandler
{
    public function __construct(
        protected DonationPaymentRecorder $recorder
    ) {}

    public function handle(Request $request, string $logContext = 'Donation'): \Illuminate\Http\Response
    {
        Log::info("{$logContext} Callback Data:", $request->all());

        $donation = Donation::where('order_id', $request->ord_mercref)->first();
        if (! $donation) {
            Log::warning("{$logContext} Callback: donation not found for {$request->ord_mercref}", $request->all());

            return response('Order Not Found', 404);
        }

        $recorded = $this->recorder->record($donation, $request->all(), true, $logContext);

        if ($recorded) {
            session()->forget('pending_donation_order_id');
        }

        return response('OK');
    }
}
