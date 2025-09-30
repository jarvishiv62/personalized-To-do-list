@extends('layouts.app')

@section('title', 'Dashboard - DailyDrive')

@section('content')
<div class="container">
    <!-- Motivational Quote Section -->
    @if($quote)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card quote-card shadow-lg border-0 bg-gradient-primary text-white">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-quote display-4 mb-3"></i>
                        <h4 class="quote-content mb-3">"{{ $quote->content }}"</h4>
                        @if($quote->author)
                            <p class="quote-author mb-0">— {{ $quote->author }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-5">
                <i class="bi bi-speedometer2 text-primary"></i> Dashboard
            </h1>
            <p class="text-muted">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <!-- Section Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs nav-tabs-custom mb-4" id="sectionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily" type="button" role="tab">
                        <i class="bi bi-sun"></i> Daily
                        <span class="badge bg-primary ms-2">{{ $dailyTasks->where('status', 'pending')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly" type="button" role="tab">
                        <i class="bi bi-calendar-week"></i> Weekly
                        <span class="badge bg-primary ms-2">{{ $weeklyTasks->where('status', 'pending')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">
                        <i class="bi bi-calendar-month"></i> Monthly
                        <span class="badge bg-primary ms-2">{{ $monthlyTasks->where('status', 'pending')->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="sectionTabContent">
                <!-- Daily Tab -->
                <div class="tab-pane fade show active" id="daily" role="tabpanel">
                    @include('partials.section-content', [
                        'section' => 'daily',
                        'tasks' => $dailyTasks,
                        'goals' => $dailyGoals
                    ])
                </div>

                <!-- Weekly Tab -->
                <div class="tab-pane fade" id="weekly" role="tabpanel">
                    @include('partials.section-content', [
                        'section' => 'weekly',
                        'tasks' => $weeklyTasks,
                        'goals' => $weeklyGoals
                    ])
                </div>

                <!-- Monthly Tab -->
                <div class="tab-pane fade" id="monthly" role="tabpanel">
                    @include('partials.section-content', [
                        'section' => 'monthly',
                        'tasks' => $monthlyTasks,
                        'goals' => $monthlyGoals
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const checkboxes = document.querySelectorAll('.task-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            
            fetch(`/tasks/${taskId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked;
                alert('Failed to update task status. Please try again.');
            });
        });
    });
});
</script>
@endpush