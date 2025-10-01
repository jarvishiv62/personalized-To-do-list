<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PomodoroSession extends Model
{
    protected $fillable = [
        'task_id',
        'status',
        'focus_duration',
        'break_duration',
        'remaining_seconds',
        'is_break',
        'started_at'
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'is_break' => 'boolean',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getFormattedTime(): string
    {
        $minutes = floor($this->remaining_seconds / 60);
        $seconds = $this->remaining_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getSessionType(): string
    {
        return $this->is_break ? 'Break' : 'Focus';
    }

    public function getSessionColor(): string
    {
        return $this->is_break ? 'success' : 'primary';
    }
}