<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Goal;
use App\Models\Quote;
use Carbon\Carbon;

class ChatbotService
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Process user message and return bot response.
     */
    public function processMessage(string $message): array
    {
        $message = trim($message);
        $messageLower = strtolower($message);

        // Check if AI mode is enabled and message is not a simple command
        if ($this->geminiService->isEnabled() && !$this->isSimpleCommand($messageLower)) {
            return $this->processWithAI($message);
        }

        // Use rule-based responses for simple commands
        return $this->processWithRules($messageLower);
    }

    /**
     * Check if message is a simple command (use rules instead of AI).
     */
    protected function isSimpleCommand(string $message): bool
    {
        $simpleCommands = [
            'tasks today',
            'today tasks',
            'daily tasks',
            'weekly tasks',
            'this week',
            'monthly tasks',
            'this month',
            'quote',
            'motivation',
            'inspire',
            'goals',
            'my goals',
            'help',
            'commands',
        ];

        foreach ($simpleCommands as $command) {
            if (str_contains($message, $command)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process message with AI.
     */
    protected function processWithAI(string $message): array
    {
        // Build context for AI
        $context = $this->buildContext();

        // Get AI response
        $response = $this->geminiService->generateResponse($message, $context);

        // If AI fails, fallback to rules
        if (!$response['success']) {
            return $this->processWithRules(strtolower($message));
        }

        return [
            'text' => $response['text'],
            'links' => $response['links'],
            'ai_powered' => true,
        ];
    }

    /**
     * Build context data for AI.
     */
    protected function buildContext(): array
    {
        $dailyTasks = Task::section('daily')->get();
        $goals = Goal::all();

        return [
            'daily_tasks_count' => $dailyTasks->count(),
            'pending_tasks_count' => $dailyTasks->where('status', 'pending')->count(),
            'completed_tasks_count' => $dailyTasks->where('status', 'completed')->count(),
            'goals_count' => $goals->count(),
            'average_goal_progress' => $goals->count() > 0 ? round($goals->avg('progress')) : 0,
        ];
    }

    /**
     * Process message with rule-based logic.
     */
    protected function processWithRules(string $message): array
    {
        if ($this->matchesKeyword($message, ['tasks today', 'today tasks', "today's tasks", 'show tasks', 'daily tasks'])) {
            return $this->getTodaysTasks();
        }

        if ($this->matchesKeyword($message, ['weekly tasks', 'this week', 'week tasks'])) {
            return $this->getWeeklyTasks();
        }

        if ($this->matchesKeyword($message, ['monthly tasks', 'this month', 'month tasks'])) {
            return $this->getMonthlyTasks();
        }

        if ($this->matchesKeyword($message, ['quote', 'motivation', 'inspire me', 'motivate me'])) {
            return $this->getMotivationalQuote();
        }

        if ($this->matchesKeyword($message, ['goals', 'show goals', 'my goals', 'goal progress'])) {
            return $this->getGoalsProgress();
        }

        if ($this->matchesKeyword($message, ['help', 'commands', 'what can you do'])) {
            return $this->getHelpMessage();
        }

        // If AI is enabled but we're here, suggest trying again
        if ($this->geminiService->isEnabled()) {
            return [
                'text' => "I couldn't understand that command. Try rephrasing your question, or use simple commands like 'tasks today', 'quote', or 'goals'. Type 'help' for all commands.",
                'links' => [],
                'ai_powered' => false,
            ];
        }

        // Default fallback
        return [
            'text' => "I'm not sure how to help with that. Try asking about 'tasks today', 'quote', 'goals', or type 'help' to see all commands.",
            'links' => [],
            'ai_powered' => false,
        ];
    }

    /**
     * Check if message matches any keywords.
     */
    protected function matchesKeyword(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get today's tasks (Rule-based).
     */
    protected function getTodaysTasks(): array
    {
        $tasks = Task::section('daily')->orderByTime()->get();

        if ($tasks->isEmpty()) {
            return [
                'text' => "You don't have any tasks scheduled for today. Ready to add some? 📝",
                'links' => [['url' => route('tasks.create'), 'text' => 'Create Task']],
                'ai_powered' => false,
            ];
        }

        $pendingCount = $tasks->where('status', 'pending')->count();
        $completedCount = $tasks->where('status', 'completed')->count();

        $taskList = $tasks->map(function ($task, $index) {
            $status = $task->status === 'completed' ? '✅' : '⏳';
            $time = $task->start_time && $task->end_time
                ? Carbon::parse($task->start_time)->format('g:i A') . ' - ' . Carbon::parse($task->end_time)->format('g:i A')
                : 'No time set';

            return ($index + 1) . ". {$status} {$task->title} ({$time})";
        })->implode("\n");

        $response = "📅 You have {$tasks->count()} tasks today:\n\n{$taskList}\n\n";
        $response .= "✅ Completed: {$completedCount} | ⏳ Pending: {$pendingCount}";

        return [
            'text' => $response,
            'links' => [['url' => route('dashboard'), 'text' => 'View Dashboard']],
            'ai_powered' => false,
        ];
    }

    /**
     * Get weekly tasks (Rule-based).
     */
    protected function getWeeklyTasks(): array
    {
        $tasks = Task::section('weekly')->orderBy('created_at', 'desc')->get();

        if ($tasks->isEmpty()) {
            return [
                'text' => "No weekly tasks found. Plan your week ahead! 📆",
                'links' => [['url' => route('tasks.create'), 'text' => 'Create Task']],
                'ai_powered' => false,
            ];
        }

        $pendingCount = $tasks->where('status', 'pending')->count();
        $completedCount = $tasks->where('status', 'completed')->count();

        $taskList = $tasks->take(5)->map(function ($task, $index) {
            $status = $task->status === 'completed' ? '✅' : '⏳';
            return ($index + 1) . ". {$status} {$task->title}";
        })->implode("\n");

        $response = "📆 Weekly Tasks ({$tasks->count()} total):\n\n{$taskList}";
        if ($tasks->count() > 5) {
            $response .= "\n\n... and " . ($tasks->count() - 5) . " more";
        }
        $response .= "\n\n✅ Completed: {$completedCount} | ⏳ Pending: {$pendingCount}";

        return [
            'text' => $response,
            'links' => [['url' => route('dashboard') . '#weekly', 'text' => 'View All Weekly Tasks']],
            'ai_powered' => false,
        ];
    }

    /**
     * Get monthly tasks (Rule-based).
     */
    protected function getMonthlyTasks(): array
    {
        $tasks = Task::section('monthly')->orderBy('created_at', 'desc')->get();

        if ($tasks->isEmpty()) {
            return [
                'text' => "No monthly tasks found. Set your monthly goals! 🎯",
                'links' => [['url' => route('tasks.create'), 'text' => 'Create Task']],
                'ai_powered' => false,
            ];
        }

        $pendingCount = $tasks->where('status', 'pending')->count();
        $completedCount = $tasks->where('status', 'completed')->count();

        $taskList = $tasks->take(5)->map(function ($task, $index) {
            $status = $task->status === 'completed' ? '✅' : '⏳';
            return ($index + 1) . ". {$status} {$task->title}";
        })->implode("\n");

        $response = "🗓️ Monthly Tasks ({$tasks->count()} total):\n\n{$taskList}";
        if ($tasks->count() > 5) {
            $response .= "\n\n... and " . ($tasks->count() - 5) . " more";
        }
        $response .= "\n\n✅ Completed: {$completedCount} | ⏳ Pending: {$pendingCount}";

        return [
            'text' => $response,
            'links' => [['url' => route('dashboard') . '#monthly', 'text' => 'View All Monthly Tasks']],
            'ai_powered' => false,
        ];
    }

    /**
     * Get motivational quote (Rule-based).
     */
    protected function getMotivationalQuote(): array
    {
        $quote = Quote::inRandomOrder()->first();

        if (!$quote) {
            return [
                'text' => "🌟 Stay focused and keep pushing forward!",
                'links' => [],
                'ai_powered' => false,
            ];
        }

        return [
            'text' => "🌟 Here's your motivation:\n\n\"{$quote->content}\"\n\n— {$quote->author}",
            'links' => [],
            'ai_powered' => false,
        ];
    }

    /**
     * Get goals progress (Rule-based).
     */
    protected function getGoalsProgress(): array
    {
        $goals = Goal::with('tasks')->get();

        if ($goals->isEmpty()) {
            return [
                'text' => "You haven't set any goals yet. Start by creating your first goal! 🎯",
                'links' => [['url' => route('goals.create'), 'text' => 'Create Goal']],
                'ai_powered' => false,
            ];
        }

        $goalsList = $goals->map(function ($goal, $index) {
            $progress = round($goal->progress);
            $bar = $this->getProgressBar($progress);
            return ($index + 1) . ". {$goal->title}\n   {$bar} {$progress}%";
        })->implode("\n\n");

        $avgProgress = round($goals->avg('progress'));

        $response = "🎯 Your Goals Progress:\n\n{$goalsList}\n\n";
        $response .= "Average Progress: {$avgProgress}%";

        return [
            'text' => $response,
            'links' => [['url' => route('goals.index'), 'text' => 'View All Goals']],
            'ai_powered' => false,
        ];
    }

    /**
     * Get progress bar visualization.
     */
    protected function getProgressBar(float $progress): string
    {
        $filled = round($progress / 10);
        $empty = 10 - $filled;
        return str_repeat('█', $filled) . str_repeat('░', $empty);
    }

    /**
     * Get help message (Rule-based).
     */
    protected function getHelpMessage(): array
    {
        $aiStatus = $this->geminiService->isEnabled() ? 'enabled ✨' : 'disabled';

        return [
            'text' => "🤖 DailyDrive Chatbot Commands:\n\n" .
                "AI Mode: {$aiStatus}\n\n" .
                "📝 Tasks:\n" .
                "• 'tasks today' - View today's tasks\n" .
                "• 'weekly tasks' - View this week's tasks\n" .
                "• 'monthly tasks' - View this month's tasks\n\n" .
                "🎯 Goals:\n" .
                "• 'goals' - View goal progress\n\n" .
                "🌟 Motivation:\n" .
                "• 'quote' - Get motivational quote\n\n" .
                ($this->geminiService->isEnabled() ?
                    "✨ AI Mode Active: Ask me anything in natural language!\n\n" : "") .
                "Just type what you need and I'll help you! 😊",
            'links' => [['url' => route('dashboard'), 'text' => 'Go to Dashboard']],
            'ai_powered' => false,
        ];
    }
}