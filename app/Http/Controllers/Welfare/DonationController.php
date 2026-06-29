<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\KiplePayService;
use App\Services\Welfare\DonationPaymentCallbackHandler;
use App\Services\Welfare\DonationPaymentReturnHandler;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function create()
    {
        return view('welfare.pages.donate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'amount' => 'required|numeric|min:1|max:999999',
            'message' => 'nullable|string|max:1200',
        ]);

        $orderNo = 'MUKMIN-' . strtoupper(uniqid());

        Donation::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        session(['pending_donation_order_id' => $orderNo]);

        $kiplePay = KiplePayService::make('guest');

        $paymentData = $kiplePay->preparePayment(
            $orderNo,
            $request->amount,
            'MUKMIN Donation',
            $request->name,
            $request->email,
            route('welfare.donate.payment.return'),
            route('welfare.donate.payment.callback')
        );

        return view('welfare.pages.donate_payment_redirect', compact('paymentData'));
    }

    public function paymentReturn(Request $request, DonationPaymentReturnHandler $handler)
    {
        return $handler->handle(
            $request,
            'welfare.donate.thank-you',
            'welfare.donate',
            'Donation'
        );
    }

    public function paymentCallback(Request $request, DonationPaymentCallbackHandler $handler)
    {
        return $handler->handle($request, 'Donation');
    }

    public function thankYou()
    {
        return view('welfare.pages.donate_thank_you');
    }
}
