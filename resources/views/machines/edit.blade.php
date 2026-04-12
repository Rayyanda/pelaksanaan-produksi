@extends('layouts.app')

@section('title', 'Edit Machine')
@section('page-title', 'Edit Machine')

@section('content')
    <section class="section">
        <form action="{{ route('machines.update', $machine->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    <!-- Machine Information Card -->
                    <div class="card shadow">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-pencil-square"></i> Edit Machine Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-exclamation-circle"></i> Validation Errors
                                    </h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Machine Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ $machine->name ?? old('name') }}" placeholder="e.g. CNC Machine 001"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Machine Model <span class="text-danger">*</span></label>
                                        <select name="model" class="form-select @error('model') is-invalid @enderror"
                                            required>
                                            <option value="">-- Select Model --</option>
                                            <option value="CNC" @selected(($machine->model ?? old('model')) === 'CNC')>
                                                <i class="bi bi-gear"></i> CNC
                                            </option>
                                            <option value="Manual" @selected(($machine->model ?? old('model')) === 'Manual')>
                                                <i class="bi bi-tools"></i> Manual
                                            </option>
                                        </select>
                                        @error('model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror"
                                            required>
                                            <option value="">-- Select Status --</option>
                                            <option value="active" @selected(($machine->status ?? old('status')) === 'active')>
                                                Active
                                            </option>
                                            <option value="inactive" @selected(($machine->status ?? old('status')) === 'inactive')>
                                                Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Shift Capability <span class="text-danger">*</span></label>
                                        <select name="shift_capability" class="form-select @error('shift_capability') is-invalid @enderror"
                                            required>
                                            <option value="">-- Select Shift Capability --</option>
                                            <option value="1" @selected(($machine->shift_capability ?? old('shift_capability')) === '1')>
                                                <i class="bi bi-calendar"></i> 1 Shift
                                            </option>
                                            <option value="2" @selected(($machine->shift_capability ?? old('shift_capability')) === '2')>
                                                <i class="bi bi-calendar2"></i> 2 Shifts
                                            </option>
                                            <option value="3" @selected(($machine->shift_capability ?? old('shift_capability')) === '3')>
                                                <i class="bi bi-calendar3"></i> 3 Shifts
                                            </option>
                                        </select>
                                        @error('shift_capability')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Person in Charge (PIC)</label>
                                <select name="pic_id" class="form-select @error('pic_id') is-invalid @enderror">
                                    <option value="">-- Select PIC --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @selected(($machine->pic_id ?? old('pic_id')) == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pic_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description"
                                    class="form-control @error('description') is-invalid @enderror" rows="4"
                                    placeholder="Machine description, specifications, or notes">{{ $machine->description ?? old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow">
                        <div class="card-footer text-end">
                            <a href="{{ route('machines.show', $machine->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle"></i> Update Machine
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle"></i> Audit Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th width="40%">Created:</th>
                                    <td>
                                        <small>{{ $machine->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>
                                        <small>{{ $machine->updated_at->format('d M Y H:i') }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Current Status:</th>
                                    <td>
                                        @if ($machine->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
