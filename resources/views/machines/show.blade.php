@extends('layouts.app')

@section('title', 'Machine Details')
@section('page-title', 'Machine: ' . $machine->name)

@section('content')
    <section class="section">
        <div class="row">
            <!-- Machine Information -->
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-gear"></i> Machine Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Name:</th>
                                <td>
                                    <strong>{{ $machine->name }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>Model:</th>
                                <td>
                                    @if ($machine->model === 'CNC')
                                        <span class="badge bg-info">
                                            <i class="bi bi-gear"></i> {{ $machine->model }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-tools"></i> {{ $machine->model }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if ($machine->status === 'active')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle"></i> Inactive
                                        </span>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th>Shift Capability:</th>
                                <td>
                                    @if ($machine->shift_capability === '1')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-calendar"></i> 1 Shift
                                        </span>
                                    @elseif ($machine->shift_capability === '2')
                                        <span class="badge bg-info">
                                            <i class="bi bi-calendar2"></i> 2 Shifts
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-calendar3"></i> 3 Shifts
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>PIC:</th>
                                <td>
                                    @if ($machine->pic)
                                        <span class="badge bg-secondary">{{ $machine->pic->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>
                                    @if ($machine->description)
                                        <small>{{ $machine->description }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>
                                    <small class="text-muted">{{ $machine->created_at->format('d M Y H:i') }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Updated:</th>
                                <td>
                                    <small class="text-muted">{{ $machine->updated_at->format('d M Y H:i') }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer d-grid gap-2">
                        <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Machine
                        </a>
                    </div>
                </div>
            </div>

            <!-- Machine Details / Additional Info -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i> Detailed Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Machine Name</label>
                                    <p class="fs-5">
                                        <strong>{{ $machine->name }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Machine Model</label>
                                    <p class="fs-5">
                                        @if ($machine->model === 'CNC')
                                            <span class="badge bg-info fs-6">
                                                <i class="bi bi-gear"></i> CNC (Computer Numerical Control)
                                            </span>
                                        @else
                                            <span class="badge bg-warning fs-6">
                                                <i class="bi bi-tools"></i> Manual Operation
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Current Status</label>
                                    <p class="fs-5">
                                        @if ($machine->status === 'active')
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="bi bi-x-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Shift Capability</label>
                                    <p class="fs-5">
                                        @if ($machine->shift_capability === '1')
                                            <span class="badge bg-warning fs-6">
                                                <i class="bi bi-calendar"></i> 1 Shift
                                            </span>
                                        @elseif ($machine->shift_capability === '2')
                                            <span class="badge bg-info fs-6">
                                                <i class="bi bi-calendar2"></i> 2 Shifts
                                            </span>
                                        @else
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-calendar3"></i> 3 Shifts
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if ($machine->description)
                            <div class="mb-3">
                                <label class="form-label text-muted">Description</label>
                                <div class="alert alert-light border">
                                    {{ $machine->description }}
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Created Date</label>
                                    <p>
                                        <small>{{ $machine->created_at->format('d M Y') }}</small><br>
                                        <small class="text-muted">{{ $machine->created_at->format('H:i') }}</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <p>
                                        <small>{{ $machine->updated_at->format('d M Y') }}</small><br>
                                        <small class="text-muted">{{ $machine->updated_at->format('H:i') }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card shadow mt-3">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Danger Zone
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Once you delete a machine, there is no going back. Please be certain.</p>
                        <form action="{{ route('machines.destroy', $machine->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you absolutely sure you want to delete this machine? This action cannot be undone.')">
                                <i class="bi bi-trash"></i> Delete Machine
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="row mt-3">
            <div class="col-lg-12">
                <a href="{{ route('machines.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Machines List
                </a>
            </div>
        </div>
    </section>
@endsection
