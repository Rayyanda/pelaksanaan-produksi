@extends('layouts.app')

@section('title', 'Production Schedule')

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .schedule-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .process-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .process-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .process-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px 10px 0 0;
        }

        .day-box {
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            margin-bottom: 10px;
            transition: all 0.2s ease;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .day-box:hover {
            transform: scale(1.05);
        }

        .working-day {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .weekend-day {
            background-color: #e9ecef;
            color: #6c757d;
        }

        .holiday-day {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .badge-process {
            font-size: 1.2rem;
            padding: 8px 15px;
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 5px 12px;
        }

        .info-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <!-- Header -->
        <div class="schedule-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2">
                        <i class="bi bi-calendar-check"></i>
                        Production Schedule
                    </h1>
                    <h3 class="mb-0">{{ $batch->batch_number ?? 'Batch #' . $batch->id }}</h3>
                </div>
                <div class="text-end">
                    <div class="badge bg-light text-dark badge-process">
                        {{ $batch->quantity ?? $batch->plan_qty }} Units
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-3">
                    <small class="d-block opacity-75">Part Name</small>
                    <strong>{{ $batch->partInternal->name ?? '-' }}</strong>
                </div>
                <div class="col-md-3">
                    <small class="d-block opacity-75">Start Date</small>
                    <strong>{{ $schedules->first()->plan_start_date ?? '-' }}</strong>
                </div>
                <div class="col-md-3">
                    <small class="d-block opacity-75">End Date</small>
                    <strong>{{ $schedules->last()->plan_end_date ?? '-' }}</strong>
                </div>
                <div class="col-md-3">
                    <small class="d-block opacity-75">Total Processes</small>
                    <strong>{{ $schedules->count() }}</strong>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history text-primary" style="font-size: 2rem;"></i>
                        <h5 class="mt-2 mb-0">{{ $totalWorkingDays }}</h5>
                        <small class="text-muted">Total Working Days</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-week text-success" style="font-size: 2rem;"></i>
                        <h5 class="mt-2 mb-0">{{ $totalDurationWeeks }}</h5>
                        <small class="text-muted">Duration (Weeks)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-list-check text-info" style="font-size: 2rem;"></i>
                        <h5 class="mt-2 mb-0">{{ $schedules->count() }}</h5>
                        <small class="text-muted">Processes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Processes -->
        @foreach ($schedules as $index => $schedule)
            <div class="process-card">

                <!-- Process Header -->
                <div class="process-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-white text-primary me-3" style="font-size: 1.2rem;">
                                #{{ $index + 1 }}
                            </span>
                            <div>
                                <h4 class="mb-0">{{ $schedule->process_name }}</h4>
                                <small class="opacity-75">
                                    {{ \Carbon\Carbon::parse($schedule->plan_start_date)->format('d M Y') }}
                                    →
                                    {{ \Carbon\Carbon::parse($schedule->plan_end_date)->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        <div>
                            @if ($schedule->status === 'completed')
                                <span class="badge bg-success status-badge">
                                    <i class="bi bi-check-circle"></i> Completed
                                </span>
                            @elseif($schedule->status === 'in_progress')
                                <span class="badge bg-warning status-badge">
                                    <i class="bi bi-clock"></i> In Progress
                                </span>
                            @elseif($schedule->status === 'delayed')
                                <span class="badge bg-danger status-badge">
                                    <i class="bi bi-exclamation-triangle"></i> Delayed
                                </span>
                            @else
                                <span class="badge bg-secondary status-badge">
                                    <i class="bi bi-calendar"></i> Planned
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Process Info -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="info-card">
                                <small class="text-muted d-block">Duration</small>
                                <strong>{{ $schedule->duration_weeks }} Week(s)</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <small class="text-muted d-block">Plan Qty</small>
                                <strong>{{ $schedule->plan_qty }} units</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <small class="text-muted d-block">Actual Qty</small>
                                <strong>{{ $schedule->actual_qty ?? '-' }} units</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card">
                                <small class="text-muted d-block">Working Days</small>
                                <strong>{{ $schedule->workingDays }} days</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Calendar -->
                    <div class="mb-3">
                        <h6 class="mb-3">
                            <i class="bi bi-calendar3"></i>
                            Daily Schedule Breakdown
                        </h6>

                        <div class="row g-2">
                            @foreach ($schedule->dailyBreakdown as $day)
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div
                                        class="day-box 
                                @if ($day['is_working_day']) working-day
                                @elseif($day['is_holiday'])
                                    holiday-day
                                @else
                                    weekend-day @endif
                            ">
                                        <div class="fw-bold" style="font-size: 0.85rem;">
                                            {{ $day['day_name_short'] }}
                                        </div>
                                        <div class="fw-bold" style="font-size: 1.5rem;">
                                            {{ \Carbon\Carbon::parse($day['date'])->format('d') }}
                                        </div>
                                        <div style="font-size: 0.75rem;">
                                            {{ \Carbon\Carbon::parse($day['date'])->format('M') }}
                                        </div>

                                        @if ($day['is_working_day'])
                                            <div class="mt-2">
                                                <span class="badge bg-white bg-opacity-25" style="font-size: 0.7rem;">
                                                    Day {{ $day['working_day_number'] }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="mt-2" style="font-size: 1rem;">
                                                @if ($day['is_holiday'])
                                                    🎉
                                                @else
                                                    🏖️
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Detail List (Collapsible) -->
                    <div class="accordion" id="accordion{{ $schedule->id }}">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $schedule->id }}">
                                    <i class="bi bi-list-ul me-2"></i>
                                    Show Detailed Status List
                                </button>
                            </h2>
                            <div id="collapse{{ $schedule->id }}" class="accordion-collapse collapse"
                                data-bs-parent="#accordion{{ $schedule->id }}">
                                <div class="accordion-body">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Day</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($schedule->dailyBreakdown as $day)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $day['date'] }}</strong>
                                                    </td>
                                                    <td>{{ $day['day_name'] }}</td>
                                                    <td>
                                                        @if ($day['is_working_day'])
                                                            <span class="badge bg-success">
                                                                ✓ Working [Day {{ $day['working_day_number'] }}]
                                                            </span>
                                                        @elseif($day['is_holiday'])
                                                            <span class="badge bg-danger">
                                                                ✗ SKIP - Holiday: {{ $day['holiday_name'] }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                ✗ SKIP - Weekend
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if ($schedule->notes)
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-info-circle"></i>
                            <strong>Notes:</strong> {{ $schedule->notes }}
                        </div>
                    @endif

                </div>
            </div>
        @endforeach

        <!-- Legend -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="bi bi-palette"></i>
                    Color Legend
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="working-day" style="width: 30px; height: 30px; border-radius: 5px;"></div>
                            <span class="ms-2">Working Day</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="weekend-day" style="width: 30px; height: 30px; border-radius: 5px;"></div>
                            <span class="ms-2">Weekend (Skipped)</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="holiday-day" style="width: 30px; height: 30px; border-radius: 5px;"></div>
                            <span class="ms-2">Holiday (Skipped)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('production-schedules.index') }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-arrow-left"></i>
                Back to Schedule List
            </a>
        </div>

    </div>
@endsection
