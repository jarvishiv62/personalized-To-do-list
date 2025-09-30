<?php

namespace App\Console\Commands;

use App\Mail\MotivationalQuoteMail;
use App\Models\Quote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMotivationalQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:send {--email= : Override recipient email address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send today\'s motivational quote email to the user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            // Get recipient email from option or environment
            $recipientEmail = $this->option('email') ?? config('mail.user_email');

            if (!$recipientEmail) {
                $this->error('No recipient email configured. Set USER_EMAIL in .env file.');
                Log::error('Failed to send motivational quote: No recipient email configured');
                return self::FAILURE;
            }

            // Validate email format
            if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                $this->error("Invalid email address: {$recipientEmail}");
                Log::error("Failed to send motivational quote: Invalid email address {$recipientEmail}");
                return self::FAILURE;
            }

            // Fetch a random quote
            $quote = Quote::inRandomOrder()->first();

            if (!$quote) {
                $this->error('No quotes found in database. Please seed quotes first.');
                Log::error('Failed to send motivational quote: No quotes in database');
                return self::FAILURE;
            }

            // Send the email
            $this->info('Sending motivational quote email...');
            $this->line("Quote: \"{$quote->content}\"");
            $this->line("Author: {$quote->author}");
            $this->line("Recipient: {$recipientEmail}");

            Mail::to($recipientEmail)->send(new MotivationalQuoteMail($quote));

            $this->newLine();
            $this->info('✓ Motivational quote email sent successfully!');

            Log::info('Motivational quote email sent successfully', [
                'recipient' => $recipientEmail,
                'quote_id' => $quote->id,
                'quote_content' => $quote->content,
            ]);

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to send motivational quote email.');
            $this->error('Error: ' . $e->getMessage());

            Log::error('Failed to send motivational quote email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return self::FAILURE;
        }
    }
}