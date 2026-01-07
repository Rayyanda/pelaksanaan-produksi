@extends('layouts.app')

@section('title', 'PO Production Detail')

@push('styles')
<style>
    .info-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .json-viewer {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        max-height: 500px;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
    }
    .timeline-item {
        padding-left: 2rem;
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #435ebe;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #435ebe;
    }
    .timeline-item:after {
        content: '';
        position: absolute;
        left: 5px;
        top: 12px;
        width: 2px;
        height: calc(100% - 12px);
        background: #dee2e6;
    }
    .timeline-item:last-child:after {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>PO Production Detail</h3>
                <p class="text-subtitle text-muted">Detailed information of {{ $poProduction->po_number }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('po-productions.index') }}">PO Production</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Basic Information</h4>
                        <div>
                            @if($poProduction->deleted_at)
                                <span class="badge bg-danger">Deleted</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-label">PO Number</div>
                                <div class="info-value">
                                    <strong class="text-primary">{{ $poProduction->po_number }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Quantity</div>
                                <div class="info-value">
                                    <i class="bi bi-box-seam"></i> {{ number_format($poProduction->quantity) }} units
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-label">Due Date</div>
                                <div class="info-value">
                                    @if($poProduction->due_date)
                                        <i class="bi bi-calendar-event"></i>
                                        {{ \Carbon\Carbon::parse($poProduction->due_date)->format('d F Y') }}

                                        @php
                                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($poProduction->due_date), false);
                                        @endphp

                                        <div class="mt-2">
                                            @if($daysLeft < 0)
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-exclamation-triangle"></i> Overdue by {{ abs($daysLeft) }} days
                                                </span>
                                            @elseif($daysLeft == 0)
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-alarm"></i> Due Today!
                                                </span>
                                            @elseif($daysLeft <= 7)
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock"></i> {{ $daysLeft }} days remaining
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> {{ $daysLeft }} days remaining
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No due date set</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">PO Source</div>
                                <div class="info-value">
                                    @if($poProduction->po_source)
                                        <a href="{{ $poProduction->po_source }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-link-45deg"></i> Open Source URL
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="refreshSnapshot()">
                                            <i class="bi bi-arrow-clockwise"></i> Refresh Data
                                        </button>
                                    @else
                                        <span class="text-muted">No source configured</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PO Snapshot Card -->
                @if($poProduction->po_snapshot)
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-file-earmark-code"></i> PO Snapshot Data
                            </h4>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copySnapshot()">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="downloadSnapshot()">
                                    <i class="bi bi-download"></i> Download
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="refreshStatus" class="mb-3"></div>

                        <!-- Snapshot Statistics -->
                        @php
                            $snapshot = json_decode($poProduction->po_snapshot, true);
                            $fieldCount = is_array($snapshot) ? count($snapshot) : 0;
                        @endphp

                        @if($fieldCount > 0)
                        <div class="alert alert-light mb-3">
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-info-circle"></i> Total Fields: <strong>{{ $fieldCount }}</strong></span>
                                <span>Last Updated: <strong>{{ $poProduction->updated_at->diffForHumans() }}</strong></span>
                            </div>
                        </div>
                        @endif

                        <!-- JSON Viewer -->
                        <div class="json-viewer" id="snapshotData">
                            <pre class="mb-0">{{ json_encode(json_decode($poProduction->po_snapshot), JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h5 class="mt-3 text-muted">No Snapshot Data Available</h5>
                        <p class="text-muted">This PO doesn't have any snapshot data yet.</p>
                        @if($poProduction->po_source)
                        <button type="button" class="btn btn-primary" onclick="refreshSnapshot()">
                            <i class="bi bi-arrow-clockwise"></i> Fetch from Source
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Actions Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('po-productions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <div>
                                <a href="{{ route('po-productions.edit', $poProduction->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('po-productions.destroy', $poProduction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure want to delete this PO?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Timeline Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history"></i> Activity Timeline
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-item">
                            <small class="text-muted">Created</small>
                            <div><strong>{{ $poProduction->created_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $poProduction->created_at->diffForHumans() }}</small>
                        </div>

                        <div class="timeline-item">
                            <small class="text-muted">Last Updated</small>
                            <div><strong>{{ $poProduction->updated_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $poProduction->updated_at->diffForHumans() }}</small>
                        </div>

                        @if($poProduction->deleted_at)
                        <div class="timeline-item">
                            <small class="text-muted text-danger">Deleted</small>
                            <div><strong class="text-danger">{{ $poProduction->deleted_at->format('d M Y, H:i') }}</strong></div>
                            <small class="text-muted">{{ $poProduction->deleted_at->diffForHumans() }}</small>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up"></i> Quick Stats
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Status</span>
                                @if($poProduction->deleted_at)
                                    <span class="badge bg-danger">Deleted</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Has Snapshot</span>
                                @if($poProduction->po_snapshot)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Yes
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle"></i> No
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Has Source</span>
                                @if($poProduction->po_source)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Yes
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle"></i> No
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($poProduction->due_date)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Days Until Due</span>
                                @php
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($poProduction->due_date), false);
                                @endphp
                                @if($daysLeft < 0)
                                    <span class="badge bg-danger">{{ abs($daysLeft) }} overdue</span>
                                @else
                                    <span class="badge bg-info">{{ $daysLeft }} days</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Snapshot Fields Preview (if available) -->
                @if($poProduction->po_snapshot)
                @php
                    $snapshot = json_decode($poProduction->po_snapshot, true);
                    $fields = is_array($snapshot) ? array_keys($snapshot) : [];
                @endphp
                @if(count($fields) > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul"></i> Snapshot Fields
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach(array_slice($fields, 0, 10) as $field)
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <code>{{ $field }}</code>
                                </li>
                            @endforeach
                            @if(count($fields) > 10)
                                <li class="text-muted">
                                    <i class="bi bi-three-dots"></i>
                                    and {{ count($fields) - 10 }} more fields
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Refresh snapshot from source
    function refreshSnapshot() {
        const statusDiv = document.getElementById('refreshStatus');
        statusDiv.innerHTML = '<div class="alert alert-info"><i class="bi bi-arrow-clockwise"></i> Fetching data from source...</div>';

        fetch('{{ route("po-productions.refresh-snapshot", $poProduction->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusDiv.innerHTML = '<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> ' + data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';

                // Update the snapshot display
                document.getElementById('snapshotData').innerHTML = '<pre class="mb-0">' + JSON.stringify(data.data, null, 4) + '</pre>';

                // Reload page after 2 seconds
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                statusDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-x-circle"></i> ' + data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            }
        })
        .catch(error => {
            statusDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-x-circle"></i> Failed to refresh: ' + error.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        });
    }

    // Copy snapshot to clipboard
    function copySnapshot() {
        const snapshotText = document.querySelector('#snapshotData pre').textContent;

        navigator.clipboard.writeText(snapshotText).then(() => {
            // Show success message
            const statusDiv = document.getElementById('refreshStatus');
            statusDiv.innerHTML = '<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> Snapshot copied to clipboard!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';

            setTimeout(() => {
                statusDiv.innerHTML = '';
            }, 3000);
        }).catch(err => {
            alert('Failed to copy: ' + err);
        });
    }

    // Download snapshot as JSON file
    function downloadSnapshot() {
        const snapshotText = document.querySelector('#snapshotData pre').textContent;
        const blob = new Blob([snapshotText], { type: 'application/json' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'po_snapshot_{{ $poProduction->po_number }}_{{ date("Y-m-d") }}.json';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }
</script>
@endpush
