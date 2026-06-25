<?php

namespace App\Console\Commands;

use App\Mail\FormSubmissionMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTestCommand extends Command
{
    protected $signature = 'mail:test {recipient?} {--scholarship : Send a sample MFLS FormSubmissionMail to the scholarship inbox}';

    protected $description = 'Send a test email using the current mail configuration';

    public function handle(): int
    {
        $scholarshipTest = $this->option('scholarship');
        $recipient = $this->argument('recipient')
            ?: ($scholarshipTest
                ? config('welfare.form_submission_recipients.mfls-scholarship', 'scholarship@mukmin.org')
                : 'support@mukmin.org');

        $smtp = config('mail.mailers.smtp');

        $this->info('Mail configuration:');
        $rows = [
            ['mailer', config('mail.default')],
            ['host', $smtp['host'] ?? '—'],
            ['port', $smtp['port'] ?? '—'],
            ['encryption', $smtp['encryption'] ?? '(none)'],
            ['username', $smtp['username'] ?? '—'],
            ['from', config('mail.from.address') . ' (' . config('mail.from.name') . ')'],
            ['mfls-scholarship inbox', config('welfare.form_submission_recipients.mfls-scholarship', '—')],
        ];
        $this->table(['Key', 'Value'], $rows);

        if (empty($smtp['password'])) {
            $this->warn('MAIL_PASSWORD appears empty. SMTP authentication will fail unless the server allows relay without auth.');
        }

        if ((int) ($smtp['port'] ?? 0) === 465 && ($smtp['encryption'] ?? '') === 'tls') {
            $this->warn('Port 465 usually requires MAIL_ENCRYPTION=ssl, not tls. Update .env and run: php artisan config:clear');
        }

        if ($scholarshipTest) {
            $this->info("Sending sample MFLS FormSubmissionMail to {$recipient}...");

            try {
                Mail::to($recipient)->send(new FormSubmissionMail(
                    'MFLS Scholarship Application',
                    $this->sampleMflsPayload(),
                    true
                ));
            } catch (\Throwable $e) {
                $this->error('Failed: ' . $e->getMessage());
                $this->line('Check storage/logs/laravel.log for details.');
                return self::FAILURE;
            }

            $this->info('Sample MFLS team email sent successfully.');
            return self::SUCCESS;
        }

        $this->info("Sending plain test email to {$recipient}...");

        try {
            Mail::raw('MUKMIN mail:test at ' . now(), function ($message) use ($recipient) {
                $message->to($recipient)->subject('MUKMIN mail:test');
            });
        } catch (\Throwable $e) {
            $this->error('Failed: ' . $e->getMessage());
            $this->line('Check storage/logs/laravel.log for details.');
            return self::FAILURE;
        }

        $this->info('Test email sent successfully.');
        return self::SUCCESS;
    }

    private function sampleMflsPayload(): array
    {
        return [
            'email' => 'mail-test@example.com',
            'full_name' => 'Mail Test Applicant',
            'nric_passport' => '010101011234',
            'dob' => '2001-01-01',
            'gender' => 'Male',
            'age' => 22,
            'citizenship' => 'Malaysian',
            'marital_status' => 'Single',
            'contact_number' => '+60123456789',
            'full_address' => '123 Test Street, Puchong',
            'state' => 'Selangor',
            'postcode' => '47100',
            'partner_institution_id' => 'test',
            'partner_institution_name' => 'Mail Test Institution',
            'current_qualification' => 'SPM',
            'institution_name' => 'SMK Test',
            'current_cgpa_result' => '8A 1B',
            'programme_course_applied' => 'Mail Test Programme',
            'applied_to_university' => false,
            'received_offer_letter' => false,
            'household_income' => '< RM2,000',
            'father_guardian_name' => 'Test Father',
            'father_guardian_occupation' => 'Driver',
            'mother_guardian_name' => 'Test Mother',
            'mother_guardian_occupation' => 'Homemaker',
            'number_of_dependents' => 3,
            'other_scholarship_details' => 'None',
            'leadership_roles' => 'Mail test only — no real application.',
            'involvement_level' => 'Active',
            'community_service_involvement' => 'Mail test only.',
            'community_contribution' => str_repeat('community ', 160),
            'leadership_experience_statement' => str_repeat('leadership ', 160),
            'scholar_selection_statement' => str_repeat('scholar ', 160),
            'declaration_confirmed' => true,
        ];
    }
}
