@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square"></i> Edit Task
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Title Field -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">
                                    Task Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title', $task->title) }}" placeholder="Enter task title"
                                    required autofocus>
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    Description <span class="text-muted small">(Optional)</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="4"
                                    placeholder="Add any additional details about this task...">{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Section Field -->
                            <div class="mb-3">
                                <label for="section" class="form-label fw-bold">
                                    Time Period <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('section') is-invalid @enderror" id="section"
                                    name="section" required>
                                    <option value="">Select a time period</option>
                                    <option value="daily" {{ old('section', $task->section) === 'daily' ? 'selected' : '' }}>
                                        Daily
                                    </option>
                                    <option value="weekly" {{ old('section', $task->section) === 'weekly' ? 'selected' : '' }}>
                                        Weekly
                                    </option>
                                    <option value="monthly" {{ old('section', $task->section) === 'monthly' ? 'selected' : '' }}>
                                        Monthly
                                    </option>
                                </select>
                                @error('section')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Goal Field -->
                            <div class="mb-3">
                                <label for="goal_id" class="form-label fw-bold">
                                    Associated Goal <span class="text-muted small">(Optional)</span>
                                </label>
                                <select class="form-select @error('goal_id') is-invalid @enderror" id="goal_id"
                                    name="goal_id">
                                    <option value="">No goal (standalone task)</option>
                                    @foreach($goals as $goal)
                                        <option value="{{ $goal->id }}" {{ old('goal_id', $task->goal_id) == $goal->id ? 'selected' : '' }}>
                                            {{ $goal->title }} ({{ ucfirst($goal->section) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('goal_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Due Date Field -->
                            <div class="mb-3">
                                <label for="due_date" class="form-label fw-bold">
                                    Due Date <span class="text-muted small">(Optional)</span>
                                </label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                    id="due_date" name="due_date"
                                    value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Status Field -->
                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Task Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Task Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-clock-history text-info"></i> Task Information
                        </h6>
                        <p class="small text-muted mb-1">
                            <strong>Created:</strong> {{ $task->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                        <p class="small text-muted mb-0">
                            <strong>Last Updated:</strong> {{ $task->updated_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection