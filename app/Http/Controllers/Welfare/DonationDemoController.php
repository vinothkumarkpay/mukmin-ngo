<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Models\DonationDemo;
use App\Services\KiplePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationDemoController extends Controller
{
    public function create()
    {
        return view('welfare.pages.donate_demo');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'amount' => 'required|numeric|min:10|max:999999',
            'message' => 'nullable|string|max:1200',
        ]);

        $orderNo = 'DEMO-' . strtoupper(uniqid());

        DonationDemo::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $kiplePay = KiplePayService::make('guest');

        $paymentData = $kiplePay->preparePayment(
            $orderNo,
            $request->amount,
            'Donation Demo',
            $request->name,
            $request->email,
            route('welfare.donate-demo.payment.return'),
            route('welfare.donate-demo.payment.callback')
        );

        return view('welfare.pages.donate_demo_payment_redirect', compact('paymentData'));
    }

    public function paymentReturn(Request $request)
    {
        Log::info('Donate Demo Return Data:', $request->all());

        if ($request->returncode == '100') {
            return redirect()
                ->route('welfare.donate-demo.thank-you')
                ->with('success', 'Thank you for your generous donation!');
        }

        return redirect()
            ->route('welfare.donate-demo')
            ->with('error', 'Payment was not successful (Code: ' . ($request->returncode ?? 'N/A') . '). Please try again.');
    }

    public function paymentCallback(Request $request)
    {
        Log::info('Donate Demo Callback Data:', $request->all());

        $donation = DonationDemo::where('order_id', $request->ord_mercref)->first();
        if (!$donation) {
            return response('Order Not Found', 404);
        }

        $kiplePay = KiplePayService::make('guest');

        if ($kiplePay->validateCallback($request->all())) {
            $isSuccess = ($request->returncode == '100');

            $donation->update([
                'status' => $isSuccess ? 'paid' : 'failed',
                'payment_payload' => $request->all(),
            ]);

            Log::info('Donate Demo ' . $donation->order_id . ' status updated to: ' . ($isSuccess ? 'paid' : 'failed'));
        } else {
            Log::warning('Donate Demo Callback Hash Validation Failed for ' . $donation->order_id);
        }

        return response('OK');
    }

    public function thankYou()
    {
        return view('welfare.pages.donate_demo_thank_you');
    }
}
