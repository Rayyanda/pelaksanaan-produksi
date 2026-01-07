@extends('layouts.app')

@section('title', 'User Detail')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>User Detail</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="avatar avatar-xl bg-primary mx-auto mb-3">
                            <span class="avatar-content" style="font-size: 2rem;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                        <h3>{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                        @if($user->role == 'admin')
                            <span class="badge bg-danger"><i class="bi bi-shield-fill-check"></i> Admin</span>
                        @elseif($user->role == 'ppc')
                            <span class="badge bg-primary"><i class="bi bi-clipboard-data"></i> PPC</span>
                        @elseif($user->role == 'supervisor produksi')
                            <span class="badge bg-warning"><i class="bi bi-person-badge"></i> Supervisor</span>
                        @else
                            <span class="badge bg-info"><i class="bi bi-person-workspace"></i> Operator</span>
                        @endif
                        @if($user->is_active)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>
                        @else
                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h5>User Information</h5></div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Full Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ ucwords($user->role) }}</td>
                            </tr>
                            <tr>
                                <th>Division</th>
                                <td>{{ $user->division ? $user->division->name : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                            </tr>
                            <tr>
                                <th>Email Verified</th>
                                <td>{{ $user->email_verified_at ? 'Yes' : 'No' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <div>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                @if($user->id != auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h6><i class="bi bi-clock-history"></i> Timeline</h6></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Created</small><br>
                            <strong>{{ $user->created_at->format('d M Y, H:i') }}</strong><br>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <small class="text-muted">Last Updated</small><br>
                            <strong>{{ $user->updated_at->format('d M Y, H:i') }}</strong><br>
                            <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
