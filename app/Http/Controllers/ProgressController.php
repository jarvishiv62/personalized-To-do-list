<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Goal;
use App\Models\Diary;
use App\Models\UserStats;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class ProgressController extends Controller
{
    /**
     * Display the progress dashboard.
     */
    public function index(): View
    {
        $stats = UserStats::getOrCreate();

        // Get recent achievements
        $recentBadges = collect($stats->badges ?? [])->take(6);

        // Quick stats
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $totalGoals = Goal::count();
        $completedGoals = Goal::where('progress', '>=', 100)->count();

        $diaryEntries = Diary::count();

        return view('progress.index', compact(
            'stats',
            'recentBadges',
            'totalTasks',
            'completedTasks',
            'completionRate',
            'totalGoals',
            'completedGoals',
            'diaryEntries'
        ));
    }

    /**
     * Get progress data for charts (JSON API).
     */
    public function getData(): JsonResponse
    {
        $stats = UserStats::getOrCreate();

        // Weekly tasks (last 7 days)
        $weeklyTasks = $this->getWeeklyTasks();

        // Monthly tasks (current month, day by day)
        $monthlyTasks = $this->getMonthlyTasks();

        // Heatmap data (last 90 days)
        $heatmapData = $this->getHeatmapData();

        // Section breakdown
        $sectionBreakdown = $this->getSectionBreakdown();

        // Completion trend (last 30 days)
        $completionTrend = $this->getCompletionTrend();

        return response()->json([
            'points' => $stats->total_points,
            'current_streak' => $stats->current_streak,
            'longest_streak' => $stats->longest_streak,
            'badges' => $stats->badges ?? [],
            'weekly_tasks' => $weeklyTasks,
            'monthly_tasks' => $monthlyTasks,
            'heatmap' => $heatmapData,
            'section_breakdown' => $sectionBreakdown,
            'completion_trend' => $completionTrend,
        ]);
    }

    /**
     * Get tasks completed in the last 7 days.
     */
    private function getWeeklyTasks(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Task::where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->count();

            $data[] = [
                'date' => $date->format('D'),
                'count' => $count,
            ];
        }

        return $data;
    }

    /**
     * Get tasks completed per day in current month.
     */
    private function getMonthlyTasks(): array
    {
        $data = [];
        $daysInMonth = now()->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = now()->startOfMonth()->addDays($day - 1);

            if ($date->isFuture()) {
                break;
            }

            $count = Task::where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->count();

            $data[] = [
                'date' => $date->format('M j'),
                'count' => $count,
            ];
        }

        return $data;
    }

    /**
     * Get heatmap data for last 90 days.
     */
    private function getHeatmapData(): array
    {
        $data = [];

        for ($i = 89; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');

            $tasksCompleted = Task::where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->count();

            $data[$dateStr] = $tasksCompleted;
        }

        return $data;
    }

    /**
     * Get task breakdown by section.
     */
    private function getSectionBreakdown(): array
    {
        return [
            'daily' => Task::where('section', 'daily')
                ->where('status', 'completed')
                ->count(),
            'weekly' => Task::where('section', 'weekly')
                ->where('status', 'completed')
                ->count(),
            'monthly' => Task::where('section', 'monthly')
                ->where('status', 'completed')
                ->count(),
        ];
    }

    /**
     * Get completion trend for last 30 days.
     */
    private function getCompletionTrend(): array
    {
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $completed = Task::where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->count();

            $created = Task::whereDate('created_at', $date)->count();

            $data[] = [
                'date' => $date->format('M j'),
                'completed' => $completed,
                'created' => $created,
            ];
        }

        return $data;
    }
}