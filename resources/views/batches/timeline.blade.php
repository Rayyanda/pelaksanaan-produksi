@extends('layouts.app')

@section('title', 'Timeline Batches')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar3"></i> Production Timeline - All Batches</h1>
    </div>

    @if($batches->isEmpty())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            No active batches found. <a href="{{ route('batches.create') }}">Create a new batch</a> to see the timeline.
        </div>
    @else
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body bg-light">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Part</label>
                        <select name="part_id" class="form-select form-select-sm">
                            <option value="">All Parts</option>
                            @foreach(\App\Models\PartInternal::all() as $part)
                                <option value="{{ $part->id }}" {{ request('part_id') == $part->id ? 'selected' : '' }}>
                                    {{ $part->part_number }} - {{ $part->part_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('batches.timeline') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Timeline Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div style="overflow-x: auto; min-width: 100%;">
                    <div style="min-width: 1400px;">
                        @php
                            // SAFETY CHECK: Pastikan $minDate dan $maxDate valid
                            if (!$minDate || !$maxDate) {
                                $minDate = now();
                                $maxDate = now()->addMonths(3);
                            }

                            $totalDays = $minDate->diffInDays($maxDate) + 1;

                            // Generate months range
                            $monthsRange = [];
                            $currentMonth = $minDate->copy()->startOfMonth();
                            $monthLimit = 0; // Safety limit untuk prevent infinite loop

                            while ($currentMonth->lte($maxDate) && $monthLimit < 36) { // Max 36 months
                                $monthsRange[] = $currentMonth->copy();
                                $currentMonth->addMonth();
                                $monthLimit++;
                            }
                        @endphp

                        <!-- Header Timeline (Months) -->
                        <div class="d-flex border-bottom pb-2 mb-3">
                            <div style="width: 250px; flex-shrink: 0;" class="fw-bold">
                                Batch / Part
                            </div>
                            <div class="flex-fill d-flex">
                                @foreach($monthsRange as $month)
                                    @php
                                        $monthStart = max($month, $minDate);
                                        $monthEnd = min($month->copy()->endOfMonth(), $maxDate);
                                        $daysInRange = $monthStart->diffInDays($monthEnd) + 1;
                                        $widthPercent = $totalDays > 0 ? ($daysInRange / $totalDays) * 100 : 0;
                                    @endphp
                                    <div class="text-center small fw-semibold border-end"
                                         style="width: {{ $widthPercent }}%">
                                        {{ $month->format('M Y') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Batch Rows -->
                        @foreach($batches as $batch)
                            @php
                                // SAFETY CHECK: Skip batch kalau gak ada schedule
                                if ($batch->productionSchedules->isEmpty()) {
                                    continue;
                                }

                                $batchStart = $batch->productionSchedules->min('plan_start_date');
                                $batchEnd = $batch->productionSchedules->max('plan_end_date');

                                // Skip kalau date null
                                if (!$batchStart || !$batchEnd) {
                                    continue;
                                }

                                $startOffset = $minDate->diffInDays($batchStart);
                                $duration = $batchStart->diffInDays($batchEnd) + 1;
                                $leftPercent = $totalDays > 0 ? ($startOffset / $totalDays) * 100 : 0;
                                $widthPercent = $totalDays > 0 ? ($duration / $totalDays) * 100 : 0;

                                $statusColors = [
                                    'planned' => 'primary',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                ];
                                $barColor = $statusColors[$batch->status] ?? 'secondary';
                            @endphp

                            <div class="d-flex align-items-start mb-4 batch-row"
                                 style="padding: 8px; border-radius: 6px; transition: background-color 0.2s;"
                                 onmouseover="this.style.backgroundColor='#f8f9fa'"
                                 onmouseout="this.style.backgroundColor='transparent'">

                                <!-- Batch Info -->
                                <div style="width: 250px; flex-shrink: 0;" class="pe-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="{{ route('batches.show', $batch) }}"
                                           class="text-decoration-none fw-bold text-primary d-block">
                                            {{ $batch->batch_number }}
                                        </a>
                                        <button class="btn btn-sm btn-link text-secondary p-0"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#process-{{ $batch->id }}">
                                            <i class="bi bi-chevron-down" id="icon-{{ $batch->id }}"></i>
                                        </button>
                                    </div>
                                    <div class="small text-muted">{{ $batch->partInternal->part_number }}</div>
                                    <div class="small text-muted">{{ number_format($batch->quantity) }} pcs</div>
                                    @php
                                        $statusBadges = [
                                            'planned' => 'bg-secondary',
                                            'in_progress' => 'bg-primary',
                                            'completed' => 'bg-success',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusBadges[$batch->status] ?? 'bg-secondary' }} mt-1">
                                        {{ ucfirst($batch->status) }}
                                    </span>
                                </div>

                                <!-- Timeline Container -->
                                <div class="flex-fill">
                                    <!-- Main Timeline Bar -->
                                    <div class="position-relative" style="height: 50px; background-color: #f8f9fa; border-radius: 6px;">
                                        <!-- Main Bar -->
                                        <div class="position-absolute bg-{{ $barColor }} rounded shadow-sm timeline-bar"
                                             style="height: 32px; left: {{ $leftPercent }}%; width: {{ $widthPercent }}%; top: 9px;"
                                             data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             title="{{ $batchStart->format('d M Y') }} - {{ $batchEnd->format('d M Y') }} ({{ $duration }} days)">
                                            <div class="d-flex align-items-center justify-content-center h-100 text-white small px-2">
                                                <span class="text-truncate">
                                                    {{ $batchStart->format('d M') }} - {{ $batchEnd->format('d M') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Progress Indicator -->
                                        @if($batch->status === 'in_progress' && $batch->getProgressPercentage() > 0)
                                            <div class="position-absolute bg-success rounded-start"
                                                 style="height: 32px; left: {{ $leftPercent }}%; width: {{ ($widthPercent * $batch->getProgressPercentage() / 100) }}%; top: 9px; opacity: 0.7;">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Process Detail (Collapsible) -->
                                    <div class="collapse mt-2" id="process-{{ $batch->id }}">
                                        <div class="card card-body bg-light p-2">
                                            @foreach($batch->productionSchedules as $schedule)
                                                @php
                                                    // Safety check untuk schedule dates
                                                    if (!$schedule->plan_start_date || !$schedule->plan_end_date) {
                                                        continue;
                                                    }

                                                    $processStart = $minDate->diffInDays($schedule->plan_start_date);
                                                    $processDuration = $schedule->plan_start_date->diffInDays($schedule->plan_end_date) + 1;
                                                    $processLeftPercent = $totalDays > 0 ? ($processStart / $totalDays) * 100 : 0;
                                                    $processWidthPercent = $totalDays > 0 ? ($processDuration / $totalDays) * 100 : 0;

                                                    $processStatusColors = [
                                                        'planned' => 'secondary',
                                                        'in_progress' => 'info',
                                                        'completed' => 'success',
                                                        'delayed' => 'danger',
                                                    ];
                                                    $processBarColor = $processStatusColors[$schedule->status] ?? 'secondary';
                                                @endphp

                                                <div class="d-flex align-items-center mb-1" style="height: 28px;">
                                                    <div style="width: 150px;" class="small text-muted pe-2 text-truncate">
                                                        {{ $schedule->getProcessLabel() }}
                                                    </div>
                                                    <div class="position-relative flex-fill" style="height: 24px; background-color: #e9ecef; border-radius: 4px;">
                                                        <div class="position-absolute bg-{{ $processBarColor }} rounded"
                                                             style="height: 24px; left: {{ $processLeftPercent }}%; width: {{ $processWidthPercent }}%;"
                                                             data-bs-toggle="tooltip"
                                                             title="{{ $schedule->plan_start_date->format('d M') }} - {{ $schedule->plan_end_date->format('d M') }}">
                                                            <div class="d-flex align-items-center h-100 px-2">
                                                                <span class="small text-white" style="font-size: 0.7rem;">
                                                                    {{ $schedule->plan_start_date->format('d/m') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Legend -->
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="fw-bold mb-3">Legend:</h6>
                            <div class="row g-3">
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 20px; height: 20px;" class="bg-primary rounded me-2"></div>
                                        <span class="small">Planned</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 20px; height: 20px;" class="bg-warning rounded me-2"></div>
                                        <span class="small">In Progress</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 20px; height: 20px;" class="bg-success rounded me-2"></div>
                                        <span class="small">Completed</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 20px; height: 20px;" class="bg-success rounded me-2" style="opacity: 0.7;"></div>
                                        <span class="small">Progress Indicator</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row g-3 mt-4">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <div class="text-primary small fw-semibold mb-1">Total Active Batches</div>
                        <div class="display-4 fw-bold text-primary">{{ $batches->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <div class="text-warning small fw-semibold mb-1">In Progress</div>
                        <div class="display-4 fw-bold text-warning">
                            {{ $batches->where('status', 'in_progress')->count() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <div class="text-success small fw-semibold mb-1">Timeline Range</div>
                        <div class="h5 fw-bold text-success mb-0">
                            {{ $minDate->format('d M Y') }}<br>
                            <small>to</small><br>
                            {{ $maxDate->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Rotate icon on collapse
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(button) {
        button.addEventListener('click', function() {
            const target = button.getAttribute('data-bs-target');
            const icon = button.querySelector('i');

            setTimeout(function() {
                const isShown = document.querySelector(target).classList.contains('show');
                if (isShown) {
                    icon.classList.remove('bi-chevron-down');
                    icon.classList.add('bi-chevron-up');
                } else {
                    icon.classList.remove('bi-chevron-up');
                    icon.classList.add('bi-chevron-down');
                }
            }, 10);
        });
    });
});
</script>
@endpush
