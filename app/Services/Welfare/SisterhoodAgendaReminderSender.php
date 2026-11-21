<?php

namespace App\Services\Welfare;

use App\Mail\SisterhoodAgendaReminderMail;
use App\Models\SisterhoodRegistration;
use App\Support\SisterhoodCollective;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SisterhoodAgendaReminderSender
{
    /**
     * Unique paid registrations with an email, keyed by lowercased email.
     *
     * @return \Illuminate\Support\Collection<int, SisterhoodRegistration>
     */
    public function paidRecipients()
    {
        return SisterhoodCollective::paidRecipientsQuery()
            ->get(['id', 'full_name', 'preferred_name', 'email'])
            ->unique(fn (SisterhoodRegistration $registration) => strtolower(trim((string) $registration->email)))
            ->values();
    }

    public function sendTest(string $email, ?string $recipientName = null): void
    {
        $this->assertAgendaAvailable();

        Mail::to($email)->send(new SisterhoodAgendaReminderMail($recipientName ?: 'Test Recipient'));
    }

    /**
     * @return array{sent: int, failed: int, skipped: int, failures: array<int, array{email: string, error: string}>}
     */
    public function sendToPaid(): array
    {
        $this->assertAgendaAvailable();

        @set_time_limit(0);

        $result = [
            'sent' => 0,
            'failed' => 0,
            'skipped' => 0,
            'failures' => [],
        ];

        foreach ($this->paidRecipients() as $registration) {
            $email = trim((string) $registration->email);
            if ($email === '') {
                $result['skipped']++;
                continue;
            }

            $name = trim((string) ($registration->preferred_name ?: $registration->full_name));

            try {
                Mail::to($email)->send(new SisterhoodAgendaReminderMail($name !== '' ? $name : null));
                $result['sent']++;
            } catch (\Throwable $e) {
                $result['failed']++;
                $result['failures'][] = [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ];

                Log::error('Sisterhood agenda reminder email failed', [
                    'registration_id' => $registration->id,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $result;
    }

    private function assertAgendaAvailable(): void
    {
        if (! SisterhoodCollective::agendaPdfAvailable()) {
            throw new \RuntimeException('The Sisterhood agenda PDF is missing. Place it at public/' . SisterhoodCollective::AGENDA_PDF_RELATIVE . '.');
        }
    }
}
