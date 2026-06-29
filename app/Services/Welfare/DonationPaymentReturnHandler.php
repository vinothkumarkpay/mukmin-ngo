<?php

namespace App\Services\Welfare;

use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationPaymentReturnHandler
{
    public function __construct(
        protected DonationPaymentRecorder $recorder
    ) {}

    public function handle(
        Request $request,
        string $successRoute,
        string $failureRoute,
        string $logContext = 'Donation'
    ): RedirectResponse {
        Log::info("{$logContext} Return Data:", $request->all());

        if ($request->filled('ord_mercref')) {
            $donation = Donation::where('order_id', $request->ord_mercref)->first();

            if ($donation) {
                $this->recorder->record($donation, $request->all(), false, $logContext);
            }
        }

        if ($request->returncode == '100') {
            return redirect()
                ->route($successRoute)
                ->with('success', 'Thank you for your generous donation!');
        }

        return redirect()
            ->route($failureRoute)
            ->with('error', 'Payment was not successful (Code: ' . ($request->returncode ?? 'N/A') . '). Please try again.');
    }
}
