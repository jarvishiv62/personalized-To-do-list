@extends('layouts.app')

@section('title', 'Create New Task')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Create New Task
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <!-- Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">
                                Task Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   placeholder="Enter task title"
                                   required
                                   autofocus>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Add any additional details about this task...">{{ old('description') }}</textarea>
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
                            <select class="form-select @error('section') is-invalid @enderror" 
                                    id="section" 
                                    name="section" 
                                    required>
                                <option value="">Select a time period</option>
                                <option value="daily" {{ old('section', request('section')) === 'daily' ? 'selected' : '' }}>
                                    Daily
                                </option>
                                <option value="weekly" {{ old('section', request('section')) === 'weekly' ? 'selected' : '' }}>
                                    Weekly
                                </option>
                                <option value="monthly" {{ old('section', request('section')) === 'monthly' ? 'selected' : '' }}>
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
                            <select class="form-select @error('goal_id') is-invalid @enderror" 
                                    id="goal_id" 
                                    name="goal_id">
                                <option value="">No goal (standalone task)</option>
                                @foreach($goals as $goal)
                                    <option value="{{ $goal->id }}" 
                                            {{ old('goal_id', request('goal_id')) == $goal->id ? 'selected' : '' }}>
                                        {{ $goal->title }} ({{ ucfirst($goal->section) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('goal_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Link this task to a goal to track progress
                            </div>
                        </div>

                        <!-- Due Date Field -->
                        <div class="mb-3">
                            <label for="due_date" class="form-label fw-bold">
                                Due Date <span class="text-muted small">(Optional)</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" 
                                   name="due_date" 
                                   value="{{ old('due_date') }}"
                                   min="{{ date('Y-m-d') }}">
                            @error('due_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Set a deadline to help you stay on track
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Helper Card -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-lightbulb text-warning"></i> Tips for Effective Tasks
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Keep task titles clear and action-oriented</li>
                        <li>Break down large tasks into smaller, manageable ones</li>
                        <li>Link tasks to goals for better progress tracking</li>
                        <li>Set realistic due dates to maintain motivation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection