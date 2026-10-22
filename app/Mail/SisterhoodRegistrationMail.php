<?php

namespace App\Mail;

use App\Models\SisterhoodRegistration;
use App\Support\SisterhoodCollective;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SisterhoodRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public SisterhoodRegistration $registration;
    public $subject;
    public string $statusKey;
    public string $statusLabel;
    public array $registrationRows;
    public array $eventRows;

    public function __construct(SisterhoodRegistration $registration)
    {
        $this->registration = $registration;
        $this->statusKey = $registration->payment_status === 'paid' ? 'success' : 'failed';
        $this->statusLabel = $registration->paymentStatusLabel();
        $this->subject = $this->statusKey === 'success'
            ? 'The Sisterhood Collective 2026 — Registration Successful'
            : 'The Sisterhood Collective 2026 — Registration Incomplete';
        $this->registrationRows = $this->buildRegistrationRows();
        $this->eventRows = [
            ['label' => 'Date', 'value' => SisterhoodCollective::EVENT_DATE],
            ['label' => 'Time', 'value' => SisterhoodCollective::EVENT_TIME],
            ['label' => 'Venue', 'value' => SisterhoodCollective::EVENT_VENUE],
            ['label' => 'Dress theme', 'value' => SisterhoodCollective::EVENT_DRESS],
            ['label' => 'Registration fee', 'value' => 'RM' . number_format(SisterhoodCollective::FEE, 0) . ' per person'],
            ['label' => 'Organised by', 'value' => SisterhoodCollective::ORGANISER],
        ];
    }

    public function build()
    {
        return $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.sisterhood_registration');
    }

    protected function buildRegistrationRows(): array
    {
        $registration = $this->registration;
        $areas = collect($registration->areas_of_interest ?? [])->filter()->implode(', ');

        $rows = [
            ['label' => 'Payment status', 'value' => $this->statusLabel],
            ['label' => 'Order reference', 'value' => $registration->order_id],
            ['label' => 'Amount', 'value' => 'RM' . number_format((float) $registration->amount, 2)],
            ['label' => 'Full name', 'value' => trim($registration->salutation . ' ' . $registration->full_name)],
            ['label' => 'Preferred name', 'value' => $registration->preferred_name],
            ['label' => 'Email', 'value' => $registration->email],
            ['label' => 'Contact number', 'value' => $registration->contact_number],
            ['label' => 'Food preference', 'value' => $registration->food_preference],
        ];

        if (filled($registration->payment_reference)) {
            $rows[] = ['label' => 'Payment reference', 'value' => $registration->payment_reference];
        }

        if ($areas !== '') {
            $rows[] = ['label' => 'Area of interest', 'value' => $areas];
        }

        return $rows;
    }
}
