<?php

namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PomodoroController extends Controller
{
    public function index(): View
    {
        $session = PomodoroSession::latest()->first() ?? new PomodoroSession();
        $dailyTasks = Task::section('daily')->pending()->get();

        return view('pomodoro.pomodoro', compact('session', 'dailyTasks'));
    }

    public function startTimer(Request $request): JsonResponse
    {
        $session = PomodoroSession::latest()->first();

        if (!$session || $session->isCompleted()) {
            $session = PomodoroSession::create([
                'task_id' => $request->task_id,
                'status' => 'running',
                'started_at' => now(),
            ]);
        } else {
            $session->update([
                'status' => 'running',
                'started_at' => now(),
                'task_id' => $request->task_id ?? $session->task_id,
            ]);
        }

        return response()->json([
            'success' => true,
            'session' => $session->fresh(),
            'message' => 'Pomodoro timer started!'
        ]);
    }

    public function pauseTimer(): JsonResponse
    {
        $session = PomodoroSession::where('status', 'running')->first();

        if ($session) {
            $session->update(['status' => 'paused']);
        }

        return response()->json([
            'success' => true,
            'session' => $session?->fresh(),
            'message' => 'Timer paused'
        ]);
    }

    public function resetTimer(): JsonResponse
    {
        $session = PomodoroSession::latest()->first();

        if ($session) {
            $session->update([
                'status' => 'paused',
                'remaining_seconds' => $session->focus_duration,
                'is_break' => false,
                'started_at' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'session' => $session?->fresh(),
            'message' => 'Timer reset'
        ]);
    }

    public function getStatus(): JsonResponse
    {
        $session = PomodoroSession::latest()->first();

        return response()->json([
            'session' => $session,
            'current_time' => now()->format('H:i:s'),
        ]);
    }

    public function updateRemainingTime(Request $request): JsonResponse
    {
        $session = PomodoroSession::latest()->first();

        if ($session && $session->isRunning()) {
            $session->update(['remaining_seconds' => $request->remaining_seconds]);

            if ($request->remaining_seconds <= 0) {
                $this->handleSessionComplete($session);
            }
        }

        return response()->json(['success' => true]);
    }

    private function handleSessionComplete(PomodoroSession $session): void
    {
        if ($session->is_break) {
            $session->update(['status' => 'completed']);
        } else {
            $session->update([
                'is_break' => true,
                'remaining_seconds' => $session->break_duration,
                'started_at' => now(),
            ]);
        }
    }
}