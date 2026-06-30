<?php

namespace App\Services\Welfare;

use App\Mail\DonationDonorPaymentMail;
use App\Mail\DonationPaymentMail;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonationPaymentNotifier
{
    public function notifyPending(Donation $donation): void
    {
        $this->sendDonorMail($donation);
    }

    public function notifyIfStatusChanged(Donation $donation, string $previousStatus, array $gatewayPayload = []): void
    {
        $donation->refresh();

        if ($previousStatus === $donation->status) {
            return;
        }

        if (! in_array($donation->status, ['paid', 'failed'], true)) {
            return;
        }

        $this->sendAdminMail($donation, $gatewayPayload);
        $this->sendDonorMail($donation, $gatewayPayload);
    }

    protected function sendAdminMail(Donation $donation, array $gatewayPayload = []): void
    {
        $recipient = config('welfare.form_submission_recipients.donation', 'donate@mukmin.org');

        try {
            Mail::to($recipient)->send(new DonationPaymentMail($donation, $gatewayPayload));
        } catch (\Throwable $e) {
            Log::error('Donation payment admin notification email failed', [
                'order_id' => $donation->order_id,
                'email' => $recipient,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendDonorMail(Donation $donation, array $gatewayPayload = []): void
    {
        if (! $donation->email) {
            return;
        }

        try {
            Mail::to($donation->email)->send(new DonationDonorPaymentMail($donation, $gatewayPayload));
        } catch (\Throwable $e) {
            Log::error('Donation payment donor notification email failed', [
                'order_id' => $donation->order_id,
                'email' => $donation->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
