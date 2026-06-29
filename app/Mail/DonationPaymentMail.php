<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;
    public array $gatewayPayload;
    public $subject;
    public array $rows;

    public function __construct(Donation $donation, array $gatewayPayload = [])
    {
        $this->donation = $donation;
        $this->gatewayPayload = $gatewayPayload;

        $statusLabel = ucfirst($donation->status);
        $this->subject = "Donation Payment {$statusLabel}: {$donation->order_id}";

        $this->rows = $this->buildRows();
    }

    public function build()
    {
        return $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.donation_payment');
    }

    protected function buildRows(): array
    {
        $donation = $this->donation;
        $channel = str_starts_with($donation->order_id ?? '', 'DEMO-') ? 'Demo (testing)' : 'Live';

        $rows = [
            ['label' => 'Order ID', 'value' => $donation->order_id],
            ['label' => 'Payment Status', 'value' => ucfirst($donation->status)],
            ['label' => 'Channel', 'value' => $channel],
            ['label' => 'Donor Name', 'value' => $donation->name],
            ['label' => 'Email', 'value' => $donation->email],
            ['label' => 'Phone', 'value' => $donation->phone],
            ['label' => 'Amount (RM)', 'value' => number_format((float) $donation->amount, 2)],
            ['label' => 'Payment Method', 'value' => $donation->payment_method],
            ['label' => 'Submitted At', 'value' => $donation->created_at?->format('d M Y, h:i A')],
            ['label' => 'Updated At', 'value' => $donation->updated_at?->format('d M Y, h:i A')],
        ];

        if ($donation->message) {
            $rows[] = ['label' => 'Donor Message', 'value' => $donation->message];
        }

        if (!empty($this->gatewayPayload['returncode'])) {
            $rows[] = ['label' => 'Gateway Return Code', 'value' => $this->gatewayPayload['returncode']];
        }

        if (!empty($this->gatewayPayload['ord_totalamt'])) {
            $rows[] = ['label' => 'Gateway Amount', 'value' => 'RM ' . $this->gatewayPayload['ord_totalamt']];
        }

        if (!empty($this->gatewayPayload['wcID'])) {
            $rows[] = ['label' => 'Gateway Transaction ID', 'value' => $this->gatewayPayload['wcID']];
        }

        return $rows;
    }
}
