<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\WipTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\DivisionProductionService;
use App\Models\Machine;
use App\Models\MachineSchedule;
use Exception;

class DivisionProductionController extends Controller
{

    protected $divisionProductionService;

    public function __construct(DivisionProductionService $divisionProductionService)
    {
        $this->divisionProductionService = $divisionProductionService;
    }
    //
    public function index(Request $request, $divisionId)
    {
        $status = $request->get('step', 'quality_check');

        // Fetch wip Trackings based on division and status
        $wipTrackings = WipTracking::whereHas('partOperation', function ($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->where('step', $status)
            ->with(['partOperation', 'partInternal', 'batch'])
            ->latest()
            ->paginate(15);

        return view('division-production.index', compact('wipTrackings', 'status', 'divisionId'));
    }


    /**
     * Display division dashboard.
     */
    public function dashboard($slug)
    {
        try {
            $division = Division::where('slug', $slug)->firstOrFail();

            // Get all WIP trackings for this division
            $allWips = WipTracking::with(['partInternal', 'partOperation', 'batch.productionSchedules'])
                ->whereHas('partOperation', function ($q) use ($division) {
                    $q->where('division_id', $division->id);
                })
                ->whereHas('batch', function ($q) {
                    $q->orderBy('target_completed', 'asc');
                }) // Ensure linked to production schedule
                ->whereIn('status', ['waiting', 'in_progress']) // Exclude completed from main view
                ->get();

            // Separate by step
            $qualityCheckWips = $allWips->where('step', 'quality_check');
            $processWips = $allWips->where('step', 'process');

            // Get completed WIPs (history)
            $historyWips = $this->divisionProductionService->getHistoryWips($division->id);

            // Calculate statistics
            $stats = [
                'quality_check' => $qualityCheckWips->count(),
                'process' => $processWips->count(),
                'completed_today' => WipTracking::whereHas('partOperation', function ($q) use ($division) {
                    $q->where('division_id', $division->id);
                })
                    ->where('status', 'completed')
                    ->whereDate('finished_at', today())
                    ->count(),
                'total_wip_qty' => $allWips->sum('wip_qty'),
            ];

            if ($slug == 'machining') {
                $machines = Machine::with([
                    'pic',
                    'machineSchedules' => fn($q) => $q->where('status', '!=', 'done')
                        ->with(['wipTracking.batch', 'wipTracking.partInternal'])
                ])
                    ->where('status', '!=', 'inactive') // tetap tampilkan inactive untuk info
                    ->orderBy('name')
                    ->get();

                // Ganti where di atas jadi tanpa filter agar inactive juga tampil di tab machines
                $machines = Machine::with([
                    'pic',
                    'machineSchedules' => fn($q) => $q->where('status', '!=', 'done')
                        ->with(['wipTracking.batch', 'wipTracking.partInternal'])
                ])
                    ->orderBy('name')
                    ->get();

                $activeMachinesCount = $machines->where('status', 'active')->count();

                return view('machining.dashboard', compact(
                    'division',
                    'qualityCheckWips',
                    'processWips',
                    'historyWips',
                    'stats',
                    'machines',
                    'activeMachinesCount'
                ));
            }

            return view('divisions.dashboard', compact(
                'division',
                'qualityCheckWips',
                'processWips',
                'historyWips',
                'stats'
            ));
        } catch (Exception $e) {
            Log::error('Error loading division dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load division dashboard');
        }
    }

    // Assign atau reassign WIP ke machine
    public function assignToMachine(Request $request, WipTracking $wipTracking)
    {
        $request->validate([
            'machine_id'     => 'required|exists:machines,id',
            'scheduled_date' => 'required|date',
            'shift_start'    => 'required|in:1,2,3',
            'shift_count'    => 'required|integer|min:1|max:3',
        ]);

        $machine = Machine::findOrFail($request->machine_id);

        if ($machine->status === 'inactive') {
            return response()->json(['success' => false, 'message' => 'Machine tidak aktif'], 422);
        }

        if ($request->shift_count > (int) $machine->shift_capability) {
            return response()->json([
                'success' => false,
                'message' => "Machine {$machine->name} hanya support maks. {$machine->shift_capability} shift"
            ], 422);
        }

        MachineSchedule::updateOrCreate(
            ['wip_tracking_id' => $wipTracking->id],
            [
                'machine_id'     => $request->machine_id,
                'assigned_by'    => auth()->id(),
                'scheduled_date' => $request->scheduled_date,
                'shift_start'    => $request->shift_start,
                'shift_count'    => $request->shift_count,
                'status'         => 'queued',
            ]
        );

        return response()->json(['success' => true, 'message' => 'WIP berhasil di-assign ke machine']);
    }

    /**
     * Get WIP summary for division (API).
     */
    public function wipSummary($divisionId)
    {
        try {
            $stats = [
                'waiting' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'waiting')->count(),

                'in_progress' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'in_progress')->count(),

                'completed_today' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'completed')
                    ->whereDate('finished_at', today())
                    ->count(),

                'completed_this_week' => WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'completed')
                    ->whereBetween('finished_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching division WIP summary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch summary'
            ], 500);
        }
    }

    /**
     * Get active batches for division.
     */
    public function activeBatches($divisionId)
    {
        try {
            $batches = WipTracking::with(['batch.partInternal', 'partOperation'])
                ->whereHas('partOperation', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->whereIn('status', ['waiting', 'in_progress'])
                ->get()
                ->pluck('batch')
                ->unique('id')
                ->values();

            return response()->json([
                'success' => true,
                'data' => $batches
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching active batches: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch batches'
            ], 500);
        }
    }

    /**
     * Get performance metrics for division.
     */
    public function performanceMetrics($divisionId)
    {
        try {
            // Average completion time (in hours)
            $completedWips = WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
                ->where('status', 'completed')
                ->whereNotNull('started_at')
                ->whereNotNull('finished_at')
                ->get();

            $avgCompletionTime = 0;
            if ($completedWips->count() > 0) {
                $totalHours = 0;
                foreach ($completedWips as $wip) {
                    $totalHours += $wip->started_at->diffInHours($wip->finished_at);
                }
                $avgCompletionTime = round($totalHours / $completedWips->count(), 2);
            }

            // Completion rate this week
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $totalThisWeek = WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $completedThisWeek = WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
                ->where('status', 'completed')
                ->whereBetween('finished_at', [$startOfWeek, $endOfWeek])
                ->count();

            $completionRate = $totalThisWeek > 0 ? round(($completedThisWeek / $totalThisWeek) * 100, 2) : 0;

            // Total quantity processed this month
            $totalQtyThisMonth = WipTracking::whereHas('partOperation', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
                ->where('status', 'completed')
                ->whereBetween('finished_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('wip_qty');

            $metrics = [
                'avg_completion_time_hours' => $avgCompletionTime,
                'completion_rate_this_week' => $completionRate,
                'total_qty_this_month' => $totalQtyThisMonth,
                'completed_this_week' => $completedThisWeek,
                'total_this_week' => $totalThisWeek,
            ];

            return response()->json([
                'success' => true,
                'data' => $metrics
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching performance metrics: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch metrics'
            ], 500);
        }
    }

    public function wipProcess(Request $request, $wipId)
    {
        $step = $request->get('step', 'quality');

        $wip = WipTracking::with(['partOperation', 'partInternal', 'batch'])->findOrFail($wipId);
        $wip->update([
            'step' => 'process',
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }
}
