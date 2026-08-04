<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\KiplePayService;
use App\Services\RecaptchaService;
use App\Services\Welfare\DonationPaymentCallbackHandler;
use App\Services\Welfare\DonationPaymentReturnHandler;
use App\Services\Welfare\DonationPaymentNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DonationDemoController extends Controller
{
    public function create()
    {
        return view('welfare.pages.donate_demo');
    }

    public function store(Request $request, DonationPaymentNotifier $notifier, RecaptchaService $recaptcha)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'amount' => 'required|numeric|min:1|max:999999',
            'message' => 'nullable|string|max:1200',
            'g-recaptcha-response' => $recaptcha->isEnabled() ? 'required|string' : 'nullable',
        ]);

        if (! $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip())) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Please complete the captcha verification and try again.',
            ]);
        }

        $orderNo = 'DEMO-' . strtoupper(uniqid());

        $donation = Donation::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'status' => 'pending',
            'order_id' => $orderNo,
            'payment_method' => 'KiplePay',
        ]);

        $notifier->notifyPending($donation);

        session(['pending_donation_order_id' => $orderNo]);

        $kiplePay = KiplePayService::make('guest');

        $paymentData = $kiplePay->preparePayment(
            $orderNo,
            $request->amount,
            'MUKMIN Donation (Demo)',
            $request->name,
            $request->email,
            route('welfare.donate-demo.payment.return'),
            route('welfare.donate-demo.payment.callback')
        );

        return view('welfare.pages.donate_demo_payment_redirect', compact('paymentData'));
    }

    public function paymentReturn(Request $request, DonationPaymentReturnHandler $handler)
    {
        return $handler->handle(
            $request,
            'welfare.donate-demo.thank-you',
            'welfare.donate-demo',
            'Donation Demo'
        );
    }

    public function paymentCallback(Request $request, DonationPaymentCallbackHandler $handler)
    {
        return $handler->handle($request, 'Donation Demo');
    }

    public function thankYou()
    {
        return view('welfare.pages.donate_demo_thank_you');
    }
}
