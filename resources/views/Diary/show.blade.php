@extends('layouts.app')

@section('title', $diary->title)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('diary.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Diary
                    </a>
                    <div class="btn-group">
                        <a href="{{ route('diary.edit', $diary) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('diary.destroy', $diary) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this diary entry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Entry Card -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <!-- Date Badge -->
                        <div class="mb-3">
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-calendar3"></i>
                                {{ $diary->date->format('l, F j, Y') }}
                            </span>
                            <span class="badge bg-info text-dark fs-6 ms-2">
                                {{ $diary->word_count }} words
                            </span>
                        </div>

                        <!-- Title -->
                        <h2 class="card-title mb-4">{{ $diary->title }}</h2>

                        <!-- Content -->
                        <div class="diary-content">
                            {!! nl2br(e($diary->content)) !!}
                        </div>

                        <!-- Metadata -->
                        <hr class="my-4">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>
                                <i class="bi bi-clock"></i>
                                Created {{ $diary->created_at->format('M j, Y \a\t g:i A') }}
                            </span>
                            @if($diary->updated_at != $diary->created_at)
                                <span>
                                    <i class="bi bi-pencil"></i>
                                    Last edited {{ $diary->updated_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .diary-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@endpush