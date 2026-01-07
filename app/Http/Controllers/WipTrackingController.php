<?php

namespace App\Http\Controllers;

use App\Models\WipTracking;
use App\Models\PartInternal;
use App\Models\Batch;
use App\Models\BatchOperation;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class WipTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Build query with relationships
            $query = WipTracking::with([
                'partInternal',
                'partOperation.division',
                'batch'
            ]);

            // Apply filters
            if ($request->filled('part_internal_id')) {
                $query->where('part_internal_id', $request->part_internal_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('step')) {
                $query->where('step', $request->step);
            }

            if ($request->filled('batch_id')) {
                $query->where('batch_id', $request->batch_id);
            }

            // Get filtered results
            $wipTrackings = $query->whereNot('status', '=', 'completed')->orderBy('created_at', 'desc')->get();

            // Calculate statistics
            $stats = [
                'in_progress' => WipTracking::where('status', 'in_progress')->count(),
                'waiting' => WipTracking::where('status', 'waiting')->count(),
                'completed' => WipTracking::where('status', 'completed')->count(),
                'total_wip_qty' => WipTracking::groupBy('batch_id')->sum('wip_qty'),
            ];

            // If filters are applied, calculate filtered stats
            if ($request->hasAny(['part_internal_id', 'status', 'step', 'batch_id'])) {
                $filteredQuery = WipTracking::query();

                if ($request->filled('part_internal_id')) {
                    $filteredQuery->where('part_internal_id', $request->part_internal_id);
                }
                if ($request->filled('batch_id')) {
                    $filteredQuery->where('batch_id', $request->batch_id);
                }

                $stats = [
                    'in_progress' => (clone $filteredQuery)->where('status', 'in_progress')->count(),
                    'waiting' => (clone $filteredQuery)->where('status', 'waiting')->count(),
                    'completed' => (clone $filteredQuery)->where('status', 'completed')->count(),
                    'total_wip_qty' => (clone $filteredQuery)->sum('wip_qty'),
                ];
            }

            // Get data for filter dropdowns
            $partInternals = PartInternal::orderBy('part_number')->get();
            $batches = Batch::orderBy('batch_number')->get();

            return view('wip-trackings.index', compact(
                'wipTrackings',
                'stats',
                'partInternals',
                'batches'
            ));
        } catch (Exception $e) {
            Log::error('Error fetching WIP Trackings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load WIP Trackings');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WipTracking $wipTracking)
    {
        $wipTracking->load([
            'partInternal',
            'partOperation.division',
            'batch.poProduction'
        ]);

        return view('wip-trackings.show', compact('wipTracking'));
    }

    /**
     * Start operation (change status to in_progress)
     */
    public function start(WipTracking $wipTracking)
    {
        try {
            if (!$wipTracking->canStart()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This operation cannot be started. Current status: ' . $wipTracking->status_label
                ], 400);
            }

            DB::beginTransaction();

            $wipTracking->update([
                'status' => 'in_progress',
                'started_at' => now()
            ]);

            $wipTracking->batch->markInProgress();

            $processName = optional($wipTracking->partOperation)
                ->division
                ->description;

            if (!$processName) {
                Log::warning('Process name not found for WIP', [
                    'wip_tracking_id' => $wipTracking->id,
                ]);
                return;
            }

            ProductionSchedule::where('process_name', $processName)
                ->where('batch_id','=', $wipTracking->batch_id)
                ->whereNull('actual_start_date')
                ->update([
                    'actual_start_date' => now(),
                    'status' => 'in_progress',
                ]);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Operation started successfully',
                'data' => [
                    'status' => $wipTracking->status,
                    'started_at' => Carbon::parse($wipTracking->started_at)->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error starting WIP operation: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to start operation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete operation (change status to completed)
     */
    public function complete(WipTracking $wipTracking)
    {
        try {
            if (!$wipTracking->canComplete()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This operation cannot be completed. Current status: ' . $wipTracking->status_label
                ], 400);
            }

            DB::beginTransaction();

            $wipTracking->update([
                'status' => 'completed',
                'finished_at' => now()
            ]);

            $newBatchOperation = BatchOperation::create([
                'batch_id' => $wipTracking->batch_id,
                'part_operation_id' => $wipTracking->part_operation_id,
                'qty_target' => $wipTracking->wip_qty,
                'qty_pass' => $wipTracking->wip_qty,
                'operation_date' => now(),
                'operator_id' => Auth::user()->id
            ]);

            $processName = optional($wipTracking->partOperation)
                ->division
                ->description;

            if (!$processName) {
                Log::warning('Process name not found for WIP', [
                    'wip_tracking_id' => $wipTracking->id,
                ]);
                return;
            }

            $actualEndDate = now();

            $schedule = ProductionSchedule::where('process_name', '=',$processName)
                ->where('batch_id','=', $wipTracking->batch_id)
                ->first();

            // Tentukan status (delayed atau completed)
            $status = Carbon::parse($actualEndDate)->gt($schedule->plan_end_date)
                ? 'delayed'
                : 'completed';

            $schedule->update([
                'actual_end_date' => $actualEndDate,
                'actual_qty' => $wipTracking->wip_qty,
                'status' => $status,
                'notes' => $schedule->notes ?? '',
            ]);

            // Auto-create next operation WIP
            $nextWip = $wipTracking->createNextOperation();

            $message = 'Operation completed successfully';
            if ($nextWip) {
                $message .= '. Next operation (Route ' . $nextWip->partOperation->route_order . ') is now ready.';
            } else {
                $message .= '. This was the last operation for this batch.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'status' => $wipTracking->status,
                    'finished_at' => Carbon::parse($wipTracking->finished_at)->format('Y-m-d H:i:s'),
                    'next_wip_created' => $nextWip ? true : false,
                    'next_wip_id' => $nextWip ? $nextWip->id : null
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error completing WIP operation: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete operation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update WIP tracking notes
     */
    public function updateNotes(Request $request, WipTracking $wipTracking)
    {
        $request->validate([
            'operation_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $wipTracking->update([
                'operation_notes' => $request->operation_notes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notes updated successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Error updating WIP notes: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update notes'
            ], 500);
        }
    }

    /**
     * Change WIP step (quality_check / process)
     */
    public function changeStep(Request $request, WipTracking $wipTracking)
    {
        $request->validate([
            'step' => 'required|in:quality_check,process'
        ]);

        try {
            $wipTracking->update([
                'step' => $request->step
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Step changed successfully',
                'data' => [
                    'step' => $wipTracking->step,
                    'step_label' => $wipTracking->step_label
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error changing WIP step: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to change step'
            ], 500);
        }
    }

    /**
     * Reset WIP to waiting status
     */
    public function reset(WipTracking $wipTracking)
    {
        try {
            DB::beginTransaction();

            $wipTracking->update([
                'status' => 'waiting',
                'started_at' => null,
                'finished_at' => null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'WIP tracking reset successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error resetting WIP: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset WIP'
            ], 500);
        }
    }

    /**
     * Export WIP trackings to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = WipTracking::with([
                'partInternal',
                'partOperation.division',
                'batch'
            ]);

            // Apply same filters as index
            if ($request->filled('part_internal_id')) {
                $query->where('part_internal_id', $request->part_internal_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('step')) {
                $query->where('step', $request->step);
            }

            if ($request->filled('batch_id')) {
                $query->where('batch_id', $request->batch_id);
            }

            $wipTrackings = $query->orderBy('created_at', 'desc')->get();

            $filename = 'wip_trackings_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($wipTrackings) {
                $file = fopen('php://output', 'w');

                // Header
                fputcsv($file, [
                    'Batch Number',
                    'Part Number',
                    'Part Name',
                    'Route Order',
                    'Division',
                    'WIP Qty',
                    'Step',
                    'Status',
                    'Started At',
                    'Finished At',
                    'Notes'
                ]);

                // Data
                foreach ($wipTrackings as $wip) {
                    fputcsv($file, [
                        $wip->batch ? $wip->batch->batch_number : '-',
                        $wip->partInternal->part_number,
                        $wip->partInternal->part_name,
                        $wip->partOperation->route_order,
                        $wip->partOperation->division ? $wip->partOperation->division->name : '-',
                        $wip->wip_qty,
                        $wip->step_label,
                        $wip->status_label,
                        $wip->started_at ? Carbon::parse($wip->started_at)->format('Y-m-d') : '-',
                        $wip->finished_at ? Carbon::parse($wip->finished_at)->format('Y-m-d') : '-',
                        $wip->operation_notes ?? '-',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            Log::error('Error exporting WIP Trackings: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to export WIP Trackings');
        }
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:wip_trackings,id',
            'status' => 'required|in:waiting,in_progress,completed'
        ]);

        try {
            DB::beginTransaction();

            $updateData = ['status' => $request->status];

            if ($request->status === 'in_progress') {
                $updateData['started_at'] = now();
            } elseif ($request->status === 'completed') {
                $updateData['finished_at'] = now();
            }

            WipTracking::whereIn('id', $request->ids)->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' WIP tracking(s) updated successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error bulk updating WIP status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update WIP trackings'
            ], 500);
        }
    }

    /**
     * Get WIP summary by division
     */
    public function summaryByDivision()
    {
        try {
            $summary = WipTracking::with('partOperation.division')
                ->get()
                ->groupBy('partOperation.division.name')
                ->map(function ($items, $divisionName) {
                    return [
                        'division' => $divisionName ?: 'No Division',
                        'total' => $items->count(),
                        'waiting' => $items->where('status', 'waiting')->count(),
                        'in_progress' => $items->where('status', 'in_progress')->count(),
                        'completed' => $items->where('status', 'completed')->count(),
                        'total_qty' => $items->sum('wip_qty')
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching WIP summary by division: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch summary'
            ], 500);
        }
    }
}
