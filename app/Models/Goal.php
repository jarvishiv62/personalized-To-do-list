<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
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
        'section',
        'progress',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'progress' => 'float',
        ];
    }

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks for the goal.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Calculate and update the progress of this goal.
     *
     * @return float
     */
    public function calculateProgress(): float
    {
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            $this->update(['progress' => 0]);
            return 0;
        }

        $completedTasks = $this->tasks()->completed()->count();
        $progress = round(($completedTasks / $totalTasks) * 100, 2);

        $this->update(['progress' => $progress]);

        return $progress;
    }

    /**
     * Check if goal is fully completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->progress >= 100;
    }

    /**
     * Scope a query to only include goals of a given section.
     */
    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Get progress color based on percentage.
     *
     * @return string
     */
    public function getProgressColor(): string
    {
        if ($this->progress >= 75) {
            return 'success';
        } elseif ($this->progress >= 50) {
            return 'info';
        } elseif ($this->progress >= 25) {
            return 'warning';
        }
        return 'danger';
    }
}