@extends('layouts.app')

@section('title', 'Division Dashboard')

@push('styles')
    <style>
        .wip-card {
            transition: all 0.3s;
            cursor: pointer;
            border-left: 4px solid #dee2e6;
        }

        .wip-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .wip-card.quality_check {
            border-left-color: #ffc107;
        }

        .wip-card.process {
            border-left-color: #0dcaf0;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #435ebe;
            font-weight: 600;
        }

        .badge-xl {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>
                        <i class="bi bi-building"></i> {{ $division->name }}
                    </h3>
                    <p class="text-subtitle text-muted">Production work center dashboard</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Division Dashboard</li>
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
                                    <h6 class="text-muted mb-1">Quality Check</h6>
                                    <h3 class="mb-0 text-warning">{{ $stats['quality_check'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-warning">
                                    <i class="bi bi-shield-check" style="font-size: 1.5rem;"></i>
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
                                    <h6 class="text-muted mb-1">In Process</h6>
                                    <h3 class="mb-0 text-info">{{ $stats['process'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-info">
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
                                    <h6 class="text-muted mb-1">Completed Today</h6>
                                    <h3 class="mb-0 text-success">{{ $stats['completed_today'] ?? 0 }}</h3>
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
                                    <h6 class="text-muted mb-1">Total WIP Qty</h6>
                                    <h3 class="mb-0 text-primary">{{ number_format($stats['total_wip_qty'] ?? 0) }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-primary">
                                    <i class="bi bi-boxes" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content with Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="divisionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="quality-tab" data-bs-toggle="tab" data-bs-target="#quality"
                                type="button" role="tab">
                                <i class="bi bi-shield-check"></i> Quality Check
                                <span class="badge bg-warning ms-2">{{ $qualityCheckWips->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="process-tab" data-bs-toggle="tab" data-bs-target="#process"
                                type="button" role="tab">
                                <i class="bi bi-gear"></i> Process
                                <span class="badge bg-info ms-2">{{ $processWips->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                                type="button" role="tab">
                                <i class="bi bi-clock-history"></i> History
                                <span class="badge bg-success ms-2">{{ $historyWips->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="divisionTabsContent">

                        <!-- Quality Check Tab -->
                        <div class="tab-pane fade show active" id="quality" role="tabpanel">
                            <div class="row py-5">
                                @forelse($qualityCheckWips as $wip)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card wip-card quality_check h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <span class="badge badge-xl bg-warning">
                                                            <i class="bi bi-shield-check"></i> Quality Check
                                                        </span>
                                                    </div>
                                                    <div>
                                                        @if ($wip->status == 'waiting')
                                                            <span class="badge bg-secondary">Waiting</span>
                                                        @elseif($wip->status == 'in_progress')
                                                            <span class="badge bg-primary">In Progress</span>
                                                        @endif
                                                        <span
                                                            class="text-muted">{{ \Carbon\Carbon::parse($wip->batch->target_completed)->diffForHumans() }}</span>
                                                    </div>
                                                </div>

                                                <h5 class="card-title mb-2">
                                                    Batch Number : {{ $wip->batch->batch_number }}
                                                </h5>

                                                <p class="text-muted mb-2">
                                                    <strong>{{ $wip->partInternal->part_number }}</strong><br>
                                                    <small>{{ $wip->partInternal->part_name }}</small>
                                                </p>

                                                <div class="mb-3">
                                                    <span class="badge bg-secondary">Route
                                                        #{{ $wip->partOperation->route_order }}</span>
                                                    <span class="badge bg-info">{{ number_format($wip->wip_qty) }}
                                                        pcs</span>
                                                </div>

                                                @if ($wip->started_at)
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="bi bi-clock"></i> Started:
                                                        {{ $wip->started_at->format('d M Y, H:i') }}
                                                    </small>
                                                @endif

                                                @if ($wip->operation_notes)
                                                    <div class="bg-light mb-3">
                                                        <small>{{ Str::limit($wip->operation_notes, 100) }}</small>
                                                    </div>
                                                @endif

                                                Area : {{ $wip->partOperation->area->name }}

                                                <div class="d-grid gap-2">
                                                    @if ($wip->status == 'waiting')
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="startWip({{ $wip->id }}, 'quality')">
                                                            <i class="bi bi-play"></i> Start Quality Check
                                                        </button>
                                                    @elseif($wip->status == 'in_progress')
                                                        {{-- <button type="button" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-exclamation"></i> Rework
                                                    </button> --}}
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="moveToProcess({{ $wip->id }})">
                                                            <i class="bi bi-arrow-right"></i> Move to Process
                                                        </button>
                                                    @endif
                                                    <a class="btn btn-outline-secondary btn-sm"
                                                        href="{{ route('wip-trackings.show', $wip->id) }}">
                                                        <i class="bi bi-eye"></i> View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                            <p class="text-muted mt-3">No items in quality check</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Process Tab -->
                        <div class="tab-pane fade" id="process" role="tabpanel">
                            <div class="row py-3">
                                @forelse($processWips as $wip)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card wip-card process h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <span class="badge badge-xl bg-info">
                                                            <i class="bi bi-gear"></i> Process
                                                        </span>
                                                    </div>
                                                    <div>
                                                        @if ($wip->status == 'waiting')
                                                            <span class="badge bg-secondary">Waiting</span>
                                                        @elseif($wip->status == 'in_progress')
                                                            <span class="badge bg-primary">In Progress</span>
                                                        @endif
                                                        <span
                                                            class="text-muted">{{ \Carbon\Carbon::parse($wip->batch->target_completed)->diffForHumans() }}</span>
                                                    </div>
                                                </div>

                                                <h5 class="card-title mb-2">
                                                    {{ $wip->batch->batch_number }}
                                                </h5>

                                                <p class="text-muted mb-2">
                                                    <strong>{{ $wip->partInternal->part_number }}</strong><br>
                                                    <small>{{ $wip->partInternal->part_name }}</small>
                                                </p>

                                                <div class="mb-3">
                                                    <span class="badge bg-secondary">Route
                                                        #{{ $wip->partOperation->route_order }}</span>
                                                    <span class="badge bg-info">{{ number_format($wip->wip_qty) }}
                                                        pcs</span>
                                                </div>

                                                @if ($wip->started_at)
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="bi bi-clock"></i> Started:
                                                        {{ $wip->started_at->format('d M Y, H:i') }}
                                                    </small>
                                                @endif

                                                @if ($wip->operation_notes)
                                                    <div class="alert alert-light mb-3">
                                                        <small>{{ Str::limit($wip->operation_notes, 100) }}</small>
                                                    </div>
                                                @endif
                                                Area :
                                                {{ $wip->partOperation->area->name }}

                                                <div class="d-grid gap-2">
                                                    @if ($wip->status == 'waiting')
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="startWip({{ $wip->id }}, 'process')">
                                                            <i class="bi bi-play"></i> Start Process
                                                        </button>
                                                    @elseif($wip->status == 'in_progress')
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="completeWip({{ $wip->id }})">
                                                            <i class="bi bi-check2"></i> Complete
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('wip-trackings.show', $wip->id) }}"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-eye"></i> View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                            <p class="text-muted mt-3">No items in process</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- History Tab -->
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Batch</th>
                                            <th>Part</th>
                                            <th>Route</th>
                                            <th>Step</th>
                                            <th>Qty</th>
                                            <th>Started</th>
                                            <th>Finished</th>
                                            <th>Duration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($historyWips as $wip)
                                            <tr>
                                                <td>
                                                    <strong>{{ $wip->batch->batch_number }}</strong>
                                                </td>
                                                <td>
                                                    {{ $wip->partInternal->part_number }}<br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($wip->partInternal->part_name, 30) }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-secondary">#{{ $wip->partOperation->route_order }}</span>
                                                </td>
                                                <td>
                                                    @if ($wip->step == 'quality_check')
                                                        <span class="badge bg-warning">QC</span>
                                                    @else
                                                        <span class="badge bg-info">Process</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($wip->wip_qty) }}</td>
                                                <td>
                                                    @if ($wip->started_at)
                                                        {{ $wip->started_at->format('d/m H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($wip->finished_at)
                                                        {{ $wip->finished_at->format('d/m H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($wip->started_at && $wip->finished_at)
                                                        {{ $wip->started_at->diffInHours($wip->finished_at) }}h
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('wip-trackings.show', $wip->id) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="bi bi-inbox" style="font-size: 2rem; color: #dee2e6;"></i>
                                                    <p class="text-muted mt-2">No completed operations yet</p>
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
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function startWip(wipId, step) {
            if (!confirm('Start this operation?')) return;

            fetch(`/wip-trackings/${wipId}/start?step=${step}`, {
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
                    body: JSON.stringify({
                        step: 'process'
                    })
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

        function showWipDetail(wipId) {
            window.location.href = `admin/wip-trackings/${wipId}`;
        }
    </script>
@endpush
