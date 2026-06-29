<?php

namespace App\Services\Welfare;

use App\Mail\DonationPaymentMail;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonationPaymentNotifier
{
    public function notifyIfStatusChanged(Donation $donation, string $previousStatus, array $gatewayPayload = []): void
    {
        $donation->refresh();

        if ($previousStatus === $donation->status) {
            return;
        }

        if (! in_array($donation->status, ['paid', 'failed'], true)) {
            return;
        }

        $recipient = config('welfare.form_submission_recipients.donation', 'donate@mukmin.org');

        try {
            Mail::to($recipient)->send(new DonationPaymentMail($donation, $gatewayPayload));
        } catch (\Throwable $e) {
            Log::error('Donation payment notification email failed', [
                'order_id' => $donation->order_id,
                'email' => $recipient,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
