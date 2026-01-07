@extends('layouts.app')

@section('title', 'PO Production')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>PO Production Management</h3>
                <p class="text-subtitle text-muted">Manage production purchase orders</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">PO Production</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">PO Production List</h5>
                    <a href="{{ route('po-productions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New PO
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="poProductionTable">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Quantity</th>
                                <th>Due Date</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($poProductions as $po)
                            <tr>
                                <td>
                                    <strong>{{ $po->po_number }}</strong>
                                    @if($po->po_snapshot)
                                    <i class="bi bi-file-earmark-check text-success" data-bs-toggle="tooltip" title="Has snapshot"></i>
                                    @endif
                                </td>
                                <td>{{ number_format($po->quantity) }}</td>
                                <td>
                                    @if($po->due_date)
                                        {{ \Carbon\Carbon::parse($po->due_date)->format('d M Y') }}
                                        @php
                                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($po->due_date), false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Overdue</span>
                                        @elseif($daysLeft <= 7)
                                            <span class="badge bg-warning">{{ round($daysLeft) }} days left</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($po->po_source)
                                        <a href="{{ $po->po_source }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-link-45deg"></i> View Source
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($po->deleted_at)
                                        <span class="badge bg-secondary">Deleted</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        {{-- <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $po->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button> --}}
                                        <a href="{{ route('po-productions.show',$po->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('po-productions.edit', $po->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('po-productions.destroy', $po->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure want to delete this PO?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            {{-- <div class="modal fade" id="viewModal{{ $po->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModal{{ $po->id }}Title" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">PO Details - {{ $po->po_number }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="30%">PO Number</th>
                                                    <td>{{ $po->po_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Quantity</th>
                                                    <td>{{ number_format($po->quantity) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Due Date</th>
                                                    <td>{{ $po->due_date ? \Carbon\Carbon::parse($po->due_date)->format('d M Y') : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>PO Source</th>
                                                    <td>
                                                        @if($po->po_source)
                                                            <a href="{{ $po->po_source }}" target="_blank">{{ $po->po_source }}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Created At</th>
                                                    <td>{{ $po->created_at->format('d M Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Updated At</th>
                                                    <td>{{ $po->updated_at->format('d M Y H:i') }}</td>
                                                </tr>
                                            </table>

                                            @if($po->po_snapshot)
                                            <hr>
                                            <h6>PO Snapshot Data</h6>
                                            <div class="alert alert-light">
                                                <pre class="mb-0" style="max-height: 300px; overflow-y: auto;">{{ json_encode(json_decode($po->po_snapshot), JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#poProductionTable').DataTable({
            responsive: true,
            order: [[0, 'desc']]
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
