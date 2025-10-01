@extends('layouts.app')

@section('title', 'Pomodoro Timer - DailyDrive')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Timer Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="mb-0">🍅 Pomodoro Timer</h2>
                        <small class="opacity-75">Focus for 25 minutes, break for 5</small>
                    </div>

                    <div class="card-body text-center p-5">
                        <!-- Session Type Badge -->
                        <div class="mb-4">
                            <span class="badge bg-{{ $session->getSessionColor() }} fs-6 px-3 py-2">
                                {{ $session->getSessionType() }} Session
                            </span>
                        </div>

                        <!-- Timer Display -->
                        <div class="timer-display mb-4">
                            <div class="display-1 fw-bold text-dark" id="timer">
                                {{ $session->getFormattedTime() }}
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-4" style="height: 8px;">
                            <div id="progressBar" class="progress-bar bg-{{ $session->getSessionColor() }}"
                                role="progressbar" style="width: 100%"></div>
                        </div>

                        <!-- Control Buttons -->
                        <div class="timer-controls mb-4">
                            <button id="startBtn" class="btn btn-success btn-lg px-4 me-2">
                                <i class="bi bi-play-fill"></i> Start
                            </button>
                            <button id="pauseBtn" class="btn btn-warning btn-lg px-4 me-2" disabled>
                                <i class="bi bi-pause-fill"></i> Pause
                            </button>
                            <button id="resetBtn" class="btn btn-danger btn-lg px-4">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </button>
                        </div>

                        <!-- Task Selection -->
                        <div class="task-selection mb-4">
                            <label for="taskSelect" class="form-label fw-semibold">Link to Daily Task (Optional)</label>
                            <select id="taskSelect" class="form-select">
                                <option value="">No task selected</option>
                                @foreach($dailyTasks as $task)
                                    <option value="{{ $task->id }}" {{ $session->task_id == $task->id ? 'selected' : '' }}>
                                        {{ $task->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Current Session Info -->
                        <div class="session-info">
                            <div class="alert alert-info">
                                <small>
                                    <i class="bi bi-info-circle"></i>
                                    <span id="sessionInfo">
                                        @if($session->isRunning())
                                            Timer running since {{ $session->started_at->format('h:i A') }}
                                        @else
                                            Ready to start a focus session
                                        @endif
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Complete Modal -->
    <div class="modal fade" id="sessionCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Session Complete! 🎉</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="display-6 mb-3" id="completeMessage"></div>
                    <p class="text-muted">Take a moment to stretch and hydrate</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timer-display {
            font-family: 'Courier New', monospace;
            font-variant-numeric: tabular-nums;
        }

        .progress {
            border-radius: 4px;
            overflow: hidden;
        }

        .btn {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn:disabled {
            opacity: 0.6;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/pomodoro.js') }}"></script>
@endpush