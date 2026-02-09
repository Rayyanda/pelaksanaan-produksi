@extends('layouts.app')

@section('title', 'Create Batch')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Create Production Batch</h3>
                    <p class="text-subtitle text-muted">Create new batch and auto-generate WIP tracking</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('batches.index') }}">Batches</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
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
                            <form action="{{ route('batches.store') }}" method="POST" id="batchForm">
                                @csrf

                                {{-- <div class="form-group mb-4">
                                    <label for="batch_number" class="form-label">
                                        Batch Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('batch_number') is-invalid @enderror"
                                        id="batch_number" name="batch_number" value="{{ old('batch_number') }}"
                                        placeholder="e.g., BATCH-2024-001" required>
                                    @error('batch_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Unique batch identifier</small>
                                </div> --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="po_production_id" class="form-label">
                                                PO Production <span class="text-danger">*</span>
                                            </label>
                                            <select
                                                class="form-select choices @error('po_production_id') is-invalid @enderror"
                                                id="po_production_id" name="po_production_id" required>
                                                <option value="">Select PO</option>
                                                @foreach ($poProductions as $po)
                                                    <option value="{{ $po->id }}" data-quantity="{{ $po->quantity }}"
                                                        data-due-date="{{ $po->due_date }}"
                                                        {{ old('po_production_id') == $po->id ? 'selected' : '' }}>
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
                                            <select
                                                class="form-select choices @error('part_internal_id') is-invalid @enderror"
                                                id="part_internal_id" name="part_internal_id" required
                                                onchange="loadPartOperations()">
                                                <option value="">Select Part</option>
                                                @foreach ($partInternals as $part)
                                                    <option value="{{ $part->id }}"
                                                        {{ old('part_internal_id') == $part->id ? 'selected' : '' }}>
                                                        {{ $part->part_number }} - {{ $part->part_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('part_internal_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                                class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                                name="quantity" value="{{ old('quantity') }}" min="1"
                                                placeholder="Enter quantity" required>
                                            @error('quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Quantity for this batch</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="target_completed" class="form-label">
                                                Target Completion Date
                                            </label>
                                            <input type="text"
                                                class="form-control flatpickr @error('target_completed') is-invalid @enderror"
                                                id="target_completed" name="target_completed"
                                                value="{{ old('target_completed') }}" placeholder="Select target date">
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
                                                id="part_no_customer" name="part_no_customer"
                                                value="{{ old('part_no_customer') }}"
                                                placeholder="Customer's part number">
                                            @error('part_no_customer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="part_no_customer_source" class="form-label">Customer Part
                                                Source</label>
                                            <input type="url"
                                                class="form-control @error('part_no_customer_source') is-invalid @enderror"
                                                id="part_no_customer_source" name="part_no_customer_source"
                                                value="{{ old('part_no_customer_source') }}"
                                                placeholder="https://example.com/part-spec">
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
                                                id="drawing_number" name="drawing_number"
                                                value="{{ old('drawing_number') }}"
                                                placeholder="Drawing/blueprint number">
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
                                                id="drawing_number_source" name="drawing_number_source"
                                                value="{{ old('drawing_number_source') }}"
                                                placeholder="https://example.com/drawing">
                                            @error('drawing_number_source')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Note:</strong> Creating this batch will automatically generate WIP tracking
                                    records
                                    for all operations defined in the selected part's routing.
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('batches.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Create Batch
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- PO Details Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-file-text"></i> PO Details
                            </h6>
                        </div>
                        <div class="card-body" id="poDetailsCard">
                            <p class="text-muted text-center">
                                <i class="bi bi-info-circle"></i><br>
                                Select a PO to view details
                            </p>
                        </div>
                    </div>

                    <!-- Part Operations Preview -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-diagram-3"></i> Part Operations
                            </h6>
                        </div>
                        <div class="card-body" id="operationsPreview">
                            <p class="text-muted text-center">
                                <i class="bi bi-info-circle"></i><br>
                                Select a part to view operations
                            </p>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightbulb"></i> Quick Guide
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Select PO and Part
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Enter batch quantity
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Set target completion date
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    WIP tracking auto-generated
                                </li>
                            </ul>
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
            dateFormat: 'Y-m-d',
            minDate: 'today'
        });

        document.addEventListener('DOMContentLoaded', () => {

            const poSelect = document.getElementById('po_production_id');

            poSelect.addEventListener('change', loadPODetails);

        });

        function loadPODetails() {

            const select = document.getElementById('po_production_id');
            const value = select.value;
            const container = document.getElementById('poDetailsCard');

            if (!value) {
                container.innerHTML = `
            <p class="text-muted text-center">
                <i class="bi bi-info-circle"></i><br>
                Select a PO to view details
            </p>`;
                return;
            }

            // ⬇️ Ambil OPTION ASLI, bukan DOM hasil Choices.js
            const option = select.querySelector(`option[value="${value}"]`);

            if (!option) {
                console.warn('Option not found for value:', value);
                return;
            }

            fetch(`/api/po/${value}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data);
                        const newQty = data.data.quantity;
                        const newDueDate = data.data.due_date;
                        let html = `
                <div class="mb-3">
                    <small class="text-muted">PO Number</small><br>
                    <strong>${option.text}</strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted">PO Quantity</small><br>
                    <strong>${new Intl.NumberFormat().format(newQty)} pcs</strong>
                </div>
            `;

                        if (newDueDate) {
                            html += `
                    <div class="mb-3">
                        <small class="text-muted">Due Date</small><br>
                        <strong>${newDueDate}</strong>
                    </div>
                `;

                            // Auto-fill target completed
                            const targetCompleted = document.getElementById('target_completed');
                            targetCompleted.value = newDueDate;

                            if (targetCompleted._flatpickr) {
                                targetCompleted._flatpickr.setDate(newDueDate);
                            }
                        }

                        // Suggest quantity
                        document.getElementById('quantity').value = newQty;

                        container.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                });

        }

        // Load PO Details
        // function loadPODetails() {
        //     const select = document.getElementById('po_production_id');
        //     const value = select.value;
        //     // const selectedOption = select.options[select.selectedIndex];
        //     const selectedOption = select.querySelector(`option[value="${value}"]`);
        //     const container = document.getElementById('poDetailsCard');
        //     console.log('yg di select : ' + select.selectedIndex);
        //     console.log(selectedOption.dataset);

        //     if (!select.value) {
        //         container.innerHTML =
        //             '<p class="text-muted text-center"><i class="bi bi-info-circle"></i><br>Select a PO to view details</p>';
        //         return;
        //     }

        //     const quantity = selectedOption.dataset.quantity;
        //     const dueDate = selectedOption.dataset.dueDate;

        //     let html = '<div class="mb-3"><small class="text-muted">PO Number</small><br><strong>' + selectedOption.text +
        //         '</strong></div>';
        //     html += '<div class="mb-3"><small class="text-muted">PO Quantity</small><br><strong>' + new Intl.NumberFormat()
        //         .format(quantity) + ' pcs</strong></div>';

        //     if (dueDate) {
        //         html += '<div class="mb-3"><small class="text-muted">Due Date</small><br><strong>' + dueDate +
        //             '</strong></div>';

        //         // Auto-fill target completed
        //         document.getElementById('target_completed').value = dueDate;
        //         document.getElementById('target_completed')._flatpickr.setDate(dueDate);
        //     }

        //     // Suggest quantity (same as PO)
        //     document.getElementById('quantity').value = quantity;

        //     container.innerHTML = html;
        // }

        // Load Part Operations
        function loadPartOperations() {
            const partId = document.getElementById('part_internal_id').value;
            const container = document.getElementById('operationsPreview');

            if (!partId) {
                container.innerHTML =
                    '<p class="text-muted text-center"><i class="bi bi-info-circle"></i><br>Select a part to view operations</p>';
                return;
            }

            container.innerHTML =
                '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div><br><small>Loading...</small></div>';

            fetch(`/api/part-operations/routing-flow/${partId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.operations.length > 0) {
                        let html =
                            '<div class="alert alert-light mb-2"><small><strong>Operations to be created:</strong></small></div>';
                        html += '<ul class="list-unstyled mb-0">';
                        data.operations.forEach(op => {
                            html += `<li class="mb-2">
                            <span class="badge bg-secondary">${op.route_order}</span>
                            ${op.division ? op.division.name : 'No Division'}
                        </li>`;
                        });
                        html += '</ul>';
                        html += '<hr><small class="text-success"><i class="bi bi-check-circle"></i> ' + data.operations
                            .length + ' WIP tracking records will be created</small>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML =
                            '<div class="alert alert-warning mb-0"><small><i class="bi bi-exclamation-triangle"></i> No operations defined for this part!</small></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML =
                        '<p class="text-danger text-center"><i class="bi bi-x-circle"></i><br><small>Failed to load</small></p>';
                });
        }

        // Auto-load if values exist (from old input)
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('po_production_id').value) {
                loadPODetails();
            }
            if (document.getElementById('part_internal_id').value) {
                loadPartOperations();
            }
        });
    </script>
@endpush
