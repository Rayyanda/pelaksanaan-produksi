@extends('layouts.app')

@section('title', 'Create User')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Create User</h3>
                    <p class="text-subtitle text-muted">Add new user to the system</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
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
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf

                                <div class="form-group mb-4">
                                    <label for="name" class="form-label">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
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
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="password" class="form-label">
                                                Password <span class="text-danger">*</span>
                                            </label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Minimum 8 characters</small>
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
                                                id="role" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="ppc" {{ old('role') == 'ppc' ? 'selected' : '' }}>PPC
                                                </option>
                                                <option value="foreman" {{ old('role') == 'foreman' ? 'selected' : '' }}>
                                                    Foreman</option>
                                                <option value="supervisor produksi"
                                                    {{ old('role') == 'supervisor produksi' ? 'selected' : '' }}>Supervisor
                                                    Produksi</option>
                                                <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>
                                                    Operator</option>
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
                                                id="division_id" name="division_id">
                                                <option value="">Select Division (Optional)</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}"
                                                        {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                                        {{ $division->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('division_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Assign user to specific division</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active User
                                        </label>
                                    </div>
                                    <small class="text-muted">Inactive users cannot login to the system</small>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Note:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Admin:</strong> Full system access</li>
                                        <li><strong>PPC:</strong> Production planning & control</li>
                                        <li><strong>Supervisor:</strong> Division management & monitoring</li>
                                        <li><strong>Operator:</strong> Division dashboard & WIP updates</li>
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Create User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Role Description -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-shield-check"></i> Role Permissions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="text-danger">
                                    <i class="bi bi-shield-fill-check"></i> Admin
                                </h6>
                                <small class="text-muted">
                                    • Manage all users<br>
                                    • Full CRUD access<br>
                                    • System configuration
                                </small>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <h6 class="text-primary">
                                    <i class="bi bi-clipboard-data"></i> PPC
                                </h6>
                                <small class="text-muted">
                                    • Manage PO & Batches<br>
                                    • Production planning<br>
                                    • Reports & analytics
                                </small>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <h6 class="text-warning">
                                    <i class="bi bi-person-badge"></i> Supervisor
                                </h6>
                                <small class="text-muted">
                                    • Division monitoring<br>
                                    • WIP management<br>
                                    • Team oversight
                                </small>
                            </div>
                            <hr>
                            <div class="mb-0">
                                <h6 class="text-info">
                                    <i class="bi bi-person-workspace"></i> Operator
                                </h6>
                                <small class="text-muted">
                                    • Division dashboard only<br>
                                    • Update WIP status<br>
                                    • View assigned tasks
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tips -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lock-fill"></i> Security Tips
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li class="mb-2">Use strong passwords</li>
                                <li class="mb-2">Assign minimal necessary role</li>
                                <li class="mb-2">Review inactive users regularly</li>
                                <li class="mb-0">Enable email verification</li>
                            </ul>
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
    </script>
@endpush
