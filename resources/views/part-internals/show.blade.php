@extends('layouts.app')

@section('title', 'Part Internal Details')

@push('styles')
    <style>
        .info-card {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .info-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem 0.5rem 0 0;
            padding: 1rem 1.5rem;
        }

        .info-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 200px;
            flex-shrink: 0;
        }

        .info-value {
            color: #495057;
            flex: 1;
        }

        .operation-card {
            background: #f8f9fa;
            border-left: 4px solid #435ebe;
            border-radius: 0.5rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .operation-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .operation-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .operation-number {
            background: #435ebe;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .operation-title {
            flex: 1;
        }

        .operation-title h5 {
            margin: 0 0 0.25rem 0;
            color: #2c3e50;
        }

        .operation-title small {
            color: #6c757d;
        }

        .process-details {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #e9ecef;
        }

        .process-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: #e3f2fd;
            border-radius: 0.5rem;
            margin: 0.25rem;
            font-size: 0.875rem;
        }

        .process-badge i {
            margin-right: 0.5rem;
            color: #435ebe;
        }

        .target-qty-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .target-qty-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
        }

        .target-qty-item .label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .target-qty-item .value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #435ebe;
        }

        .badge-division {
            background: #667eea;
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .badge-area {
            background: #f093fb;
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .no-operations {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-operations i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .action-buttons {
            position: sticky;
            top: 20px;
            z-index: 100;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Part Internal Details</h3>
                    <p class="text-subtitle text-muted">{{ $partInternal->part_number }}</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('part-internals.index') }}">Part Internals</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-9">
                    <!-- Part Information Card -->
                    <div class="info-card">
                        <div class="card-header">
                            <i class="bi bi-info-circle"></i> Part Information
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">Part Number</div>
                                <div class="info-value"><strong>{{ $partInternal->part_number }}</strong></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">No Internal</div>
                                <div class="info-value">{{ $partInternal->nointernal ?? '-' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Part Name</div>
                                <div class="info-value">{{ $partInternal->part_name ?? '-' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Material Spec</div>
                                <div class="info-value">{{ $partInternal->matl_spec ?? '-' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Material Required</div>
                                <div class="info-value">
                                    {{ $partInternal->matl_req ? number_format($partInternal->matl_req, 2) . ' Kg' : '-' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Component Per Mould</div>
                                <div class="info-value">{{ $partInternal->comp_per_mould ?? '-' }}</div>
                            </div>
                            @if ($partInternal->deoxidation)
                                <div class="info-row">
                                    <div class="info-label">Deoxidation</div>
                                    <div class="info-value">
                                        <pre style="margin: 0; white-space: pre-wrap; font-family: inherit;">{{ $partInternal->deoxidation }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Target Quantities Card -->
                    <div class="info-card">
                        <div class="card-header">
                            <i class="bi bi-bar-chart"></i> Target Quantities (Week)
                        </div>
                        <div class="card-body">
                            <div class="target-qty-grid">
                                <div class="target-qty-item">
                                    <div class="label">Waxing</div>
                                    <div class="value">{{ $partInternal->target_qty_waxing ?? 0 }}</div>
                                </div>
                                <div class="target-qty-item">
                                    <div class="label">Mould Room</div>
                                    <div class="value">{{ $partInternal->target_qty_mould_room ?? 0 }}</div>
                                </div>
                                <div class="target-qty-item">
                                    <div class="label">Melting</div>
                                    <div class="value">{{ $partInternal->target_qty_melting ?? 0 }}</div>
                                </div>
                                <div class="target-qty-item">
                                    <div class="label">Heat Treatment</div>
                                    <div class="value">{{ $partInternal->target_qty_heat_treatment ?? 0 }}</div>
                                </div>
                                <div class="target-qty-item">
                                    <div class="label">Cut Off</div>
                                    <div class="value">{{ $partInternal->target_qty_cut_off ?? 0 }}</div>
                                </div>
                                <div class="target-qty-item">
                                    <div class="label">Finishing</div>
                                    <div class="value">{{ $partInternal->target_qty_finishing ?? 0 }}</div>
                                </div>
                                <div class="target-qty-item">
                                    <div class="label">Machining</div>
                                    <div class="value">{{ $partInternal->target_qty_machining ?? 0 }}</div>
                                </div>
                                <div class="target-qty-item">
                                    <div class="label">Quality Control</div>
                                    <div class="value">{{ $partInternal->target_qty_quality_control ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operations & Processes Card -->
                    <div class="info-card">
                        <div class="card-header">
                            <i class="bi bi-list-check"></i> Operations & Processes
                            <span class="badge bg-light text-dark ms-2">{{ $partInternal->partOperations->count() }}
                                Operations</span>
                        </div>
                        <div class="card-body">
                            @forelse($partInternal->partOperations->sortBy('route_order') as $operation)
                                <div class="operation-card">
                                    <div class="operation-header">
                                        <div class="operation-number">{{ $operation->route_order }}</div>
                                        <div class="operation-title">
                                            <h5>
                                                @if ($operation->division)
                                                    <span class="badge-division">{{ $operation->division->name }}</span>
                                                @endif
                                                @if ($operation->area)
                                                    <span class="badge-area">{{ $operation->area->name }}</span>
                                                @endif
                                            </h5>
                                            <small>Operation #{{ $operation->route_order }}</small>
                                        </div>
                                    </div>

                                    @if ($operation->operation_data)
                                        <div class="mb-3">
                                            <strong class="text-muted d-block mb-2"><i class="bi bi-file-text"></i>
                                                Operation Data:</strong>
                                            <pre
                                                style="background: white; padding: 0.75rem; border-radius: 0.25rem; border: 1px solid #e9ecef; margin: 0; white-space: pre-wrap; font-family: inherit;">{{ $operation->operation_data }}</pre>
                                        </div>
                                    @endif

                                    @if ($operation->process)
                                        <div class="process-details">
                                            <h6 class="text-primary mb-3">
                                                <i class="bi bi-gear-fill"></i> Process Details
                                            </h6>

                                            <div class="row">
                                                @if ($operation->process->process_order)
                                                    <div class="col-md-3 mb-2">
                                                        <div class="process-badge">
                                                            <i class="bi bi-sort-numeric-down"></i>
                                                            <div>
                                                                <small class="d-block text-muted"
                                                                    style="font-size: 0.7rem;">Process Order</small>
                                                                <strong>{{ $operation->process->process_order }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($operation->process->capacity)
                                                    <div class="col-md-3 mb-2">
                                                        <div class="process-badge">
                                                            <i class="bi bi-speedometer2"></i>
                                                            <div>
                                                                <small class="d-block text-muted"
                                                                    style="font-size: 0.7rem;">Capacity</small>
                                                                <strong>{{ $operation->process->capacity }} units</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($operation->process->duration)
                                                    <div class="col-md-3 mb-2">
                                                        <div class="process-badge">
                                                            <i class="bi bi-clock"></i>
                                                            <div>
                                                                <small class="d-block text-muted"
                                                                    style="font-size: 0.7rem;">Duration</small>
                                                                <strong>{{ $operation->process->duration }} min</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($operation->process->operator_count)
                                                    <div class="col-md-3 mb-2">
                                                        <div class="process-badge">
                                                            <i class="bi bi-people"></i>
                                                            <div>
                                                                <small class="d-block text-muted"
                                                                    style="font-size: 0.7rem;">Operators</small>
                                                                <strong>{{ $operation->process->operator_count }}
                                                                    person(s)</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($operation->process->equipment)
                                                    <div class="col-md-12 mb-2">
                                                        <div class="process-badge" style="width: 100%;">
                                                            <i class="bi bi-tools"></i>
                                                            <div>
                                                                <small class="d-block text-muted"
                                                                    style="font-size: 0.7rem;">Equipment</small>
                                                                <strong>{{ $operation->process->equipment }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-12">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input class="form-check-input" type="checkbox" disabled
                                                            {{ $operation->process->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            <span
                                                                class="badge {{ $operation->process->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ $operation->process->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="no-operations">
                                    <i class="bi bi-inbox"></i>
                                    <p class="mb-0">No operations found for this part.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="action-buttons">
                        <!-- Actions Card -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('part-internals.edit', $partInternal->id) }}"
                                    class="btn btn-warning w-100 mb-2">
                                    <i class="bi bi-pencil"></i> Edit Part
                                </a>
                                {{-- <a href="{{ route('part-internals.pdf', $partInternal->id) }}"
                                    class="btn btn-info w-100 mb-2" target="_blank">
                                    <i class="bi bi-file-pdf"></i> Export PDF
                                </a> --}}
                                <a href="{{ route('part-internals.index') }}" class="btn btn-secondary w-100">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Created</small>
                                    <strong>{{ $partInternal->created_at->format('d M Y, H:i') }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Last Updated</small>
                                    <strong>{{ $partInternal->updated_at->format('d M Y, H:i') }}</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Operations</small>
                                    <strong>{{ $partInternal->partOperations->count() }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Card -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-graph-up"></i> Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Operations with Process</small>
                                    <strong>{{ $partInternal->partOperations->filter(fn($op) => $op->process)->count() }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Total Divisions Used</small>
                                    <strong>{{ $partInternal->partOperations->whereNotNull('division_id')->unique('division_id')->count() }}</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Areas Used</small>
                                    <strong>{{ $partInternal->partOperations->whereNotNull('area_id')->unique('area_id')->count() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
