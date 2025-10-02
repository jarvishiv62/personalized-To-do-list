<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Diary;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the calendar view.
     */
    public function index(): View
    {
        return view('calendar.index');
    }

    /**
     * Get events for calendar (tasks and diary entries).
     */
    public function events(): JsonResponse
    {
        $events = [];

        // Get all tasks with dates/times
        $tasks = Task::all();

        foreach ($tasks as $task) {
            $event = [
                'id' => 'task-' . $task->id,
                'title' => $task->title,
                'type' => 'task',
                'status' => $task->status,
                'description' => $task->description,
                'backgroundColor' => $this->getTaskColor($task),
                'borderColor' => $this->getTaskColor($task),
                'textColor' => '#ffffff',
            ];

            // Tasks with time scheduling
            if ($task->start_time && $task->end_time) {
                $date = $task->due_date ? $task->due_date->format('Y-m-d') : now()->format('Y-m-d');
                $event['start'] = $date . 'T' . \Carbon\Carbon::parse($task->start_time)->format('H:i:s');
                $event['end'] = $date . 'T' . \Carbon\Carbon::parse($task->end_time)->format('H:i:s');
            }
            // Tasks with only due date
            elseif ($task->due_date) {
                $event['start'] = $task->due_date->format('Y-m-d');
                $event['allDay'] = true;
            }
            // Tasks without dates (show on today)
            else {
                $event['start'] = now()->format('Y-m-d');
                $event['allDay'] = true;
            }

            // Add goal info if exists
            if ($task->goal) {
                $event['goal'] = $task->goal->title;
            }

            $events[] = $event;
        }

        // Get all diary entries
        $diaryEntries = Diary::all();

        foreach ($diaryEntries as $entry) {
            $events[] = [
                'id' => 'diary-' . $entry->id,
                'title' => '📔 ' . $entry->title,
                'start' => $entry->date->format('Y-m-d'),
                'allDay' => true,
                'type' => 'diary',
                'content' => $entry->excerpt,
                'wordCount' => $entry->word_count,
                'backgroundColor' => '#198754',  // Green
                'borderColor' => '#198754',
                'textColor' => '#ffffff',
            ];
        }

        return response()->json($events);
    }

    /**
     * Get color for task based on status and section.
     */
    private function getTaskColor(Task $task): string
    {
        // Completed tasks - Green
        if ($task->status === 'completed') {
            return '#198754';
        }

        // Overdue tasks - Red
        if ($task->isOverdue()) {
            return '#dc3545';
        }

        // Section-based colors for pending tasks
        return match ($task->section) {
            'daily' => '#0d6efd',    // Blue
            'weekly' => '#ffc107',   // Yellow
            'monthly' => '#6f42c1',  // Purple
            default => '#6c757d',    // Gray
        };
    }
}