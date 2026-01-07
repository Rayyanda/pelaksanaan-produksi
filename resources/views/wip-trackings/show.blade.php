@extends('layouts.app')

@section('title', 'WIP Tracking Detail')

@push('styles')
<style>
    .wip-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }
    .info-card {
        border-left: 4px solid #435ebe;
    }
    .timeline-item {
        padding-left: 2rem;
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #435ebe;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #435ebe;
    }
    .timeline-item:after {
        content: '';
        position: absolute;
        left: 5px;
        top: 12px;
        width: 2px;
        height: calc(100% - 12px);
        background: #dee2e6;
    }
    .timeline-item:last-child:after {
        display: none;
    }
    .route-indicator {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>WIP Tracking Detail</h3>
                <p class="text-subtitle text-muted">Detailed work in progress information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('wip-trackings.index') }}">WIP Trackings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- WIP Header -->
        <div class="wip-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-3">
                        <i class="bi bi-clipboard-check"></i>
                        @if($wipTracking->batch)
                            {{ $wipTracking->batch->batch_number }}
                        @else
                            WIP Tracking #{{ $wipTracking->id }}
                        @endif
                    </h2>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <small class="opacity-75">Part Number</small>
                            <h5>{{ $wipTracking->partInternal->part_number }}</h5>
                        </div>
                        <div class="col-md-4 mb-2">
                            <small class="opacity-75">Route Order</small>
                            <h5>Route #{{ $wipTracking->partOperation->route_order }}</h5>
                        </div>
                        <div class="col-md-4 mb-2">
                            <small class="opacity-75">WIP Quantity</small>
                            <h5>{{ number_format($wipTracking->wip_qty) }} pcs</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="route-indicator bg-light text-dark">
                        {{ $wipTracking->partOperation->route_order }}
                    </div>
                    <div class="mt-2">
                        @if($wipTracking->status == 'waiting')
                            <span class="badge bg-warning" style="font-size: 1rem; padding: 0.5rem 1rem;">Waiting</span>
                        @elseif($wipTracking->status == 'in_progress')
                            <span class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">In Progress</span>
                        @else
                            <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">Completed</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card info-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle"></i> WIP Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Part Name</small>
                                <strong>{{ $wipTracking->partInternal->part_name }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Division</small>
                                @if($wipTracking->partOperation->division)
                                    <span class="badge bg-info">{{ $wipTracking->partOperation->division->name }}</span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Step</small>
                                @if($wipTracking->step == 'quality_check')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-shield-check"></i> Quality Check
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="bi bi-gear"></i> Process
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Status</small>
                                @if($wipTracking->status == 'waiting')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-hourglass-split"></i> Waiting
                                    </span>
                                @elseif($wipTracking->status == 'in_progress')
                                    <span class="badge bg-primary">
                                        <i class="bi bi-play-circle"></i> In Progress
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Completed
                                    </span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Started At</small>
                                @if($wipTracking->started_at)
                                    <strong>{{ $wipTracking->started_at->format('d F Y, H:i') }}</strong>
                                    <br><small class="text-muted">{{ $wipTracking->started_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">Not started yet</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Finished At</small>
                                @if($wipTracking->finished_at)
                                    <strong>{{ $wipTracking->finished_at->format('d F Y, H:i') }}</strong>
                                    <br><small class="text-muted">{{ $wipTracking->finished_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">Not finished yet</span>
                                @endif
                            </div>
                        </div>

                        @if($wipTracking->started_at && $wipTracking->finished_at)
                        <div class="alert alert-light">
                            <strong>Duration:</strong>
                            {{ $wipTracking->started_at->diffInHours($wipTracking->finished_at) }} hours
                            ({{ $wipTracking->started_at->diffInDays($wipTracking->finished_at) }} days)
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Batch Information -->
                @if($wipTracking->batch)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box-seam"></i> Batch Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Batch Number</small>
                                <strong>{{ $wipTracking->batch->batch_number }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">PO Number</small>
                                <strong>{{ $wipTracking->batch->poProduction->po_number }}</strong>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Batch Quantity</small>
                                <strong>{{ number_format($wipTracking->batch->quantity) }} pcs</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Target Completion</small>
                                @if($wipTracking->batch->target_completed)
                                    <strong>{{ \Carbon\Carbon::parse($wipTracking->batch->target_completed)->format('d M Y') }}</strong>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('batches.show', $wipTracking->batch->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-box-arrow-up-right"></i> View Batch Detail
                        </a>
                    </div>
                </div>
                @endif

                <!-- Operation Notes -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-chat-left-text"></i> Operation Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($wipTracking->operation_notes)
                            <div class="alert alert-light">
                                <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $wipTracking->operation_notes }}</pre>
                            </div>
                        @else
                            <p class="text-muted text-center py-3 mb-0">
                                <i class="bi bi-chat"></i> No notes available
                            </p>
                        @endif

                        @if($wipTracking->status != 'completed')
                        <button class="btn btn-outline-secondary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#notesModal">
                            <i class="bi bi-pencil"></i> {{ $wipTracking->operation_notes ? 'Edit Notes' : 'Add Notes' }}
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('wip-trackings.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <div>
                                @if($wipTracking->status == 'waiting')
                                    <button class="btn btn-primary" onclick="startWip({{ $wipTracking->id }})">
                                        <i class="bi bi-play"></i> Start Operation
                                    </button>
                                @elseif($wipTracking->status == 'in_progress')
                                    @if($wipTracking->step == 'quality_check')
                                        <button class="btn btn-info" onclick="moveToProcess({{ $wipTracking->id }})">
                                            <i class="bi bi-arrow-right"></i> Move to Process
                                        </button>
                                    @else
                                        <button class="btn btn-success" onclick="completeWip({{ $wipTracking->id }})">
                                            <i class="bi bi-check2"></i> Complete
                                        </button>
                                    @endif
                                    <button class="btn btn-warning" onclick="resetWip({{ $wipTracking->id }})">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-clock-history"></i> Timeline
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline-item">
                            <small class="text-muted">Created</small>
                            <div><strong>{{ $wipTracking->created_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $wipTracking->created_at->diffForHumans() }}</small>
                        </div>

                        @if($wipTracking->started_at)
                        <div class="timeline-item">
                            <small class="text-muted">Started</small>
                            <div><strong>{{ $wipTracking->started_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $wipTracking->started_at->diffForHumans() }}</small>
                        </div>
                        @endif

                        @if($wipTracking->finished_at)
                        <div class="timeline-item">
                            <small class="text-muted">Finished</small>
                            <div><strong>{{ $wipTracking->finished_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $wipTracking->finished_at->diffForHumans() }}</small>
                        </div>
                        @endif

                        <div class="timeline-item">
                            <small class="text-muted">Last Updated</small>
                            <div><strong>{{ $wipTracking->updated_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $wipTracking->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-speedometer2"></i> Quick Info
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">WIP ID</small>
                            <code>#{{ $wipTracking->id }}</code>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Current Step</small>
                            @if($wipTracking->step == 'quality_check')
                                <span class="badge bg-warning">Quality Check</span>
                            @else
                                <span class="badge bg-info">Process</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Current Status</small>
                            @if($wipTracking->status == 'waiting')
                                <span class="badge bg-warning">Waiting</span>
                            @elseif($wipTracking->status == 'in_progress')
                                <span class="badge bg-primary">In Progress</span>
                            @else
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </div>

                        @if($wipTracking->status == 'in_progress' && $wipTracking->started_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Time Elapsed</small>
                            <strong class="text-primary">{{ $wipTracking->started_at->diffForHumans(null, true) }}</strong>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Operation Data -->
                @if($wipTracking->partOperation->operation_data)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-file-text"></i> Operation Data
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert-light mb-0">
                            <pre class="mb-0" style="max-height: 200px; overflow-y: auto; white-space: pre-wrap; font-size: 0.875rem;">{{ $wipTracking->partOperation->operation_data }}</pre>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Operation Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="operationNotes" rows="6" placeholder="Enter operation notes...">{{ $wipTracking->operation_notes }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveNotes()">Save Notes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function startWip(wipId) {
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

    function moveToProcess(wipId) {
        if (!confirm('Move to process step?')) return;

        fetch(`/wip-trackings/${wipId}/change-step`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ step: 'process' })
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

    function completeWip(wipId) {
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

    function resetWip(wipId) {
        if (!confirm('Reset this operation to waiting status? This will clear started/finished dates.')) return;

        fetch(`/wip-trackings/${wipId}/reset`, {
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

    function saveNotes() {
        const notes = document.getElementById('operationNotes').value;

        fetch(`/wip-trackings/{{ $wipTracking->id }}/update-notes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ operation_notes: notes })
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
</script>
@endpush
