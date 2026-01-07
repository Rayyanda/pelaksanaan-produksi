@extends('layouts.app')

@section('title','Detail Part Internals')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Part Internal Detail</h3>
                <p class="text-subtitle text-muted">View part internal information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('part-internals.index') }}">Part Internals</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Part Internal Information</h5>
                    <div>
                        <a href="{{ route('part-internals.edit', $partInternal->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('part-internals.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Part Number</h6>
                            <p class="fw-bold">{{ $partInternal->part_number }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">No Internal</h6>
                            <p>{{ $partInternal->nointernal ?? '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Part Name</h6>
                            <p>{{ $partInternal->part_name ?? '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Material Spec</h6>
                            <p>{{ $partInternal->matl_spec ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Material Required</h6>
                            <p>{{ $partInternal->matl_req ? number_format($partInternal->matl_req, 2) : '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Component Per Mould</h6>
                            <p>{{ $partInternal->comp_per_mould ?? '-' }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Created At</h6>
                            <p>{{ $partInternal->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Last Updated</h6>
                            <p>{{ $partInternal->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Deoxidation</h6>
                            <p>{{ $partInternal->deoxidation ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-4">Target Quantities (Week)</h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Waxing</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_waxing ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Mould Room</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_mould_room ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Melting</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_melting ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Heat Treatment</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_heat_treatment ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Cut Off</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_cut_off ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Finishing</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_finishing ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Machining</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_machining ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Quality Control</h6>
                                <h4 class="card-title mb-0">{{ $partInternal->target_qty_quality_control ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <form action="{{ route('part-internals.destroy', $partInternal->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this part internal?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
