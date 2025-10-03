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
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}" placeholder="Enter task title" required
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
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="4"
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
                                <select class="form-select @error('section') is-invalid @enderror" id="section"
                                    name="section" required>
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

                            <!-- Time Schedule Fields (for Daily tasks) -->
                            <div class="card border-info mb-3" id="timeScheduleCard"
                                style="display: {{ old('section', request('section')) === 'daily' ? 'block' : 'none' }}">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-clock"></i> Time Schedule (Optional for Daily Tasks)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Start Time -->
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label for="start_time" class="form-label fw-bold">
                                                Start Time
                                            </label>
                                            <input type="time"
                                                class="form-control @error('start_time') is-invalid @enderror"
                                                id="start_time" name="start_time" value="{{ old('start_time') }}">
                                            @error('start_time')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="bi bi-info-circle"></i> When does this task start?
                                            </div>
                                        </div>

                                        <!-- End Time -->
                                        <div class="col-md-6">
                                            <label for="end_time" class="form-label fw-bold">
                                                End Time
                                            </label>
                                            <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                                id="end_time" name="end_time" value="{{ old('end_time') }}">
                                            @error('end_time')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="bi bi-info-circle"></i> When does this task end?
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-3 mb-0">
                                        <i class="bi bi-lightbulb"></i> <strong>Tip:</strong> Set times to create a
                                        structured daily timetable. Tasks will be displayed in chronological order.
                                    </div>
                                </div>
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
                                        <option value="{{ $goal->id }}" {{ old('goal_id', request('goal_id')) == $goal->id ? 'selected' : '' }}>
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
                                    id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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
                            <i class="bi bi-lightbulb text-warning"></i> Tips for Effective Task Scheduling
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>For daily tasks, set start and end times to create a structured timetable</li>
                            <li>Time-scheduled tasks will appear in chronological order on your dashboard</li>
                            <li>Current ongoing tasks will be highlighted automatically</li>
                            <li>Break down large tasks into smaller time blocks for better focus</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sectionSelect = document.getElementById('section');
            const timeScheduleCard = document.getElementById('timeScheduleCard');

            // Show/hide time schedule card based on section
            sectionSelect.addEventListener('change', function () {
                if (this.value === 'daily') {
                    timeScheduleCard.style.display = 'block';
                } else {
                    timeScheduleCard.style.display = 'none';
                    // Clear time fields when not daily
                    document.getElementById('start_time').value = '';
                    document.getElementById('end_time').value = '';
                }
            });
        });
    </script>
@endpush