<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_points',
        'current_streak',
        'longest_streak',
        'last_activity_date',
        'badges',
        'daily_completion',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_activity_date' => 'date',
            'badges' => 'array',
            'daily_completion' => 'array',
        ];
    }

    /**
     * Get the user that owns the stats.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create stats for user (single-user setup uses null user_id).
     */
    public static function getOrCreate($userId = null): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'total_points' => 0,
                'current_streak' => 0,
                'longest_streak' => 0,
                'badges' => [],
                'daily_completion' => [],
            ]
        );
    }

    /**
     * Award points for completing a task.
     */
    public function awardPoints(int $points = 10): void
    {
        $this->increment('total_points', $points);
        $this->checkAndAwardBadges();
    }

    /**
     * Update streak based on task completion.
     */
    public function updateStreak(): void
    {
        $today = now()->toDateString();
        $lastActivity = $this->last_activity_date?->toDateString();

        if ($lastActivity === $today) {
            // Already counted today
            return;
        }

        $yesterday = now()->subDay()->toDateString();

        if ($lastActivity === $yesterday) {
            // Continuing streak
            $this->increment('current_streak');
        } else {
            // Streak broken
            $this->current_streak = 1;
        }

        // Update longest streak if current is higher
        if ($this->current_streak > $this->longest_streak) {
            $this->longest_streak = $this->current_streak;
        }

        $this->last_activity_date = now();
        $this->save();

        $this->checkAndAwardBadges();
    }

    /**
     * Check and award badges based on achievements.
     */
    public function checkAndAwardBadges(): void
    {
        $badges = $this->badges ?? [];
        $newBadges = [];

        // Streak badges
        if ($this->current_streak >= 7 && !in_array('7-day-streak', $badges)) {
            $newBadges[] = '7-day-streak';
        }
        if ($this->current_streak >= 30 && !in_array('30-day-streak', $badges)) {
            $newBadges[] = '30-day-streak';
        }
        if ($this->current_streak >= 100 && !in_array('100-day-streak', $badges)) {
            $newBadges[] = '100-day-streak';
        }

        // Task completion badges
        $completedTasks = Task::where('status', 'completed')->count();

        if ($completedTasks >= 10 && !in_array('10-tasks', $badges)) {
            $newBadges[] = '10-tasks';
        }
        if ($completedTasks >= 50 && !in_array('50-tasks', $badges)) {
            $newBadges[] = '50-tasks';
        }
        if ($completedTasks >= 100 && !in_array('100-tasks', $badges)) {
            $newBadges[] = '100-tasks';
        }
        if ($completedTasks >= 500 && !in_array('500-tasks', $badges)) {
            $newBadges[] = '500-tasks';
        }

        // Points badges
        if ($this->total_points >= 100 && !in_array('100-points', $badges)) {
            $newBadges[] = '100-points';
        }
        if ($this->total_points >= 500 && !in_array('500-points', $badges)) {
            $newBadges[] = '500-points';
        }
        if ($this->total_points >= 1000 && !in_array('1000-points', $badges)) {
            $newBadges[] = '1000-points';
        }

        // Goal completion badges
        $completedGoals = Goal::where('progress', '>=', 100)->count();

        if ($completedGoals >= 5 && !in_array('5-goals', $badges)) {
            $newBadges[] = '5-goals';
        }
        if ($completedGoals >= 10 && !in_array('10-goals', $badges)) {
            $newBadges[] = '10-goals';
        }

        if (!empty($newBadges)) {
            $this->badges = array_unique(array_merge($badges, $newBadges));
            $this->save();
        }
    }

    /**
     * Record daily task completion.
     */
    public function recordDailyCompletion(string $date, int $count): void
    {
        $completion = $this->daily_completion ?? [];
        $completion[$date] = $count;

        // Keep only last 365 days
        if (count($completion) > 365) {
            $completion = array_slice($completion, -365, 365, true);
        }

        $this->daily_completion = $completion;
        $this->save();
    }

    /**
     * Get badge information.
     */
    public static function getBadgeInfo(string $badgeId): array
    {
        $badges = [
            // Streak badges
            '7-day-streak' => [
                'name' => '7 Day Streak',
                'description' => 'Complete tasks for 7 consecutive days',
                'icon' => 'bi-fire',
                'color' => 'warning',
            ],
            '30-day-streak' => [
                'name' => '30 Day Streak',
                'description' => 'Complete tasks for 30 consecutive days',
                'icon' => 'bi-fire',
                'color' => 'danger',
            ],
            '100-day-streak' => [
                'name' => '100 Day Streak',
                'description' => 'Complete tasks for 100 consecutive days',
                'icon' => 'bi-trophy-fill',
                'color' => 'warning',
            ],

            // Task badges
            '10-tasks' => [
                'name' => 'Getting Started',
                'description' => 'Complete 10 tasks',
                'icon' => 'bi-check-circle-fill',
                'color' => 'success',
            ],
            '50-tasks' => [
                'name' => 'Productive',
                'description' => 'Complete 50 tasks',
                'icon' => 'bi-check2-circle',
                'color' => 'success',
            ],
            '100-tasks' => [
                'name' => 'Achiever',
                'description' => 'Complete 100 tasks',
                'icon' => 'bi-award-fill',
                'color' => 'primary',
            ],
            '500-tasks' => [
                'name' => 'Master',
                'description' => 'Complete 500 tasks',
                'icon' => 'bi-star-fill',
                'color' => 'warning',
            ],

            // Points badges
            '100-points' => [
                'name' => 'Century',
                'description' => 'Earn 100 points',
                'icon' => 'bi-gem',
                'color' => 'info',
            ],
            '500-points' => [
                'name' => 'Point Collector',
                'description' => 'Earn 500 points',
                'icon' => 'bi-gem',
                'color' => 'primary',
            ],
            '1000-points' => [
                'name' => 'Point Master',
                'description' => 'Earn 1000 points',
                'icon' => 'bi-gem',
                'color' => 'warning',
            ],

            // Goal badges
            '5-goals' => [
                'name' => 'Goal Getter',
                'description' => 'Complete 5 goals',
                'icon' => 'bi-bullseye',
                'color' => 'success',
            ],
            '10-goals' => [
                'name' => 'Goal Master',
                'description' => 'Complete 10 goals',
                'icon' => 'bi-bullseye',
                'color' => 'primary',
            ],
        ];

        return $badges[$badgeId] ?? [
            'name' => $badgeId,
            'description' => 'Achievement unlocked',
            'icon' => 'bi-award',
            'color' => 'secondary',
        ];
    }
}