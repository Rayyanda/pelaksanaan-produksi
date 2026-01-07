@extends('layouts.app')

@section('title', 'Create Part Internal')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Add Part Internal</h3>
                    <p class="text-subtitle text-muted">Create new part internal data</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('part-internals.index') }}">Part Internals</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Add New</li>
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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card shadow mt-5">
                <div class="card-header">
                    <h5 class="card-title">Part Internal Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('part-internals.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="part_number" class="form-label">Part Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('part_number') is-invalid @enderror"
                                        id="part_number" name="part_number" value="{{ old('part_number') }}" required>
                                    @error('part_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nointernal" class="form-label">No Internal</label>
                                    <input type="number" class="form-control @error('nointernal') is-invalid @enderror"
                                        id="nointernal" name="nointernal" value="{{ old('nointernal') }}">
                                    @error('nointernal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="part_name" class="form-label">Part Name</label>
                                    <input type="text" class="form-control @error('part_name') is-invalid @enderror"
                                        id="part_name" name="part_name" value="{{ old('part_name') }}">
                                    @error('part_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="matl_spec" class="form-label">Material Spec</label>
                                    <input type="text" class="form-control @error('matl_spec') is-invalid @enderror"
                                        id="matl_spec" name="matl_spec" value="{{ old('matl_spec') }}">
                                    @error('matl_spec')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="matl_req" class="form-label">Material Required</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('matl_req') is-invalid @enderror" id="matl_req"
                                        name="matl_req" value="{{ old('matl_req') }}">
                                    @error('matl_req')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="comp_per_mould" class="form-label">Component Per Mould</label>
                                    <input type="number"
                                        class="form-control @error('comp_per_mould') is-invalid @enderror"
                                        id="comp_per_mould" name="comp_per_mould" value="{{ old('comp_per_mould') }}">
                                    @error('comp_per_mould')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="deoxidation" class="form-label">Deoxidation</label>
                            <textarea class="form-control @error('deoxidation') is-invalid @enderror" id="deoxidation" name="deoxidation"
                                rows="3">{{ old('deoxidation') }}</textarea>
                            @error('deoxidation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="mb-3 mt-4">Target Quantities (Week)</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_waxing" class="form-label">Waxing</label>
                                    <input type="number" class="form-control" id="target_qty_waxing"
                                        name="target_qty_waxing" value="{{ old('target_qty_waxing') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_mould_room" class="form-label">Mould Room</label>
                                    <input type="number" class="form-control" id="target_qty_mould_room"
                                        name="target_qty_mould_room" value="{{ old('target_qty_mould_room') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_melting" class="form-label">Melting</label>
                                    <input type="number" class="form-control" id="target_qty_melting"
                                        name="target_qty_melting" value="{{ old('target_qty_melting') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_heat_treatment" class="form-label">Heat Treatment</label>
                                    <input type="number" class="form-control" id="target_qty_heat_treatment"
                                        name="target_qty_heat_treatment" value="{{ old('target_qty_heat_treatment') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_cut_off" class="form-label">Cut Off</label>
                                    <input type="number" class="form-control" id="target_qty_cut_off"
                                        name="target_qty_cut_off" value="{{ old('target_qty_cut_off') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_finishing" class="form-label">Finishing</label>
                                    <input type="number" class="form-control" id="target_qty_finishing"
                                        name="target_qty_finishing" value="{{ old('target_qty_finishing') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_machining" class="form-label">Machining</label>
                                    <input type="number" class="form-control" id="target_qty_machining"
                                        name="target_qty_machining" value="{{ old('target_qty_machining') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="target_qty_quality_control" class="form-label">Quality Control</label>
                                    <input type="number" class="form-control" id="target_qty_quality_control"
                                        name="target_qty_quality_control"
                                        value="{{ old('target_qty_quality_control') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save
                            </button>
                            <a href="{{ route('part-internals.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
