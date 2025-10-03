<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Goal;
use App\Http\Requests\StoreTaskRequest;
use App\Services\QuotePicker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TaskController extends \App\Http\Controllers\Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display the dashboard with tasks organized by section.
     */
    public function dashboard(QuotePicker $quotePicker): View
    {
        $quote = $quotePicker->getDailyQuote();
        $currentTime = now();

        $user = Auth::user();

        // Get daily tasks ordered by time for the current user
        $dailyTasks = $user->tasks()
            ->section('daily')
            ->orderByTime()
            ->get();

        $weeklyTasks = $user->tasks()
            ->section('weekly')
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $monthlyTasks = $user->tasks()
            ->section('monthly')
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get goals with their associated tasks for the current user
        $dailyGoals = $user->goals()->section('daily')->with(['tasks' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $weeklyGoals = $user->goals()->section('weekly')->with(['tasks' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $monthlyGoals = $user->goals()->section('monthly')->with(['tasks' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view('dashboard', compact(
            'quote',
            'currentTime',
            'dailyTasks',
            'weeklyTasks',
            'monthlyTasks',
            'dailyGoals',
            'weeklyGoals',
            'monthlyGoals'
        ));
    }

    /**
     * Display a listing of tasks.
     */
    public function index(Request $request): View
    {
        $section = $request->get('section');
        $goalId = $request->get('goal_id');

        $user = Auth::user();
        $query = $user->tasks()->with('goal');

        if ($section) {
            $query->section($section);

            // Order daily tasks by time
            if ($section === 'daily') {
                $query->orderByTime();
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($goalId) {
            $query->where('goal_id', $goalId);
        }

        $tasks = $query->get();
        $goals = Auth::user()->goals;

        return view('tasks.index', compact('tasks', 'goals', 'section', 'goalId'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        $goals = Auth::user()->goals;
        return view('tasks.create', compact('goals'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Auth::user()->tasks()->create($request->validated());

        // Update goal progress if task is associated with a goal
        if ($task->goal_id) {
            $task->goal->calculateProgress();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task): View
    {
        $goals = Auth::user()->goals;
        return view('tasks.edit', compact('task', 'goals'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(StoreTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        $oldGoalId = $task->goal_id;
        $task->update($request->validated());

        // Update progress for both old and new goals if changed
        if ($oldGoalId && $oldGoalId != $task->goal_id) {
            Goal::find($oldGoalId)?->calculateProgress();
        }

        if ($task->goal_id) {
            $task->goal->calculateProgress();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $goalId = $task->goal_id;
        $task->delete();

        // Update goal progress after deletion
        if ($goalId) {
            Goal::find($goalId)?->calculateProgress();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Toggle task completion status.
     */
    public function toggle(Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'status' => $task->status === 'pending' ? 'completed' : 'pending'
        ]);
        
        // Update goal progress when task status changes
        if ($task->goal_id) {
            $task->goal->calculateProgress();
        }
        
        // Handle gamification
        if ($task->status === 'completed') {
            $task->handleCompletion();
        }

        $message = $task->status === 'completed' 
            ? 'Task marked as completed! +10 points earned! 🎉' 
            : 'Task marked as pending!';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $task->status,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}