@extends('layouts.app')

@section('title', 'Create Part Internal with Operations')

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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .section-divider {
            border-top: 3px solid #435ebe;
            margin: 2rem 0;
            padding-top: 2rem;
        }

        .process-details {
            background: #f0f8ff;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #435ebe;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Add Part Internal with Operations</h3>
                    <p class="text-subtitle text-muted">Create new part internal data and its operations</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('part-internals.index') }}">Part Internals</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Add New</li>
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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-9">
                    <form action="{{ route('part-internals.store-with-operations') }}" method="POST" id="mainForm">
                        @csrf

                        <!-- PART INTERNAL SECTION -->
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-gear"></i> Part Internal Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="part_number" class="form-label">Part Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('part_number') is-invalid @enderror"
                                                id="part_number" name="part_number" value="{{ old('part_number') }}"
                                                required>
                                            @error('part_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nointernal" class="form-label">No Internal</label>
                                            <input type="number"
                                                class="form-control @error('nointernal') is-invalid @enderror"
                                                id="nointernal" name="nointernal" value="{{ old('nointernal') }}">
                                            @error('nointernal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="part_name" class="form-label">Part Name</label>
                                            <input type="text"
                                                class="form-control @error('part_name') is-invalid @enderror" id="part_name"
                                                name="part_name" value="{{ old('part_name') }}">
                                            @error('part_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="matl_spec" class="form-label">Material Spec</label>
                                            <input type="text"
                                                class="form-control @error('matl_spec') is-invalid @enderror" id="matl_spec"
                                                name="matl_spec" value="{{ old('matl_spec') }}">
                                            @error('matl_spec')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="matl_req" class="form-label">Material Required</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('matl_req') is-invalid @enderror"
                                                id="matl_req" name="matl_req" value="{{ old('matl_req') }}">
                                            @error('matl_req')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="comp_per_mould" class="form-label">Component Per Mould</label>
                                            <input type="number"
                                                class="form-control @error('comp_per_mould') is-invalid @enderror"
                                                id="comp_per_mould" name="comp_per_mould"
                                                value="{{ old('comp_per_mould') }}">
                                            @error('comp_per_mould')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="deoxidation" class="form-label">Deoxidation</label>
                                    <textarea class="form-control @error('deoxidation') is-invalid @enderror" id="deoxidation" name="deoxidation"
                                        rows="3">{{ old('deoxidation') }}</textarea>
                                    @error('deoxidation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h6 class="mb-3 mt-4">Target Quantities (Week)</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_waxing" class="form-label">Waxing</label>
                                            <input type="number" class="form-control" id="target_qty_waxing"
                                                name="target_qty_waxing" value="{{ old('target_qty_waxing') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_mould_room" class="form-label">Mould Room</label>
                                            <input type="number" class="form-control" id="target_qty_mould_room"
                                                name="target_qty_mould_room" value="{{ old('target_qty_mould_room') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_melting" class="form-label">Melting</label>
                                            <input type="number" class="form-control" id="target_qty_melting"
                                                name="target_qty_melting" value="{{ old('target_qty_melting') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_heat_treatment" class="form-label">Heat
                                                Treatment</label>
                                            <input type="number" class="form-control" id="target_qty_heat_treatment"
                                                name="target_qty_heat_treatment"
                                                value="{{ old('target_qty_heat_treatment') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_cut_off" class="form-label">Cut Off</label>
                                            <input type="number" class="form-control" id="target_qty_cut_off"
                                                name="target_qty_cut_off" value="{{ old('target_qty_cut_off') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_finishing" class="form-label">Finishing</label>
                                            <input type="number" class="form-control" id="target_qty_finishing"
                                                name="target_qty_finishing" value="{{ old('target_qty_finishing') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_machining" class="form-label">Machining</label>
                                            <input type="number" class="form-control" id="target_qty_machining"
                                                name="target_qty_machining" value="{{ old('target_qty_machining') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="target_qty_quality_control" class="form-label">Quality
                                                Control</label>
                                            <input type="number" class="form-control" id="target_qty_quality_control"
                                                name="target_qty_quality_control"
                                                value="{{ old('target_qty_quality_control') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PART OPERATIONS SECTION -->
                        <div class="card shadow mt-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-list-check"></i> Part Operations
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6>Operations List</h6>
                                    <p class="text-muted">Add all operations for this part</p>
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
                                    <strong>Tip:</strong> You can add multiple operations at once. Each operation will be
                                    created with the route order you specify.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('part-internals.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Save Part & All Operations
                            </button>
                        </div>
                    </form>
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
                                <li class="mb-2">Fill part information</li>
                                <li class="mb-2">Set target quantities</li>
                                <li class="mb-2">Click "Add Operation"</li>
                                <li class="mb-2">Select division & area</li>
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
                        <input type="number" class="form-control" name="operations[INDEX][route_order]" value="INDEX"
                            min="1" required>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label class="form-label">Division</label>
                        <select class="form-select division-select" name="operations[INDEX][division_id]"
                            data-index="INDEX" onchange="loadAreas(INDEX)">
                            <option value="">Select Division (Optional)</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label">Area</label>
                        <select class="form-select area-select" name="operations[INDEX][area_id]" data-index="INDEX"
                            onchange="toggleProcessFields(INDEX)">
                            <option value="">Select Area (Optional)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Operation Data</label>
                <textarea class="form-control" name="operations[INDEX][operation_data]" rows="2"
                    placeholder="Enter operation details, instructions, parameters, etc."></textarea>
            </div>

            <!-- Process Details Section (Hidden by default, shown when area is selected) -->
            <div class="process-details" id="processDetails_INDEX" style="display: none;">
                <hr class="my-3">
                <h6 class="text-primary mb-3">
                    <i class="bi bi-gear-fill"></i> Process Details
                </h6>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Process Order</label>
                            <input type="number" class="form-control" name="operations[INDEX][process_order]"
                                min="1" placeholder="Order">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="form-control" name="operations[INDEX][capacity]" min="0"
                                placeholder="Units">
                            <small class="text-muted">Per duration in minutes</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Duration</label>
                            <input type="number" class="form-control" name="operations[INDEX][duration]" min="0"
                                placeholder="Minutes">
                            <small class="text-muted">Per unit</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label">Operator Count</label>
                            <input type="number" class="form-control" name="operations[INDEX][operator_count]"
                                min="0" placeholder="Count">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group mb-3">
                            <label class="form-label">Equipment / Machine</label>
                            <input type="text" class="form-control" name="operations[INDEX][equipment]"
                                placeholder="e.g., Mesin Wax Injection A, CNC Machine B">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="operations[INDEX][is_active]"
                                    value="1" checked>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script>
        let operationIndex = 0;
        let choicesInstances = {};

        // Area data by division (passed from controller)
        const areasByDivision = @json($areasByDivision ?? []);

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

            // Initialize Choices.js for new selects
            const divisionSelect = container.querySelector(`select[name="operations[${operationIndex}][division_id]"]`);
            const areaSelect = container.querySelector(`select[name="operations[${operationIndex}][area_id]"]`);

            if (divisionSelect) {
                choicesInstances[`division_${operationIndex}`] = new Choices(divisionSelect, {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Select Division (Optional)'
                });
            }

            if (areaSelect) {
                choicesInstances[`area_${operationIndex}`] = new Choices(areaSelect, {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Select Area (Optional)'
                });
            }

            // Update counter
            updateCounter();

            // Scroll to new operation
            setTimeout(() => {
                const newRow = container.querySelector(`[data-index="${operationIndex}"]`);
                newRow.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 100);
        }

        // Load areas based on selected division
        function loadAreas(index) {
            const divisionSelect = document.querySelector(`select[name="operations[${index}][division_id]"]`);
            const areaSelect = document.querySelector(`select[name="operations[${index}][area_id]"]`);

            const divisionId = divisionSelect.value;

            // Get Choices instance for area
            const areaChoices = choicesInstances[`area_${index}`];

            if (!divisionId) {
                // Clear and disable area select
                areaChoices.clearStore();
                areaChoices.setChoices([{
                    value: '',
                    label: 'Select Area (Optional)',
                    selected: true
                }], 'value', 'label', true);
                areaSelect.disabled = true;

                // Hide process fields
                toggleProcessFields(index);
                return;
            }

            // Get areas for selected division
            const areas = areasByDivision[divisionId] || [];

            // Clear current choices
            areaChoices.clearStore();

            // Add default option
            const choices = [{
                value: '',
                label: 'Select Area (Optional)',
                selected: true
            }];

            // Add areas for this division
            areas.forEach(area => {
                choices.push({
                    value: area.id,
                    label: area.name,
                    selected: false
                });
            });

            // Set new choices
            areaChoices.setChoices(choices, 'value', 'label', true);

            // Enable area select
            areaSelect.disabled = false;
        }

        // Toggle process fields visibility based on area selection
        function toggleProcessFields(index) {
            const areaSelect = document.querySelector(`select[name="operations[${index}][area_id]"]`);
            const processDetailsDiv = document.getElementById(`processDetails_${index}`);

            if (processDetailsDiv) {
                if (areaSelect && areaSelect.value) {
                    // Show process fields with animation
                    processDetailsDiv.style.display = 'block';
                    processDetailsDiv.style.animation = 'fadeIn 0.3s ease-in';
                } else {
                    // Hide process fields
                    processDetailsDiv.style.display = 'none';
                }
            }
        }

        // Remove operation function
        function removeOperation(index) {
            if (confirm('Remove this operation?')) {
                const row = document.querySelector(`[data-index="${index}"]`);

                // Destroy Choices instances
                if (choicesInstances[`division_${index}`]) {
                    choicesInstances[`division_${index}`].destroy();
                    delete choicesInstances[`division_${index}`];
                }
                if (choicesInstances[`area_${index}`]) {
                    choicesInstances[`area_${index}`].destroy();
                    delete choicesInstances[`area_${index}`];
                }

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
        document.getElementById('mainForm').addEventListener('submit', function(e) {
            const operationCount = document.querySelectorAll('.operation-row').length;

            if (operationCount === 0) {
                e.preventDefault();
                alert('Please add at least one operation!');
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
