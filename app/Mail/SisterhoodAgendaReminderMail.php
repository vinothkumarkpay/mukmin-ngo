<?php

namespace App\Mail;

use App\Support\SisterhoodCollective;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SisterhoodAgendaReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'See you this Sunday! The Sisterhood Collective 2026 – Programme Agenda Inside';

    public string $recipientName;

    public function __construct(?string $recipientName = null)
    {
        $this->recipientName = trim((string) $recipientName);
    }

    public function build()
    {
        $mail = $this->from(
            config('mail.from.address'),
            config('mail.from.name')
        )
            ->subject($this->subject)
            ->view('emails.sisterhood_agenda_reminder')
            ->text('emails.sisterhood_agenda_reminder_text');

        $agendaPath = SisterhoodCollective::agendaPdfPath();
        if (is_file($agendaPath)) {
            $mail->attach($agendaPath, [
                'as' => SisterhoodCollective::AGENDA_PDF_FILENAME,
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
