<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchOperation;
use App\Models\Division;
use App\Models\ProductionSchedule;
use App\Models\WipTracking;
use App\Models\PartOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        //load data disini jika perlu

        $user = Auth::user();

        // Route ke dashboard sesuai role
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'supervisor produksi':
                return $this->supervisorDashboard();
            case 'ppc':
                return $this->ppcDashboard();
            case 'foreman':
                return $this->foremanDashboard();
            case 'operator':
                return $this->operatorDashboard();
            default:
                abort(403, 'Unauthorized role');
        }
    }

    // ==================== ADMIN DASHBOARD ====================
    protected function adminDashboard()
    {
        $data = [
            // Summary Cards
            'totalActiveBatches' => Batch::whereIn('status', ['planned', 'in_progress'])->count(),
            'totalCompletedThisMonth' => Batch::where('status', 'completed')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'totalDelayedProcesses' => ProductionSchedule::where('status', 'delayed')->count(),
            'totalActiveWip' => WipTracking::where('status', 'in_progress')->sum('wip_qty'),

            // Active Batches by Division
            'batchesByDivision' => $this->getBatchesByDivision(),

            // Recent Activities
            'recentBatches' => Batch::with('partInternal')
                ->latest()
                ->take(5)
                ->get(),

            // Delayed Schedules
            'delayedSchedules' => ProductionSchedule::with(['batch.partInternal'])
                ->where('status', 'delayed')
                ->latest()
                ->take(5)
                ->get(),

            // Division Overview
            'divisions' => Division::where('is_active', true)
                ->withCount(['wipTrackings as active_wip' => function ($q) {
                    $q->where('status', 'in_progress');
                }])
                ->get(),
        ];

        return view('dashboard.admin', $data);
    }

    // ==================== SUPERVISOR DASHBOARD ====================
    protected function supervisorDashboard()
    {
        $user = Auth::user();
        $divisionId = $user->division_id;

        if (!$divisionId) {
            abort(403, 'Supervisor must be assigned to a division');
        }

        $division = Division::findOrFail($divisionId);
        $processName = $division->description; // waxing, mould_room, dll

        $data = [
            'division' => $division,

            // Active Schedules di Division ini
            'activeSchedules' => ProductionSchedule::with(['batch.partInternal'])
                ->where('process_name', $processName)
                ->whereIn('status', ['planned', 'in_progress'])
                ->orderBy('plan_start_date')
                ->get(),

            // Delayed Schedules
            'delayedSchedules' => ProductionSchedule::with(['batch.partInternal'])
                ->where('process_name', $processName)
                ->where('status', 'delayed')
                ->get(),

            // Active WIP Tracking
            'activeWip' => WipTracking::with(['partInternal', 'batch', 'partOperation'])
                ->whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->where('status', 'in_progress')
                ->latest()
                ->get(),

            // Summary Stats
            'stats' => [
                'total_active' => ProductionSchedule::where('process_name', $processName)
                    ->whereIn('status', ['planned', 'in_progress'])
                    ->count(),
                'on_time' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'completed')
                    ->whereColumn('actual_end_date', '<=', 'plan_end_date')
                    ->whereMonth('actual_end_date', now()->month)
                    ->count(),
                'delayed' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'delayed')
                    ->count(),
                'wip_qty' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'in_progress')->sum('wip_qty'),
            ],
        ];

        return view('dashboard.supervisor', $data);
    }

    // ==================== PPC DASHBOARD ====================
    protected function ppcDashboard()
    {
        $data = [
            // Upcoming Schedules (2 weeks ahead)
            'upcomingSchedules' => ProductionSchedule::with(['batch.partInternal'])
                ->where('status', 'planned')
                ->whereBetween('plan_start_date', [now(), now()->addWeeks(2)])
                ->orderBy('plan_start_date')
                ->get(),

            // Active Batches
            'activeBatches' => Batch::with(['partInternal', 'productionSchedules'])
                ->whereIn('status', ['planned', 'in_progress'])
                ->latest()
                ->get(),

            // Capacity by Division (this week)
            'divisionCapacity' => $this->getDivisionCapacity(),

            // Summary Stats
            'stats' => [
                'total_batches' => Batch::whereIn('status', ['planned', 'in_progress'])->count(),
                'planned' => Batch::where('status', 'planned')->count(),
                'in_progress' => Batch::where('status', 'in_progress')->count(),
                'this_week_schedules' => ProductionSchedule::whereBetween('plan_start_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            ],

            // Recent Created Batches
            'recentBatches' => Batch::with('partInternal')
                ->latest()
                ->take(8)
                ->get(),
        ];

        return view('dashboard.ppc', $data);
    }

    // ==================== FOREMAN DASHBOARD ====================
    protected function foremanDashboard()
    {
        $user = Auth::user();
        $divisionId = $user->division_id;

        if (!$divisionId) {
            abort(403, 'Foreman must be assigned to a division');
        }

        $division = Division::findOrFail($divisionId);
        $processName = $division->description;

        $data = [
            'division' => $division,

            // Active Schedules di Division ini
            'activeSchedules' => ProductionSchedule::with(['batch.partInternal'])
                ->where('process_name', $processName)
                ->whereIn('status', ['planned', 'in_progress'])
                ->orderBy('plan_start_date')
                ->get(),

            // Delayed Schedules
            'delayedSchedules' => ProductionSchedule::with(['batch.partInternal'])
                ->where('process_name', $processName)
                ->where('status', 'delayed')
                ->get(),

            // Active WIP Tracking dengan operator detail
            'activeWip' => WipTracking::with(['partInternal', 'batch', 'partOperation'])
                ->whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->where('status', 'in_progress')
                ->latest()
                ->get(),

            // Completed Today (untuk monitoring produktivitas)
            'completedToday' => WipTracking::with(['partInternal', 'batch'])
                ->whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->where('status', 'completed')
                ->whereDate('updated_at', today())
                ->latest()
                ->get(),

            // Operator Performance (operator di division ini)
            'operatorPerformance' => $this->getOperatorPerformance($divisionId),

            // Summary Stats
            'stats' => [
                'total_active' => ProductionSchedule::where('process_name', $processName)
                    ->whereIn('status', ['planned', 'in_progress'])
                    ->count(),
                'on_time' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'completed')
                    ->whereColumn('actual_end_date', '<=', 'plan_end_date')
                    ->whereMonth('actual_end_date', now()->month)
                    ->count(),
                'delayed' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'delayed')
                    ->count(),
                'wip_qty' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'in_progress')->sum('wip_qty'),
                'active_operators' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                    ->where('status', 'in_progress')
                    ->distinct('operator_id')
                    ->count('operator_id'),
                'completed_today_count' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                    ->where('status', 'completed')
                    ->whereDate('updated_at', today())
                    ->count(),
            ],
        ];

        return view('dashboard.foreman', $data);
    }

    // ==================== OPERATOR DASHBOARD ====================
    protected function operatorDashboard()
    {
        $user = Auth::user();
        $divisionId = $user->division_id;

        if (!$divisionId) {
            abort(403, 'Operator must be assigned to a division');
        }

        $division = Division::findOrFail($divisionId);
        $processName = $division->description;

        $data = [
            'division' => $division,

            // My Work Queue (task yang bisa dikerjakan hari ini)
            'myWorkQueue' => ProductionSchedule::with(['batch.partInternal'])
                ->where('process_name', $processName)
                ->where('status', 'planned')
                ->where('plan_start_date', '<=', now())
                ->orderBy('plan_start_date')
                ->take(10)
                ->get(),

            // Currently Working On
            'currentWork' => ProductionSchedule::with(['batch.partInternal'])
                ->where('process_name', $processName)
                ->where('status', 'in_progress')
                ->latest('actual_start_date')
                ->get(),

            // Completed Today
            'completedToday' => ProductionSchedule::with(['batch.partInternal'])
                ->where('process_name', $processName)
                ->where('status', 'completed')
                ->whereDate('actual_end_date', today())
                ->latest('actual_end_date')
                ->get(),

            // Active WIP
            'activeWip' => WipTracking::with(['partInternal', 'batch'])
                ->whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->where('status', 'in_progress')
                ->latest()
                ->get(),

            // Stats
            'stats' => [
                'pending_tasks' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'planned')
                    ->where('plan_start_date', '<=', now())
                    ->count(),
                'in_progress' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'in_progress')
                    ->count(),
                'completed_today' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'completed')
                    ->whereDate('actual_end_date', today())
                    ->count(),
                'wip_qty' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'in_progress')->sum('wip_qty'),
            ],
        ];

        return view('dashboard.operator', $data);
    }

    // ==================== HELPER METHODS ====================

    protected function getBatchesByDivision()
    {
        $divisions = Division::where('is_active', true)->get();
        $result = [];

        foreach ($divisions as $division) {
            $processName = $division->description;

            $result[] = [
                'division' => $division->name,
                'active' => ProductionSchedule::where('process_name', $processName)
                    ->whereIn('status', ['planned', 'in_progress'])
                    ->distinct('batch_id')
                    ->count('batch_id'),
                'completed' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'completed')
                    ->whereMonth('updated_at', now()->month)
                    ->distinct('batch_id')
                    ->count('batch_id'),
                'delayed' => ProductionSchedule::where('process_name', $processName)
                    ->where('status', 'delayed')
                    ->distinct('batch_id')
                    ->count('batch_id'),
            ];
        }

        return collect($result);
    }

    protected function getDivisionCapacity()
    {
        $divisions = Division::where('is_active', true)->get();
        $result = [];

        foreach ($divisions as $division) {
            $processName = $division->description;

            $thisWeekSchedules = ProductionSchedule::where('process_name', $processName)
                ->whereBetween('plan_start_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])
                ->count();

            $result[] = [
                'division' => $division->name,
                'this_week_schedules' => $thisWeekSchedules,
                'active_batches' => ProductionSchedule::where('process_name', $processName)
                    ->whereIn('status', ['planned', 'in_progress'])
                    ->distinct('batch_id')
                    ->count('batch_id'),
            ];
        }

        return collect($result);
    }

    /**
     * Get operator performance in a specific division
     */
    protected function getOperatorPerformance($divisionId)
    {
        $operators = \App\Models\User::where('role', 'operator')
            ->where('division_id', $divisionId)
            ->get();

        $result = [];

        foreach ($operators as $operator) {
            $activeWip = WipTracking::whereHas('batchOperation', function ($q) use ($operator) {
                $q->where('operator_id', $operator->id);
            })
                ->where('status', 'in_progress')
                ->count();

            $completedToday = WipTracking::whereHas('batchOperation', function ($q) use ($operator) {
                $q->where('operator_id', $operator->id);
            })
                ->where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count();

            $completedThisWeek = WipTracking::whereHas('batchOperation', function ($q) use ($operator) {
                $q->where('operator_id', $operator->id);
            })
                ->where('status', 'completed')
                ->whereBetween('updated_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])
                ->count();

            $result[] = [
                'operator' => $operator,
                'active_tasks' => $activeWip,
                'completed_today' => $completedToday,
                'completed_this_week' => $completedThisWeek,
                'status' => $activeWip > 0 ? 'working' : 'idle',
            ];
        }

        return collect($result);
    }
}
