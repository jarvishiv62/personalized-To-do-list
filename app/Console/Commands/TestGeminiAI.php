<?php

namespace App\Console\Commands;

use App\Services\ChatbotService;
use App\Services\GeminiService;
use Illuminate\Console\Command;

class TestGeminiAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:test {message? : The message to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Gemini AI integration';

    /**
     * Execute the console command.
     */
    public function handle(ChatbotService $chatbot, GeminiService $gemini): int
    {
        $this->info('🤖 Testing Gemini AI Integration');
        $this->info('================================');
        $this->newLine();

        // Check if AI is enabled
        if (!$gemini->isEnabled()) {
            $this->error('❌ AI Mode is DISABLED');
            $this->warn('Set AI_MODE=true in .env to enable AI features');
            $this->newLine();

            if (empty(config('services.gemini.api_key'))) {
                $this->error('❌ GEMINI_API_KEY is not set in .env');
            }

            return Command::FAILURE;
        }

        $this->info('✅ AI Mode: ENABLED');
        $this->info('✅ API Key: ' . substr(config('services.gemini.api_key'), 0, 10) . '...');
        $this->info('✅ Model: ' . config('services.gemini.model'));
        $this->newLine();

        // Get test message
        $message = $this->argument('message');

        if (!$message) {
            $message = $this->ask('Enter a message to test (or press Enter for default)', 'How can I be more productive today?');
        }

        $this->info('📤 Sending: ' . $message);
        $this->newLine();

        // Show loading
        $this->output->write('⏳ Waiting for AI response...');

        // Process message
        $response = $chatbot->processMessage($message);

        $this->output->write("\r" . str_repeat(' ', 50) . "\r"); // Clear loading message

        // Display response
        if (isset($response['ai_powered']) && $response['ai_powered']) {
            $this->info('✨ AI Response:');
        } else {
            $this->info('🔧 Rule-based Response:');
        }

        $this->newLine();
        $this->line($response['text']);
        $this->newLine();

        // Show links if any
        if (!empty($response['links'])) {
            $this->info('🔗 Suggested Links:');
            foreach ($response['links'] as $link) {
                $this->line('  • ' . $link['text'] . ': ' . $link['url']);
            }
            $this->newLine();
        }

        // Additional tests
        if ($this->confirm('Run additional tests?', false)) {
            $this->runAdditionalTests($chatbot);
        }

        $this->info('✅ Test completed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Run additional test cases.
     */
    protected function runAdditionalTests(ChatbotService $chatbot): void
    {
        $this->newLine();
        $this->info('Running additional test cases...');
        $this->newLine();

        $testCases = [
            'tasks today' => 'Simple command (should use rules)',
            'quote' => 'Simple command (should use rules)',
            'What are my priorities?' => 'Complex query (should use AI)',
            'How can I improve my goal progress?' => 'Complex query (should use AI)',
        ];

        foreach ($testCases as $message => $description) {
            $this->info("Test: {$description}");
            $this->line("Message: {$message}");

            $response = $chatbot->processMessage($message);

            $mode = isset($response['ai_powered']) && $response['ai_powered'] ? '✨ AI' : '🔧 Rules';
            $this->line("Mode: {$mode}");
            $this->line("Response: " . substr($response['text'], 0, 100) . '...');
            $this->newLine();
        }
    }
}