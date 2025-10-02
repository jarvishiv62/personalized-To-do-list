@extends('layouts.app')

@section('title', 'Calendar View')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="display-5">
                        <i class="bi bi-calendar3 text-primary"></i> Calendar
                    </h1>
                    <div class="btn-group">
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> New Task
                        </a>
                        <a href="{{ route('diary.create') }}" class="btn btn-success">
                            <i class="bi bi-journal-plus"></i> New Diary Entry
                        </a>
                    </div>
                </div>
                <p class="text-muted">View all your tasks and diary entries in one place</p>
            </div>
        </div>

        <!-- Legend -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body py-2">
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge bg-primary">Daily Tasks</span>
                            <span class="badge" style="background-color: #ffc107;">Weekly Tasks</span>
                            <span class="badge" style="background-color: #6f42c1;">Monthly Tasks</span>
                            <span class="badge bg-success">Diary Entries</span>
                            <span class="badge bg-danger">Overdue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Detail Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <!-- Event details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="eventEditLink" class="btn btn-primary" style="display: none;">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            const eventModalBody = document.getElementById('eventModalBody');
            const eventEditLink = document.getElementById('eventEditLink');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: '{{ route('calendar.events') }}',
                eventClick: function (info) {
                    info.jsEvent.preventDefault();

                    const event = info.event;
                    const extendedProps = event.extendedProps;

                    let modalContent = '';

                    // Build modal content based on event type
                    if (extendedProps.type === 'task') {
                        modalContent = `
                        <div class="mb-3">
                            <span class="badge bg-primary">Task</span>
                            <span class="badge bg-${extendedProps.status === 'completed' ? 'success' : 'warning'}">
                                ${extendedProps.status}
                            </span>
                        </div>
                        <h4>${event.title}</h4>
                        ${extendedProps.description ? `<p class="text-muted">${extendedProps.description}</p>` : ''}
                        ${extendedProps.goal ? `<p><strong>Goal:</strong> ${extendedProps.goal}</p>` : ''}
                        <p><strong>Date:</strong> ${event.start ? event.start.toLocaleDateString() : 'N/A'}</p>
                        ${event.start && event.end ? `<p><strong>Time:</strong> ${event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })} - ${event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>` : ''}
                    `;

                        // Set edit link
                        const taskId = event.id.replace('task-', '');
                        eventEditLink.href = `/tasks/${taskId}/edit`;
                        eventEditLink.style.display = 'inline-block';

                    } else if (extendedProps.type === 'diary') {
                        modalContent = `
                        <div class="mb-3">
                            <span class="badge bg-success">Diary Entry</span>
                            ${extendedProps.wordCount ? `<span class="badge bg-info text-dark">${extendedProps.wordCount} words</span>` : ''}
                        </div>
                        <h4>${event.title}</h4>
                        ${extendedProps.content ? `<p class="text-muted">${extendedProps.content}</p>` : ''}
                        <p><strong>Date:</strong> ${event.start ? event.start.toLocaleDateString() : 'N/A'}</p>
                    `;

                        // Set edit link
                        const diaryId = event.id.replace('diary-', '');
                        eventEditLink.href = `/diary/${diaryId}`;
                        eventEditLink.style.display = 'inline-block';
                    }

                    eventModalBody.innerHTML = modalContent;
                    eventModal.show();
                },
                eventDidMount: function (info) {
                    // Add tooltip
                    info.el.title = info.event.title;
                },
                height: 'auto',
                aspectRatio: 1.8,
                nowIndicator: true,
                navLinks: true,
                businessHours: true,
                editable: false,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
            });

            calendar.render();
        });
    </script>
@endpush