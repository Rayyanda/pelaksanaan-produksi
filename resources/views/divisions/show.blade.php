@extends('layouts.app')

@section('title', 'Division Details')
@section('page-title', 'Division: ' . $division->name)

@section('content')
    <section class="section">
        <div class="row">
            <!-- Division Information -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Division Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Name:</th>
                                <td>{{ $division->name }}</td>
                            </tr>
                            <tr>
                                <th>Code:</th>
                                <td><code>{{ $division->code }}</code></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $division->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if ($division->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Total Areas:</th>
                                <td><span class="badge bg-info">{{ $division->areas->count() }}</span></td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $division->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated:</th>
                                <td>{{ $division->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-warning w-100">
                            <i class="bi bi-pencil"></i> Edit Division
                        </a>
                    </div>
                </div>
            </div>

            <!-- Areas List -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Areas (Sub-Divisions)</h5>
                        <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Manage Areas
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($division->areas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Area Name</th>
                                            <th>Process Order</th>
                                            <th>Capacity</th>
                                            <th>Operators</th>
                                            <th>Duration</th>
                                            <th>Foreman</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($division->areas->sortBy('process_order') as $area)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $area->name }}</strong>
                                                    @if ($area->description)
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($area->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($area->process_order)
                                                        <span class="badge bg-secondary">{{ $area->process_order }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $area->capacity ?? '-' }}</td>
                                                <td>{{ $area->operator_count ?? '-' }}</td>
                                                <td>
                                                    @if ($area->duration)
                                                        {{ $area->duration }} min
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($area->foreman)
                                                        <span class="badge bg-primary">{{ $area->foreman->name }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($area->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Area Details Cards (Optional) -->
                            <div class="row mt-3">
                                @foreach ($division->areas->sortBy('process_order') as $area)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    {{ $area->name }}
                                                    @if ($area->process_order)
                                                        <span class="badge bg-secondary float-end">Order:
                                                            {{ $area->process_order }}</span>
                                                    @endif
                                                </h6>
                                                @if ($area->description)
                                                    <p class="card-text text-muted small">{{ $area->description }}</p>
                                                @endif
                                                <div class="row small">
                                                    <div class="col-6">
                                                        <strong>Capacity:</strong> {{ $area->capacity ?? '-' }}<br>
                                                        <strong>Operators:</strong> {{ $area->operator_count ?? '-' }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Duration:</strong>
                                                        {{ $area->duration ? $area->duration . ' min' : '-' }}<br>
                                                        <strong>Equipment:</strong> {{ $area->equipment ?? '-' }}
                                                    </div>
                                                </div>
                                                @if ($area->foreman)
                                                    <div class="mt-2">
                                                        <span class="badge bg-primary">
                                                            <i class="bi bi-person-badge"></i> {{ $area->foreman->name }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No areas have been created for this division yet.
                                <a href="{{ route('divisions.edit', $division->id) }}" class="alert-link">Add areas now</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row mt-3">
            <div class="col-12">
                <a href="{{ route('divisions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Divisions List
                </a>
            </div>
        </div>
    </section>
@endsection
