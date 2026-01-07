@extends('layouts.app')

@section('title', 'Edit PO Production')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit PO Production</h3>
                <p class="text-subtitle text-muted">Update production purchase order - {{ $poProduction->po_number }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('po-productions.index') }}">PO Production</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">PO Production Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('po-productions.update', $poProduction->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="po_number" class="form-label">PO Number <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('po_number') is-invalid @enderror"
                                               id="po_number"
                                               name="po_number"
                                               value="{{ old('po_number', $poProduction->po_number) }}"
                                               placeholder="e.g., PO-2024-001"
                                               required>
                                        @error('po_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Unique identifier for the purchase order</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control @error('quantity') is-invalid @enderror"
                                               id="quantity"
                                               name="quantity"
                                               value="{{ old('quantity', $poProduction->quantity) }}"
                                               min="1"
                                               placeholder="Enter quantity"
                                               required>
                                        @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input type="text"
                                               class="form-control flatpickr @error('due_date') is-invalid @enderror"
                                               id="due_date"
                                               name="due_date"
                                               value="{{ old('due_date', $poProduction->due_date) }}"
                                               placeholder="Select due date">
                                        @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="po_source" class="form-label">PO Source (URL/API)</label>
                                        <div class="input-group">
                                            <input type="url"
                                                   class="form-control @error('po_source') is-invalid @enderror"
                                                   id="po_source"
                                                   name="po_source"
                                                   value="{{ old('po_source', $poProduction->po_source) }}"
                                                   placeholder="https://example.com/api/po/123">
                                            @if($poProduction->po_source)
                                            <a href="{{ $poProduction->po_source }}" target="_blank" class="btn btn-outline-secondary">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            @endif
                                        </div>
                                        @error('po_source')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Link or API endpoint to fetch latest PO data</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="po_snapshot" class="form-label">PO Snapshot (JSON)</label>
                                        <textarea class="form-control @error('po_snapshot') is-invalid @enderror"
                                                  id="po_snapshot"
                                                  name="po_snapshot"
                                                  rows="10"
                                                  placeholder='{"item": "Product Name", "vendor": "Vendor Name", "price": 10000}'
                                                  style="font-family: monospace;">{{ old('po_snapshot', $poProduction->po_snapshot ? json_encode(json_decode($poProduction->po_snapshot), JSON_PRETTY_PRINT) : '') }}</textarea>
                                        @error('po_snapshot')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Snapshot data from external PO module in JSON format (optional)</small>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="validateJSON()">
                                                <i class="bi bi-check-circle"></i> Validate JSON
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="formatJSON()">
                                                <i class="bi bi-code-square"></i> Format JSON
                                            </button>
                                            @if($poProduction->po_source)
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshSnapshot()">
                                                <i class="bi bi-arrow-clockwise"></i> Refresh from Source
                                            </button>
                                            @endif
                                        </div>
                                        <div id="jsonStatus" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Note:</strong> The PO snapshot will store a copy of the PO data from the external module.
                                    Use the PO Source field to specify where to fetch the latest data when needed.
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('po-productions.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update PO Production
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Record Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Created At</small>
                            <strong>{{ $poProduction->created_at->format('d M Y, H:i') }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $poProduction->updated_at->format('d M Y, H:i') }}</strong>
                        </div>
                        @if($poProduction->deleted_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Deleted At</small>
                            <strong class="text-danger">{{ $poProduction->deleted_at->format('d M Y, H:i') }}</strong>
                        </div>
                        @endif

                        @if($poProduction->due_date)
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted d-block">Due Date Status</small>
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($poProduction->due_date), false);
                            @endphp
                            @if($daysLeft < 0)
                                <span class="badge bg-danger">Overdue by {{ abs($daysLeft) }} days</span>
                            @elseif($daysLeft == 0)
                                <span class="badge bg-warning">Due Today!</span>
                            @elseif($daysLeft <= 7)
                                <span class="badge bg-warning">{{ $daysLeft }} days remaining</span>
                            @else
                                <span class="badge bg-success">{{ $daysLeft }} days remaining</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                @if($poProduction->po_snapshot)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Snapshot Preview</h5>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Current snapshot contains:</small>
                        <ul class="list-unstyled mt-2">
                            @php
                                $snapshot = json_decode($poProduction->po_snapshot, true);
                                $fields = is_array($snapshot) ? array_keys($snapshot) : [];
                            @endphp
                            @foreach(array_slice($fields, 0, 5) as $field)
                                <li><i class="bi bi-check-circle text-success"></i> {{ $field }}</li>
                            @endforeach
                            @if(count($fields) > 5)
                                <li class="text-muted">... and {{ count($fields) - 5 }} more fields</li>
                            @endif
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
<script>
    // Initialize Flatpickr for date picker
    flatpickr('.flatpickr', {
        dateFormat: 'Y-m-d'
    });

    // JSON validation function
    function validateJSON() {
        const textarea = document.getElementById('po_snapshot');
        const statusDiv = document.getElementById('jsonStatus');
        const value = textarea.value.trim();

        if (!value) {
            statusDiv.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> JSON is empty</div>';
            return false;
        }

        try {
            JSON.parse(value);
            statusDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> Valid JSON format!</div>';
            return true;
        } catch (e) {
            statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Invalid JSON: ' + e.message + '</div>';
            return false;
        }
    }

    // JSON formatting function
    function formatJSON() {
        const textarea = document.getElementById('po_snapshot');
        const statusDiv = document.getElementById('jsonStatus');
        const value = textarea.value.trim();

        if (!value) {
            statusDiv.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> JSON is empty</div>';
            return;
        }

        try {
            const parsed = JSON.parse(value);
            textarea.value = JSON.stringify(parsed, null, 4);
            statusDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> JSON formatted successfully!</div>';
        } catch (e) {
            statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Cannot format: ' + e.message + '</div>';
        }
    }

    // Refresh snapshot from source
    function refreshSnapshot() {
        const poSource = document.getElementById('po_source').value;
        const statusDiv = document.getElementById('jsonStatus');

        if (!poSource) {
            statusDiv.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> PO Source URL is empty</div>';
            return;
        }

        statusDiv.innerHTML = '<div class="alert alert-info"><i class="bi bi-arrow-clockwise"></i> Fetching data from source...</div>';

        // TODO: Implement actual API call to fetch data
        // This is a placeholder - you'll need to implement the actual fetch logic based on your API
        fetch(poSource)
            .then(response => response.json())
            .then(data => {
                document.getElementById('po_snapshot').value = JSON.stringify(data, null, 4);
                statusDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> Snapshot refreshed successfully!</div>';
            })
            .catch(error => {
                statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Failed to fetch: ' + error.message + '</div>';
            });
    }

    // Auto-hide status messages
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    setTimeout(function() {
                        const statusDiv = document.getElementById('jsonStatus');
                        if (statusDiv.innerHTML) {
                            setTimeout(function() {
                                statusDiv.innerHTML = '';
                            }, 5000);
                        }
                    }, 100);
                }
            });
        });

        observer.observe(document.getElementById('jsonStatus'), { childList: true });
    });
</script>
@endpush
