@extends('layouts.app')

@section('title', 'Part Operations')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
<style>
    .route-badge {
        font-size: 1.2rem;
        padding: 0.5rem 0.8rem;
        min-width: 50px;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Part Operations Management</h3>
                <p class="text-subtitle text-muted">Manage part manufacturing operations and routing</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Part Operations</li>
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

        <!-- Filter Card -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('part-operations.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Part Internal</label>
                        <select name="part_internal_id" class="form-select">
                            <option value="">All Parts</option>
                            @foreach($partInternals as $part)
                                <option value="{{ $part->id }}" {{ request('part_internal_id') == $part->id ? 'selected' : '' }}>
                                    {{ $part->part_number }} - {{ $part->part_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Division</label>
                        <select name="division_id" class="form-select">
                            <option value="">All Divisions</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('part-operations.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Operations List Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Part Operations List</h5>
                    <a href="{{ route('part-operations.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Operation
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="partOperationsTable">
                        <thead>
                            <tr>
                                <th>Route Order</th>
                                <th>Part Internal</th>
                                <th>Division</th>
                                <th>Operation Details</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($partOperations as $operation)
                            <tr>
                                <td class="text-center">
                                    <span class="badge route-badge bg-primary">
                                        {{ $operation->route_order }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $operation->partInternal->part_number }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $operation->partInternal->part_name }}</small>
                                </td>
                                <td>
                                    @if($operation->division)
                                        <span class="badge bg-info">
                                            {{ $operation->division->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($operation->operation_data)
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#operationDataModal{{ $operation->id }}">
                                            <i class="bi bi-eye"></i> View Details
                                        </button>
                                    @else
                                        <span class="text-muted">No data</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $operation->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('part-operations.show', $operation->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('part-operations.edit', $operation->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('part-operations.destroy', $operation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure want to delete this operation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Operation Data Modal -->
                            @if($operation->operation_data)
                            <div class="modal fade" id="operationDataModal{{ $operation->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Operation Data - Route {{ $operation->route_order }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-light">
                                                <pre class="mb-0" style="max-height: 400px; overflow-y: auto; white-space: pre-wrap;">{{ $operation->operation_data }}</pre>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                    <p class="text-muted mt-3">No operations found</p>
                                    <a href="{{ route('part-operations.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Add First Operation
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Routing Visualization (Group by Part) -->
        @if($partOperations->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-diagram-3"></i> Routing Visualization
                </h5>
            </div>
            <div class="card-body">
                @php
                    $groupedOperations = $partOperations->groupBy('part_internal_id');
                @endphp

                @foreach($groupedOperations as $partId => $operations)
                    @php
                        $part = $operations->first()->partInternal;
                    @endphp
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="bi bi-box-seam"></i> {{ $part->part_number }} - {{ $part->part_name }}
                        </h6>
                        <div class="d-flex align-items-center overflow-auto pb-2">
                            @foreach($operations->sortBy('route_order') as $index => $operation)
                                <div class="text-center">
                                    <div class="badge bg-primary route-badge">{{ $operation->route_order }}</div>
                                    <div class="mt-2" style="max-width: 150px;">
                                        @if($operation->division)
                                            <small class="d-block"><strong>{{ $operation->division->name }}</strong></small>
                                        @else
                                            <small class="text-muted">No Division</small>
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
                        @if(!$loop->last)
                            <hr class="my-4">
                        @endif
                    </div>
                @endforeach
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
        $('#partOperationsTable').DataTable({
            responsive: true,
            order: [[0, 'asc'], [1, 'asc']]
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
