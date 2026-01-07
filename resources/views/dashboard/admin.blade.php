@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>
            <p class="text-muted mb-0">Production Overview</p>
        </div>
        <div class="text-end">
            <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <x-stat-card
            title="Active Batches"
            :value="$totalActiveBatches"
            icon="box-seam"
            color="border-primary"
            textColor="text-primary"
            iconColor="primary"
        />
        <x-stat-card
            title="Completed (This Month)"
            :value="$totalCompletedThisMonth"
            icon="check-circle"
            color="border-success"
            textColor="text-success"
            iconColor="success"
        />
        <x-stat-card
            title="Delayed Processes"
            :value="$totalDelayedProcesses"
            icon="exclamation-triangle"
            color="border-danger"
            textColor="text-danger"
            iconColor="danger"
        />
        <x-stat-card
            title="Active WIP Qty"
            :value="number_format($totalActiveWip)"
            icon="arrow-repeat"
            color="border-warning"
            textColor="text-warning"
            iconColor="warning"
            subtitle="pieces"
        />
    </div>

    <div class="row g-4">
        <!-- Batches by Division -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Batches by Division</h5>
                    <a href="{{ route('batches.timeline') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-calendar3"></i> View Timeline
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="">
                                <tr>
                                    <th>Division</th>
                                    <th class="text-center">Active</th>
                                    <th class="text-center">Completed</th>
                                    <th class="text-center">Delayed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batchesByDivision as $item)
                                    <tr>
                                        <td class="fw-semibold">{{ $item['division'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $item['active'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $item['completed'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($item['delayed'] > 0)
                                                <span class="badge bg-danger">{{ $item['delayed'] }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Division Overview -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Division Status</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($divisions as $division)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <div class="fw-semibold">{{ $division->name }}</div>
                                    <small class="text-muted">{{ $division->code }}</small>
                                </div>
                                <span class="badge bg-info">{{ $division->active_wip }} WIP</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Batches -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Batches</h5>
                    <a href="{{ route('batches.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentBatches as $batch)
                            <a href="{{ route('batches.show', $batch) }}" class="list-group-item list-group-item-action px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-primary">{{ $batch->batch_number }}</div>
                                        <small class="text-muted">{{ $batch->partInternal->part_number }} - {{ $batch->partInternal->part_name }}</small>
                                    </div>
                                    @php
                                        $badges = [
                                            'planned' => 'bg-secondary',
                                            'in_progress' => 'bg-primary',
                                            'completed' => 'bg-success',
                                        ];
                                    @endphp
                                    <span class="badge {{ $badges[$batch->status] ?? 'bg-secondary' }} ms-2">
                                        {{ ucfirst($batch->status) }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-3">No recent batches</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Delayed Schedules Alert -->
        <div class="col-lg-6">
            <div class="card h-100 border-danger">
                <div class="card-header text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Delayed Processes</h5>
                </div>
                <div class="card-body">
                    @forelse($delayedSchedules as $schedule)
                        <div class="alert alert-danger mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">{{ $schedule->batch->batch_number }}</div>
                                    <small>{{ $schedule->getProcessLabel() }}</small><br>
                                    <small class="text-muted">Plan: {{ $schedule->plan_end_date->format('d M Y') }}</small>
                                </div>
                                <span class="badge bg-danger">+{{ $schedule->getDelayDays() }} days</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-success py-4">
                            <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                            <p class="mb-0 mt-2">No delayed processes!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
