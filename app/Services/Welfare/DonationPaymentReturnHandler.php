<?php

namespace App\Services\Welfare;

use App\Models\Donation;
use App\Services\KiplePayService;
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
        $payload = $request->all();
        Log::info("{$logContext} Return Data:", $payload);

        $orderRef = $this->resolveOrderReference($request);
        $donation = $orderRef
            ? Donation::where('order_id', $orderRef)->first()
            : null;

        if ($donation) {
            $this->recorder->record($donation, $payload, false, $logContext);
            session()->forget('pending_donation_order_id');
        } else {
            Log::warning("{$logContext} Return: donation not found", [
                'order_ref' => $orderRef,
                'payload' => $payload,
            ]);
        }

        $kiplePay = KiplePayService::make('guest');

        if ($kiplePay->isSuccessfulReturn($request->returncode)) {
            return redirect()
                ->route($successRoute)
                ->with('success', 'Thank you for your generous contribution to MUKMIN.');
        }

        return redirect()
            ->route($failureRoute)
            ->with('error', 'Payment was not successful (Code: ' . ($request->returncode ?? 'N/A') . '). Please try again.');
    }

    private function resolveOrderReference(Request $request): ?string
    {
        foreach (['ord_mercref', 'orderno', 'order_id', 'merchant_ref'] as $field) {
            $value = trim((string) $request->input($field, ''));
            if ($value !== '') {
                return $value;
            }
        }

        $sessionOrder = session('pending_donation_order_id');
        if (is_string($sessionOrder) && $sessionOrder !== '') {
            return $sessionOrder;
        }

        return null;
    }
}
