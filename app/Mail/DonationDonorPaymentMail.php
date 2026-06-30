<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationDonorPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;
    public array $gatewayPayload;
    public $subject;
    public string $statusLabel;
    public string $statusKey;
    public string $introMessage;
    public string $footerMessage;
    public array $rows;

    public function __construct(Donation $donation, array $gatewayPayload = [])
    {
        $this->donation = $donation;
        $this->gatewayPayload = $gatewayPayload;
        $this->statusKey = $this->resolveStatusKey($donation->status);
        $this->statusLabel = $this->statusLabels()[$this->statusKey];
        $this->subject = $this->makeSubjectLine();
        $this->introMessage = $this->buildIntroMessage();
        $this->footerMessage = $this->buildFooterMessage();
        $this->rows = $this->buildRows();
    }

    public function build()
    {
        return $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.donation_donor_payment');
    }

    protected function resolveStatusKey(string $status): string
    {
        return match ($status) {
            'paid' => 'success',
            'failed' => 'failed',
            default => 'pending',
        };
    }

    protected function statusLabels(): array
    {
        return [
            'pending' => 'Pending',
            'success' => 'Successful',
            'failed' => 'Failed',
        ];
    }

    protected function makeSubjectLine(): string
    {
        return match ($this->statusKey) {
            'success' => 'Donation Payment Successful — MUKMIN',
            'failed' => 'Donation Payment Unsuccessful — MUKMIN',
            default => 'Donation Payment Pending — MUKMIN',
        };
    }

    protected function buildIntroMessage(): string
    {
        $name = $this->donation->name ?: 'there';

        return match ($this->statusKey) {
            'success' => 'Salam from MUKMIN,<br><br>'
                . 'Thank you for your generous contribution to MUKMIN.<br><br>'
                . 'We have successfully received your donation, and your support will help strengthen initiatives that advance education, community welfare, leadership development, economic empowerment, and community-building efforts across the communities we serve.<br>'
                . 'At MUKMIN, we believe that meaningful change begins when individuals, organisations and communities come together with a common purpose. Every contribution, regardless of its size, becomes part of a larger movement dedicated to creating sustainable impact and improving lives across our communities.',
            'failed' => "Assalamu alaikum {$name},<br><br>We were unable to complete your online donation payment. Your transaction was <strong>not successful</strong>.",
            default => "Assalamu alaikum {$name},<br><br>Thank you for choosing to support MUKMIN. Your donation payment is currently <strong>pending</strong> while you complete checkout with our payment gateway.",
        };
    }

    protected function buildFooterMessage(): string
    {
        return match ($this->statusKey) {
            'success' => 'Should you have any questions regarding your contribution, please feel free to contact us at <a href="mailto:info@mukmin.org" style="color: #0c5930; text-decoration: none; font-weight: 500;">info@mukmin.org</a>.<br><br>'
                . 'On behalf of the entire MUKMIN family, thank you for believing in our mission and standing with us as we work towards a more empowered, compassionate and united community.<br><br>'
                . 'With sincere appreciation,<br>'
                . 'MUKMIN Secretariat<br>'
                . 'Pertubuhan Gabungan MUKMIN Nasional<br>'
                . '🌐 <a href="https://www.mukmin.org" style="color: #0c5930; text-decoration: none; font-weight: 500;">www.mukmin.org</a>',
            'failed' => 'No amount has been charged. You may return to the donation page and try again. If you need assistance, please contact us at <a href="mailto:donate@mukmin.org" style="color: #0c5930; text-decoration: none; font-weight: 500;">donate@mukmin.org</a>.',
            default => 'Please complete your payment on the KiplePay page. You will receive another email once your payment is confirmed or if it fails.',
        };
    }

    protected function buildRows(): array
    {
        $donation = $this->donation;

        $rows = [
            ['label' => 'Payment Status', 'value' => $this->statusLabel],
            ['label' => 'Order Reference', 'value' => $donation->order_id],
            ['label' => 'Amount (RM)', 'value' => number_format((float) $donation->amount, 2)],
            ['label' => 'Payment Method', 'value' => $donation->payment_method],
            ['label' => 'Submitted At', 'value' => $donation->created_at?->format('d M Y, h:i A')],
        ];

        if ($this->statusKey !== 'pending' && $donation->updated_at) {
            $rows[] = ['label' => 'Updated At', 'value' => $donation->updated_at->format('d M Y, h:i A')];
        }

        if ($donation->message) {
            $rows[] = ['label' => 'Your Message', 'value' => $donation->message];
        }

        return $rows;
    }
}
