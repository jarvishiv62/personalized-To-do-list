<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiaryController extends Controller
{
    /**
     * Display a listing of diary entries.
     */
    public function index(Request $request): View
    {
        $query = Diary::query();

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $query->forDate($request->date);
        }

        // Search by title or content
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        $entries = $query->latest()->paginate(10);

        return view('diary.index', compact('entries'));
    }

    /**
     * Show the form for creating a new diary entry.
     */
    public function create(): View
    {
        return view('diary.create');
    }

    /**
     * Store a newly created diary entry in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
        ], [
            'title.required' => 'Please provide a title for your diary entry.',
            'content.required' => 'Please write some content for your diary entry.',
            'date.required' => 'Please select a date for your diary entry.',
        ]);

        // For single-user app, user_id can be null or hardcoded
        // If you implement authentication later, use: auth()->id()
        $validated['user_id'] = null;

        Diary::create($validated);

        return redirect()->route('diary.index')
            ->with('success', 'Diary entry created successfully!');
    }

    /**
     * Display the specified diary entry.
     */
    public function show(Diary $diary): View
    {
        return view('diary.show', compact('diary'));
    }

    /**
     * Show the form for editing the specified diary entry.
     */
    public function edit(Diary $diary): View
    {
        return view('diary.edit', compact('diary'));
    }

    /**
     * Update the specified diary entry in storage.
     */
    public function update(Request $request, Diary $diary): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
        ], [
            'title.required' => 'Please provide a title for your diary entry.',
            'content.required' => 'Please write some content for your diary entry.',
            'date.required' => 'Please select a date for your diary entry.',
        ]);

        $diary->update($validated);

        return redirect()->route('diary.index')
            ->with('success', 'Diary entry updated successfully!');
    }

    /**
     * Remove the specified diary entry from storage.
     */
    public function destroy(Diary $diary): RedirectResponse
    {
        $diary->delete();

        return redirect()->route('diary.index')
            ->with('success', 'Diary entry deleted successfully!');
    }
}