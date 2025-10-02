@extends('layouts.app')

@section('title', 'My Diary')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5">
                            <i class="bi bi-journal-text text-primary"></i> My Diary
                        </h1>
                        <p class="text-muted">Your personal thoughts and reflections</p>
                    </div>
                    <a href="{{ route('diary.create') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle"></i> New Entry
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('diary.index') }}" class="row g-3">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="search" placeholder="Search diary entries..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($entries->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-journal-plus display-1 text-muted"></i>
                            <h3 class="mt-3">No diary entries yet!</h3>
                            <p class="text-muted">Start documenting your thoughts and experiences.</p>
                            <a href="{{ route('diary.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Write Your First Entry
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    @foreach($entries as $entry)
                        <div class="card shadow-sm mb-3 diary-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-calendar3 text-primary me-2"></i>
                                            <span class="text-muted">
                                                {{ $entry->date->format('l, F j, Y') }}
                                            </span>
                                            <span class="badge bg-info text-dark ms-2">
                                                {{ $entry->word_count }} words
                                            </span>
                                        </div>
                                        <h4 class="card-title mb-3">
                                            <a href="{{ route('diary.show', $entry) }}" class="text-decoration-none text-dark">
                                                {{ $entry->title }}
                                            </a>
                                        </h4>
                                        <p class="card-text text-muted">
                                            {{ $entry->excerpt }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i>
                                            Created {{ $entry->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="btn-group ms-3" role="group">
                                        <a href="{{ route('diary.show', $entry) }}" class="btn btn-sm btn-outline-primary"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('diary.edit', $entry) }}" class="btn btn-sm btn-outline-secondary"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('diary.destroy', $entry) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this diary entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection