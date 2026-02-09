@extends('layouts.app')

@section('title', 'Batch Detail')

@push('styles')
    <style>
        .batch-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .operation-card {
            border-left: 4px solid #dee2e6;
            transition: all 0.3s;
        }

        .operation-card.waiting {
            border-left-color: #ffc107;
        }

        .operation-card.in_progress {
            border-left-color: #0d6efd;
        }

        .operation-card.completed {
            border-left-color: #198754;
            opacity: 0.7;
        }

        .operation-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .timeline-connector {
            width: 2px;
            height: 30px;
            background: #dee2e6;
            margin: 0 auto;
        }

        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto;
        }

        .step-indicator.waiting {
            background: #ffc107;
            color: white;
        }

        .step-indicator.in_progress {
            background: #0d6efd;
            color: white;
        }

        .step-indicator.completed {
            background: #198754;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Batch Detail</h3>
                    <p class="text-subtitle text-muted">Production tracking and monitoring</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('batches.index') }}">Batches</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Batch Header -->
            <div class="batch-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-3">
                            <i class="bi bi-box-seam"></i> {{ $batch->batch_number }}
                        </h2>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <small class="opacity-75">PO Number</small>
                                <h5>{{ $batch->poProduction->po_number }}</h5>
                            </div>
                            <div class="col-md-3 mb-2">
                                <small class="opacity-75">Part Number</small>
                                <h5>{{ $batch->partInternal->part_number }}</h5>
                            </div>
                            <div class="col-md-3 mb-2">
                                <small class="opacity-75">Drawing Number</small>
                                <h5>{{ $batch->drawing_number }}</h5>
                            </div>
                            <div class="col-md-3 mb-2">
                                <small class="opacity-75">Quantity</small>
                                <h5>{{ number_format($batch->quantity) }} pcs</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        @php
                            // Count based on total operations, not just WIP trackings
                            $totalOps = $batch->partInternal->partOperations->count();
                            $completedOps = $batch->wipTrackings->where('status', 'completed')->count();
                            $percentage = $totalOps > 0 ? round(($completedOps / $totalOps) * 100) : 0;
                        @endphp
                        <div style="font-size: 3rem; font-weight: bold;">{{ $percentage }}%</div>
                        <div>{{ $completedOps }}/{{ $totalOps }} Operations Completed</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle"></i> Batch Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Part Name</small>
                                    <strong>{{ $batch->partInternal->part_name }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Target Completion</small>
                                    @if ($batch->target_completed)
                                        <strong>{{ \Carbon\Carbon::parse($batch->target_completed)->format('d F Y') }}</strong>
                                        @php
                                            $daysLeft = \Carbon\Carbon::now()->diffInWeeks(
                                                \Carbon\Carbon::parse($batch->target_completed),
                                                false,
                                            );
                                        @endphp
                                        <br>
                                        @if ($daysLeft < 0)
                                            <span class="badge bg-danger mt-1">Overdue {{ abs($daysLeft) }} weeks</span>
                                        @elseif($daysLeft == 0)
                                            <span class="badge bg-warning mt-1">This week</span>
                                        @else
                                            <span class="badge bg-success mt-1">{{ round($daysLeft) }} weeks
                                                remaining</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </div>
                            </div>

                            @if ($batch->part_no_customer || $batch->drawing_number)
                                <hr>
                                <h6 class="mb-3">Customer Information</h6>
                                <div class="row">
                                    @if ($batch->part_no_customer)
                                        <div class="col-md-6 mb-3">
                                            <small class="text-muted d-block">Customer Part Number</small>
                                            <strong>{{ $batch->part_no_customer }}</strong>
                                            @if ($batch->part_no_customer_source)
                                                <br><a href="{{ $batch->part_no_customer_source }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary mt-1">
                                                    <i class="bi bi-link-45deg"></i> View Source
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($batch->drawing_number)
                                        <div class="col-md-6 mb-3">
                                            <small class="text-muted d-block">Drawing Number</small>
                                            <strong>{{ $batch->drawing_number }}</strong>
                                            @if ($batch->drawing_number_source)
                                                <br><a href="{{ $batch->drawing_number_source }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary mt-1">
                                                    <i class="bi bi-file-earmark"></i> View Drawing
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="production-operation-tab" data-bs-toggle="tab"
                                        href="#production-operation" role="tab" aria-controls="production-operation"
                                        aria-selected="true"><i class="bi bi-diagram-3"></i> Production Operations</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="production-schedule-tab" data-bs-toggle="tab"
                                        href="#production-schedule" role="tab" aria-controls="production-schedule"
                                        aria-selected="false"><i class="bi bi-calendar"></i> Production Schedule</a>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="production-operation" role="tabpanel"
                            aria-labelledby="production-operation-tab">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-diagram-3"></i> Production Operations
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        // Get all operations for this part (not just WIP trackings)
                                        $allOperations = $batch->partInternal
                                            ->partOperations()
                                            ->orderBy('route_order')
                                            ->get();
                                    @endphp

                                    @forelse($allOperations as $operation)
                                        @php
                                            // Find WIP tracking for this batch + operation
                                            $wip = $batch->wipTrackings
                                                ->where('part_operation_id', $operation->id)
                                                ->first();
                                        @endphp

                                        <div class="operation-card card mb-3 pending"
                                            id="operation-{{ $operation->id }}">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-1 text-center">
                                                        <div class="step-indicator {{ $wip ? $wip->status : 'pending' }}">
                                                            {{ $operation->route_order }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <h6 class="mb-1">
                                                            @if ($operation->division)
                                                                {{ $operation->division->name }}
                                                            @else
                                                                Operation {{ $operation->route_order }}
                                                            @endif
                                                        </h6>
                                                        @if ($wip)
                                                            <small class="text-muted">
                                                                @if ($wip->step == 'quality_check')
                                                                    <i class="bi bi-shield-check"></i> Quality Check

                                                                @else
                                                                    <i class="bi bi-gear"></i> Process
                                                                @endif

                                                            </small>
                                                        @else
                                                            <small class="text-muted">
                                                                <i class="bi bi-hourglass-split"></i> Awaiting previous
                                                                operation
                                                            </small>
                                                        @endif
                                                        <hr>
                                                        {{ $operation->operation_data }}
                                                    </div>
                                                    <div class="col-md-3">
                                                        @if ($wip)
                                                            @if ($wip->status == 'waiting')
                                                                <span class="badge bg-warning">
                                                                    <i class="bi bi-hourglass-split"></i> Waiting
                                                                </span>
                                                            @elseif($wip->status == 'in_progress')
                                                                <span class="badge bg-primary">
                                                                    <i class="bi bi-play-circle"></i> In Progress
                                                                </span>
                                                                @if ($wip->started_at)
                                                                    <br><small class="text-muted">Started:
                                                                        {{ \Carbon\Carbon::parse($wip->started_at)->format('d M') }}</small>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-check-circle"></i> Completed
                                                                </span>
                                                                @if ($wip->finished_at)
                                                                    <br><small class="text-muted">Finished:
                                                                        {{ \Carbon\Carbon::parse($wip->finished_at)->format('d M') }}</small>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="bi bi-lock"></i> Menunggu
                                                            </span>
                                                            <br><small class="text-muted">Not started yet</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 text-end">
                                                        @if ($wip)
                                                            @if ($wip->status == 'waiting')
                                                                <button class="btn btn-sm btn-primary"
                                                                    onclick="startOperation({{ $wip->id }})">
                                                                    <i class="bi bi-play"></i> Start
                                                                </button>
                                                            @elseif($wip->status == 'in_progress')
                                                                <button class="btn btn-sm btn-success"
                                                                    onclick="completeOperation({{ $wip->id }})">
                                                                    <i class="bi bi-check2"></i> Complete
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-sm btn-outline-secondary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#wipModal{{ $wip->id }}">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-secondary" disabled>
                                                                <i class="bi bi-lock"></i> Locked
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if ($wip && $wip->operation_notes)
                                                    <div class="mt-3">
                                                        <small class="text-muted">Notes:</small>
                                                        <p class="mb-0">{{ $wip->operation_notes }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if (!$loop->last)
                                            <div class="timeline-connector"></div>
                                        @endif

                                        <!-- WIP Detail Modal (only if WIP exists) -->
                                        @if ($wip)
                                            <div class="modal fade" id="wipModal{{ $wip->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Operation Detail - Route
                                                                #{{ $operation->route_order }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <th width="40%">Division</th>
                                                                    <td>{{ $operation->division ? $operation->division->name : '-' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Step</th>
                                                                    <td>
                                                                        @if ($wip->step == 'quality_check')
                                                                            <span class="badge bg-warning">Quality
                                                                                Check</span>
                                                                        @else
                                                                            <span class="badge bg-info">Process</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <td>
                                                                        @if ($wip->status == 'waiting')
                                                                            <span class="badge bg-warning">Waiting</span>
                                                                        @elseif($wip->status == 'in_progress')
                                                                            <span class="badge bg-primary">In
                                                                                Progress</span>
                                                                        @else
                                                                            <span class="badge bg-success">Completed</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>WIP Quantity</th>
                                                                    <td><strong>{{ number_format($wip->wip_qty) }}</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Started At</th>
                                                                    <td>{{ $wip->started_at ? \Carbon\Carbon::parse($wip->started_at)->format('d M Y') : '-' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Finished At</th>
                                                                    <td>{{ $wip->finished_at ? \Carbon\Carbon::parse($wip->finished_at)->format('d M Y') : '-' }}
                                                                    </td>
                                                                </tr>
                                                                @if ($wip->operation_notes)
                                                                    <tr>
                                                                        <th>Notes</th>
                                                                        <td>{{ $wip->operation_notes }}</td>
                                                                    </tr>
                                                                @endif
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="{{ route('wip-trackings.show', $wip->id) }}"
                                                                class="btn btn-primary">
                                                                View Full Details
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                            <p class="text-muted mt-3">No operations found for this part</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="production-schedule" role="tabpanel"
                            aria-labelledby="production-schedule-tab">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h2 class="text-xl font-semibold">Production Schedule</h2>
                                </div>
                                <div class="card-body">
                                    <table class="table" id="table1">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Process</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actual</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach($batch->productionSchedules as $schedule)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4">
                                                        <div class="font-medium text-gray-900">{{ $schedule->getProcessLabel() }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $schedule->duration_weeks }} week{{ $schedule->duration_weeks > 1 ? 's' : '' }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm">
                                                            <div class="font-medium">{{ $schedule->plan_start_date->format('d M Y') }}</div>
                                                            <div class="text-gray-500">to {{ $schedule->plan_end_date->format('d M Y') }}</div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @if($schedule->actual_start_date)
                                                            <div class="text-sm">
                                                                <div class="font-medium">{{ $schedule->actual_start_date->format('d M Y') }}</div>
                                                                @if($schedule->actual_end_date)
                                                                    <div class="text-gray-500">to {{ $schedule->actual_end_date->format('d M Y') }}</div>
                                                                @else
                                                                    <div class="text-blue-600">In Progress...</div>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400">Not started</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm">
                                                            <div>Plan: {{ number_format($schedule->plan_qty) }}</div>
                                                            @if($schedule->actual_qty)
                                                                <div class="text-gray-600">Actual: {{ number_format($schedule->actual_qty) }}</div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $scheduleStatusColors = [
                                                                'planned' => 'bg-gray-100 text-gray-800',
                                                                'in_progress' => 'bg-blue-100 text-blue-800',
                                                                'completed' => 'bg-green-100 text-green-800',
                                                                'delayed' => 'bg-red-100 text-red-800',
                                                            ];
                                                        @endphp
                                                        <span class="px-2 py-1 text-xs font-semibold rounded {{ $scheduleStatusColors[$schedule->status] ?? '' }}">
                                                            {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                                        </span>
                                                        @if($schedule->isDelayed())
                                                            <div class="text-xs text-red-600 mt-1">
                                                                +{{ $schedule->getDelayDays() }} days delay
                                                            </div>
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

                    <!-- Production Operations -->

                    <!-- Actions -->
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('batches.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                                <div>
                                    <a href="{{ route('batches.edit', $batch->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Edit Batch
                                    </a>
                                    <form action="{{ route('batches.destroy', $batch->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this batch and all WIP tracking records?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Progress Summary -->
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-graph-up"></i> Progress Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $totalOps = $batch->partInternal->partOperations->count();
                                $waiting = $totalOps - $batch->wipTrackings->count(); // Operations not started yet
                                $inProgress = $batch->wipTrackings->where('status', 'in_progress')->count();
                                $completed = $batch->wipTrackings->where('status', 'completed')->count();
                                $percentage = $totalOps > 0 ? round(($completed / $totalOps) * 100) : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Completed</span>
                                    <strong class="text-success">{{ $completed }}</strong>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>In Progress</span>
                                    <strong class="text-primary">{{ $inProgress }}</strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Pending/Not Started</span>
                                    <strong class="text-secondary">{{ $waiting }}</strong>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <h3 class="mb-0">{{ $percentage }}%</h3>
                                <small class="text-muted">Overall Progress</small>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-clock-history"></i> Timeline
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">Created</small>
                                <strong>{{ $batch->created_at->format('d M Y, H:i') }}</strong>
                                <br><small class="text-muted">{{ $batch->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Last Updated</small>
                                <strong>{{ $batch->updated_at->format('d M Y, H:i') }}</strong>
                                <br><small class="text-muted">{{ $batch->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning"></i> Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" onclick="printBatchReport()">
                                    <i class="bi bi-printer"></i> Print Report
                                </button>
                                <a href="{{ route('batches.export-single', $batch->id) }}"
                                    class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-excel"></i> Export Data
                                </a>
                                <button class="btn btn-outline-info" onclick="refreshStatus()">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function startOperation(wipId) {
            if (!confirm('Start this operation?')) return;

            fetch(`/wip-trackings/${wipId}/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }

        function completeOperation(wipId) {
            if (!confirm('Mark this operation as completed?')) return;

            fetch(`/wip-trackings/${wipId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message with info about next operation
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }

        function printBatchReport() {
            window.print();
        }

        function refreshStatus() {
            location.reload();
        }
    </script>
@endpush
