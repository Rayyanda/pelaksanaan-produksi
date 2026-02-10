@extends('layouts.app')

@section('title', 'Create Division')
@section('page-title', 'Create New Division')

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
        <form action="{{ route('divisions.store') }}" method="POST">
            @csrf
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
                                            value="{{ old('name') }}" placeholder="e.g. Finishing Department" required>
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
                                            value="{{ old('code') }}" placeholder="Auto-generated if empty">
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                    placeholder="Description about this division">{{ old('description') }}</textarea>
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
                                <!-- Area rows will be added here -->
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="card">
                        <div class="card-footer text-end">
                            <a href="{{ route('divisions.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Division</button>
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

        function addAreaRow() {
            const container = document.getElementById('areasContainer');
            const areaRow = document.createElement('div');
            areaRow.className = 'area-row';
            areaRow.dataset.index = areaIndex;

            areaRow.innerHTML = `
        <div class="area-row-header">
            <h6 class="mb-0">Area #${areaIndex + 1}</h6>
            <button type="button" class="btn btn-sm btn-danger remove-area-btn" onclick="removeAreaRow(this)">
                <i class="bi bi-trash"></i> Remove
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Area Name <span class="text-danger">*</span></label>
                    <input type="text" name="areas[${areaIndex}][name]" 
                           class="form-control" placeholder="e.g. Polishing" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Foreman</label>
                    <select name="areas[${areaIndex}][foreman_id]" class="form-select foreman-select-${areaIndex}">
                        <option value="">Select Foreman (Optional)</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
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
                           class="form-control" min="0" placeholder="1">
                    <small class="text-muted">Urutan proses</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="areas[${areaIndex}][capacity]" 
                           class="form-control" min="0" placeholder="100">
                    <small class="text-muted">Unit per durasi</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Operator Count</label>
                    <input type="number" name="areas[${areaIndex}][operator_count]" 
                           class="form-control" min="0" placeholder="5">
                    <small class="text-muted">Jumlah operator</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Duration (min)</label>
                    <input type="number" name="areas[${areaIndex}][duration]" 
                           class="form-control" min="0" placeholder="30">
                    <small class="text-muted">Per unit (menit)</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Equipment</label>
                    <input type="text" name="areas[${areaIndex}][equipment]" 
                           class="form-control" placeholder="e.g. Polishing Machine">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="areas[${areaIndex}][description]" 
                              class="form-control" rows="2" placeholder="Detail area/proses"></textarea>
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

        function removeAreaRow(button) {
            const areaRow = button.closest('.area-row');
            if (areaRow) {
                areaRow.remove();
                updateAreaNumbers();
            }
        }

        function updateAreaNumbers() {
            const areaRows = document.querySelectorAll('.area-row');
            areaRows.forEach((row, index) => {
                row.querySelector('.area-row-header h6').textContent = `Area #${index + 1}`;
            });
        }

        // Add event listener to Add Area button
        document.getElementById('addAreaBtn').addEventListener('click', addAreaRow);

        // Add first area by default
        addAreaRow();
    </script>
@endpush
