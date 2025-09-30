@extends('layouts.app')

@section('title', 'My Daily Tasks')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="display-5">
                        <i class="bi bi-list-task text-primary"></i> My Daily Tasks
                    </h1>
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle"></i> Add New Task
                    </a>
                </div>
                <p class="text-muted">{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>

        @if($tasks->isEmpty())
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h3 class="mt-3">No tasks yet!</h3>
                            <p class="text-muted">Start your productive day by adding your first task.</p>
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Create Your First Task
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clock-history"></i> Pending Tasks
                                <span
                                    class="badge bg-warning text-dark">{{ $tasks->where('status', 'pending')->count() }}</span>
                            </h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            @forelse($tasks->where('status', 'pending') as $task)
                                <li class="list-group-item task-item" data-task-id="{{ $task->id }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="form-check">
                                                <input class="form-check-input task-checkbox" type="checkbox"
                                                    id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                                                <label class="form-check-label" for="task-{{ $task->id }}">
                                                    <h6 class="mb-1">{{ $task->title }}</h6>
                                                </label>
                                            </div>
                                            @if($task->description)
                                                <p class="text-muted small mb-1 ms-4">{{ $task->description }}</p>
                                            @endif
                                            @if($task->due_date)
                                                <small class="ms-4">
                                                    <i class="bi bi-calendar-event"></i>
                                                    <span class="{{ $task->isOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                                                        {{ $task->due_date->format('M j, Y') }}
                                                        @if($task->isOverdue())
                                                            (Overdue)
                                                        @endif
                                                    </span>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    <i class="bi bi-check-circle display-4"></i>
                                    <p class="mt-2 mb-0">All tasks completed! Great job!</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    @if($tasks->where('status', 'completed')->count() > 0)
                        <div class="card shadow-sm mt-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-check-circle-fill text-success"></i> Completed Tasks
                                    <span class="badge bg-success">{{ $tasks->where('status', 'completed')->count() }}</span>
                                </h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach($tasks->where('status', 'completed') as $task)
                                    <li class="list-group-item task-item completed">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="form-check">
                                                    <input class="form-check-input task-checkbox" type="checkbox" checked
                                                        id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                                                    <label class="form-check-label text-decoration-line-through text-muted"
                                                        for="task-{{ $task->id }}">
                                                        <h6 class="mb-1">{{ $task->title }}</h6>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-graph-up text-primary"></i> Progress
                            </h5>
                            <div class="progress mb-2" style="height: 25px;">
                                @php
                                    $totalTasks = $tasks->count();
                                    $completedTasks = $tasks->where('status', 'completed')->count();
                                    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
                                    {{ $percentage }}%
                                </div>
                            </div>
                            <p class="text-muted small mb-0">
                                {{ $completedTasks }} of {{ $totalTasks }} tasks completed
                            </p>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-info-circle text-info"></i> Quick Tips
                            </h5>
                            <ul class="small text-muted ps-3">
                                <li>Click the checkbox to mark tasks as complete</li>
                                <li>Set due dates to track deadlines</li>
                                <li>Edit tasks anytime you need</li>
                                <li>Stay focused on daily goals</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Add event listeners to all task checkboxes
            const checkboxes = document.querySelectorAll('.task-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const taskId = this.dataset.taskId;
                    const taskItem = this.closest('.task-item');

                    // Send AJAX request to toggle task status
                    fetch(`/tasks/${taskId}/toggle`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reload page to update task lists
                                window.location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Revert checkbox state on error
                            this.checked = !this.checked;
                            alert('Failed to update task status. Please try again.');
                        });
                });
            });
        });
    </script>
@endpush