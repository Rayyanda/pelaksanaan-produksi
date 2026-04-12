@extends('layouts.app')
@section('title', 'Data Machines')
@section('page-title', 'List of Machines')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Machines</h5>
                        <a href="{{ route('machines.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add New Machine
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Machine Name</th>
                                        <th>Model</th>
                                        <th>Shift Capability</th>
                                        <th>Status</th>
                                        <th>PIC (Person in Charge)</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($machines as $machine)
                                        <tr>
                                            <td>{{ $machines->firstItem() + $loop->index }}</td>
                                            <td>
                                                <strong>{{ $machine->name }}</strong>
                                            </td>
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
                                            <td>
                                                @if ($machine->status === 'active')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($machine->pic)
                                                    <span class="badge bg-primary">{{ $machine->pic->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $machine->created_at->format('d M Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <a href="{{ route('machines.show', $machine->id) }}"
                                                    class="btn btn-sm btn-info" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('machines.edit', $machine->id) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('machines.destroy', $machine->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this machine?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <p class="text-muted">
                                                    <i class="bi bi-inbox"></i> No machines found
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($machines->count())
                            <div class="d-flex justify-content-end mt-3">
                                {{ $machines->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
