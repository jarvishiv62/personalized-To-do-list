<?php

namespace Database\Seeders;

use App\Models\UserStats;
use App\Models\Task;
use Illuminate\Database\Seeder;

class UserStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create user stats
        $stats = UserStats::getOrCreate();

        // Calculate points based on completed tasks
        $completedTasks = Task::where('status', 'completed')->count();
        $points = $completedTasks * 10; // 10 points per task

        // Set initial stats
        $stats->update([
            'total_points' => $points,
            'current_streak' => rand(3, 15),
            'longest_streak' => rand(15, 30),
            'last_activity_date' => now(),
            'badges' => [],
        ]);

        // Check and award badges
        $stats->checkAndAwardBadges();

        $this->command->info('User stats initialized successfully!');
        $this->command->info("Total Points: {$stats->total_points}");
        $this->command->info("Badges Earned: " . count($stats->badges ?? []));
    }
}