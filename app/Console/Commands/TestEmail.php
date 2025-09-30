<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : Email address to send test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify mail configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?? config('mail.user_email');

        if (!$email) {
            $this->error('No email address provided. Use: php artisan mail:test your@email.com');
            return self::FAILURE;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address: {$email}");
            return self::FAILURE;
        }

        $this->info('Sending test email...');
        $this->line("Mailer: " . config('mail.default'));
        $this->line("To: {$email}");
        $this->line("From: " . config('mail.from.address'));

        try {
            Mail::raw('This is a test email from DailyDrive. If you received this, your mail configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('DailyDrive - Test Email');
            });

            $this->newLine();
            $this->info('✓ Test email sent successfully!');

            if (config('mail.default') === 'log') {
                $this->line('Mail driver is set to "log". Check storage/logs/laravel.log for the email content.');
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to send test email.');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->line('Troubleshooting tips:');
            $this->line('1. Check your .env file for correct MAIL_* settings');
            $this->line('2. Verify MAIL_MAILER is set (smtp, log, etc.)');
            $this->line('3. For Gmail, use an App Password, not your regular password');
            $this->line('4. Check storage/logs/laravel.log for detailed error messages');

            return self::FAILURE;
        }
    }
}