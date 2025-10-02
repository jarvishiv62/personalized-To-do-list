@extends('layouts.app')

@section('title', 'New Diary Entry')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-journal-plus"></i> New Diary Entry
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('diary.store') }}" method="POST">
                            @csrf

                            <!-- Date Field -->
                            <div class="mb-3">
                                <label for="date" class="form-label fw-bold">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                    name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Select the date for this diary entry
                                </div>
                            </div>

                            <!-- Title Field -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">
                                    Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}" placeholder="What's on your mind today?"
                                    required autofocus>
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Content Field -->
                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">
                                    Content <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                    name="content" rows="15"
                                    placeholder="Write your thoughts, feelings, and experiences here..."
                                    required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Express yourself freely - this is your personal space
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('diary.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Entry
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Writing Tips Card -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-lightbulb text-warning"></i> Journaling Tips
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Write honestly - this is for your eyes only</li>
                            <li>Include details about your day, thoughts, and feelings</li>
                            <li>Reflect on what you learned or experienced</li>
                            <li>Note things you're grateful for</li>
                            <li>Set intentions for tomorrow</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection