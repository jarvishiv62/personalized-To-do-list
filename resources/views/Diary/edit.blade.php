@extends('layouts.app')

@section('title', 'Edit Diary Entry')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square"></i> Edit Diary Entry
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('diary.update', $diary) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Date Field -->
                            <div class="mb-3">
                                <label for="date" class="form-label fw-bold">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                    name="date" value="{{ old('date', $diary->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Title Field -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">
                                    Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title', $diary->title) }}"
                                    placeholder="What's on your mind today?" required autofocus>
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
                                    required>{{ old('content', $diary->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('diary.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Entry
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Entry Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-info-circle text-info"></i> Entry Information
                        </h6>
                        <p class="small text-muted mb-1">
                            <strong>Word Count:</strong> {{ $diary->word_count }} words
                        </p>
                        <p class="small text-muted mb-1">
                            <strong>Created:</strong> {{ $diary->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                        <p class="small text-muted mb-0">
                            <strong>Last Updated:</strong> {{ $diary->updated_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection