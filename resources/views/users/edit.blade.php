@extends('layouts.app')

@section('title', 'Edit User')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit User</h3>
                <p class="text-subtitle text-muted">Update user information - {{ $user->name }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">User Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label for="name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="email" class="form-label">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $user->email) }}"
                                               required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="password" class="form-label">
                                            New Password
                                        </label>
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="role" class="form-label">
                                            Role <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select choices @error('role') is-invalid @enderror"
                                                id="role"
                                                name="role"
                                                required>
                                            <option value="">Select Role</option>
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="ppc" {{ old('role', $user->role) == 'ppc' ? 'selected' : '' }}>PPC</option>
                                            <option value="supervisor produksi" {{ old('role', $user->role) == 'supervisor produksi' ? 'selected' : '' }}>Supervisor Produksi</option>
                                            <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                                        </select>
                                        @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="division_id" class="form-label">Division</label>
                                        <select class="form-select choices @error('division_id') is-invalid @enderror"
                                                id="division_id"
                                                name="division_id">
                                            <option value="">Select Division (Optional)</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_active"
                                           id="is_active"
                                           value="1"
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active User
                                    </label>
                                </div>
                                <small class="text-muted">Inactive users cannot login to the system</small>
                            </div>

                            @if($user->id == auth()->id())
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Warning:</strong> You are editing your own account. Be careful not to lock yourself out!
                            </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- User Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle"></i> Account Info
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">User ID</small>
                            <code>#{{ $user->id }}</code>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Created At</small>
                            <strong>{{ $user->created_at->format('d M Y, H:i') }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $user->updated_at->format('d M Y, H:i') }}</strong>
                        </div>
                        @if($user->email_verified_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Email Verified</small>
                            <span class="badge bg-success">
                                <i class="bi bi-patch-check-fill"></i> Verified
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Changes Tracker -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-pencil-square"></i> Changes
                        </h6>
                    </div>
                    <div class="card-body" id="changesTracker">
                        <p class="text-muted text-center mb-0">
                            <small>No changes yet</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script>
    // Store original values
    const originalValues = {
        name: '{{ $user->name }}',
        email: '{{ $user->email }}',
        role: '{{ $user->role }}',
        division_id: '{{ $user->division_id ?? '' }}',
        is_active: {{ $user->is_active ? 'true' : 'false' }}
    };

    // Initialize Choices.js
    new Choices('#role', {
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select Role'
    });

    new Choices('#division_id', {
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select Division (Optional)'
    });

    // Track changes
    function trackChanges() {
        const changes = [];
        const currentValues = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            role: document.getElementById('role').value,
            division_id: document.getElementById('division_id').value,
            is_active: document.getElementById('is_active').checked
        };

        if (currentValues.name !== originalValues.name) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Name changed');
        }
        if (currentValues.email !== originalValues.email) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Email changed');
        }
        if (currentValues.role !== originalValues.role) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Role changed');
        }
        if (currentValues.division_id !== originalValues.division_id) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Division changed');
        }
        if (currentValues.is_active !== originalValues.is_active) {
            changes.push('<i class="bi bi-pencil text-warning"></i> Status changed');
        }
        if (document.getElementById('password').value) {
            changes.push('<i class="bi bi-key text-info"></i> Password will be updated');
        }

        const tracker = document.getElementById('changesTracker');
        if (changes.length > 0) {
            tracker.innerHTML = '<ul class="list-unstyled mb-0">' + changes.map(c => '<li class="mb-1">' + c + '</li>').join('') + '</ul>';
        } else {
            tracker.innerHTML = '<p class="text-muted text-center mb-0"><small>No changes yet</small></p>';
        }
    }

    // Event listeners
    ['name', 'email', 'role', 'division_id', 'is_active', 'password'].forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('change', trackChanges);
            element.addEventListener('input', trackChanges);
        }
    });
</script>
@endpush
