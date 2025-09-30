@extends('layouts.app')

@section('title', 'Goals - DailyDrive')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="display-5">
                        <i class="bi bi-bullseye text-primary"></i> Goals
                    </h1>
                    <a href="{{ route('goals.create') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle"></i> Create New Goal
                    </a>
                </div>
                <p class="text-muted">Track your progress towards achieving your goals</p>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="btn-group" role="group">
                    <a href="{{ route('goals.index') }}" class="btn btn-{{ !$section ? 'primary' : 'outline-primary' }}">
                        <i class="bi bi-list-ul"></i> All
                    </a>
                    <a href="{{ route('goals.index', ['section' => 'daily']) }}"
                        class="btn btn-{{ $section === 'daily' ? 'primary' : 'outline-primary' }}">
                        <i class="bi bi-sun"></i> Daily
                    </a>
                    <a href="{{ route('goals.index', ['section' => 'weekly']) }}"
                        class="btn btn-{{ $section === 'weekly' ? 'primary' : 'outline-primary' }}">
                        <i class="bi bi-calendar-week"></i> Weekly
                    </a>
                    <a href="{{ route('goals.index', ['section' => 'monthly']) }}"
                        class="btn btn-{{ $section === 'monthly' ? 'primary' : 'outline-primary' }}">
                        <i class="bi bi-calendar-month"></i> Monthly
                    </a>
                </div>
            </div>
        </div>

        @if($goals->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-bullseye display-1 text-muted"></i>
                            <h3 class="mt-3">No goals yet!</h3>
                            <p class="text-muted">Set your first goal and start making progress.</p>
                            <a href="{{ route('goals.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Create Your First Goal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($goals as $goal)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100 goal-card">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $goal->title }}</h5>
                                    <span
                                        class="badge bg-{{ $goal->section === 'daily' ? 'info' : ($goal->section === 'weekly' ? 'warning' : 'success') }}">
                                        {{ ucfirst($goal->section) }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($goal->description)
                                    <p class="text-muted">{{ $goal->description }}</p>
                                @endif

                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">Progress</span>
                                        <span class="badge bg-{{ $goal->getProgressColor() }}">
                                            {{ number_format($goal->progress, 0) }}%
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $goal->getProgressColor() }}" role="progressbar"
                                            style="width: {{ $goal->progress }}%">
                                            {{ number_format($goal->progress, 0) }}%
                                        </div>
                                    </div>
                                </div>

                                <!-- Task Count -->
                                <div class="d-flex justify-content-between text-muted small mb-3">
                                    <span>
                                        <i class="bi bi-list-check"></i>
                                        {{ $goal->tasks->count() }} Total Tasks
                                    </span>
                                    <span>
                                        <i class="bi bi-check-circle"></i>
                                        {{ $goal->tasks->where('status', 'completed')->count() }} Completed
                                    </span>
                                </div>

                                <!-- Recent Tasks -->
                                @if($goal->tasks->take(3)->count() > 0)
                                    <div class="mb-3">
                                        <p class="small text-muted mb-1"><strong>Recent Tasks:</strong></p>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($goal->tasks->take(3) as $task)
                                                <li
                                                    class="small {{ $task->isCompleted() ? 'text-decoration-line-through text-muted' : '' }}">
                                                    <i
                                                        class="bi bi-{{ $task->isCompleted() ? 'check-circle-fill text-success' : 'circle' }}"></i>
                                                    {{ Str::limit($task->title, 40) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('goals.show', $goal) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <div>
                                        <a href="{{ route('goals.edit', $goal) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure? This will also remove the goal association from all tasks.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection