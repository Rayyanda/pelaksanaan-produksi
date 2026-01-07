<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\WipTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DivisionProductionController extends Controller
{
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
    public function dashboard($divisionId)
    {
        try {
            $division = Division::findOrFail($divisionId);

            // Get all WIP trackings for this division
            $allWips = WipTracking::with(['partInternal', 'partOperation', 'batch.poProduction'])
                ->whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->whereIn('status', ['waiting', 'in_progress']) // Exclude completed from main view
                ->get();

            // Separate by step
            $qualityCheckWips = $allWips->where('step', 'quality_check');
            $processWips = $allWips->where('step', 'process');

            // Get completed WIPs (history)
            $historyWips = WipTracking::with(['partInternal', 'partOperation', 'batch'])
                ->whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->where('status', 'completed')
                ->orderBy('finished_at', 'desc')
                ->limit(50) // Last 50 completed
                ->get();

            // Calculate statistics
            $stats = [
                'quality_check' => $qualityCheckWips->count(),
                'process' => $processWips->count(),
                'completed_today' => WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->where('status', 'completed')
                ->whereDate('finished_at', today())
                ->count(),
                'total_wip_qty' => $allWips->sum('wip_qty'),
            ];

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

    /**
     * Get WIP summary for division (API).
     */
    public function wipSummary($divisionId)
    {
        try {
            $stats = [
                'waiting' => WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'waiting')->count(),

                'in_progress' => WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'in_progress')->count(),

                'completed_today' => WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })->where('status', 'completed')
                  ->whereDate('finished_at', today())
                  ->count(),

                'completed_this_week' => WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
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
                ->whereHas('partOperation', function($q) use ($divisionId) {
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
            $completedWips = WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
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

            $totalThisWeek = WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $completedThisWeek = WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                })
                ->where('status', 'completed')
                ->whereBetween('finished_at', [$startOfWeek, $endOfWeek])
                ->count();

            $completionRate = $totalThisWeek > 0 ? round(($completedThisWeek / $totalThisWeek) * 100, 2) : 0;

            // Total quantity processed this month
            $totalQtyThisMonth = WipTracking::whereHas('partOperation', function($q) use ($divisionId) {
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
