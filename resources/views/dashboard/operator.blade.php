@extends('layouts.app')

@section('title','Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-person-badge"></i> Operator Dashboard</h1>
                <p class="text-muted mb-0">{{ $division->name }} - My Tasks</p>
            </div>
            <div class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ $division->code }}
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <x-stat-card title="Pending Tasks" :value="$stats['pending_tasks']" icon="hourglass-split" color="border-warning"
                textColor="text-warning" />
            <x-stat-card title="In Progress" :value="$stats['in_progress']" icon="arrow-repeat" color="border-primary"
                textColor="text-primary" />
            <x-stat-card title="Completed Today" :value="$stats['completed_today']" icon="check-circle" color="border-success"
                textColor="text-success" />
            <x-stat-card title="WIP Quantity" :value="number_format($stats['wip_qty'])" icon="boxes" color="border-info" textColor="text-info"
                subtitle="pieces" />
        </div>

        <div class="row g-4">
            <!-- My Work Queue -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header border border-bottom text-dark">
                        <h5 class="mb-0"><i class="bi bi-list-task"></i> My Work Queue</h5>
                    </div>
                    <div class="card-body">
                        @forelse($myWorkQueue as $schedule)
                            <div class="card mb-2 border-start border-warning border-3">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('batches.show', $schedule->batch) }}"
                                                    class="text-decoration-none">
                                                    {{ $schedule->batch->batch_number }}
                                                </a>
                                            </h6>
                                            <small
                                                class="text-muted">{{ $schedule->batch->partInternal->part_number }}</small>
                                        </div>
                                        <span class="badge bg-secondary">{{ number_format($schedule->plan_qty) }} pcs</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i> Start:
                                            {{ $schedule->plan_start_date->format('d M Y') }}
                                        </small>
                                        <form action="{{ route('production-schedules.start', $schedule) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="bi bi-play-circle"></i> Start
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2 mb-0">No pending tasks</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Currently Working -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Currently Working On</h5>
                    </div>
                    <div class="card-body py-3">
                        @forelse($currentWork as $schedule)
                            <div class="card mb-2 border-start border-primary border-3">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('batches.show', $schedule->batch) }}"
                                                    class="text-decoration-none">
                                                    {{ $schedule->batch->batch_number }}
                                                </a>
                                            </h6>
                                            <small
                                                class="text-muted">{{ $schedule->batch->partInternal->part_number }}</small>
                                        </div>
                                        <span class="badge bg-info">In Progress</span>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        Started: {{ $schedule->actual_start_date->format('d M Y') }}
                                    </div>
                                    <button class="btn btn-sm btn-success w-100" data-bs-toggle="modal"
                                        data-bs-target="#completeModal"
                                        onclick="setCompleteModal({{ $schedule->id }}, '{{ $schedule->batch->batch_number }}')">
                                        <i class="bi bi-check-circle"></i> Mark as Complete
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-pause-circle" style="font-size: 3rem;"></i>
                                <p class="mt-2 mb-0">No active work</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- Completed Today -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header ">
                        <h5 class="mb-0 text-success"><i class="bi bi-check-circle"></i> Completed Today</h5>
                    </div>
                    <div class="card-body py-3">
                        @forelse($completedToday as $schedule)
                            <div class="card mb-2 bg-light">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small">
                                            <div class="fw-semibold">{{ $schedule->batch->batch_number }}</div>
                                            <div class="text-muted">{{ $schedule->batch->partInternal->part_number }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="small">{{ number_format($schedule->actual_qty) }} pcs</div>
                                            <span class="badge bg-success">Done</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <p class="mb-0">No completed tasks today yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Active WIP -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0 text-success"><i class="bi bi-boxes"></i> Active WIP</h5>
                    </div>
                    <div class="card-body py-3">
                        @forelse($activeWip as $wip)
                            <div class="card mb-2">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="small flex-grow-1">
                                            <div class="fw-semibold">{{ $wip->partInternal->part_number }}</div>
                                            @if ($wip->batch)
                                                <div class="text-muted">{{ $wip->batch->batch_number }}</div>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-primary">{{ number_format($wip->wip_qty) }}</div>
                                            <span
                                                class="badge bg-{{ $wip->step == 'process' ? 'primary' : 'warning' }} small">
                                                {{ ucfirst($wip->step) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <p class="mb-0">No active WIP</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Complete Modal (reuse dari batches.show) -->
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Complete Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="completeForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Actual Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="actual_qty" class="form-control" required min="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Actual End Date</label>
                            <input type="date" name="actual_end_date" value="{{ now()->format('Y-m-d') }}"
                                class="form-control">
                            <small class="form-text text-muted">Leave empty to use current date</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes (Optional)</label>
                            <textarea name="notes" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Mark as Complete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function setCompleteModal(scheduleId, batchNumber) {
            document.getElementById('modalTitle').textContent = 'Complete: ' + batchNumber;
            document.getElementById('completeForm').action = `/production-schedules/${scheduleId}/complete`;
        }
    </script>
@endpush
