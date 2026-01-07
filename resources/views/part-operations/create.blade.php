@extends('layouts.app')

@section('title', 'Create Part Operations')

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
                <h3>Create Part Operations</h3>
                <p class="text-subtitle text-muted">Add multiple operations for part routing</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('part-operations.index') }}">Part Operations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
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
                        <form action="{{ route('part-operations.store-bulk') }}" method="POST" id="operationForm">
                            @csrf

                            <!-- Part Selection -->
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
                                        <option value="{{ $part->id }}" {{ old('part_internal_id') == $part->id ? 'selected' : '' }}>
                                            {{ $part->part_number }} - {{ $part->part_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('part_internal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Select the part for all operations below</small>
                            </div>

                            <hr class="my-4">

                            <!-- Operations List -->
                            <div class="mb-3">
                                <h6>Operations List</h6>
                                <p class="text-muted">Add all operations for the selected part</p>
                            </div>

                            <div id="operationsContainer">
                                <!-- Operation rows will be added here dynamically -->
                            </div>

                            <!-- Add Operation Button -->
                            <button type="button" class="add-operation-btn" onclick="addOperation()">
                                <i class="bi bi-plus-circle"></i> Add Operation
                            </button>

                            <div class="alert alert-info mt-4">
                                <i class="bi bi-info-circle"></i>
                                <strong>Tip:</strong> You can add multiple operations at once. Each operation will be created with the route order you specify.
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('part-operations.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save All Operations
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Quick Guide -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightbulb"></i> Quick Guide
                        </h6>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0 ps-3">
                            <li class="mb-2">Select part</li>
                            <li class="mb-2">Click "Add Operation"</li>
                            <li class="mb-2">Fill operation details</li>
                            <li class="mb-2">Add more if needed</li>
                            <li class="mb-2">Save all at once</li>
                        </ol>
                    </div>
                </div>

                <!-- Operations Counter -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-list-ol"></i> Operations Count
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="mb-0 text-primary" id="operationCount">0</h2>
                        <small class="text-muted">Total operations</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Operation Row Template (Hidden) -->
<template id="operationTemplate">
    <div class="operation-row" data-index="INDEX">
        <span class="operation-number">Operation INDEX</span>
        <button type="button" class="btn btn-sm btn-danger remove-operation" onclick="removeOperation(INDEX)">
            <i class="bi bi-trash"></i>
        </button>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label">Route Order <span class="text-danger">*</span></label>
                    <input type="number"
                           class="form-control"
                           name="operations[INDEX][route_order]"
                           value="INDEX"
                           min="1"
                           required>
                </div>
            </div>

            <div class="col-md-9">
                <div class="form-group mb-3">
                    <label class="form-label">Division</label>
                    <select class="form-select division-select" name="operations[INDEX][division_id]">
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
                      name="operations[INDEX][operation_data]"
                      rows="3"
                      placeholder="Enter operation details, instructions, parameters, etc."></textarea>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script>
    let operationIndex = 0;
    let choicesInstances = [];

    // Initialize Choices.js for part selection
    const partSelect = new Choices('#part_internal_id', {
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select Part'
    });

    // Add operation function
    function addOperation() {
        operationIndex++;

        // Clone template
        const template = document.getElementById('operationTemplate');
        const clone = template.content.cloneNode(true);

        // Replace INDEX placeholders
        const div = document.createElement('div');
        div.innerHTML = clone.querySelector('.operation-row').outerHTML;
        let html = div.innerHTML;
        html = html.replace(/INDEX/g, operationIndex);

        // Add to container
        const container = document.getElementById('operationsContainer');
        container.insertAdjacentHTML('beforeend', html);

        // Initialize Choices.js for new division select
        const newSelect = container.querySelector(`[name="operations[${operationIndex}][division_id]"]`);
        if (newSelect) {
            const choicesInstance = new Choices(newSelect, {
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Select Division (Optional)'
            });
            choicesInstances.push(choicesInstance);
        }

        // Update counter
        updateCounter();

        // Scroll to new operation
        setTimeout(() => {
            const newRow = container.querySelector(`[data-index="${operationIndex}"]`);
            newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }

    // Remove operation function
    function removeOperation(index) {
        if (confirm('Remove this operation?')) {
            const row = document.querySelector(`[data-index="${index}"]`);
            row.remove();
            updateCounter();
            reorderOperations();
        }
    }

    // Update counter
    function updateCounter() {
        const count = document.querySelectorAll('.operation-row').length;
        document.getElementById('operationCount').textContent = count;
    }

    // Reorder operations (update route_order and labels)
    function reorderOperations() {
        const rows = document.querySelectorAll('.operation-row');
        rows.forEach((row, idx) => {
            const newOrder = idx + 1;
            row.querySelector('.operation-number').textContent = `Operation ${newOrder}`;
            row.querySelector('input[name*="route_order"]').value = newOrder;
        });
    }

    // Form validation
    document.getElementById('operationForm').addEventListener('submit', function(e) {
        const operationCount = document.querySelectorAll('.operation-row').length;

        if (operationCount === 0) {
            e.preventDefault();
            alert('Please add at least one operation!');
            return false;
        }

        const partId = document.getElementById('part_internal_id').value;
        if (!partId) {
            e.preventDefault();
            alert('Please select a part!');
            return false;
        }

        return true;
    });

    // Add first operation on page load
    document.addEventListener('DOMContentLoaded', function() {
        addOperation();
    });
</script>
@endpush
