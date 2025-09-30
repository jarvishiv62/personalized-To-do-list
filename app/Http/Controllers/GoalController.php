<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoalController extends Controller
{
    /**
     * Display a listing of goals.
     */
    public function index(Request $request): View
    {
        $section = $request->get('section');

        $query = Goal::withCount('tasks')
            ->with([
                'tasks' => function ($query) {
                    $query->orderBy('status', 'asc');
                }
            ]);

        if ($section) {
            $query->section($section);
        }

        $goals = $query->orderBy('created_at', 'desc')->get();

        // Recalculate progress for all goals
        foreach ($goals as $goal) {
            $goal->calculateProgress();
        }

        return view('goals.index', compact('goals', 'section'));
    }

    /**
     * Show the form for creating a new goal.
     */
    public function create(): View
    {
        return view('goals.create');
    }

    /**
     * Store a newly created goal in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'section' => 'required|in:daily,weekly,monthly',
        ]);

        Goal::create($validated);

        return redirect()->route('goals.index')
            ->with('success', 'Goal created successfully!');
    }

    /**
     * Display the specified goal.
     */
    public function show(Goal $goal): View
    {
        $goal->load('tasks');
        $goal->calculateProgress();

        return view('goals.show', compact('goal'));
    }

    /**
     * Show the form for editing the specified goal.
     */
    public function edit(Goal $goal): View
    {
        return view('goals.edit', compact('goal'));
    }

    /**
     * Update the specified goal in storage.
     */
    public function update(Request $request, Goal $goal): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'section' => 'required|in:daily,weekly,monthly',
        ]);

        $goal->update($validated);

        return redirect()->route('goals.index')
            ->with('success', 'Goal updated successfully!');
    }

    /**
     * Remove the specified goal from storage.
     */
    public function destroy(Goal $goal): RedirectResponse
    {
        $goal->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Goal deleted successfully!');
    }
}