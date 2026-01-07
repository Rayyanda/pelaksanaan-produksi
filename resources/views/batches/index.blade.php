@extends('layouts.app')

@section('title', 'Batches')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
<style>
    .batch-card {
        transition: transform 0.2s;
        cursor: pointer;
    }
    .batch-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .progress-ring {
        width: 80px;
        height: 80px;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Production Batches</h3>
                <p class="text-subtitle text-muted">Manage production batches and tracking</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Batches</li>
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
                                <h6 class="text-muted mb-1">Total Batches</h6>
                                <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                            </div>
                            <div class="avatar avatar-xl bg-primary">
                                <i class="bi bi-boxes" style="font-size: 1.5rem;"></i>
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
                                <h6 class="text-muted mb-1">In Production</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['in_production'] ?? 0 }}</h3>
                            </div>
                            <div class="avatar avatar-xl bg-primary">
                                <i class="bi bi-gear-fill" style="font-size: 1.5rem;"></i>
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
                                <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
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
                                <h6 class="text-muted mb-1">Total Quantity</h6>
                                <h3 class="mb-0 text-info">{{ number_format($stats['total_qty'] ?? 0) }}</h3>
                            </div>
                            <div class="avatar avatar-xl bg-info">
                                <i class="bi bi-stack" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('batches.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">PO Production</label>
                        <select name="po_production_id" class="form-select">
                            <option value="">All PO</option>
                            @foreach($poProductions as $po)
                                <option value="{{ $po->id }}" {{ request('po_production_id') == $po->id ? 'selected' : '' }}>
                                    {{ $po->po_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_production" {{ request('status') == 'in_production' ? 'selected' : '' }}>In Production</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('batches.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Batches List -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Batch List</h5>
                    <div>
                        <a href="{{ route('batches.export') }}" class="btn btn-outline-success me-2">
                            <i class="bi bi-file-earmark-excel"></i> Export
                        </a>
                        <a href="{{ route('batches.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Batch
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="batchesTable">
                        <thead>
                            <tr>
                                <th>Batch Number</th>
                                <th>PO Number</th>
                                <th>Part</th>
                                <th class="text-center">Quantity</th>
                                <th>Target Date</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                            <tr onclick="window.location='{{ route('batches.show', $batch->id) }}'" style="cursor: pointer;">
                                <td>
                                    <strong class="text-primary">{{ $batch->batch_number }}</strong>
                                    @if($batch->drawing_number)
                                        <br><small class="text-muted">
                                            <i class="bi bi-file-earmark-text"></i> {{ $batch->drawing_number }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $batch->poProduction->po_number }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $batch->partInternal->part_number }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $batch->partInternal->part_name }}</small>
                                    @if($batch->part_no_customer)
                                        <br><small class="text-muted">Cust: {{ $batch->part_no_customer }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong style="font-size: 1.2rem;">{{ number_format($batch->quantity) }}</strong>
                                </td>
                                <td>
                                    @if($batch->target_completed)
                                        {{ \Carbon\Carbon::parse($batch->target_completed)->format('d M Y') }}
                                        @php
                                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($batch->target_completed), false);
                                        @endphp
                                        <br>
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Overdue {{ abs($daysLeft) }}d</span>
                                        @elseif($daysLeft == 0)
                                            <span class="badge bg-warning">Due Today</span>
                                        @elseif($daysLeft <= 7)
                                            <span class="badge bg-warning">{{ $daysLeft }} days left</span>
                                        @else
                                            <span class="badge bg-success">{{ $daysLeft }} days left</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td onclick="event.stopPropagation();">
                                    @php
                                        $wipCount = $batch->partInternal->partOperations->count();
                                        $completedCount = $batch->wipTrackings->where('status', 'completed')->count();
                                        $percentage = $wipCount > 0 ? round(($completedCount / $wipCount) * 100) : 0;
                                    @endphp
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-success"
                                             role="progressbar"
                                             style="width: {{ $percentage }}%"
                                             aria-valuenow="{{ $percentage }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            {{ $percentage }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $completedCount }}/{{ $wipCount }} operations</small>
                                </td>
                                <td onclick="event.stopPropagation();">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('batches.show', $batch->id) }}"
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('batches.edit', $batch->id) }}"
                                           class="btn btn-sm btn-warning"
                                           data-bs-toggle="tooltip"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('batches.destroy', $batch->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure? This will also delete all WIP tracking records.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                    <p class="text-muted mt-3">No batches found</p>
                                    <a href="{{ route('batches.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Create First Batch
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        @if($batches->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history"></i> Recent Batches
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($batches->take(4) as $batch)
                    <div class="col-md-3">
                        <div class="card batch-card" onclick="window.location='{{ route('batches.show', $batch->id) }}'">
                            <div class="card-body">
                                <h6 class="text-primary mb-2">{{ $batch->batch_number }}</h6>
                                <p class="text-muted mb-1" style="font-size: 0.875rem;">
                                    {{ $batch->partInternal->part_number }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-info">{{ number_format($batch->quantity) }} pcs</span>
                                    @php
                                        $wipCount = $batch->wipTrackings->count();
                                        $completedCount = $batch->wipTrackings->where('status', 'completed')->count();
                                        $percentage = $wipCount > 0 ? round(($completedCount / $wipCount) * 100) : 0;
                                    @endphp
                                    <span class="badge bg-success">{{ $percentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
        $('#batchesTable').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
