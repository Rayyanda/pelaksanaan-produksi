@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-clipboard-check"></i> Supervisor Dashboard</h1>
            <p class="text-muted mb-0">{{ $division->name }} - Monitoring</p>
        </div>
        <div class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
            {{ $division->code }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <x-stat-card
            title="Active Tasks"
            :value="$stats['total_active']"
            icon="list-check"
            color="border-primary"
            textColor="text-primary"
        />
        <x-stat-card
            title="On Time (This Month)"
            :value="$stats['on_time']"
            icon="check-circle"
            color="border-success"
            textColor="text-success"
        />
        <x-stat-card
            title="Delayed"
            :value="$stats['delayed']"
            icon="exclamation-triangle"
            color="border-danger"
            textColor="text-danger"
        />
        <x-stat-card
            title="WIP Quantity"
            :value="number_format($stats['wip_qty'])"
            icon="arrow-repeat"
            color="border-info"
            textColor="text-info"
            subtitle="pieces"
        />
    </div>

    <div class="row g-4">
        <!-- Active Schedules -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Active Production Schedules</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Batch</th>
                                    <th>Part</th>
                                    <th>Plan Schedule</th>
                                    <th>Qty</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeSchedules as $schedule)
                                    <tr>
                                        <td>
                                            <a href="{{ route('batches.show', $schedule->batch) }}" class="fw-bold text-decoration-none">
                                                {{ $schedule->batch->batch_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div class="fw-semibold">{{ $schedule->batch->partInternal->part_number }}</div>
                                                <div class="text-muted">{{ $schedule->batch->partInternal->part_name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                {{ $schedule->plan_start_date->format('d M') }} - {{ $schedule->plan_end_date->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td>{{ number_format($schedule->plan_qty) }}</td>
                                        <td>
                                            @php
                                                $badges = [
                                                    'planned' => 'bg-secondary',
                                                    'in_progress' => 'bg-primary',
                                                ];
                                            @endphp
                                            <span class="badge {{ $badges[$schedule->status] ?? 'bg-secondary' }}">
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No active schedules</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delayed Alert -->
        <div class="col-lg-4">
            <div class="card border-danger h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Delayed Tasks</h5>
                </div>
                <div class="card-body">
                    @forelse($delayedSchedules as $schedule)
                        <div class="alert alert-danger mb-2 p-2">
                            <div class="fw-bold small">{{ $schedule->batch->batch_number }}</div>
                            <div class="small text-muted">{{ $schedule->batch->partInternal->part_number }}</div>
                            <div class="mt-1">
                                <span class="badge bg-danger">+{{ $schedule->getDelayDays() }} days</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-success py-4">
                            <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                            <p class="mb-0 mt-2 small">All on schedule!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Active WIP Tracking -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Active WIP Tracking</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Part</th>
                                    <th>Batch</th>
                                    <th>WIP Qty</th>
                                    <th>Step</th>
                                    <th>Started At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeWip as $wip)
                                    <tr>
                                        <td>
                                            <div class="small">
                                                <div class="fw-semibold">{{ $wip->partInternal->part_number }}</div>
                                                <div class="text-muted">{{ $wip->partInternal->part_name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($wip->batch)
                                                <a href="{{ route('batches.show', $wip->batch) }}" class="text-decoration-none small">
                                                    {{ $wip->batch->batch_number }}
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ number_format($wip->wip_qty) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($wip->step) }}</span>
                                        </td>
                                        <td class="small">{{ $wip->started_at ? $wip->started_at->format('d M Y') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($wip->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No active WIP</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
