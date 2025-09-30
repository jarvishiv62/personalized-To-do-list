<div class="row">
    <!-- Tasks Column -->
    <div class="col-lg-8">
        <!-- Goals Section -->
        @if($goals->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bullseye"></i> {{ ucfirst($section) }} Goals
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($goals as $goal)
                        <div class="goal-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">
                                    <a href="{{ route('goals.show', $goal) }}" class="text-decoration-none">
                                        {{ $goal->title }}
                                    </a>
                                </h6>
                                <span class="badge bg-{{ $goal->getProgressColor() }}">
                                    {{ number_format($goal->progress, 0) }}%
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $goal->getProgressColor() }}" role="progressbar"
                                    style="width: {{ $goal->progress }}%">
                                </div>
                            </div>
                            @if($goal->description)
                                <p class="text-muted small mt-2 mb-0">{{ Str::limit($goal->description, 100) }}</p>
                            @endif
                        </div>
                    @endforeach
                    <a href="{{ route('goals.create') }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Add New Goal
                    </a>
                </div>
            </div>
        @endif

        <!-- Pending Tasks -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Pending Tasks
                        <span
                            class="badge bg-warning text-dark">{{ $tasks->where('status', 'pending')->count() }}</span>
                    </h5>
                    <a href="{{ route('tasks.create') }}?section={{ $section }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Task
                    </a>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($tasks->where('status', 'pending') as $task)
                    <li class="list-group-item task-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="form-check">
                                    <input class="form-check-input task-checkbox" type="checkbox" id="task-{{ $task->id }}"
                                        data-task-id="{{ $task->id }}">
                                    <label class="form-check-label" for="task-{{ $task->id }}">
                                        <h6 class="mb-1">{{ $task->title }}</h6>
                                    </label>
                                </div>
                                @if($task->goal)
                                    <span class="badge bg-info text-dark ms-4 mb-1">
                                        <i class="bi bi-bullseye"></i> {{ $task->goal->title }}
                                    </span>
                                @endif
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
                        <p class="mt-2 mb-0">No pending tasks! Time to create new ones or celebrate.</p>
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Completed Tasks -->
        @if($tasks->where('status', 'completed')->count() > 0)
            <div class="card shadow-sm">
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
                                    @if($task->goal)
                                        <span class="badge bg-secondary ms-4 mb-1">
                                            <i class="bi bi-bullseye"></i> {{ $task->goal->title }}
                                        </span>
                                    @endif
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
    <div class="col-lg-4">
        <!-- Progress Summary -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-graph-up text-primary"></i> Progress Summary
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

        <!-- Quick Stats -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-bar-chart text-info"></i> Quick Stats
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-circle-fill text-warning"></i>
                        <strong>Pending:</strong> {{ $tasks->where('status', 'pending')->count() }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-circle-fill text-success"></i>
                        <strong>Completed:</strong> {{ $tasks->where('status', 'completed')->count() }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-circle-fill text-danger"></i>
                        <strong>Overdue:</strong> {{ $tasks->filter(fn($t) => $t->isOverdue())->count() }}
                    </li>
                    <li>
                        <i class="bi bi-circle-fill text-primary"></i>
                        <strong>Goals:</strong> {{ $goals->count() }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-lightning-charge text-warning"></i> Quick Actions
                </h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('tasks.create') }}?section={{ $section }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Add {{ ucfirst($section) }} Task
                    </a>
                    <a href="{{ route('goals.create') }}?section={{ $section }}" class="btn btn-outline-success">
                        <i class="bi bi-bullseye"></i> Create {{ ucfirst($section) }} Goal
                    </a>
                    <a href="{{ route('goals.index') }}?section={{ $section }}" class="btn btn-outline-info">
                        <i class="bi bi-list-ul"></i> View All Goals
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>