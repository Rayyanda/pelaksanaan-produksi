@extends('layouts.app')

@section('title', 'WIP Trackings')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
<style>
    .status-badge {
        min-width: 90px;
        display: inline-block;
    }
    .step-badge {
        min-width: 110px;
        display: inline-block;
    }
    .wip-qty {
        font-size: 1.3rem;
        font-weight: bold;
    }
    .progress-timeline {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .timeline-step {
        flex: 1;
        text-align: center;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    .timeline-step.active {
        background-color: #435ebe;
        color: white;
    }
    .timeline-step.completed {
        background-color: #198754;
        color: white;
    }
    .timeline-step.waiting {
        background-color: #e9ecef;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>WIP Trackings</h3>
                <p class="text-subtitle text-muted">Work In Progress tracking and monitoring</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">WIP Trackings</li>
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

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">In Progress</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['in_progress'] ?? 0 }}</h3>
                            </div>
                            <div class="avatar avatar-xl bg-primary">
                                <i class="bi bi-play-circle" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Waiting</h6>
                                <h3 class="mb-0 text-warning">{{ $stats['waiting'] ?? 0 }}</h3>
                            </div>
                            <div class="avatar avatar-xl bg-warning">
                                <i class="bi bi-hourglass-split" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Completed</h6>
                                <h3 class="mb-0 text-success">{{ $stats['completed'] ?? 0 }}</h3>
                            </div>
                            <div class="avatar avatar-xl bg-success">
                                <i class="bi bi-check-circle" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total WIP Qty</h6>
                                <h3 class="mb-0 text-info">{{ number_format($stats['total_wip_qty'] ?? 0) }}</h3>
                            </div>
                            <div class="avatar avatar-xl bg-info">
                                <i class="bi bi-boxes" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('wip-trackings.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Part Internal</label>
                        <select name="part_internal_id" class="form-select">
                            <option value="">All Parts</option>
                            @foreach($partInternals as $part)
                                <option value="{{ $part->id }}" {{ request('part_internal_id') == $part->id ? 'selected' : '' }}>
                                    {{ $part->part_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Step</label>
                        <select name="step" class="form-select">
                            <option value="">All Steps</option>
                            <option value="quality_check" {{ request('step') == 'quality_check' ? 'selected' : '' }}>Quality Check</option>
                            <option value="process" {{ request('step') == 'process' ? 'selected' : '' }}>Process</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('wip-trackings.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- WIP Trackings List -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">WIP Tracking List</h5>
                    {{-- <div>
                        <a href="{{ route('wip-trackings.export') }}" class="btn btn-outline-success me-2">
                            <i class="bi bi-file-earmark-excel"></i> Export
                        </a>
                        <a href="{{ route('wip-trackings.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New WIP
                        </a>
                    </div> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table1">
                        <thead>
                            <tr>
                                <th>Part</th>
                                <th>Operation</th>
                                <th>Batch</th>
                                <th class="text-center">WIP Qty</th>
                                <th>Step</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wipTrackings as $wip)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $wip->partInternal->part_number }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $wip->partInternal->part_name }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        Route #{{ $wip->partOperation->route_order }}
                                    </span>
                                    @if($wip->partOperation->division)
                                        <br><small class="text-muted">{{ $wip->partOperation->division->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($wip->batch)
                                        <span class="badge bg-info">{{ $wip->batch->batch_number }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="wip-qty text-primary">{{ number_format($wip->wip_qty) }}</span>
                                </td>
                                <td>
                                    @if($wip->step == 'quality_check')
                                        <span class="badge step-badge bg-warning">
                                            <i class="bi bi-shield-check"></i> Quality Check
                                        </span>
                                    @else
                                        <span class="badge step-badge bg-info">
                                            <i class="bi bi-gear"></i> Process
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($wip->status == 'in_progress')
                                        <span class="badge status-badge bg-primary">
                                            <i class="bi bi-play-circle"></i> In Progress
                                        </span>
                                    @elseif($wip->status == 'waiting')
                                        <span class="badge status-badge bg-warning">
                                            <i class="bi bi-hourglass-split"></i> Waiting
                                        </span>
                                    @else
                                        <span class="badge status-badge bg-success">
                                            <i class="bi bi-check-circle"></i> Completed
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="min-width: 200px;">
                                        @if($wip->started_at && $wip->finished_at)
                                            <small class="text-success">
                                                <i class="bi bi-check-circle"></i>
                                                {{ \Carbon\Carbon::parse($wip->started_at)->format('d/m') }} -
                                                {{ \Carbon\Carbon::parse($wip->finished_at)->format('d/m/Y') }}
                                            </small>
                                        @elseif($wip->started_at)
                                            <small class="text-primary">
                                                <i class="bi bi-clock"></i>
                                                Started: {{ \Carbon\Carbon::parse($wip->started_at)->format('d M Y') }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($wip->started_at)->diffForHumans() }}
                                            </small>
                                        @else
                                            <small class="text-muted">
                                                <i class="bi bi-dash-circle"></i> Not started
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('wip-trackings.show', $wip->id) }}"
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- <a href="{{ route('wip-trackings.edit', $wip->id) }}"
                                           class="btn btn-sm btn-warning"
                                           data-bs-toggle="tooltip"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a> --}}
                                        @if($wip->status != 'completed')
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                data-bs-toggle="tooltip"
                                                title="Complete"
                                                onclick="completeWip({{ $wip->id }})">
                                            <i class="bi bi-check2-square"></i>
                                        </button>
                                        @endif
                                        {{-- <form action="{{ route('wip-trackings.destroy', $wip->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure want to delete this WIP tracking?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                    <p class="text-muted mt-3">No WIP trackings found</p>
                                    {{-- <a href="{{ route('wip-trackings.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Add First WIP Tracking
                                    </a> --}}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- WIP by Part Summary -->
        @if($wipTrackings->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart"></i> WIP Summary by Part
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th class="text-center">Total WIP Qty</th>
                                <th class="text-center">In Progress</th>
                                <th class="text-center">Waiting</th>
                                <th class="text-center">Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $summary = $wipTrackings->groupBy('part_internal_id')->map(function($items) {
                                    return [
                                        'part' => $items->first()->partInternal,
                                        'total_qty' => $items->sum('wip_qty'),
                                        'in_progress' => $items->where('status', 'in_progress')->count(),
                                        'waiting' => $items->where('status', 'waiting')->count(),
                                        'completed' => $items->where('status', 'completed')->count(),
                                    ];
                                });
                            @endphp
                            @foreach($summary as $item)
                            <tr>
                                <td><strong>{{ $item['part']->part_number }}</strong></td>
                                <td>{{ $item['part']->part_name }}</td>
                                <td class="text-center"><strong>{{ number_format($item['total_qty']) }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $item['in_progress'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning">{{ $item['waiting'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $item['completed'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#wipTrackingTable').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 25
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    // Complete WIP function
    function completeWip(wipId) {
        if (confirm('Mark this WIP tracking as completed?')) {
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
                    location.reload();
                } else {
                    alert('Failed to complete WIP: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while completing WIP');
            });
        }
    }
</script>
@endpush
