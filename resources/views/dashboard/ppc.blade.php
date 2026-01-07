@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-calendar3"></i> PPC Dashboard</h1>
            <p class="text-muted mb-0">Production Planning & Control</p>
        </div>
        <a href="{{ route('batches.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create New Batch
        </a>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <x-stat-card
            title="Total Batches"
            :value="$stats['total_batches']"
            icon="box-seam"
            color="border-primary"
            textColor="text-primary"
        />
        <x-stat-card
            title="Planned"
            :value="$stats['planned']"
            icon="calendar-plus"
            color="border-secondary"
            textColor="text-secondary"
        />
        <x-stat-card
            title="In Progress"
            :value="$stats['in_progress']"
            icon="arrow-repeat"
            color="border-warning"
            textColor="text-warning"
        />
        <x-stat-card
            title="This Week Schedules"
            :value="$stats['this_week_schedules']"
            icon="calendar-week"
            color="border-info"
            textColor="text-info"
        />
    </div>

    <div class="row g-4">
        <!-- Upcoming Schedules -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Upcoming Schedules (Next 2 Weeks)</h5>
                    <a href="{{ route('batches.timeline') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-diagram-3"></i> Timeline View
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="">
                                <tr>
                                    <th>Batch</th>
                                    <th>Process</th>
                                    <th>Part</th>
                                    <th>Start Date</th>
                                    <th>Duration</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingSchedules->take(10) as $schedule)
                                    <tr>
                                        <td>
                                            <a href="{{ route('batches.show', $schedule->batch) }}" class="fw-bold text-decoration-none small">
                                                {{ $schedule->batch->batch_number }}
                                            </a>
                                        </td>
                                        <td><span class="badge bg-info small">{{ $schedule->getProcessLabel() }}</span></td>
                                        <td class="small">{{ $schedule->batch->partInternal->part_number }}</td>
                                        <td class="small">{{ $schedule->plan_start_date->format('d M Y') }}</td>
                                        <td class="small">{{ $schedule->duration_weeks }}w</td>
                                        <td class="small">{{ number_format($schedule->plan_qty) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No upcoming schedules</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Division Capacity -->
        <div class="col-lg-4">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-speedometer"></i> Division Capacity (This Week)</h5>
                </div>
                <div class="card-body">
                    @foreach($divisionCapacity as $item)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="fw-semibold">{{ $item['division'] }}</small>
                                <span class="badge bg-primary">{{ $item['active_batches'] }} active</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                @php
                                    $percentage = min(($item['this_week_schedules'] / 5) * 100, 100); // assume max 5 per week
                                @endphp
                                <div class="progress-bar {{ $percentage > 80 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success') }}"
                                     style="width: {{ $percentage }}%">
                                    {{ $item['this_week_schedules'] }} tasks
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Active Batches -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-boxes"></i> Active Batches</h5>
                    <a href="{{ route('batches.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($activeBatches->take(6) as $batch)
                            <div class="col-md-4">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <a href="{{ route('batches.show', $batch) }}" class="fw-bold text-decoration-none">
                                                {{ $batch->batch_number }}
                                            </a>
                                            @php
                                                $badges = [
                                                    'planned' => 'bg-secondary',
                                                    'in_progress' => 'bg-primary',
                                                ];
                                            @endphp
                                            <span class="badge {{ $badges[$batch->status] ?? 'bg-secondary' }}">
                                                {{ ucfirst($batch->status) }}
                                            </span>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            {{ $batch->partInternal->part_number }}<br>
                                            Qty: {{ number_format($batch->qty_target) }} pcs
                                        </div>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $batch->getProgressPercentage() }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $batch->getProgressPercentage() }}% complete</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
