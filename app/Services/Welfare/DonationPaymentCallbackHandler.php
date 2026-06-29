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
            return response('Order Not Found', 404);
        }

        $this->recorder->record($donation, $request->all(), true, $logContext);

        return response('OK');
    }
}
