@extends('layouts.app')

@section('title', 'Part Operation Detail')

@push('styles')
<style>
    .operation-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }
    .route-number {
        font-size: 4rem;
        font-weight: bold;
        opacity: 0.3;
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
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
    .operation-data-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1.5rem;
        white-space: pre-wrap;
        font-family: 'Courier New', monospace;
        max-height: 500px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Part Operation Detail</h3>
                <p class="text-subtitle text-muted">Detailed information of operation routing</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('part-operations.index') }}">Part Operations</a></li>
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

        <!-- Operation Header -->
        <div class="operation-header position-relative">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-3">
                        <span class="badge bg-light text-dark me-2" style="font-size: 2rem;">
                            #{{ $partOperation->route_order }}
                        </span>
                        Operation Details
                    </h2>
                    <h4 class="mb-2">{{ $partOperation->partInternal->part_number }}</h4>
                    <p class="mb-0 opacity-75">{{ $partOperation->partInternal->part_name }}</p>
                </div>
                <div class="col-md-4 text-end">
                    @if($partOperation->division)
                        <span class="badge bg-light text-dark" style="font-size: 1.2rem;">
                            <i class="bi bi-building"></i> {{ $partOperation->division->name }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="route-number">{{ $partOperation->route_order }}</div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card info-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle"></i> Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <small class="text-muted d-block">Route Order</small>
                                <strong style="font-size: 1.5rem;" class="text-primary">{{ $partOperation->route_order }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Part Number</small>
                                <strong>{{ $partOperation->partInternal->part_number }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Division</small>
                                @if($partOperation->division)
                                    <span class="badge bg-info">{{ $partOperation->division->name }}</span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <small class="text-muted d-block">Part Name</small>
                            <strong>{{ $partOperation->partInternal->part_name }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Operation Data -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-text"></i> Operation Data
                        </h5>
                        @if($partOperation->operation_data)
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyOperationData()">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($partOperation->operation_data)
                            <div class="operation-data-box" id="operationDataBox">{{ $partOperation->operation_data }}</div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-3">No operation data available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Part Operations (Same Part) -->
                @php
                    $relatedOps = \App\Models\PartOperation::where('part_internal_id', $partOperation->part_internal_id)
                        ->where('id', '!=', $partOperation->id)
                        ->orderBy('route_order')
                        ->get();
                @endphp

                @if($relatedOps->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-diagram-3"></i> Complete Routing for This Part
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center overflow-auto pb-2">
                            @php
                                $allOps = collect([$partOperation])->merge($relatedOps)->sortBy('route_order');
                            @endphp
                            @foreach($allOps as $op)
                                <div class="text-center {{ $op->id == $partOperation->id ? 'border border-primary rounded p-2' : '' }}" style="min-width: 150px;">
                                    <div class="badge {{ $op->id == $partOperation->id ? 'bg-primary' : 'bg-secondary' }}" style="font-size: 1.2rem; padding: 0.5rem 0.8rem;">
                                        {{ $op->route_order }}
                                    </div>
                                    <div class="mt-2">
                                        @if($op->division)
                                            <small class="d-block"><strong>{{ $op->division->name }}</strong></small>
                                        @else
                                            <small class="text-muted">No Division</small>
                                        @endif
                                        @if($op->id == $partOperation->id)
                                            <span class="badge bg-primary mt-1">Current</span>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="mx-3">
                                        <i class="bi bi-arrow-right text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('part-operations.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <div>
                                <a href="{{ route('part-operations.edit', $partOperation->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('part-operations.destroy', $partOperation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure want to delete this operation?')">
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
                            <div><strong>{{ $partOperation->created_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $partOperation->created_at->diffForHumans() }}</small>
                        </div>

                        <div class="timeline-item">
                            <small class="text-muted">Last Updated</small>
                            <div><strong>{{ $partOperation->updated_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $partOperation->updated_at->diffForHumans() }}</small>
                        </div>

                        @if($partOperation->deleted_at)
                        <div class="timeline-item">
                            <small class="text-muted text-danger">Deleted</small>
                            <div><strong class="text-danger">{{ $partOperation->deleted_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $partOperation->deleted_at->diffForHumans() }}</small>
                        </div>
                        @endif
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
                            <small class="text-muted d-block">Status</small>
                            @if($partOperation->deleted_at)
                                <span class="badge bg-danger">Deleted</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Has Operation Data</small>
                            @if($partOperation->operation_data)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Yes
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle"></i> No
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Division Assigned</small>
                            @if($partOperation->division)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Yes
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle"></i> No
                                </span>
                            @endif
                        </div>

                        @php
                            $totalOps = \App\Models\PartOperation::where('part_internal_id', $partOperation->part_internal_id)->count();
                        @endphp
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Operations for This Part</small>
                            <strong class="text-primary" style="font-size: 1.5rem;">{{ $totalOps }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-arrows-move"></i> Navigate
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $prevOp = \App\Models\PartOperation::where('part_internal_id', $partOperation->part_internal_id)
                                ->where('route_order', '<', $partOperation->route_order)
                                ->orderBy('route_order', 'desc')
                                ->first();

                            $nextOp = \App\Models\PartOperation::where('part_internal_id', $partOperation->part_internal_id)
                                ->where('route_order', '>', $partOperation->route_order)
                                ->orderBy('route_order', 'asc')
                                ->first();
                        @endphp

                        <div class="d-grid gap-2">
                            @if($prevOp)
                                <a href="{{ route('part-operations.show', $prevOp->id) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Previous (#{{ $prevOp->route_order }})
                                </a>
                            @else
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="bi bi-arrow-left"></i> No Previous
                                </button>
                            @endif

                            @if($nextOp)
                                <a href="{{ route('part-operations.show', $nextOp->id) }}" class="btn btn-outline-primary">
                                    Next (#{{ $nextOp->route_order }}) <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <button class="btn btn-outline-secondary" disabled>
                                    No Next <i class="bi bi-arrow-right"></i>
                                </button>
                            @endif
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
    function copyOperationData() {
        const dataBox = document.getElementById('operationDataBox');
        const text = dataBox.textContent;

        navigator.clipboard.writeText(text).then(() => {
            // Show success notification
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
            alert.style.zIndex = '9999';
            alert.innerHTML = '<i class="bi bi-check-circle"></i> Operation data copied to clipboard!<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            document.body.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 3000);
        }).catch(err => {
            alert('Failed to copy: ' + err);
        });
    }
</script>
@endpush
