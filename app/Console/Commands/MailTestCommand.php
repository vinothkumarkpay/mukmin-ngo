<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTestCommand extends Command
{
    protected $signature = 'mail:test {recipient=support@mukmin.org}';

    protected $description = 'Send a test email using the current mail configuration';

    public function handle(): int
    {
        $recipient = $this->argument('recipient');
        $smtp = config('mail.mailers.smtp');

        $this->info('Mail configuration:');
        $this->table(
            ['Key', 'Value'],
            [
                ['mailer', config('mail.default')],
                ['host', $smtp['host'] ?? '—'],
                ['port', $smtp['port'] ?? '—'],
                ['encryption', $smtp['encryption'] ?? '(none)'],
                ['username', $smtp['username'] ?? '—'],
                ['from', config('mail.from.address') . ' (' . config('mail.from.name') . ')'],
            ]
        );

        if ((int) ($smtp['port'] ?? 0) === 465 && ($smtp['encryption'] ?? '') === 'tls') {
            $this->warn('Port 465 usually requires MAIL_ENCRYPTION=ssl, not tls. Update .env and run: php artisan config:clear');
        }

        $this->info("Sending test email to {$recipient}...");

        try {
            Mail::raw('MUKMIN mail:test at ' . now(), function ($message) use ($recipient) {
                $message->to($recipient)->subject('MUKMIN mail:test');
            });
        } catch (\Throwable $e) {
            $this->error('Failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info('Test email sent successfully.');
        return self::SUCCESS;
    }
}
