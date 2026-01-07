@extends('layouts.app')

@section('title', 'Edit Part Operations')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
<style>
    .operation-row {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s;
    }
    .operation-row:hover {
        border-color: #435ebe;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .operation-row.existing {
        border-color: #198754;
    }
    .operation-row.new {
        border-color: #0dcaf0;
    }
    .operation-number {
        position: absolute;
        top: -12px;
        left: 20px;
        background: #435ebe;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: bold;
        font-size: 0.875rem;
    }
    .operation-number.existing {
        background: #198754;
    }
    .operation-number.new {
        background: #0dcaf0;
    }
    .remove-operation {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .add-operation-btn {
        border: 2px dashed #435ebe;
        background: transparent;
        color: #435ebe;
        width: 100%;
        padding: 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s;
    }
    .add-operation-btn:hover {
        background: #435ebe;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Part Operations</h3>
                <p class="text-subtitle text-muted">Edit operations for {{ $partOperation->partInternal->part_number }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('part-operations.index') }}">Part Operations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Operations Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('part-operations.update-bulk', $partInternal->id) }}" method="POST" id="operationForm">
                            @csrf
                            @method('PUT')

                            <!-- Part Info (Read-only) -->
                            <div class="alert alert-light">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <strong>Part:</strong> {{ $partInternal->part_number }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Name:</strong> {{ $partInternal->part_name }}
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="part_internal_id" value="{{ $partInternal->id }}">

                            <hr class="my-4">

                            <!-- Operations List -->
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Operations List</h6>
                                    <p class="text-muted mb-0">Edit existing or add new operations</p>
                                </div>
                                <div>
                                    <span class="badge bg-success me-2">Existing</span>
                                    <span class="badge bg-info">New</span>
                                </div>
                            </div>

                            <div id="operationsContainer">
                                @foreach($operations as $operation)
                                <div class="operation-row existing" data-index="{{ $operation->id }}" data-type="existing">
                                    <span class="operation-number existing">Operation {{ $loop->iteration }} (ID: {{ $operation->id }})</span>
                                    <button type="button" class="btn btn-sm btn-danger remove-operation" onclick="markForDeletion({{ $operation->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <input type="hidden" name="existing[{{ $operation->id }}][id]" value="{{ $operation->id }}">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Route Order <span class="text-danger">*</span></label>
                                                <input type="number"
                                                       class="form-control"
                                                       name="existing[{{ $operation->id }}][route_order]"
                                                       value="{{ $operation->route_order }}"
                                                       min="1"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Division</label>
                                                <select class="form-select division-select-existing" name="existing[{{ $operation->id }}][division_id]">
                                                    <option value="">Select Division (Optional)</option>
                                                    @foreach($divisions as $division)
                                                        <option value="{{ $division->id }}" {{ $operation->division_id == $division->id ? 'selected' : '' }}>
                                                            {{ $division->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label class="form-label">Operation Data</label>
                                        <textarea class="form-control"
                                                  name="existing[{{ $operation->id }}][operation_data]"
                                                  rows="3"
                                                  placeholder="Enter operation details...">{{ $operation->operation_data }}</textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Add Operation Button -->
                            <button type="button" class="add-operation-btn" onclick="addOperation()">
                                <i class="bi bi-plus-circle"></i> Add New Operation
                            </button>

                            <!-- Deleted Operations (Hidden) -->
                            <div id="deletedOperations"></div>

                            <div class="alert alert-info mt-4">
                                <i class="bi bi-info-circle"></i>
                                <strong>Note:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Green badge = Existing operations (will be updated)</li>
                                    <li>Blue badge = New operations (will be created)</li>
                                    <li>Click trash icon to mark for deletion</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('part-operations.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save All Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Operations Summary -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-list-check"></i> Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Existing Operations</small>
                            <h3 class="mb-0 text-success" id="existingCount">{{ $operations->count() }}</h3>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">New Operations</small>
                            <h3 class="mb-0 text-info" id="newCount">0</h3>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block">Marked for Deletion</small>
                            <h3 class="mb-0 text-danger" id="deletedCount">0</h3>
                        </div>
                    </div>
                </div>

                <!-- Record Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle"></i> Part Info
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Part ID</small>
                            <code>#{{ $partInternal->id }}</code>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Operations</small>
                            <strong class="text-primary">{{ $operations->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Operation Row Template (Hidden) -->
<template id="operationTemplate">
    <div class="operation-row new" data-index="INDEX" data-type="new">
        <span class="operation-number new">New Operation INDEX</span>
        <button type="button" class="btn btn-sm btn-danger remove-operation" onclick="removeNewOperation(INDEX)">
            <i class="bi bi-trash"></i>
        </button>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">Route Order <span class="text-danger">*</span></label>
                    <input type="number"
                           class="form-control"
                           name="new[INDEX][route_order]"
                           value="INDEX"
                           min="1"
                           required>
                </div>
            </div>

            <div class="col-md-9">
                <div class="form-group mb-3">
                    <label class="form-label">Division</label>
                    <select class="form-select division-select-new" name="new[INDEX][division_id]">
                        <option value="">Select Division (Optional)</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb-0">
            <label class="form-label">Operation Data</label>
            <textarea class="form-control"
                      name="new[INDEX][operation_data]"
                      rows="3"
                      placeholder="Enter operation details..."></textarea>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script>
    let newOperationIndex = {{ $operations->max('route_order') ?? 0 }};
    let choicesInstances = [];

    // Initialize Choices.js for existing selects
    document.querySelectorAll('.division-select-existing').forEach(select => {
        new Choices(select, {
            searchEnabled: true,
            placeholder: true,
            placeholderValue: 'Select Division (Optional)'
        });
    });

    // Add new operation
    function addOperation() {
        newOperationIndex++;

        const template = document.getElementById('operationTemplate');
        const clone = template.content.cloneNode(true);

        const div = document.createElement('div');
        div.innerHTML = clone.querySelector('.operation-row').outerHTML;
        let html = div.innerHTML;
        html = html.replace(/INDEX/g, newOperationIndex);

        const container = document.getElementById('operationsContainer');
        container.insertAdjacentHTML('beforeend', html);

        // Initialize Choices.js
        const newSelect = container.querySelector(`[name="new[${newOperationIndex}][division_id]"]`);
        if (newSelect) {
            new Choices(newSelect, {
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Select Division (Optional)'
            });
        }

        updateCounters();

        setTimeout(() => {
            const newRow = container.querySelector(`[data-index="${newOperationIndex}"][data-type="new"]`);
            newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }

    // Remove new operation
    function removeNewOperation(index) {
        if (confirm('Remove this new operation?')) {
            const row = document.querySelector(`[data-index="${index}"][data-type="new"]`);
            row.remove();
            updateCounters();
        }
    }

    // Mark existing operation for deletion
    function markForDeletion(id) {
        if (confirm('Mark this operation for deletion?')) {
            const row = document.querySelector(`[data-index="${id}"][data-type="existing"]`);
            row.style.display = 'none';

            // Add to deleted operations
            const deletedContainer = document.getElementById('deletedOperations');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleted[]';
            input.value = id;
            input.id = 'deleted-' + id;
            deletedContainer.appendChild(input);

            updateCounters();
        }
    }

    // Update counters
    function updateCounters() {
        const existing = document.querySelectorAll('[data-type="existing"]').length;
        const visibleExisting = document.querySelectorAll('[data-type="existing"]:not([style*="display: none"])').length;
        const newOps = document.querySelectorAll('[data-type="new"]').length;
        const deleted = existing - visibleExisting;

        document.getElementById('existingCount').textContent = visibleExisting;
        document.getElementById('newCount').textContent = newOps;
        document.getElementById('deletedCount').textContent = deleted;
    }

    // Form validation
    document.getElementById('operationForm').addEventListener('submit', function(e) {
        const visibleOps = document.querySelectorAll('.operation-row:not([style*="display: none"])').length;

        if (visibleOps === 0) {
            e.preventDefault();
            alert('You must have at least one operation!');
            return false;
        }

        return true;
    });
</script>
@endpush

@section('title', 'Edit Part Operation')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
<style>
    .operation-preview {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        min-height: 100px;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Part Operation</h3>
                <p class="text-subtitle text-muted">Update operation routing - Route #{{ $partOperation->route_order }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('part-operations.index') }}">Part Operations</a></li>
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
                        <h5 class="card-title">Operation Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('part-operations.update', $partOperation->id) }}" method="POST" id="operationForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label for="part_internal_id" class="form-label">
                                    Part Internal <span class="text-danger">*</span>
                                </label>
                                <select class="form-select choices @error('part_internal_id') is-invalid @enderror"
                                        id="part_internal_id"
                                        name="part_internal_id"
                                        required
                                        onchange="loadExistingRoutes()">
                                    <option value="">Select Part</option>
                                    @foreach($partInternals as $part)
                                        <option value="{{ $part->id }}" {{ old('part_internal_id', $partOperation->part_internal_id) == $part->id ? 'selected' : '' }}>
                                            {{ $part->part_number }} - {{ $part->part_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('part_internal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Select the part for this operation</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="route_order" class="form-label">
                                            Route Order <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control @error('route_order') is-invalid @enderror"
                                               id="route_order"
                                               name="route_order"
                                               value="{{ old('route_order', $partOperation->route_order) }}"
                                               min="1"
                                               required>
                                        @error('route_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Sequence number in the routing process</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="division_id" class="form-label">Division</label>
                                        <select class="form-select choices @error('division_id') is-invalid @enderror"
                                                id="division_id"
                                                name="division_id">
                                            <option value="">Select Division (Optional)</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_id', $partOperation->division_id) == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Division responsible for this operation</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="operation_data" class="form-label">Operation Data</label>
                                <textarea class="form-control @error('operation_data') is-invalid @enderror"
                                          id="operation_data"
                                          name="operation_data"
                                          rows="8"
                                          placeholder="Enter operation details, instructions, parameters, etc.">{{ old('operation_data', $partOperation->operation_data) }}</textarea>
                                @error('operation_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Detailed information about this operation (optional)</small>
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Warning:</strong> Changing the route order may affect the operation sequence.
                                Make sure the new order number doesn't conflict with existing operations.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('part-operations.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Operation
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
                            <small class="text-muted d-block">Created At</small>
                            <strong>{{ $partOperation->created_at->format('d M Y, H:i') }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $partOperation->updated_at->format('d M Y, H:i') }}</strong>
                        </div>
                        @if($partOperation->deleted_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Deleted At</small>
                            <strong class="text-danger">{{ $partOperation->deleted_at->format('d M Y, H:i') }}</strong>
                        </div>
                        @endif
                        <hr>
                        <div>
                            <small class="text-muted d-block">Operation ID</small>
                            <code>#{{ $partOperation->id }}</code>
                        </div>
                    </div>
                </div>

                <!-- Existing Routes Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-diagram-3"></i> Existing Routes
                        </h6>
                    </div>
                    <div class="card-body" id="existingRoutesContainer">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                            <br><small>Loading...</small>
                        </div>
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-eye"></i> Operation Preview
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="operation-preview">
                            <div class="text-center">
                                <div class="badge bg-primary" style="font-size: 1.5rem; padding: 0.5rem 1rem;" id="previewRouteOrder">{{ $partOperation->route_order }}</div>
                                <p class="mt-3 mb-1" id="previewPart">
                                    <strong>{{ $partOperation->partInternal->part_number }}</strong>
                                </p>
                                <small class="text-muted" id="previewDivision">
                                    {{ $partOperation->division ? $partOperation->division->name : 'No division' }}
                                </small>
                            </div>
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
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script>
    // Store original values
    const originalValues = {
        part_internal_id: '{{ $partOperation->part_internal_id }}',
        route_order: '{{ $partOperation->route_order }}',
        division_id: '{{ $partOperation->division_id ?? '' }}',
        operation_data: `{{ $partOperation->operation_data }}`
    };

    // Initialize Choices.js
    const partSelect = new Choices('#part_internal_id', {
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select Part'
    });

    const divisionSelect = new Choices('#division_id', {
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select Division (Optional)'
    });

    // Load existing routes when part is selected
    function loadExistingRoutes() {
        const partId = document.getElementById('part_internal_id').value;
        const container = document.getElementById('existingRoutesContainer');
        const currentOperationId = {{ $partOperation->id }};

        if (!partId) {
            container.innerHTML = '<p class="text-muted text-center"><i class="bi bi-info-circle"></i><br>Select a part to view existing routes</p>';
            return;
        }

        container.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div><br><small>Loading...</small></div>';

        fetch(`/api/part-operations/existing-routes/${partId}`)
            .then(response => response.json())
            .then(data => {
                if (data.operations && data.operations.length > 0) {
                    let html = '<div class="alert alert-light mb-0"><small><strong>Current routes:</strong></small><ul class="mb-0 mt-2">';
                    data.operations.forEach(op => {
                        const isCurrent = op.id == currentOperationId;
                        html += `<li>
                            <span class="badge ${isCurrent ? 'bg-primary' : 'bg-secondary'}">${op.route_order}</span>
                            ${op.division_name || 'No Division'}
                            ${isCurrent ? ' <strong>(Current)</strong>' : ''}
                        </li>`;
                    });
                    html += '</ul></div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p class="text-muted text-center"><i class="bi bi-inbox"></i><br><small>No other routes</small></p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<p class="text-danger text-center"><i class="bi bi-x-circle"></i><br><small>Failed to load</small></p>';
            });
    }

    // Update preview
    function updatePreview() {
        const partSelect = document.getElementById('part_internal_id');
        const divisionSelect = document.getElementById('division_id');
        const routeOrder = document.getElementById('route_order').value;

        document.getElementById('previewRouteOrder').textContent = routeOrder || '-';
        document.getElementById('previewPart').innerHTML = partSelect.options[partSelect.selectedIndex].text !== 'Select Part'
            ? '<strong>' + partSelect.options[partSelect.selectedIndex].text + '</strong>'
            : '<strong>No part selected</strong>';
        document.getElementById('previewDivision').textContent = divisionSelect.options[divisionSelect.selectedIndex].text !== 'Select Division (Optional)'
            ? divisionSelect.options[divisionSelect.selectedIndex].text
            : 'No division';

        trackChanges();
    }

    // Track changes
    function trackChanges() {
        const changes = [];
        const currentValues = {
            part_internal_id: document.getElementById('part_internal_id').value,
            route_order: document.getElementById('route_order').value,
            division_id: document.getElementById('division_id').value,
            operation_data: document.getElementById('operation_data').value
        };

        if (currentValues.part_internal_id !== originalValues.part_internal_id) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Part changed');
        }
        if (currentValues.route_order !== originalValues.route_order) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Route order: ' + originalValues.route_order + ' → ' + currentValues.route_order);
        }
        if (currentValues.division_id !== originalValues.division_id) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Division changed');
        }
        if (currentValues.operation_data !== originalValues.operation_data) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Operation data modified');
        }

        const tracker = document.getElementById('changesTracker');
        if (changes.length > 0) {
            tracker.innerHTML = '<ul class="list-unstyled mb-0">' + changes.map(c => '<li class="mb-1">' + c + '</li>').join('') + '</ul>';
        } else {
            tracker.innerHTML = '<p class="text-muted text-center mb-0"><small>No changes yet</small></p>';
        }
    }

    // Event listeners
    document.getElementById('part_internal_id').addEventListener('change', updatePreview);
    document.getElementById('division_id').addEventListener('change', updatePreview);
    document.getElementById('route_order').addEventListener('input', updatePreview);
    document.getElementById('operation_data').addEventListener('input', trackChanges);

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadExistingRoutes();
        updatePreview();
    });
</script>
@endpush
