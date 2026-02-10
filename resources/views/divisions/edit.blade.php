@extends('layouts.app')

@section('title', 'Edit Division')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <style>
        .area-row {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
            position: relative;
        }

        .area-row-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .remove-area-btn {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <form action="{{ route('divisions.update', $division->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden input for deleted areas -->
            <input type="hidden" name="deleted_areas_json" id="deletedAreasJson" value="[]">

            <div class="row">
                <div class="col-lg-12">
                    <!-- Division Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Division Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Division Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ $division->name ?? old('name') }}"
                                            placeholder="e.g. Finishing Department" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Code</label>
                                        <input type="text" name="code"
                                            class="form-control @error('code') is-invalid @enderror"
                                            value="{{ $division->code ?? old('code') }}"
                                            placeholder="Auto-generated if empty" readonly>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                    placeholder="Description about this division">{{ $division->description ?? old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Areas Card -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Areas (Sub-Divisions)</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="addAreaBtn">
                                <i class="bi bi-plus-circle"></i> Add Area
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="areasContainer">
                                <!-- Existing areas will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="card">
                        <div class="card-footer text-end">
                            <a href="{{ route('divisions.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Division</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script>
        let areaIndex = 0;
        const choicesInstances = [];
        const deletedAreas = [];

        // Existing areas from server
        const existingAreas = @json($division->areas);

        function addAreaRow(areaData = null) {
            const container = document.getElementById('areasContainer');
            const areaRow = document.createElement('div');
            areaRow.className = 'area-row';
            areaRow.dataset.index = areaIndex;

            const isExisting = areaData !== null;
            const areaId = isExisting ? areaData.id : '';
            const areaName = isExisting ? areaData.name : '';
            const foremanId = isExisting ? (areaData.foreman_id || '') : '';
            const processOrder = isExisting ? (areaData.process_order || '') : '';
            const capacity = isExisting ? (areaData.capacity || '') : '';
            const operatorCount = isExisting ? (areaData.operator_count || '') : '';
            const duration = isExisting ? (areaData.duration || '') : '';
            const equipment = isExisting ? (areaData.equipment || '') : '';
            const description = isExisting ? (areaData.description || '') : '';

            areaRow.innerHTML = `
        ${areaId ? `<input type="hidden" name="areas[${areaIndex}][id]" value="${areaId}">` : ''}
        <div class="area-row-header">
            <h6 class="mb-0">Area #${areaIndex + 1}</h6>
            <button type="button" class="btn btn-sm btn-danger remove-area-btn" 
                    onclick="removeAreaRow(this, ${areaId})" data-area-id="${areaId}">
                <i class="bi bi-trash"></i> Remove
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Area Name <span class="text-danger">*</span></label>
                    <input type="text" name="areas[${areaIndex}][name]" 
                           class="form-control" placeholder="e.g. Polishing" value="${areaName}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Foreman</label>
                    <select name="areas[${areaIndex}][foreman_id]" class="form-select foreman-select-${areaIndex}">
                        <option value="">Select Foreman (Optional)</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" ${foremanId == {{ $user->id }} ? 'selected' : ''}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Process Order</label>
                    <input type="number" name="areas[${areaIndex}][process_order]" 
                           class="form-control" min="0" placeholder="1" value="${processOrder}">
                    <small class="text-muted">Urutan proses</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="areas[${areaIndex}][capacity]" 
                           class="form-control" min="0" placeholder="100" value="${capacity}">
                    <small class="text-muted">Unit per durasi</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Operator Count</label>
                    <input type="number" name="areas[${areaIndex}][operator_count]" 
                           class="form-control" min="0" placeholder="5" value="${operatorCount}">
                    <small class="text-muted">Jumlah operator</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Duration (min)</label>
                    <input type="number" name="areas[${areaIndex}][duration]" 
                           class="form-control" min="0" placeholder="30" value="${duration}">
                    <small class="text-muted">Per unit (menit)</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Equipment</label>
                    <input type="text" name="areas[${areaIndex}][equipment]" 
                           class="form-control" placeholder="e.g. Polishing Machine" value="${equipment}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="areas[${areaIndex}][description]" 
                              class="form-control" rows="2" placeholder="Detail area/proses">${description}</textarea>
                </div>
            </div>
        </div>
    `;

            container.appendChild(areaRow);

            // Initialize Choices.js for the new select
            const selectElement = document.querySelector(`.foreman-select-${areaIndex}`);
            if (selectElement) {
                const choicesInstance = new Choices(selectElement, {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Select Foreman (Optional)'
                });
                choicesInstances.push(choicesInstance);
            }

            areaIndex++;
        }

        function removeAreaRow(button, areaId) {
            const areaRow = button.closest('.area-row');
            if (areaRow) {
                // If it's an existing area, add to deleted list
                if (areaId) {
                    deletedAreas.push(areaId);
                    updateDeletedAreasInput();
                }

                areaRow.remove();
                updateAreaNumbers();
            }
        }

        function updateDeletedAreasInput() {
            // Remove existing hidden inputs for deleted areas
            document.querySelectorAll('input[name^="deleted_areas["]').forEach(el => el.remove());

            // Add new hidden inputs for each deleted area
            const form = document.querySelector('form');
            deletedAreas.forEach((areaId, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `deleted_areas[${index}]`;
                input.value = areaId;
                form.appendChild(input);
            });
        }

        function updateAreaNumbers() {
            const areaRows = document.querySelectorAll('.area-row');
            areaRows.forEach((row, index) => {
                row.querySelector('.area-row-header h6').textContent = `Area #${index + 1}`;
            });
        }

        // Add event listener to Add Area button
        document.getElementById('addAreaBtn').addEventListener('click', () => addAreaRow());

        // Load existing areas
        existingAreas.forEach(area => {
            addAreaRow(area);
        });

        // If no areas exist, add one empty row
        if (existingAreas.length === 0) {
            addAreaRow();
        }
    </script>
@endpush
