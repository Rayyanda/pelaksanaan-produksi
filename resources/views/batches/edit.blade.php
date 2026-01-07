@extends('layouts.app')

@section('title', 'Edit Batch')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Batch</h3>
                <p class="text-subtitle text-muted">Update batch information - {{ $batch->batch_number }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('batches.index') }}">Batches</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Batch Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('batches.update', $batch->id) }}" method="POST" id="batchForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label for="batch_number" class="form-label">
                                    Batch Number <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('batch_number') is-invalid @enderror"
                                       id="batch_number"
                                       name="batch_number"
                                       value="{{ old('batch_number', $batch->batch_number) }}"
                                       placeholder="e.g., BATCH-2024-001"
                                       required>
                                @error('batch_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="po_production_id" class="form-label">
                                            PO Production <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select choices @error('po_production_id') is-invalid @enderror"
                                                id="po_production_id"
                                                name="po_production_id"
                                                required>
                                            <option value="">Select PO</option>
                                            @foreach($poProductions as $po)
                                                <option value="{{ $po->id }}" {{ old('po_production_id', $batch->po_production_id) == $po->id ? 'selected' : '' }}>
                                                    {{ $po->po_number }} (Qty: {{ number_format($po->quantity) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('po_production_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="part_internal_id" class="form-label">
                                            Part Internal <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select choices @error('part_internal_id') is-invalid @enderror"
                                                id="part_internal_id"
                                                name="part_internal_id"
                                                required>
                                            <option value="">Select Part</option>
                                            @foreach($partInternals as $part)
                                                <option value="{{ $part->id }}" {{ old('part_internal_id', $batch->part_internal_id) == $part->id ? 'selected' : '' }}>
                                                    {{ $part->part_number }} - {{ $part->part_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('part_internal_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="bi bi-exclamation-triangle text-warning"></i>
                                            Changing part will not update existing WIP tracking
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="quantity" class="form-label">
                                            Batch Quantity <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control @error('quantity') is-invalid @enderror"
                                               id="quantity"
                                               name="quantity"
                                               value="{{ old('quantity', $batch->quantity) }}"
                                               min="1"
                                               required>
                                        @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="target_completed" class="form-label">
                                            Target Completion Date
                                        </label>
                                        <input type="text"
                                               class="form-control flatpickr @error('target_completed') is-invalid @enderror"
                                               id="target_completed"
                                               name="target_completed"
                                               value="{{ old('target_completed', $batch->target_completed) }}"
                                               placeholder="Select target date">
                                        @error('target_completed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="mb-3">Customer Part Information (Optional)</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="part_no_customer" class="form-label">Customer Part Number</label>
                                        <input type="text"
                                               class="form-control @error('part_no_customer') is-invalid @enderror"
                                               id="part_no_customer"
                                               name="part_no_customer"
                                               value="{{ old('part_no_customer', $batch->part_no_customer) }}">
                                        @error('part_no_customer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="part_no_customer_source" class="form-label">Customer Part Source</label>
                                        <input type="url"
                                               class="form-control @error('part_no_customer_source') is-invalid @enderror"
                                               id="part_no_customer_source"
                                               name="part_no_customer_source"
                                               value="{{ old('part_no_customer_source', $batch->part_no_customer_source) }}">
                                        @error('part_no_customer_source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="drawing_number" class="form-label">Drawing Number</label>
                                        <input type="text"
                                               class="form-control @error('drawing_number') is-invalid @enderror"
                                               id="drawing_number"
                                               name="drawing_number"
                                               value="{{ old('drawing_number', $batch->drawing_number) }}">
                                        @error('drawing_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="drawing_number_source" class="form-label">Drawing Source</label>
                                        <input type="url"
                                               class="form-control @error('drawing_number_source') is-invalid @enderror"
                                               id="drawing_number_source"
                                               name="drawing_number_source"
                                               value="{{ old('drawing_number_source', $batch->drawing_number_source) }}">
                                        @error('drawing_number_source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Note:</strong> Changing PO or Part will not automatically update existing WIP tracking records.
                                Make sure to update WIP tracking separately if needed.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Batch
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Record Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle"></i> Record Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Batch ID</small>
                            <code>#{{ $batch->id }}</code>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Created At</small>
                            <strong>{{ $batch->created_at->format('d M Y, H:i') }}</strong>
                            <br><small class="text-muted">{{ $batch->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $batch->updated_at->format('d M Y, H:i') }}</strong>
                            <br><small class="text-muted">{{ $batch->updated_at->diffForHumans() }}</small>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted d-block">Total WIP Records</small>
                            <strong class="text-primary" style="font-size: 1.5rem;">{{ $batch->wipTrackings->count() }}</strong>
                        </div>
                    </div>
                </div>

                <!-- WIP Summary -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-bar-chart"></i> WIP Status
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $waiting = $batch->wipTrackings->where('status', 'waiting')->count();
                            $inProgress = $batch->wipTrackings->where('status', 'in_progress')->count();
                            $completed = $batch->wipTrackings->where('status', 'completed')->count();
                            $total = $batch->wipTrackings->count();
                            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-hourglass-split text-warning"></i> Waiting</span>
                                <strong>{{ $waiting }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-play-circle text-primary"></i> In Progress</span>
                                <strong>{{ $inProgress }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-check-circle text-success"></i> Completed</span>
                                <strong>{{ $completed }}</strong>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h3 class="mb-0 text-success">{{ $percentage }}%</h3>
                            <small class="text-muted">Overall Progress</small>
                        </div>
                    </div>
                </div>

                <!-- Changes Tracker -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-pencil-square"></i> Changes
                        </h6>
                    </div>
                    <div class="card-body" id="changesTracker">
                        <p class="text-muted text-center mb-0">
                            <small>No changes yet</small>
                        </p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-link-45deg"></i> Quick Links
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-eye"></i> View Batch Detail
                            </a>
                            <a href="{{ route('wip-trackings.index', ['batch_id' => $batch->id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-list-check"></i> View WIP Trackings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
<script>
    // Store original values
    const originalValues = {
        batch_number: '{{ $batch->batch_number }}',
        po_production_id: '{{ $batch->po_production_id }}',
        part_internal_id: '{{ $batch->part_internal_id }}',
        quantity: '{{ $batch->quantity }}',
        target_completed: '{{ $batch->target_completed }}',
        part_no_customer: '{{ $batch->part_no_customer }}',
        part_no_customer_source: '{{ $batch->part_no_customer_source }}',
        drawing_number: '{{ $batch->drawing_number }}',
        drawing_number_source: '{{ $batch->drawing_number_source }}'
    };

    // Initialize Choices.js
    const poSelect = new Choices('#po_production_id', {
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select PO'
    });

    const partSelect = new Choices('#part_internal_id', {
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select Part'
    });

    // Initialize Flatpickr
    flatpickr('.flatpickr', {
        dateFormat: 'Y-m-d'
    });

    // Track changes
    function trackChanges() {
        const changes = [];
        const currentValues = {
            batch_number: document.getElementById('batch_number').value,
            po_production_id: document.getElementById('po_production_id').value,
            part_internal_id: document.getElementById('part_internal_id').value,
            quantity: document.getElementById('quantity').value,
            target_completed: document.getElementById('target_completed').value,
            part_no_customer: document.getElementById('part_no_customer').value,
            part_no_customer_source: document.getElementById('part_no_customer_source').value,
            drawing_number: document.getElementById('drawing_number').value,
            drawing_number_source: document.getElementById('drawing_number_source').value
        };

        if (currentValues.batch_number !== originalValues.batch_number) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Batch number changed');
        }
        if (currentValues.po_production_id !== originalValues.po_production_id) {
            changes.push('<i class="bi bi-pencil text-warning"></i> PO Production changed');
        }
        if (currentValues.part_internal_id !== originalValues.part_internal_id) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Part Internal changed');
        }
        if (currentValues.quantity !== originalValues.quantity) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Quantity: ' + originalValues.quantity + ' → ' + currentValues.quantity);
        }
        if (currentValues.target_completed !== originalValues.target_completed) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Target date changed');
        }
        if (currentValues.part_no_customer !== originalValues.part_no_customer) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Customer part number changed');
        }
        if (currentValues.drawing_number !== originalValues.drawing_number) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Drawing number changed');
        }

        const tracker = document.getElementById('changesTracker');
        if (changes.length > 0) {
            tracker.innerHTML = '<ul class="list-unstyled mb-0">' + changes.map(c => '<li class="mb-1">' + c + '</li>').join('') + '</ul>';
        } else {
            tracker.innerHTML = '<p class="text-muted text-center mb-0"><small>No changes yet</small></p>';
        }
    }

    // Event listeners
    const inputFields = ['batch_number', 'po_production_id', 'part_internal_id', 'quantity', 'target_completed', 'part_no_customer', 'part_no_customer_source', 'drawing_number', 'drawing_number_source'];
    inputFields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('change', trackChanges);
            element.addEventListener('input', trackChanges);
        }
    });
</script>
@endpush
