<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Models\SisterhoodRegistration;
use App\Services\KiplePayService;
use App\Services\RecaptchaService;
use App\Services\Welfare\SisterhoodPaymentRecorder;
use App\Support\SisterhoodCollective;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class SisterhoodRegistrationController extends Controller
{
    public function create()
    {
        return view('welfare.pages.sisterhood.register', [
            'registrationOpen' => SisterhoodCollective::isOpen(),
            'registrationFull' => SisterhoodCollective::isFull(),
        ]);
    }

    public function store(Request $request, RecaptchaService $recaptcha)
    {
        if (! SisterhoodCollective::isOpen()) {
            return redirect()
                ->route('welfare.sisterhood.create')
                ->with('error', 'Registration closed on 17 November 2026.');
        }

        $request->merge(SisterhoodCollective::capitaliseInput($request->all()));

        $request->validate(
            array_merge(SisterhoodCollective::rules(), [
                'g-recaptcha-response' => $recaptcha->isEnabled() ? 'required|string' : 'nullable',
            ]),
            SisterhoodCollective::validationMessages(),
            SisterhoodCollective::attributeNames()
        );

        if (! $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip())) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Please complete the captcha verification and try again.',
            ]);
        }

        if (SisterhoodCollective::hasPaidRegistration((string) $request->input('nric_passport'))) {
            throw ValidationException::withMessages([
                'nric_passport' => 'A registration for this NRIC or passport number has already been confirmed.',
            ]);
        }

        $registration = $this->reservePlace(function () use ($request) {
            return SisterhoodRegistration::create(array_merge(
                SisterhoodCollective::attributesFrom($request->all()),
                [
                    'amount' => SisterhoodCollective::FEE,
                    'payment_status' => 'pending',
                    'status' => SisterhoodRegistration::STATUS_PENDING,
                    'payment_method' => 'KiplePay',
                    'order_id' => SisterhoodCollective::newOrderId(),
                ]
            ));
        });

        if (! $registration) {
            return redirect()
                ->route('welfare.sisterhood.create')
                ->with('error', SisterhoodCollective::fullMessage());
        }

        session(['sisterhood_registration_id' => $registration->id]);

        return $this->paymentRedirect($registration);
    }

    public function retry(Request $request)
    {
        $registration = $this->registrationFromSession();

        if (! $registration) {
            return redirect()->route('welfare.sisterhood.create');
        }

        if ($registration->isPaid()) {
            return redirect()->route('welfare.sisterhood.result');
        }

        $holdsPlace = $registration->payment_status === 'pending';
        $attempts = $registration->payment_attempts ?? [];
        $attempts[] = [
            'order_id' => $registration->order_id,
            'payment_status' => $registration->payment_status,
            'at' => now()->toIso8601String(),
        ];

        $renew = function () use ($registration, $attempts) {
            $attributes = [
                'order_id' => SisterhoodCollective::newOrderId(),
                'amount' => SisterhoodCollective::FEE,
                'payment_status' => 'pending',
                'payment_attempts' => $attempts,
            ];

            if (! $registration->isTest()) {
                $attributes['status'] = SisterhoodRegistration::STATUS_PENDING;
            }

            $registration->update($attributes);

            return $registration;
        };

        if ($holdsPlace) {
            $renew();
        } elseif (! $this->reservePlace($renew)) {
            return redirect()
                ->route('welfare.sisterhood.create')
                ->with('error', SisterhoodCollective::fullMessage());
        }

        session(['sisterhood_registration_id' => $registration->id]);

        return $this->paymentRedirect($registration->fresh());
    }

    public function paymentReturn(Request $request, SisterhoodPaymentRecorder $recorder)
    {
        $registration = $this->findRegistration($request);

        if ($registration) {
            $recorder->record($registration, $request->all(), false);
            $registration->refresh();
            session(['sisterhood_registration_id' => $registration->id]);
        }

        return redirect()->route('welfare.sisterhood.result');
    }

    public function paymentCallback(Request $request, SisterhoodPaymentRecorder $recorder)
    {
        $registration = SisterhoodRegistration::where('order_id', $request->input('ord_mercref'))->first();

        if (! $registration) {
            return response('Order Not Found', 404);
        }

        $recorder->record($registration, $request->all());

        return response('OK');
    }

    public function result()
    {
        $registration = $this->registrationFromSession();

        return view('welfare.pages.sisterhood.result', [
            'registration' => $registration,
            'successful' => $registration ? $registration->isPaid() : false,
        ]);
    }

    private function reservePlace(callable $create): ?SisterhoodRegistration
    {
        $lock = Cache::lock('sisterhood-registration', 10);

        try {
            $lock->block(5);
        } catch (LockTimeoutException $e) {
            return null;
        }

        try {
            if (SisterhoodCollective::isFull()) {
                return null;
            }

            return $create();
        } finally {
            $lock->release();
        }
    }

    private function paymentRedirect(SisterhoodRegistration $registration)
    {
        $kiplePay = KiplePayService::make('guest');
        $paymentData = $kiplePay->preparePayment(
            $registration->order_id,
            (float) $registration->amount,
            'The Sisterhood Collective 2026 Registration',
            trim($registration->salutation . ' ' . $registration->full_name),
            $registration->email,
            route('welfare.sisterhood.payment.return'),
            route('welfare.sisterhood.payment.callback')
        );

        return view('welfare.pages.sisterhood.payment_redirect', compact('paymentData'));
    }

    private function findRegistration(Request $request): ?SisterhoodRegistration
    {
        foreach (['ord_mercref', 'orderno', 'order_id', 'merchant_ref'] as $field) {
            $value = trim((string) $request->input($field, ''));
            if ($value !== '') {
                $registration = SisterhoodRegistration::where('order_id', $value)->first();
                if ($registration) {
                    return $registration;
                }
            }
        }

        return $this->registrationFromSession();
    }

    private function registrationFromSession(): ?SisterhoodRegistration
    {
        $id = session('sisterhood_registration_id');

        if (! $id) {
            return null;
        }

        return SisterhoodRegistration::find($id);
    }
}
