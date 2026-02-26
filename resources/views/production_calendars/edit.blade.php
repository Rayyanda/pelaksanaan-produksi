@extends('layouts.app')

@section('title', 'Edit Production Calendar')
@section('page-title', 'Edit Production Calendar')

@section('content')
    <section class="section">
        <form action="{{ route('production_calendars.update', $productionCalendar->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Calendar Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Activity Name <span class="text-danger">*</span></label>
                                <input type="text" name="activity"
                                    class="form-control @error('activity') is-invalid @enderror"
                                    value="{{ old('activity', $productionCalendar->activity) }}"
                                    placeholder="e.g. Christmas Holiday, Ramadan Break" required>
                                @error('activity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Name of the holiday or event</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            value="{{ old('start_date', $productionCalendar->start_date->format('Y-m-d')) }}"
                                            required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            value="{{ old('end_date', $productionCalendar->end_date->format('Y-m-d')) }}"
                                            required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Must be on or after start date</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Day Type <span class="text-danger">*</span></label>
                                        <select name="day_type" class="form-select @error('day_type') is-invalid @enderror"
                                            required>
                                            <option value="">Select Day Type</option>
                                            <option value="holiday"
                                                {{ old('day_type', $productionCalendar->day_type) == 'holiday' ? 'selected' : '' }}>
                                                Holiday</option>
                                            <option value="mass leave"
                                                {{ old('day_type', $productionCalendar->day_type) == 'mass leave' ? 'selected' : '' }}>
                                                Mass Leave</option>
                                        </select>
                                        @error('day_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Working Hours <span class="text-danger">*</span></label>
                                        <input type="number" name="working_hours"
                                            class="form-control @error('working_hours') is-invalid @enderror"
                                            value="{{ old('working_hours', $productionCalendar->working_hours) }}"
                                            min="0" max="24" required>
                                        @error('working_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Working hours during this period (0-24)</small>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('production_calendars.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Calendar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
