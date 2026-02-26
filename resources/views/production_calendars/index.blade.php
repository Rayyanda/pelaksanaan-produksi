@extends('layouts.app')
@section('title', 'Production Calendars')
@section('page-title', 'Production Calendars')

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <style>
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }

        .fc-event {
            cursor: pointer;
        }

        .fc-event-holiday {
            background-color: #0dcaf0 !important;
            border-color: #0dcaf0 !important;
        }

        .fc-event-mass-leave {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #000 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <!-- Calendar Card -->
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Calendar View</h5>
                        <div class="d-flex gap-3">
                            <span><span class="badge bg-info">■</span> Holiday</span>
                            <span><span class="badge bg-warning">■</span> Mass Leave</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Production Calendar List</h5>
                        <a href="{{ route('production_calendars.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add New Calendar
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Activity</th>
                                        <th width="12%">Start Date</th>
                                        <th width="12%">End Date</th>
                                        <th width="15%">Day Type</th>
                                        <th width="18%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($calendars as $calendar)
                                        <tr>
                                            <td>{{ $loop->iteration + ($calendars->currentPage() - 1) * $calendars->perPage() }}
                                            </td>
                                            <td>{{ $calendar->activity }}</td>
                                            <td>{{ $calendar->start_date->format('d M Y') }}</td>
                                            <td>{{ $calendar->end_date->format('d M Y') }}</td>
                                            <td>
                                                @if ($calendar->day_type == 'holiday')
                                                    <span class="badge bg-info">Holiday</span>
                                                @else
                                                    <span class="badge bg-warning">Mass Leave</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('production_calendars.edit', $calendar->id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('production_calendars.destroy', $calendar->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this calendar entry?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                                                    <p class="mt-2">No production calendar entries found.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($calendars->hasPages())
                            <div class="mt-3">
                                {{ $calendars->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,listMonth'
                },
                events: @json($calendarEvents),
                eventClick: function(info) {
                    alert('Event: ' + info.event.title + '\nDate: ' + info.event.start
                        .toLocaleDateString());
                },
                eventClassNames: function(arg) {
                    if (arg.event.extendedProps.day_type === 'holiday') {
                        return ['fc-event-holiday'];
                    } else if (arg.event.extendedProps.day_type === 'mass leave') {
                        return ['fc-event-mass-leave'];
                    }
                },
                height: 'auto',
                dayMaxEvents: true
            });

            calendar.render();
        });
    </script>
@endpush
