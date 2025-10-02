@extends('layouts.app')

@section('title', 'Progress & Analytics')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="display-5">
                    <i class="bi bi-graph-up text-primary"></i> Progress & Analytics
                </h1>
                <p class="text-muted">Track your productivity journey and celebrate achievements</p>
            </div>
        </div>

        <!-- Stats Summary Cards -->
        <div class="row mb-4">
            <!-- Points Card -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm stat-card bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2">Total Points</h6>
                                <h2 class="mb-0">{{ number_format($stats->total_points) }}</h2>
                            </div>
                            <i class="bi bi-gem display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Streak -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm stat-card bg-gradient-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2">Current Streak</h6>
                                <h2 class="mb-0">
                                    🔥 {{ $stats->current_streak }}
                                    <small>days</small>
                                </h2>
                            </div>
                            <i class="bi bi-fire display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Longest Streak -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm stat-card bg-gradient-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2">Longest Streak</h6>
                                <h2 class="mb-0">
                                    {{ $stats->longest_streak }}
                                    <small>days</small>
                                </h2>
                            </div>
                            <i class="bi bi-trophy-fill display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Completed -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm stat-card bg-gradient-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2">Tasks Completed</h6>
                                <h2 class="mb-0">
                                    {{ $completedTasks }}
                                    <small>/ {{ $totalTasks }}</small>
                                </h2>
                            </div>
                            <i class="bi bi-check-circle-fill display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-list-check text-primary display-4 mb-3"></i>
                        <h5>Completion Rate</h5>
                        <h2 class="text-primary">{{ $completionRate }}%</h2>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $completionRate }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-bullseye text-success display-4 mb-3"></i>
                        <h5>Goals Completed</h5>
                        <h2 class="text-success">{{ $completedGoals }} / {{ $totalGoals }}</h2>
                        <p class="text-muted mb-0">Keep crushing your goals!</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-text text-info display-4 mb-3"></i>
                        <h5>Diary Entries</h5>
                        <h2 class="text-info">{{ $diaryEntries }}</h2>
                        <p class="text-muted mb-0">Days documented</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Badges Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-award"></i> Achievements & Badges
                            </h5>
                            <span class="badge bg-primary">
                                {{ count($stats->badges ?? []) }} Earned
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(empty($stats->badges))
                            <div class="text-center py-4">
                                <i class="bi bi-trophy display-1 text-muted"></i>
                                <p class="text-muted mt-3">Complete tasks to earn your first badge!</p>
                            </div>
                        @else
                            <div class="row">
                                @foreach($stats->badges as $badgeId)
                                    @php
                                        $badge = \App\Models\UserStats::getBadgeInfo($badgeId);
                                    @endphp
                                    <div class="col-md-4 col-lg-3 mb-3">
                                        <div class="badge-card">
                                            <div class="text-center">
                                                <i class="bi {{ $badge['icon'] }} text-{{ $badge['color'] }} badge-icon"></i>
                                                <h6 class="mt-2 mb-1">{{ $badge['name'] }}</h6>
                                                <p class="small text-muted mb-0">{{ $badge['description'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <!-- Weekly Activity -->
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart"></i> Task Breakdown
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="sectionChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Completion Trend -->
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-activity"></i> 30-Day Completion Trend
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="trendChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Heatmap Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-heat"></i> Activity Heatmap (Last 90 Days)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="heatmap" class="heatmap-container"></div>
                        <div class="d-flex justify-content-center mt-3 gap-2">
                            <small class="text-muted">Less</small>
                            <div class="heatmap-legend">
                                <span class="legend-box level-0"></span>
                                <span class="legend-box level-1"></span>
                                <span class="legend-box level-2"></span>
                                <span class="legend-box level-3"></span>
                                <span class="legend-box level-4"></span>
                            </div>
                            <small class="text-muted">More</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Gradient Cards */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        }

        /* Stat Cards */
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
        }

        /* Badge Cards */
        .badge-card {
            padding: 1.5rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: white;
        }

        .badge-card:hover {
            border-color: #0d6efd;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .badge-icon {
            font-size: 3rem;
        }

        /* Heatmap Styles */
        .heatmap-container {
            display: grid;
            grid-template-columns: repeat(13, 1fr);
            gap: 3px;
            padding: 10px;
            max-width: 100%;
            overflow-x: auto;
        }

        .heatmap-cell {
            aspect-ratio: 1;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .heatmap-cell:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .heatmap-cell.level-0 {
            background-color: #ebedf0;
        }

        .heatmap-cell.level-1 {
            background-color: #c6e48b;
        }

        .heatmap-cell.level-2 {
            background-color: #7bc96f;
        }

        .heatmap-cell.level-3 {
            background-color: #239a3b;
        }

        .heatmap-cell.level-4 {
            background-color: #196127;
        }

        /* Heatmap Legend */
        .heatmap-legend {
            display: flex;
            gap: 3px;
        }

        .legend-box {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .legend-box.level-0 {
            background-color: #ebedf0;
        }

        .legend-box.level-1 {
            background-color: #c6e48b;
        }

        .legend-box.level-2 {
            background-color: #7bc96f;
        }

        .legend-box.level-3 {
            background-color: #239a3b;
        }

        .legend-box.level-4 {
            background-color: #196127;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card h2 {
                font-size: 1.5rem;
            }

            .badge-icon {
                font-size: 2rem;
            }

            .heatmap-container {
                grid-template-columns: repeat(7, 1fr);
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fetch progress data
            fetch('{{ route('progress.data') }}')
                .then(response => response.json())
                .then(data => {
                    console.log('Progress Data:', data);

                    // Render Weekly Chart
                    renderWeeklyChart(data.weekly_tasks);

                    // Render Monthly Chart
                    renderMonthlyChart(data.monthly_tasks);

                    // Render Section Breakdown
                    renderSectionChart(data.section_breakdown);

                    // Render Completion Trend
                    renderTrendChart(data.completion_trend);

                    // Render Heatmap
                    renderHeatmap(data.heatmap);
                })
                .catch(error => {
                    console.error('Error fetching progress data:', error);
                });
        });

        function renderWeeklyChart(data) {
            const ctx = document.getElementById('weeklyChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: 'Tasks Completed',
                        data: data.map(d => d.count),
                        backgroundColor: 'rgba(13, 110, 253, 0.8)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function renderMonthlyChart(data) {
            const ctx = document.getElementById('monthlyChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: 'Tasks Completed',
                        data: data.map(d => d.count),
                        borderColor: 'rgba(25, 135, 84, 1)',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function renderSectionChart(data) {
            const ctx = document.getElementById('sectionChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Daily', 'Weekly', 'Monthly'],
                    datasets: [{
                        data: [data.daily, data.weekly, data.monthly],
                        backgroundColor: [
                            'rgba(13, 110, 253, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(111, 66, 193, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function renderTrendChart(data) {
            const ctx = document.getElementById('trendChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [
                        {
                            label: 'Completed',
                            data: data.map(d => d.completed),
                            borderColor: 'rgba(25, 135, 84, 1)',
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Created',
                            data: data.map(d => d.created),
                            borderColor: 'rgba(13, 110, 253, 1)',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function renderHeatmap(data) {
            const container = document.getElementById('heatmap');
            container.innerHTML = '';

            // Get min and max values for scaling
            const values = Object.values(data);
            const maxValue = Math.max(...values);

            // Create cells
            Object.keys(data).forEach(dateStr => {
                const count = data[dateStr];
                const level = getHeatmapLevel(count, maxValue);

                const cell = document.createElement('div');
                cell.className = `heatmap-cell level-${level}`;
                cell.title = `${dateStr}: ${count} tasks`;
                cell.dataset.date = dateStr;
                cell.dataset.count = count;

                container.appendChild(cell);
            });
        }

        function getHeatmapLevel(count, maxValue) {
            if (count === 0) return 0;
            if (maxValue === 0) return 0;

            const percentage = (count / maxValue) * 100;

            if (percentage >= 75) return 4;
            if (percentage >= 50) return 3;
            if (percentage >= 25) return 2;
            if (percentage > 0) return 1;
            return 0;
        }
    </script>
 @endpush
<!-- <i class="bi bi-bar-chart"></i> Weekly Activity
</h5>
</div>
<div class="card-body">
    <canvas id="weeklyChart" height="250"></canvas>
</div>
</div>
</div> -->

<!-- Monthly Trend -->
<!-- <div class="col-md-6 mb-3">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-graph-up"></i> Monthly Trend
            </h5>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" height="250"></canvas>
        </div>
    </div>
</div>
</div> -->

<!-- Section Breakdown & Completion Trend -->
<!-- <div class="row mb-4"> -->
     <!-- Section Breakdown Pie Chart  -->
    <!-- <div class="col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">  --> 