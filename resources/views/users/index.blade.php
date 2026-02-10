@extends('layouts.app')

@section('title', 'Users Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Users Management</h3>
                    <p class="text-subtitle text-muted">Manage system users and access</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
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

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Users</h6>
                                    <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-primary">
                                    <i class="bi bi-people-fill" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Active Users</h6>
                                    <h3 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-success">
                                    <i class="bi bi-person-check-fill" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Inactive Users</h6>
                                    <h3 class="mb-0 text-danger">{{ $stats['inactive'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-danger">
                                    <i class="bi bi-person-x-fill" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Admins</h6>
                                    <h3 class="mb-0 text-info">{{ $stats['admins'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-info">
                                    <i class="bi bi-shield-fill-check" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="ppc" {{ request('role') == 'ppc' ? 'selected' : '' }}>PPC</option>
                                <option value="supervisor produksi"
                                    {{ request('role') == 'supervisor produksi' ? 'selected' : '' }}>Supervisor Produksi
                                </option>
                                <option value="foreman" {{ request('role') == 'foreman' ? 'selected' : '' }}>Foreman
                                </option>
                                <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Division</label>
                            <select name="division_id" class="form-select">
                                <option value="">All Divisions</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="">All Status</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users List -->
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Users List</h5>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Add New User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Division</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md me-3">
                                                    <span class="avatar-content bg-primary text-white">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    @if ($user->email_verified_at)
                                                        <i class="bi bi-patch-check-fill text-success"
                                                            data-bs-toggle="tooltip" title="Email Verified"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role == 'admin')
                                                <span class="badge bg-danger"><i class="bi bi-shield-fill-check"></i>
                                                    Admin</span>
                                            @elseif($user->role == 'ppc')
                                                <span class="badge bg-primary"><i class="bi bi-clipboard-data"></i>
                                                    PPC</span>
                                            @elseif($user->role == 'supervisor produksi')
                                                <span class="badge bg-warning"><i class="bi bi-person-badge"></i>
                                                    Supervisor</span>
                                            @elseif($user->role == 'foreman')
                                                <span class="badge bg-info"><i class="bi bi-person-workspace"></i>
                                                    Foreman</span>
                                            @else<span class="badge bg-info"><i class="bi bi-person-workspace"></i>
                                                    Operator</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->division)
                                                <span
                                                class="badge bg-secondary">{{ $user->division->name }}</span>@else<span
                                                    class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->is_active)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->last_login_at)
                                                <small>{{ $user->last_login_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.show', $user->id) }}"
                                                    class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if ($user->id != auth()->id())
                                                    <button type="button"
                                                        class="btn btn-sm {{ $user->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                        onclick="toggleStatus({{ $user->id }})"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                                                    </button>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure want to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="tooltip" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                            <p class="text-muted mt-3">No users found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Users by Role -->
            @if ($users->isNotEmpty())
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pie-chart"></i> Users by Role
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $roleStats = $users->groupBy('role')->map(function ($items) {
                                    return $items->count();
                                });
                            @endphp
                            @foreach (['admin' => 'danger', 'ppc' => 'primary', 'supervisor produksi' => 'warning', 'operator' => 'info'] as $role => $color)
                                @if ($roleStats->has($role))
                                    <div class="col-md-3">
                                        <div class="card bg-{{ $color }} text-white">
                                            <div class="card-body">
                                                <h6 class="text-white mb-1">{{ ucwords($role) }}</h6>
                                                <h2 class="mb-0">{{ $roleStats[$role] }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true,
                order: [
                    [0, 'asc']
                ],
                pageLength: 25
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        function toggleStatus(userId) {
            if (confirm('Toggle user status?')) {
                fetch(`/users/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred');
                    });
            }
        }
    </script>
@endpush
