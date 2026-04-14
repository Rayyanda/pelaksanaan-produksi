@extends('layouts.app')

@section('title', 'Machining Dashboard')

@push('styles')
    <style>
        .wip-card {
            transition: all 0.3s;
            border-left: 4px solid #dee2e6;
        }

        .wip-card.quality_check {
            border-left-color: #ffc107;
        }

        .wip-card.process {
            border-left-color: #0dcaf0;
        }

        .wip-card.assigned {
            border-left-color: #198754;
        }

        .machine-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #435ebe;
            font-weight: 600;
        }

        .badge-xl {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .machine-card {
            transition: all 0.3s;
            border: 1px solid #dee2e6;
        }

    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-building"></i> {{ $division->name }}</h3>
                    <p class="text-subtitle text-muted">Machining work center dashboard</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Machining Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Quality Check</h6>
                                    <h3 class="mb-0 text-warning">{{ $stats['quality_check'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-warning">
                                    <i class="bi bi-shield-check" style="font-size:1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">In Process</h6>
                                    <h3 class="mb-0 text-info">{{ $stats['process'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-info">
                                    <i class="bi bi-gear-fill" style="font-size:1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Completed Today</h6>
                                    <h3 class="mb-0 text-success">{{ $stats['completed_today'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-success">
                                    <i class="bi bi-check-circle-fill" style="font-size:1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Active Machines</h6>
                                    <h3 class="mb-0 text-primary">{{ $activeMachinesCount ?? 0 }}</h3>
                                </div>
                                <div class="avatar avatar-xl bg-primary">
                                    <i class="bi bi-cpu" style="font-size:1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="divisionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#quality" type="button">
                                <i class="bi bi-shield-check"></i> Quality Check
                                <span class="badge bg-warning ms-2">{{ $qualityCheckWips->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#process" type="button">
                                <i class="bi bi-gear"></i> Process
                                <span class="badge bg-info ms-2">{{ $processWips->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#machines" type="button">
                                <i class="bi bi-cpu"></i> Machines
                                <span class="badge bg-primary ms-2">{{ $machines->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#history" type="button">
                                <i class="bi bi-clock-history"></i> History
                                <span class="badge bg-success ms-2">{{ $historyWips->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">

                        <!-- Quality Check Tab -->
                        <div class="tab-pane fade show active" id="quality" role="tabpanel">
                            <div class="row py-3">
                                @forelse($qualityCheckWips as $wip)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card wip-card quality_check h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <span class="badge badge-xl bg-warning">
                                                        <i class="bi bi-shield-check"></i> Quality Check
                                                    </span>
                                                    <div class="text-end">
                                                        @if ($wip->status == 'waiting')
                                                            <span class="badge bg-secondary">Waiting</span>
                                                        @elseif($wip->status == 'in_progress')
                                                            <span class="badge bg-primary">In Progress</span>
                                                        @endif
                                                        <small class="text-muted d-block">
                                                            {{ \Carbon\Carbon::parse($wip->batch->target_completed)->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <h5 class="mb-1">{{ $wip->batch->batch_number }}</h5>
                                                <p class="text-muted mb-2">
                                                    <strong>{{ $wip->partInternal->part_number }}</strong><br>
                                                    <small>{{ $wip->partInternal->part_name }}</small>
                                                </p>

                                                <div class="mb-3">
                                                    <span class="badge bg-secondary">Route
                                                        #{{ $wip->partOperation->route_order }}</span>
                                                    <span class="badge bg-info">{{ number_format($wip->wip_qty) }}
                                                        pcs</span>
                                                </div>

                                                @if ($wip->started_at)
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="bi bi-clock"></i> Started:
                                                        {{ $wip->started_at->format('d M Y, H:i') }}
                                                    </small>
                                                @endif

                                                <small class="text-muted">Area:
                                                    {{ $wip->partOperation->area->name }}</small>

                                                <div class="d-grid gap-2 mt-3">
                                                    @if ($wip->status == 'waiting')
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="startWip({{ $wip->id }}, 'quality')">
                                                            <i class="bi bi-play"></i> Start Quality Check
                                                        </button>
                                                    @elseif($wip->status == 'in_progress')
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="moveToProcess({{ $wip->id }})">
                                                            <i class="bi bi-arrow-right"></i> Move to Process
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('wip-trackings.show', $wip->id) }}"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-eye"></i> View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-inbox" style="font-size:3rem; color:#dee2e6;"></i>
                                        <p class="text-muted mt-3">No items in quality check</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Process Tab -->
                        <div class="tab-pane fade" id="process" role="tabpanel">
                            <div class="row py-3">
                                @forelse($processWips as $wip)
                                    @php $schedule = $wip->machineSchedule; @endphp
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card wip-card process {{ $schedule ? 'assigned' : '' }} h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <span class="badge badge-xl bg-info">
                                                        <i class="bi bi-gear"></i> Process
                                                    </span>
                                                    <div class="text-end">
                                                        @if ($wip->status == 'waiting')
                                                            <span class="badge bg-secondary">Waiting</span>
                                                        @elseif($wip->status == 'in_progress')
                                                            <span class="badge bg-primary">In Progress</span>
                                                        @endif
                                                        <small class="text-muted d-block">
                                                            {{ \Carbon\Carbon::parse($wip->batch->target_completed)->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <h5 class="mb-1">{{ $wip->batch->batch_number }}</h5>
                                                <p class="text-muted mb-2">
                                                    <strong>{{ $wip->partInternal->part_number }}</strong><br>
                                                    <small>{{ $wip->partInternal->part_name }}</small>
                                                </p>

                                                <div class="mb-2">
                                                    <span class="badge bg-secondary">Route
                                                        #{{ $wip->partOperation->route_order }}</span>
                                                    <span class="badge bg-info">{{ number_format($wip->wip_qty) }}
                                                        pcs</span>
                                                </div>

                                                <!-- Machine Assignment Info -->
                                                @if ($schedule)
                                                    <div class="alert alert-success py-2 px-3 mb-2" role="alert">
                                                        <small>
                                                            <i class="bi bi-cpu"></i>
                                                            <strong>{{ $schedule->machine->name }}</strong>
                                                            <span
                                                                class="badge bg-{{ $schedule->status == 'running' ? 'success' : 'secondary' }} ms-1">
                                                                {{ ucfirst($schedule->status) }}
                                                            </span>
                                                        </small>
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning py-2 px-3 mb-2">
                                                        <small><i class="bi bi-exclamation-triangle"></i> Belum di-assign
                                                            ke machine</small>
                                                    </div>
                                                @endif

                                                <small class="text-muted">Area:
                                                    {{ $wip->partOperation->area->name }}</small>

                                                <div class="d-grid gap-2 mt-3">
                                                    <!-- Assign / Reassign Machine -->
                                                    {{-- <button class="btn btn-outline-primary btn-sm"
                                                        onclick="openAssignModal({{ $wip->id }}, {{ $schedule?->machine_id ?? 'null' }})">
                                                        <i class="bi bi-cpu"></i>
                                                        {{ $schedule ? 'Reassign Machine' : 'Assign to Machine' }}
                                                    </button> --}}
                                                    <button class="btn btn-outline-primary btn-sm"
                                                        onclick="openAssignModal(
                                                            {{ $wip->id }},
                                                            {{ $schedule?->machine_id ?? 'null' }},
                                                            {{ $schedule?->shift_start ?? 'null' }},
                                                            {{ $schedule?->shift_count ?? 'null' }},
                                                            '{{ $schedule?->scheduled_date ?? '' }}'
                                                        )">
                                                        <i class="bi bi-cpu"></i>
                                                        {{ $schedule ? 'Reassign Machine' : 'Assign to Machine' }}
                                                    </button>

                                                    @if ($wip->status == 'waiting')
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="startWip({{ $wip->id }}, 'process')"
                                                            {{ !$schedule ? 'disabled' : '' }}
                                                            title="{{ !$schedule ? 'Assign machine dulu' : '' }}">
                                                            <i class="bi bi-play"></i> Start Process
                                                        </button>
                                                    @elseif($wip->status == 'in_progress')
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="completeWip({{ $wip->id }})">
                                                            <i class="bi bi-check2"></i> Complete
                                                        </button>
                                                    @endif

                                                    <a href="{{ route('wip-trackings.show', $wip->id) }}"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-eye"></i> View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-inbox" style="font-size:3rem; color:#dee2e6;"></i>
                                        <p class="text-muted mt-3">No items in process</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Machines Tab -->
                        <div class="tab-pane fade" id="machines" role="tabpanel">
                            <div class="row py-3">
                                @forelse($machines as $machine)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div
                                            class="card machnine-card h-100 {{ $machine->status == 'inactive' ? 'border-danger opacity-75' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h5 class="mb-0">{{ $machine->name }}</h5>
                                                        <small class="text-muted">{{ $machine->model }}</small>
                                                    </div>
                                                    <span
                                                        class="badge bg-{{ $machine->status == 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($machine->status) }}
                                                    </span>
                                                </div>

                                                @if ($machine->pic)
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="bi bi-person"></i> PIC: {{ $machine->pic->name }}
                                                    </small>
                                                @endif

                                                <!-- WIPs running on this machine -->
                                                @php
                                                    $machineWips = $machine->machineSchedules->where(
                                                        'status',
                                                        '!=',
                                                        'done',
                                                    );
                                                @endphp

                                                @if ($machineWips->count() > 0)
                                                    <div class="mb-2">
                                                        <small class="text-muted fw-bold">WIP Terjadwal:</small>
                                                        @foreach ($machineWips as $ms)
                                                            <div class="bg-light rounded p-2 mt-1">
                                                                <small>
                                                                    <strong>{{ $ms->wipTracking->batch->batch_number }}</strong>
                                                                    — {{ $ms->wipTracking->partInternal->part_number }}<br>
                                                                    {{ number_format($ms->wipTracking->wip_qty) }} pcs
                                                                    <span
                                                                        class="badge bg-{{ $ms->status == 'running' ? 'primary' : 'secondary' }} ms-1">
                                                                        {{ ucfirst($ms->status) }}
                                                                    </span>
                                                                    <br>
                                                                    Scheduled at : <span class="badge bg-success">{{ \Carbon\Carbon::parse($ms->scheduled_date)->isoFormat('dddd, D MMMM Y') }}</span>
                                                                </small>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <small class="text-muted"><i class="bi bi-dash"></i> Tidak ada WIP
                                                        terjadwal</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-cpu" style="font-size:3rem; color:#dee2e6;"></i>
                                        <p class="text-muted mt-3">Belum ada machine terdaftar</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- History Tab -->
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Batch</th>
                                            <th>Part</th>
                                            <th>Route</th>
                                            <th>Step</th>
                                            <th>Machine</th>
                                            <th>Qty</th>
                                            <th>Started</th>
                                            <th>Finished</th>
                                            <th>Duration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($historyWips as $wip)
                                            <tr>
                                                <td><strong>{{ $wip->batch->batch_number }}</strong></td>
                                                <td>
                                                    {{ $wip->partInternal->part_number }}<br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($wip->partInternal->part_name, 30) }}</small>
                                                </td>
                                                <td><span
                                                        class="badge bg-secondary">#{{ $wip->partOperation->route_order }}</span>
                                                </td>
                                                <td>
                                                    @if ($wip->step == 'quality_check')
                                                        <span class="badge bg-warning">QC</span>
                                                    @else
                                                        <span class="badge bg-info">Process</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($wip->machineSchedule)
                                                        <small>{{ $wip->machineSchedule->machine->name }}</small>
                                                    @else
                                                        <small class="text-muted">-</small>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($wip->wip_qty) }}</td>
                                                <td>{{ $wip->started_at?->format('d/m H:i') ?? '-' }}</td>
                                                <td>{{ $wip->finished_at?->format('d/m H:i') ?? '-' }}</td>
                                                <td>
                                                    @if ($wip->started_at && $wip->finished_at)
                                                        {{ $wip->started_at->diffInHours($wip->finished_at) }}h
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('wip-trackings.show', $wip->id) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
                                                    <i class="bi bi-inbox" style="font-size:2rem; color:#dee2e6;"></i>
                                                    <p class="text-muted mt-2">No completed operations yet</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Assign Machine Modal -->
    <div class="modal fade" id="assignMachineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-cpu"></i> Assign ke Machine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="assignWipId">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Jadwal</label>
                        <input type="date" name="scheduled_date" class="form-control" id="scheduledDate"
                            min="{{ now()->toDateString() }}">
                        <small class="text-muted">Pilih tanggal WIP akan diproses di machine</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Machine</label>
                        <select class="form-select" id="machineSelect" onchange="onMachineChange(this)">
                            <option value="" data-capability="0">-- Pilih Machine --</option>
                            @foreach ($machines->where('status', 'active') as $machine)
                                <option value="{{ $machine->id }}" data-capability="{{ $machine->shift_capability }}">
                                    {{ $machine->name }} ({{ $machine->model }}) — maks. {{ $machine->shift_capability }}
                                    shift
                                    @if ($machine->pic)
                                        | PIC: {{ $machine->pic->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row" id="shiftFields" style="display:none!important">
                        <div class="col-6">
                            <label class="form-label fw-bold">Shift Mulai</label>
                            <select class="form-select" id="shiftStart"></select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Jumlah Shift</label>
                            <select class="form-select" id="shiftCount"></select>
                            <small class="text-muted" id="shiftCapabilityNote"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitAssign()">
                        <i class="bi bi-check2"></i> Assign
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ── Assign Machine ──
        function openAssignModal(wipId, currentMachineId, currentShiftStart, currentShiftCount, currentDate) {
            document.getElementById('assignWipId').value = wipId;
            document.getElementById('scheduledDate').value = currentDate || new Date().toISOString().split('T')[0];

            const select = document.getElementById('machineSelect');
            select.value = currentMachineId || '';
            onMachineChange(select);

            if (currentMachineId) {
                setTimeout(() => {
                    document.getElementById('shiftStart').value = currentShiftStart || '1';
                    document.getElementById('shiftCount').value = currentShiftCount || '1';
                }, 50);
            }

            new bootstrap.Modal(document.getElementById('assignMachineModal')).show();
        }


        function onMachineChange(select) {
            const capability = parseInt(select.options[select.selectedIndex]?.dataset?.capability || 0);
            const shiftFields = document.getElementById('shiftFields');
            const shiftCount = document.getElementById('shiftCount');
            const note = document.getElementById('shiftCapabilityNote');
            const shiftStart = document.getElementById('shiftStart');

            if (capability > 0) {
                shiftFields.style.setProperty('display', 'flex', 'important');
                shiftFields.classList.add('d-flex');

                // Build shift start options (max = capability)
                shiftStart.innerHTML = '';
                for (let i = 1; i <= capability; i++) {
                    shiftStart.innerHTML += `<option value="${i}">Shift ${i}</option>`;
                }

                // Build shift count options (max = capability)
                shiftCount.innerHTML = '';
                for (let i = 1; i <= capability; i++) {
                    shiftCount.innerHTML += `<option value="${i}">${i} shift</option>`;
                }

                note.textContent = `Maks. ${capability} shift untuk machine ini`;
            } else {
                shiftFields.style.setProperty('display', 'none', 'important');
            }
        }

        function submitAssign() {
            const wipId = document.getElementById('assignWipId').value;
            const machineId = document.getElementById('machineSelect').value;
            const scheduledDate = document.getElementById('scheduledDate').value;
            const shiftStart = document.getElementById('shiftStart').value;
            const shiftCount = document.getElementById('shiftCount').value;

            if (!machineId) {
                alert('Pilih machine terlebih dahulu');
                return;
            }
            if (!scheduledDate) {
                alert('Pilih tanggal jadwal');
                return;
            }

            fetch(`/wip-trackings/${wipId}/assign-machine`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        machine_id: machineId,
                        scheduled_date: scheduledDate,
                        shift_start: shiftStart,
                        shift_count: shiftCount,
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('assignMachineModal')).hide();
                        location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                });
        }

        function startWip(wipId, step) {
            if (!confirm('Start operasi ini?')) return;
            fetch(`/wip-trackings/${wipId}/start?step=${step}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert('Gagal: ' + data.message);
                });
        }

        function moveToProcess(wipId) {
            if (!confirm('Pindah ke step process?')) return;
            fetch(`/wip-trackings/${wipId}/change-step`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        step: 'process'
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert('Gagal: ' + data.message);
                });
        }

        function completeWip(wipId) {
            if (!confirm('Tandai operasi ini sebagai selesai?')) return;
            fetch(`/wip-trackings/${wipId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else alert('Gagal: ' + data.message);
                });
        }
    </script>
@endpush
