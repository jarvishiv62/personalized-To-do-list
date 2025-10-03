<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl;
    protected string $model;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => true,
        ]);

        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = config('services.gemini.base_url');
        $this->model = config('services.gemini.model');
    }

    /**
     * Generate AI response from Gemini.
     */
    public function generateResponse(string $message, array $context = []): array
    {
        try {
            // Build system context
            $systemPrompt = $this->buildSystemPrompt($context);

            // Combine system prompt with user message
            $fullPrompt = $systemPrompt . "\n\nUser Question: " . $message;

            // Make API request
            $response = $this->client->post(
                "{$this->baseUrl}/models/{$this->model}:generateContent",
                [
                    'query' => ['key' => $this->apiKey],
                    'json' => [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $fullPrompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => config('services.gemini.temperature'),
                            'maxOutputTokens' => config('services.gemini.max_tokens'),
                        ],
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]
            );

            $data = json_decode($response->getBody()->getContents(), true);

            // Extract response text
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $responseText = $data['candidates'][0]['content']['parts'][0]['text'];

                return [
                    'success' => true,
                    'text' => trim($responseText),
                    'links' => $this->extractLinks($responseText),
                ];
            }

            throw new \Exception('Invalid response format from Gemini API');

        } catch (GuzzleException $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());

            return [
                'success' => false,
                'text' => 'I apologize, but I\'m having trouble connecting to my AI brain right now. Please try again in a moment, or use simple commands like "tasks today" or "quote".',
                'links' => [],
            ];
        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());

            return [
                'success' => false,
                'text' => 'Something went wrong while processing your request. Please try again.',
                'links' => [],
            ];
        }
    }

    /**
     * Build system prompt with context.
     */
    protected function buildSystemPrompt(array $context): string
    {
        $prompt = "You are DailyDrive Assistant, a helpful AI chatbot for a personal productivity application called DailyDrive.\n\n";

        $prompt .= "Your role is to help users manage their tasks, goals, diary entries, and stay motivated.\n\n";

        $prompt .= "Instructions:\n";
        $prompt .= "- Be friendly, concise, and encouraging\n";
        $prompt .= "- Keep responses under 200 words\n";
        $prompt .= "- Use emojis sparingly but appropriately\n";
        $prompt .= "- Focus on actionable advice\n";
        $prompt .= "- When discussing tasks, use time format like '9:00 AM - 10:00 AM'\n\n";

        // Add user context if available
        if (!empty($context)) {
            $prompt .= "Current User Context:\n";

            if (isset($context['daily_tasks_count'])) {
                $prompt .= "- User has {$context['daily_tasks_count']} tasks today\n";
            }

            if (isset($context['pending_tasks_count'])) {
                $prompt .= "- {$context['pending_tasks_count']} tasks are still pending\n";
            }

            if (isset($context['completed_tasks_count'])) {
                $prompt .= "- {$context['completed_tasks_count']} tasks completed\n";
            }

            if (isset($context['goals_count'])) {
                $prompt .= "- User has {$context['goals_count']} active goals\n";
            }

            if (isset($context['average_goal_progress'])) {
                $prompt .= "- Average goal progress: {$context['average_goal_progress']}%\n";
            }

            $prompt .= "\n";
        }

        $prompt .= "Available Features in DailyDrive:\n";
        $prompt .= "- Task Management (Daily/Weekly/Monthly sections)\n";
        $prompt .= "- Goal Tracking with progress bars\n";
        $prompt .= "- Diary/Journal entries\n";
        $prompt .= "- Motivational quotes\n";
        $prompt .= "- Time scheduling with Pomodoro timer\n";
        $prompt .= "- Calendar view\n\n";

        return $prompt;
    }

    /**
     * Extract potential links from response.
     */
    protected function extractLinks(string $text): array
    {
        $links = [];

        // Check for common actions mentioned
        if (stripos($text, 'dashboard') !== false) {
            $links[] = ['url' => route('dashboard'), 'text' => 'Go to Dashboard'];
        }

        if (stripos($text, 'task') !== false || stripos($text, 'to-do') !== false) {
            $links[] = ['url' => route('tasks.create'), 'text' => 'Create Task'];
        }

        if (stripos($text, 'goal') !== false) {
            $links[] = ['url' => route('goals.index'), 'text' => 'View Goals'];
        }

        return $links;
    }

    /**
     * Check if Gemini AI is enabled.
     */
    public function isEnabled(): bool
    {
        return config('services.gemini.enabled') && !empty($this->apiKey);
    }
}