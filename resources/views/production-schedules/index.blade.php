@extends('layouts.app')

@section('title', 'Production Schedules')

@push('styles')
    <style>
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
        }

        .batch-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
        }

        .batch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-planned {
            background-color: #6c757d;
        }

        .status-in-progress {
            background-color: #ffc107;
        }

        .status-completed {
            background-color: #28a745;
        }

        .status-delayed {
            background-color: #dc3545;
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <div class="header-gradient rounded">
        <div class="container">
            <h1 class="mb-2">
                <i class="bi bi-calendar-check-fill"></i>
                Production Schedules
            </h1>
            <p class="mb-0">View and manage all production schedules</p>
        </div>
    </div>

    <div class="container py-5">

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <strong>Success:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">All Batches</h4>
            {{-- <div>
                <a href="{{ route('production-schedules.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle"></i>
                    Create New Schedule
                </a>
            </div> --}}
        </div>

        <!-- Batches Grid -->
        @if ($batches->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                No batches with schedules found. Please create a schedule first.
            </div>
        @else
            <div class="row g-4">
                @foreach ($batches as $batch)
                    <div class="col-md-6 col-lg-4">
                        <div class="card batch-card shadow-sm h-100">
                            <div class="card-body">

                                <!-- Batch Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">
                                            {{ $batch->batch_number ?? 'Batch #' . $batch->id }}
                                        </h5>
                                        <small class="text-muted">
                                            @if ($batch->partInternal)
                                                {{ $batch->partInternal->name }}
                                            @else
                                                No Part Info
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge bg-primary">
                                        {{ $batch->quantity ?? $batch->plan_qty }} units
                                    </span>
                                </div>

                                <!-- Schedule Info -->
                                @if ($batch->productionSchedules->isNotEmpty())
                                    <div class="mb-3">
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Start Date</small>
                                                <strong style="font-size: 0.9rem;">
                                                    {{ \Carbon\Carbon::parse($batch->productionSchedules->first()->plan_start_date)->format('d M Y') }}
                                                </strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">End Date</small>
                                                <strong style="font-size: 0.9rem;">
                                                    {{ \Carbon\Carbon::parse($batch->productionSchedules->last()->plan_end_date)->format('d M Y') }}
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Processes</small>
                                                <strong style="font-size: 0.9rem;">
                                                    {{ $batch->productionSchedules->count() }} processes
                                                </strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Duration</small>
                                                <strong style="font-size: 0.9rem;">
                                                    {{ $batch->productionSchedules->sum('duration_weeks') }} week(s)
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Summary -->
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-2">Status Summary</small>
                                        <div class="d-flex flex-wrap gap-2">
                                            @php
                                                $statusCounts = $batch->productionSchedules
                                                    ->groupBy('status')
                                                    ->map->count();
                                            @endphp

                                            @foreach (['planned' => 'secondary', 'in_progress' => 'warning', 'completed' => 'success', 'delayed' => 'danger'] as $status => $color)
                                                @if ($statusCounts->get($status, 0) > 0)
                                                    <span class="badge bg-{{ $color }}">
                                                        <span class="status-dot status-{{ $status }}"></span>
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}:
                                                        {{ $statusCounts->get($status) }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('production-schedules.detail', $batch->id) }}"
                                            class="btn btn-primary">
                                            <i class="bi bi-eye"></i>
                                            View Schedule Detail
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        <small>No schedules generated yet</small>
                                    </div>
                                @endif

                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i>
                                        Target:
                                        {{ $batch->target_completed ? \Carbon\Carbon::parse($batch->target_completed)->format('d M Y') : '-' }}
                                    </small>
                                    {{-- <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($batches->hasPages())
                <div class="mt-4">
                    {{ $batches->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif

    </div>
@endsection
