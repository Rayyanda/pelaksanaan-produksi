@extends('layouts.app')

@section('title', 'Dashboard Foreman')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-person-badge"></i> Foreman Dashboard</h1>
                <p class="text-muted mb-0">{{ $division->name }} - Production Monitoring & Operator Management</p>
            </div>
            <div class="badge bg-warning text-dark" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ $division->code }}
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <x-stat-card title="Active Tasks" :value="$stats['total_active']" icon="list-check" color="border-primary"
                textColor="text-primary" />
            <x-stat-card title="Active Operators" :value="$stats['active_operators']" icon="people" color="border-success"
                textColor="text-success" />
            <x-stat-card title="Delayed Tasks" :value="$stats['delayed']" icon="exclamation-triangle" color="border-danger"
                textColor="text-danger" />
            <x-stat-card title="Completed Today" :value="$stats['completed_today_count']" icon="check-circle" color="border-info"
                textColor="text-info" />
            <x-stat-card title="WIP Quantity" :value="number_format($stats['wip_qty'])" icon="arrow-repeat" color="border-warning"
                textColor="text-warning" subtitle="pieces" />
        </div>

        <div class="row g-4">
            <!-- Operator Performance -->
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-people-fill"></i> Operator Performance</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($operatorPerformance as $performance)
                            <div
                                class="mb-3 p-3 border rounded {{ $performance['status'] === 'working' ? 'bg-success bg-opacity-10' : 'bg-light' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <div class="fw-bold">{{ $performance['operator']->name }}</div>
                                        <small class="text-muted">{{ $performance['operator']->email }}</small>
                                    </div>
                                    @if ($performance['status'] === 'working')
                                        <span class="badge bg-success">
                                            <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Working
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Idle</span>
                                    @endif
                                </div>
                                <div class="small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Active Tasks:</span>
                                        <span class="fw-bold text-primary">{{ $performance['active_tasks'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Today:</span>
                                        <span class="fw-bold text-success">{{ $performance['completed_today'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>This Week:</span>
                                        <span class="fw-bold text-info">{{ $performance['completed_this_week'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-person-x" style="font-size: 2.5rem;"></i>
                                <p class="mb-0 mt-2">No operators assigned</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Active Schedules -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
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
                                                <a href="{{ route('batches.show', $schedule->batch) }}"
                                                    class="fw-bold text-decoration-none">
                                                    {{ $schedule->batch->batch_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div class="fw-semibold">
                                                        {{ $schedule->batch->partInternal->part_number }}</div>
                                                    <div class="text-muted">{{ $schedule->batch->partInternal->part_name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    {{ $schedule->plan_start_date->format('d M') }} -
                                                    {{ $schedule->plan_end_date->format('d M Y') }}
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
            @if ($delayedSchedules->count() > 0)
                <div class="col-lg-4">
                    <div class="card shadow border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Delayed Tasks</h5>
                        </div>
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            @foreach ($delayedSchedules as $schedule)
                                <div class="alert alert-danger mb-2 p-2">
                                    <div class="fw-bold small">{{ $schedule->batch->batch_number }}</div>
                                    <div class="small text-muted">{{ $schedule->batch->partInternal->part_number }}</div>
                                    <div class="mt-1">
                                        <span class="badge bg-danger">+{{ $schedule->getDelayDays() }} days</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Active WIP Tracking with Operator Details -->
            <div class="{{ $delayedSchedules->count() > 0 ? 'col-lg-8' : 'col-12' }}">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Active WIP Tracking</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Part</th>
                                        <th>Batch</th>
                                        <th>Operator</th>
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
                                                @if ($wip->batch)
                                                    <a href="{{ route('batches.show', $wip->batch) }}"
                                                        class="text-decoration-none small">
                                                        {{ $wip->batch->batch_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($wip->operator)
                                                    <div class="small">
                                                        <i class="bi bi-person-circle"></i> {{ $wip->operator->name }}
                                                    </div>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td class="fw-bold">{{ number_format($wip->wip_qty) }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($wip->step) }}</span>
                                            </td>
                                            <td class="small">
                                                {{ $wip->started_at ? $wip->started_at->format('d M Y H:i') : '-' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($wip->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">No active WIP</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Today -->
            @if ($completedToday->count() > 0)
                <div class="col-12">
                    <div class="card shadow border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Completed Today</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Part</th>
                                            <th>Batch</th>
                                            <th>Operator</th>
                                            <th>Qty Completed</th>
                                            <th>Completed At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($completedToday as $wip)
                                            <tr>
                                                <td>
                                                    <div class="small">
                                                        <div class="fw-semibold">{{ $wip->partInternal->part_number }}
                                                        </div>
                                                        <div class="text-muted">{{ $wip->partInternal->part_name }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($wip->batch)
                                                        <a href="{{ route('batches.show', $wip->batch) }}"
                                                            class="text-decoration-none small">
                                                            {{ $wip->batch->batch_number }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($wip->operator)
                                                        <div class="small">
                                                            <i class="bi bi-person-check"></i> {{ $wip->operator->name }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-success">{{ number_format($wip->wip_qty) }}</td>
                                                <td class="small">{{ $wip->updated_at->format('H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
