@extends('layouts.app')

@section('title', 'Batches')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable.css') }}">
    <style>
        .batch-card {
            transition: transform 0.2s;
            cursor: pointer;
        }

        .batch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .progress-ring {
            width: 80px;
            height: 80px;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #435ebe;
            font-weight: 600;
        }

        .nav-tabs .nav-link .badge {
            margin-left: 5px;
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
            {{-- {{ $batches }} --}}
            <!-- Filter Card -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('batches.index') }}" method="GET" class="row g-3" id="filterForm">
                        <div class="col-md-3">
                            <label class="form-label">PO Production</label>
                            <select name="po_production_id" class="form-select"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="">All PO</option>
                                @foreach ($poProductions as $po)
                                    <option value="{{ $po->id }}"
                                        {{ request('po_production_id') == $po->id ? 'selected' : '' }}>
                                        {{ $po->po_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Part Internal</label>
                            <select name="part_internal_id" class="form-select"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Parts</option>
                                @foreach ($partInternals as $part)
                                    <option value="{{ $part->id }}"
                                        {{ request('part_internal_id') == $part->id ? 'selected' : '' }}>
                                        {{ $part->part_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort_by" class="form-select"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="batch_number" {{ request('sort_by') == 'batch_number' ? 'selected' : '' }}>
                                    Batch Number</option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                    Created Date</option>
                                <option value="target_completed"
                                    {{ request('sort_by') == 'target_completed' ? 'selected' : '' }}>Target Date</option>
                                <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>Quantity
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Order</label>
                            <select name="order" class="form-select"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending
                                </option>
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Batch List with Tabs -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul"></i> Batch List
                        </h5>
                        <a href="{{ route('batches.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create New Batch
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="statusTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab"
                                data-bs-target="#pending" type="button" role="tab" aria-controls="pending"
                                aria-selected="true">
                                <i class="bi bi-hourglass-split"></i> Pending
                                <span class="badge bg-warning text-dark">{{ $pendingBatches->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="in_progress-tab" data-bs-toggle="tab"
                                data-bs-target="#in_progress" type="button" role="tab" aria-controls="in_progress"
                                aria-selected="false">
                                <i class="bi bi-gear-fill"></i> In Progress
                                <span class="badge bg-primary">{{ $inProgressBatches->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed"
                                type="button" role="tab" aria-controls="completed" aria-selected="false">
                                <i class="bi bi-check-circle-fill"></i> Completed
                                <span class="badge bg-success">{{ $completedBatches->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="on_hold-tab" data-bs-toggle="tab" data-bs-target="#on_hold"
                                type="button" role="tab" aria-controls="on_hold" aria-selected="false">
                                <i class="bi bi-pause-circle-fill"></i> On Hold
                                <span class="badge bg-secondary">{{ $onHoldBatches->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled"
                                type="button" role="tab" aria-controls="cancelled" aria-selected="false">
                                <i class="bi bi-x-circle-fill"></i> Cancelled
                                <span class="badge bg-danger">{{ $cancelledBatches->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="statusTabsContent">
                        <!-- Pending Tab -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel"
                            aria-labelledby="pending-tab">
                            <div class="table-responsive">
                                <table class="table table-hover batch-table" id="pendingTable">
                                    <thead>
                                        <tr>
                                            <th>Batch Number</th>
                                            <th>PO Number</th>
                                            <th>Part Details</th>
                                            <th class="text-center">Quantity</th>
                                            <th>Target Date</th>
                                            <th>Progress</th>
                                            @if (auth()->user()->isAdmin())
                                                <th class="text-center">Approval</th>
                                            @endif
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingBatches as $batch)
                                            <tr style="cursor: pointer;"
                                                onclick="window.location='{{ route('batches.show', $batch->id) }}'">
                                                <td onclick="event.stopPropagation();">
                                                    <strong class="text-primary">{{ $batch->batch_number }}</strong>
                                                    <br>
                                                    <span
                                                        class="badge bg-warning text-dark">{{ ucfirst($batch->status) }}</span>
                                                    <br><small
                                                        class="text-muted">{{ $batch->created_at->format('d M Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $batch->poProduction->po_number ?? '-' }}</strong>
                                                    @if ($batch->poProduction)
                                                        <br><small
                                                            class="text-muted">{{ $batch->poProduction->customer_name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($batch->partInternal->image)
                                                            <img src="{{ asset('storage/' . $batch->partInternal->image) }}"
                                                                alt="Part"
                                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $batch->partInternal->part_number }}</strong>
                                                            <br><small
                                                                class="text-muted">{{ $batch->partInternal->part_name }}</small>
                                                            @if ($batch->part_no_customer)
                                                                <br><small class="text-muted">Cust:
                                                                    {{ $batch->part_no_customer }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <strong
                                                        style="font-size: 1.2rem;">{{ number_format($batch->quantity) }}</strong>
                                                </td>
                                                <td>
                                                    @if ($batch->target_completed)
                                                        {{ \Carbon\Carbon::parse($batch->target_completed)->format('d M Y') }}
                                                        @php
                                                            $daysLeft = \Carbon\Carbon::now()->diffInDays(
                                                                \Carbon\Carbon::parse($batch->target_completed),
                                                                false,
                                                            );
                                                        @endphp
                                                        <br>
                                                        @if ($daysLeft < 0)
                                                            <span class="badge bg-danger">Overdue
                                                                {{ abs($daysLeft) }}d</span>
                                                        @elseif($daysLeft == 0)
                                                            <span class="badge bg-warning">Due Today</span>
                                                        @elseif($daysLeft <= 7)
                                                            <span class="badge bg-warning">{{ round($daysLeft) }} days
                                                                left</span>
                                                        @else
                                                            <span class="badge bg-success">{{ round($daysLeft) }} days
                                                                left</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    @php
                                                        $wipCount = $batch->partInternal->partOperations->count();
                                                        $completedCount = $batch->wipTrackings
                                                            ->where('status', 'completed')
                                                            ->count();
                                                        $percentage =
                                                            $wipCount > 0
                                                                ? round(($completedCount / $wipCount) * 100)
                                                                : 0;
                                                    @endphp
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $percentage }}%"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            {{ $percentage }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $completedCount }}/{{ $wipCount }}
                                                        operations</small>
                                                </td>
                                                @if (auth()->user()->role === 'admin' || auth()->user()->role == 'manager')
                                                    <td class="text-center" onclick="event.stopPropagation();">
                                                        <form action="{{ route('batches.approve', $batch->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('Approve batch {{ $batch->batch_number }}? Status akan diubah ke In Progress.')"
                                                                data-bs-toggle="tooltip"
                                                                title="Approve & Start Production">
                                                                <i class="bi bi-check-circle"></i> ACC
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endif
                                                <td onclick="event.stopPropagation();">
                                                    <div class="btn-group" role="group">
                                                        <a href=p="{{ route('batches.show', $batch->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('batches.edit', $batch->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('batches.destroy', $batch->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure? This will also delete all WIP tracking records.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="tooltip" title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->can('approve-batch') ? '8' : '7' }}"
                                                    class="text-center py-5">
                                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                                    <p class="text-muted mt-3">No pending batches</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- In Progress Tab -->
                        <div class="tab-pane fade" id="in_progress" role="tabpanel" aria-labelledby="in_progress-tab">
                            <div class="table-responsive">
                                <table class="table table-hover batch-table" id="inProgressTable">
                                    <thead>
                                        <tr>
                                            <th>Batch Number</th>
                                            <th>PO Number</th>
                                            <th>Part Details</th>
                                            <th class="text-center">Quantity</th>
                                            <th>Target Date</th>
                                            <th>Progress</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($inProgressBatches as $batch)
                                            <tr style="cursor: pointer;"
                                                onclick="window.location='{{ route('batches.show', $batch->id) }}'">
                                                <td onclick="event.stopPropagation();">
                                                    <strong class="text-primary">{{ $batch->batch_number }}</strong>
                                                    <br>
                                                    <span
                                                        class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $batch->status)) }}</span>
                                                    <br><small
                                                        class="text-muted">{{ $batch->created_at->format('d M Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $batch->poProduction->po_number ?? '-' }}</strong>
                                                    @if ($batch->poProduction)
                                                        <br><small
                                                            class="text-muted">{{ $batch->poProduction->customer_name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($batch->partInternal->image)
                                                            <img src="{{ asset('storage/' . $batch->partInternal->image) }}"
                                                                alt="Part"
                                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $batch->partInternal->part_number }}</strong>
                                                            <br><small
                                                                class="text-muted">{{ $batch->partInternal->part_name }}</small>
                                                            @if ($batch->part_no_customer)
                                                                <br><small class="text-muted">Cust:
                                                                    {{ $batch->part_no_customer }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <strong
                                                        style="font-size: 1.2rem;">{{ number_format($batch->quantity) }}</strong>
                                                </td>
                                                <td>
                                                    @if ($batch->target_completed)
                                                        {{ \Carbon\Carbon::parse($batch->target_completed)->format('d M Y') }}
                                                        @php
                                                            $daysLeft = \Carbon\Carbon::now()->diffInDays(
                                                                \Carbon\Carbon::parse($batch->target_completed),
                                                                false,
                                                            );
                                                        @endphp
                                                        <br>
                                                        @if ($daysLeft < 0)
                                                            <span class="badge bg-danger">Overdue
                                                                {{ abs($daysLeft) }}d</span>
                                                        @elseif($daysLeft == 0)
                                                            <span class="badge bg-warning">Due Today</span>
                                                        @elseif($daysLeft <= 7)
                                                            <span class="badge bg-warning">{{ round($daysLeft) }} days
                                                                left</span>
                                                        @else
                                                            <span class="badge bg-success">{{ round($daysLeft) }} days
                                                                left</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    @php
                                                        $wipCount = $batch->partInternal->partOperations->count();
                                                        $completedCount = $batch->wipTrackings
                                                            ->where('status', 'completed')
                                                            ->count();
                                                        $percentage =
                                                            $wipCount > 0
                                                                ? round(($completedCount / $wipCount) * 100)
                                                                : 0;
                                                    @endphp
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $percentage }}%"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            {{ $percentage }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $completedCount }}/{{ $wipCount }}
                                                        operations</small>
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('batches.show', $batch->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </a>

                                                        {{-- <a href="{{ route('batches.edit', $batch->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('batches.destroy', $batch->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure? This will also delete all WIP tracking records.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="tooltip" title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                                    <p class="text-muted mt-3">No batches in progress</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Completed Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <div class="table-responsive">
                                <table class="table table-hover batch-table" id="completedTable">
                                    <thead>
                                        <tr>
                                            <th>Batch Number</th>
                                            <th>PO Number</th>
                                            <th>Part Details</th>
                                            <th class="text-center">Quantity</th>
                                            <th>Completed Date</th>
                                            <th>Progress</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($completedBatches as $batch)
                                            <tr style="cursor: pointer;"
                                                onclick="window.location='{{ route('batches.show', $batch->id) }}'">
                                                <td onclick="event.stopPropagation();">
                                                    <strong class="text-primary">{{ $batch->batch_number }}</strong>
                                                    <br>
                                                    <span class="badge bg-success">{{ ucfirst($batch->status) }}</span>
                                                    <br><small
                                                        class="text-muted">{{ $batch->created_at->format('d M Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $batch->poProduction->po_number ?? '-' }}</strong>
                                                    @if ($batch->poProduction)
                                                        <br><small
                                                            class="text-muted">{{ $batch->poProduction->customer_name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($batch->partInternal->image)
                                                            <img src="{{ asset('storage/' . $batch->partInternal->image) }}"
                                                                alt="Part"
                                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $batch->partInternal->part_number }}</strong>
                                                            <br><small
                                                                class="text-muted">{{ $batch->partInternal->part_name }}</small>
                                                            @if ($batch->part_no_customer)
                                                                <br><small class="text-muted">Cust:
                                                                    {{ $batch->part_no_customer }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <strong
                                                        style="font-size: 1.2rem;">{{ number_format($batch->quantity) }}</strong>
                                                </td>
                                                <td>
                                                    @if ($batch->actual_completed)
                                                        {{ \Carbon\Carbon::parse($batch->actual_completed)->format('d M Y') }}
                                                        <br><small
                                                            class="text-muted">{{ \Carbon\Carbon::parse($batch->actual_completed)->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    @php
                                                        $wipCount = $batch->partInternal->partOperations->count();
                                                        $completedCount = $batch->wipTrackings
                                                            ->where('status', 'completed')
                                                            ->count();
                                                        $percentage =
                                                            $wipCount > 0
                                                                ? round(($completedCount / $wipCount) * 100)
                                                                : 0;
                                                    @endphp
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $percentage }}%"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            {{ $percentage }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $completedCount }}/{{ $wipCount }}
                                                        operations</small>
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('batches.show', $batch->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                                    <p class="text-muted mt-3">No completed batches</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- On Hold Tab -->
                        <div class="tab-pane fade" id="on_hold" role="tabpanel" aria-labelledby="on_hold-tab">
                            <div class="table-responsive">
                                <table class="table table-hover batch-table" id="onHoldTable">
                                    <thead>
                                        <tr>
                                            <th>Batch Number</th>
                                            <th>PO Number</th>
                                            <th>Part Details</th>
                                            <th class="text-center">Quantity</th>
                                            <th>Target Date</th>
                                            <th>Progress</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($onHoldBatches as $batch)
                                            <tr style="cursor: pointer;"
                                                onclick="window.location='{{ route('batches.show', $batch->id) }}'">
                                                <td onclick="event.stopPropagation();">
                                                    <strong class="text-primary">{{ $batch->batch_number }}</strong>
                                                    <br>
                                                    <span
                                                        class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $batch->status)) }}</span>
                                                    <br><small
                                                        class="text-muted">{{ $batch->created_at->format('d M Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $batch->poProduction->po_number ?? '-' }}</strong>
                                                    @if ($batch->poProduction)
                                                        <br><small
                                                            class="text-muted">{{ $batch->poProduction->customer_name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($batch->partInternal->image)
                                                            <img src="{{ asset('storage/' . $batch->partInternal->image) }}"
                                                                alt="Part"
                                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $batch->partInternal->part_number }}</strong>
                                                            <br><small
                                                                class="text-muted">{{ $batch->partInternal->part_name }}</small>
                                                            @if ($batch->part_no_customer)
                                                                <br><small class="text-muted">Cust:
                                                                    {{ $batch->part_no_customer }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <strong
                                                        style="font-size: 1.2rem;">{{ number_format($batch->quantity) }}</strong>
                                                </td>
                                                <td>
                                                    @if ($batch->target_completed)
                                                        {{ \Carbon\Carbon::parse($batch->target_completed)->format('d M Y') }}
                                                    @else
                                                        <span class="text-muted">Not set</span>
                                                    @endif
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    @php
                                                        $wipCount = $batch->partInternal->partOperations->count();
                                                        $completedCount = $batch->wipTrackings
                                                            ->where('status', 'completed')
                                                            ->count();
                                                        $percentage =
                                                            $wipCount > 0
                                                                ? round(($completedCount / $wipCount) * 100)
                                                                : 0;
                                                    @endphp
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $percentage }}%"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            {{ $percentage }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $completedCount }}/{{ $wipCount }}
                                                        operations</small>
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('batches.show', $batch->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('batches.edit', $batch->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                                    <p class="text-muted mt-3">No batches on hold</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cancelled Tab -->
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                            <div class="table-responsive">
                                <table class="table table-hover batch-table" id="cancelledTable">
                                    <thead>
                                        <tr>
                                            <th>Batch Number</th>
                                            <th>PO Number</th>
                                            <th>Part Details</th>
                                            <th class="text-center">Quantity</th>
                                            <th>Cancelled Date</th>
                                            <th>Progress</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cancelledBatches as $batch)
                                            <tr style="cursor: pointer;"
                                                onclick="window.location='{{ route('batches.show', $batch->id) }}'">
                                                <td onclick="event.stopPropagation();">
                                                    <strong class="text-primary">{{ $batch->batch_number }}</strong>
                                                    <br>
                                                    <span class="badge bg-danger">{{ ucfirst($batch->status) }}</span>
                                                    <br><small
                                                        class="text-muted">{{ $batch->created_at->format('d M Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $batch->poProduction->po_number ?? '-' }}</strong>
                                                    @if ($batch->poProduction)
                                                        <br><small
                                                            class="text-muted">{{ $batch->poProduction->customer_name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($batch->partInternal->image)
                                                            <img src="{{ asset('storage/' . $batch->partInternal->image) }}"
                                                                alt="Part"
                                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $batch->partInternal->part_number }}</strong>
                                                            <br><small
                                                                class="text-muted">{{ $batch->partInternal->part_name }}</small>
                                                            @if ($batch->part_no_customer)
                                                                <br><small class="text-muted">Cust:
                                                                    {{ $batch->part_no_customer }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <strong
                                                        style="font-size: 1.2rem;">{{ number_format($batch->quantity) }}</strong>
                                                </td>
                                                <td>
                                                    @if ($batch->updated_at)
                                                        {{ $batch->updated_at->format('d M Y') }}
                                                        <br><small
                                                            class="text-muted">{{ $batch->updated_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    @php
                                                        $wipCount = $batch->partInternal->partOperations->count();
                                                        $completedCount = $batch->wipTrackings
                                                            ->where('status', 'completed')
                                                            ->count();
                                                        $percentage =
                                                            $wipCount > 0
                                                                ? round(($completedCount / $wipCount) * 100)
                                                                : 0;
                                                    @endphp
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: {{ $percentage }}%"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            {{ $percentage }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $completedCount }}/{{ $wipCount }}
                                                        operations</small>
                                                </td>
                                                <td onclick="event.stopPropagation();">
                                                    <a href="{{ route('batches.show', $batch->id) }}"
                                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                        title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                                    <p class="text-muted mt-3">No cancelled batches</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if ($batches->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history"></i> Recent Batches
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($batches->take(4) as $batch)
                                <div class="col-md-3">
                                    <div class="card batch-card"
                                        onclick="window.location='{{ route('batches.show', $batch->id) }}'">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-2">{{ $batch->batch_number }}</h6>
                                            <p class="text-muted mb-1" style="font-size: 0.875rem;">
                                                {{ $batch->partInternal->part_number }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="badge bg-info">{{ number_format($batch->quantity) }}
                                                    pcs</span>
                                                @php
                                                    $wipCount = $batch->wipTrackings->count();
                                                    $completedCount = $batch->wipTrackings
                                                        ->where('status', 'completed')
                                                        ->count();
                                                    $percentage =
                                                        $wipCount > 0 ? round(($completedCount / $wipCount) * 100) : 0;
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
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    {{-- <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Track which tables have been initialized
            const initializedTables = {};

            // DataTable configuration for each table
            const tableConfigs = {
                pendingTable: {
                    perPage: 25,
                    perPageSelect: [10, 25, 50, 100],
                    labels: {
                        placeholder: "Search batches...",
                        noRows: "No pending batches",
                        info: "Showing {start} to {end} of {rows} batches"
                    }
                },
                inProgressTable: {
                    perPage: 25,
                    perPageSelect: [10, 25, 50, 100],
                    labels: {
                        placeholder: "Search batches...",
                        noRows: "No batches in progress",
                        info: "Showing {start} to {end} of {rows} batches"
                    }
                },
                completedTable: {
                    perPage: 25,
                    perPageSelect: [10, 25, 50, 100],
                    labels: {
                        placeholder: "Search batches...",
                        noRows: "No completed batches",
                        info: "Showing {start} to {end} of {rows} batches"
                    }
                },
                onHoldTable: {
                    perPage: 25,
                    perPageSelect: [10, 25, 50, 100],
                    labels: {
                        placeholder: "Search batches...",
                        noRows: "No batches on hold",
                        info: "Showing {start} to {end} of {rows} batches"
                    }
                },
                cancelledTable: {
                    perPage: 25,
                    perPageSelect: [10, 25, 50, 100],
                    labels: {
                        placeholder: "Search batches...",
                        noRows: "No cancelled batches",
                        info: "Showing {start} to {end} of {rows} batches"
                    }
                }
            };

            // Function to initialize a Simple DataTable
            function initializeDataTable(tableId) {
                if (!initializedTables[tableId]) {
                    const table = document.getElementById(tableId);
                    if (table) {
                        initializedTables[tableId] = new simpleDatatables.DataTable(table, tableConfigs[tableId]);
                    }
                }
            }

            // Initialize the first active tab (pending)
            initializeDataTable('pendingTable');

            // Listen for tab show events and initialize tables lazily
            const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function(e) {
                    const targetId = e.target.getAttribute('data-bs-target');

                    // Map tab IDs to table IDs
                    const tabToTableMap = {
                        '#pending': 'pendingTable',
                        '#in_progress': 'inProgressTable',
                        '#completed': 'completedTable',
                        '#on_hold': 'onHoldTable',
                        '#cancelled': 'cancelledTable'
                    };

                    const tableId = tabToTableMap[targetId];
                    if (tableId) {
                        initializeDataTable(tableId);
                    }
                });
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
