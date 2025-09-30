@extends('layouts.app')

@section('title', 'Edit Goal')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square"></i> Edit Goal
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('goals.update', $goal) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Title Field -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">
                                    Goal Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $goal->title) }}"
                                       placeholder="Enter your goal title"
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
                                          placeholder="Describe your goal and what you want to achieve...">{{ old('description', $goal->description) }}</textarea>
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
                                    <option value="daily" {{ old('section', $goal->section) === 'daily' ? 'selected' : '' }}>
                                        Daily - Short-term goals for today
                                    </option>
                                    <option value="weekly" {{ old('section', $goal->section) === 'weekly' ? 'selected' : '' }}>
                                        Weekly - Goals to achieve this week
                                    </option>
                                    <option value="monthly" {{ old('section', $goal->section) === 'monthly' ? 'selected' : '' }}>
                                        Monthly - Long-term goals for the month
                                    </option>
                                </select>
                                @error('section')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('goals.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Goal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Goal Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-info-circle text-info"></i> Goal Information
                        </h6>
                        <p class="small text-muted mb-1">
                            <strong>Progress:</strong> {{ number_format($goal->progress, 0) }}%
                        </p>
                        <p class="small text-muted mb-1">
                            <strong>Total Tasks:</strong> {{ $goal->tasks->count() }}
                        </p>
                        <p class="small text-muted mb-1">
                            <strong>Created:</strong> {{ $goal->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                        <p class="small text-muted mb-0">
                            <strong>Last Updated:</strong> {{ $goal->updated_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection