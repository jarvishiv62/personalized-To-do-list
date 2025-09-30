@extends('layouts.app')

@section('title', $goal->title . ' - Goal Details')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5">
                        <i class="bi bi-bullseye text-primary"></i> {{ $goal->title }}
                    </h1>
                    <span class="badge bg-{{ $goal->section === 'daily' ? 'info' : ($goal->section === 'weekly' ? 'warning' : 'success') }} fs-6">
                        {{ ucfirst($goal->section) }} Goal
                    </span>
                </div>
                <div>
                    <a href="{{ route('goals.edit', $goal) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Goal
                    </a>
                    <a href="{{ route('goals.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Goals
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Goal Description -->
            @if($goal->description)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-file-text"></i> Description
                        </h5>
                        <p class="mb-0">{{ $goal->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Tasks List -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check"></i> Tasks 
                            <span class="badge bg-primary">{{ $goal->tasks->count() }}</span>
                        </h5>
                        <a href="{{ route('tasks.create') }}?section={{ $goal->section }}&goal_id={{ $goal->id }}" 
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Task
                        </a>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($goal->tasks as $task)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="form-check">
                                        <input class="form-check-input task-checkbox" 
                                               type="checkbox" 
                                               {{ $task->isCompleted() ? 'checked' : '' }}
                                               id="task-{{ $task->id }}"
                                               data-task-id="{{ $task->id }}">
                                        <label class="form-check-label {{ $task->isCompleted() ? 'text-decoration-line-through text-muted' : '' }}" 
                                               for="task-{{ $task->id }}">
                                            <h6 class="mb-1">{{ $task->title }}</h6>
                                        </label>
                                    </div>
                                    @if($task->description)
                                        <p class="text-muted small mb-1 ms-4">{{ $task->description }}</p>
                                    @endif
                                    <div class="ms-4">
                                        <span class="badge bg-{{ $task->status === 'pending' ? 'warning' : 'success' }} text-dark">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                        @if($task->due_date)
                                            <small class="text-muted ms-2">
                                                <i class="bi bi-calendar-event"></i> 
                                                {{ $task->due_date->format('M j, Y') }}
                                                @if($task->isOverdue())
                                                    <span class="text-danger fw-bold">(Overdue)</span>
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tasks.edit', $task) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" 
                                          method="POST" 
                                          class="d-inline"
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
                            <i class="bi bi-inbox display-4"></i>
                            <p class="mt-2 mb-0">No tasks yet. Add your first task to start tracking progress!</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Progress Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-graph-up text-primary"></i> Progress
                    </h5>
                    <div class="text-center mb-3">
                        <h2 class="display-4 mb-0 text-{{ $goal->getProgressColor() }}">
                            {{ number_format($goal->progress, 0) }}%
                        </h2>
                        <p class="text-muted small">Completion Rate</p>
                    </div>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-{{ $goal->getProgressColor() }}" 
                             role="progressbar" 
                             style="width: {{ $goal->progress }}%">
                        </div>
                    </div>
                    <p class="text-muted small mb-0 text-center">
                        {{ $goal->tasks->where('status', 'completed')->count() }} of {{ $goal->tasks->count() }} tasks completed
                    </p>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart text-info"></i> Statistics
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-circle-fill text-primary"></i>
                            <strong>Total Tasks:</strong> {{ $goal->tasks->count() }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-circle-fill text-warning"></i>
                            <strong>Pending:</strong> {{ $goal->tasks->where('status', 'pending')->count() }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-circle-fill text-success"></i>
                            <strong>Completed:</strong> {{ $goal->tasks->where('status', 'completed')->count() }}
                        </li>
                        <li>
                            <i class="bi bi-circle-fill text-danger"></i>
                            <strong>Overdue:</strong> {{ $goal->tasks->filter(fn($t) => $t->isOverdue())->count() }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Goal Info Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle text-secondary"></i> Information
                    </h5>
                    <p class="small mb-1">
                        <strong>Time Period:</strong> {{ ucfirst($goal->section) }}
                    </p>
                    <p class="small mb-1">
                        <strong>Created:</strong> {{ $goal->created_at->format('M j, Y') }}
                    </p>
                    <p class="small mb-0">
                        <strong>Last Updated:</strong> {{ $goal->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const checkboxes = document.querySelectorAll('.task-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            
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
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked;
                alert('Failed to update task status. Please try again.');
            });
        });
    });
});
</script>
@endpush