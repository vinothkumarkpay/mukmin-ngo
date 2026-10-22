<?php

namespace App\Services\Welfare;

use App\Mail\SisterhoodRegistrationMail;
use App\Models\SisterhoodRegistration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SisterhoodRegistrationNotifier
{
    public function notifyIfStatusChanged(SisterhoodRegistration $registration, string $previousStatus): void
    {
        $registration->refresh();

        if ($previousStatus === $registration->payment_status) {
            return;
        }

        if (! in_array($registration->payment_status, ['paid', 'failed'], true)) {
            return;
        }

        if (! filled($registration->email)) {
            return;
        }

        try {
            Mail::to($registration->email)->send(new SisterhoodRegistrationMail($registration));
        } catch (\Throwable $e) {
            Log::error('Sisterhood registration email failed', [
                'order_id' => $registration->order_id,
                'email' => $registration->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
