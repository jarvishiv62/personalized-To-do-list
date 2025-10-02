<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'section',
        'goal_id',
        'due_date',
        'start_time',
        'end_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    /**
     * Get the goal that owns the task.
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    /**
     * Check if task is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if task is overdue.
     *
     * @return bool
     */
    public function isOverdue(): bool
    {
        return $this->due_date &&
            $this->due_date->isPast() &&
            $this->status === 'pending';
    }

    /**
     * Check if task is currently ongoing based on time.
     *
     * @return bool
     */
    public function isOngoing(): bool
    {
        if (!$this->start_time || !$this->end_time || $this->status === 'completed') {
            return false;
        }

        $now = now()->format('H:i:s');
        $startTime = Carbon::parse($this->start_time)->format('H:i:s');
        $endTime = Carbon::parse($this->end_time)->format('H:i:s');

        return $now >= $startTime && $now <= $endTime;
    }

    /**
     * Check if task is upcoming (starts in the future today).
     *
     * @return bool
     */
    public function isUpcoming(): bool
    {
        if (!$this->start_time || $this->status === 'completed') {
            return false;
        }

        $now = now()->format('H:i:s');
        $startTime = Carbon::parse($this->start_time)->format('H:i:s');

        return $now < $startTime;
    }

    /**
     * Check if task time has passed.
     *
     * @return bool
     */
    public function isPastTime(): bool
    {
        if (!$this->end_time) {
            return false;
        }

        $now = now()->format('H:i:s');
        $endTime = Carbon::parse($this->end_time)->format('H:i:s');

        return $now > $endTime && $this->status === 'pending';
    }

    /**
     * Get formatted time range string.
     *
     * @return string|null
     */
    public function getTimeRangeAttribute(): ?string
    {
        if (!$this->start_time && !$this->end_time) {
            return null;
        }

        if ($this->start_time && $this->end_time) {
            return Carbon::parse($this->start_time)->format('g:i A') . ' - ' .
                Carbon::parse($this->end_time)->format('g:i A');
        }

        if ($this->start_time) {
            return 'From ' . Carbon::parse($this->start_time)->format('g:i A');
        }

        return 'Until ' . Carbon::parse($this->end_time)->format('g:i A');
    }

    /**
     * Get the duration in minutes.
     *
     * @return int|null
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $start->diffInMinutes($end);
    }

    /**
     * Scope a query to only include tasks of a given section.
     */
    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope a query to only include pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to order tasks by time.
     */
    public function scopeOrderByTime($query)
    {
        return $query->orderByRaw('start_time IS NULL')
            ->orderBy('start_time', 'asc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Handle task completion gamification.
     * Call this method after marking a task as completed.
     */
    public function handleCompletion(): void
    {
        if ($this->status !== 'completed') {
            return;
        }
        
        $stats = \App\Models\UserStats::getOrCreate();
        
        // Award points (10 points per task)
        $stats->awardPoints(10);
        
        // Update streak
        $stats->updateStreak();
        
        // Record daily completion
        $today = now()->toDateString();
        $todayCount = self::where('status', 'completed')
            ->whereDate('updated_at', today())
            ->count();
        
        $stats->recordDailyCompletion($today, $todayCount);
    }
}