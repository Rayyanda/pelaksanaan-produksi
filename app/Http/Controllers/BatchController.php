<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\PoProduction;
use App\Models\PartInternal;
use App\Models\PartOperation;
use App\Models\WipTracking;
use App\Models\ProductionSchedule;
use carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Services\ProductionScheduleService;
use Barryvdh\DomPDF\Facade\Pdf;

class BatchController extends Controller
{

    protected $scheduleService;

    public function __construct(ProductionScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query dasar
        $query = Batch::with([
            'poProduction',
            'partInternal.partOperations',
            'wipTrackings'
        ]);

        // Filter berdasarkan PO Production
        if ($request->filled('po_production_id')) {
            $query->where('po_production_id', $request->po_production_id);
        }

        // Filter berdasarkan Part Internal
        if ($request->filled('part_internal_id')) {
            $query->where('part_internal_id', $request->part_internal_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'target_completed');
        $order = $request->get('order', 'desc');

        $query->orderBy($sortBy, $order);

        $baseQuery = clone $query;

        $pendingBatches = (clone $baseQuery)->where('status', 'pending')->get();
        $inProgressBatches = (clone $baseQuery)->where('status', 'in_progress')->get();
        $completedBatches = (clone $baseQuery)->where('status', 'completed')->get();
        $onHoldBatches = (clone $baseQuery)->where('status', 'on_hold')->get();
        $cancelledBatches = (clone $baseQuery)->where('status', 'cancelled')->get();

        $batches = (clone $baseQuery)->get();

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'in_production' => $inProgressBatches->count(),
            'completed' => $completedBatches->count(),
            'total_qty' => (clone $baseQuery)->sum('quantity'),
            'pending' => $pendingBatches->count(),
            'on_hold' => $onHoldBatches->count(),
            'cancelled' => $cancelledBatches->count(),
        ];

        // Data untuk dropdown filter
        $poProductions = PoProduction::orderBy('po_number', 'desc')->get();
        $partInternals = PartInternal::orderBy('part_number', 'asc')->get();

        return view('batches.index', compact(
            'stats',
            'poProductions',
            'partInternals',
            'batches',
            'pendingBatches',
            'inProgressBatches',
            'completedBatches',
            'onHoldBatches',
            'cancelledBatches'
        ));
    }

    public function approve(Batch $batch)
    {
        // Check permission
        if (!Auth::user()->role == 'admin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Get first operation only (route_order = 1)
        $firstOperation = PartOperation::where('part_internal_id', $batch->part_internal_id)
            ->orderBy('route_order')
            ->first();

        if (!$firstOperation) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No operations defined for this part. Please create operations first.');
        }

        $this->scheduleService->generateScheduleForBatch(
            $batch->id,
            Carbon::parse(now()),
        );

        // Create WIP Tracking only for first operation
        WipTracking::create([
            'part_internal_id' => $batch->part_internal_id,
            'part_operation_id' => $firstOperation->id,
            'batch_id' => $batch->id,
            'wip_qty' => $batch->quantity,
            'step' => 'process',
            'status' => 'waiting',
        ]);

        // Update status
        $batch->status = 'in_progress';
        $batch->approval_manager = Auth::user()->id;
        $batch->approval_manager_at = now();
        $batch->save();
        $batch->poProduction->status = 'on_production';
        $batch->poProduction->save();

        return redirect()->route('batches.index')->with('success', 'Batch approved and production started!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $poProductions = PoProduction::orderBy('po_number')->get();
        $partInternals = PartInternal::orderBy('part_number')->get();

        return view('batches.create', compact('poProductions', 'partInternals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_production_id' => 'required|exists:po_productions,id',
            'part_internal_id' => 'required|exists:part_internals,id',
            'quantity' => 'required|integer|min:1',
            'target_completed' => 'nullable|date',
            'part_no_customer' => 'nullable|string|max:255',
            'part_no_customer_source' => 'nullable|url|max:500',
            'drawing_number' => 'nullable|string|max:255',
            'drawing_number_source' => 'nullable|url|max:500',
        ], [
            'batch_number.required' => 'Batch number is required',
            'batch_number.unique' => 'Batch number already exists',
            'po_production_id.required' => 'PO Production is required',
            'part_internal_id.required' => 'Part Internal is required',
            'quantity.required' => 'Quantity is required',
            'quantity.min' => 'Quantity must be at least 1',
        ]);

        try {
            DB::beginTransaction();

            // Create batch
            $batch = Batch::create($validated);

            // Generate production schedules
            // $this->scheduleService->generateScheduleForBatch(
            //     $batch->id,
            //     Carbon::parse(now()),
            // );


            $batch->poProduction->update([
                'status' => 'scheduled',
            ]);

            DB::commit();

            return redirect()
                ->route('batches.show', $batch->id)
                ->with('success', 'Batch created successfully. Wait for approve manager production');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating Batch: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create Batch: ' . $e->getMessage());
        }
    }

    // Helper method untuk generate production schedules
    protected function generateProductionSchedules(Batch $batch)
    {
        $part = $batch->partInternal;
        $qtyTarget = $batch->quantity;

        // Urutan proses (sequential)
        $processes = $part->getProcessCapacities();

        $startDate = Carbon::parse(now());

        foreach ($processes as $processName => $weeklyCapacity) {
            // Skip jika capacity 0 atau null
            if (!$weeklyCapacity || $weeklyCapacity <= 0) {
                continue;
            }

            // Hitung durasi (bulatkan ke atas)
            $durationWeeks = ceil($qtyTarget / $weeklyCapacity);

            // Hitung end date (durasi dalam minggu, dikurangi 1 hari)
            $endDate = $startDate->copy()->addWeeks($durationWeeks)->subDay();

            ProductionSchedule::create([
                'batch_id' => $batch->id,
                'process_name' => $processName,
                'duration_weeks' => $durationWeeks,
                'plan_start_date' => $startDate->format('Y-m-d'),
                'plan_end_date' => $endDate->format('Y-m-d'),
                'plan_qty' => $qtyTarget,
                'status' => 'planned',
            ]);

            // Next process start setelah current process selesai
            $startDate = $endDate->addDay();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Batch $batch)
    {
        $batch->load([
            'poProduction',
            'partInternal',
            'wipTrackings.partOperation.division'
        ]);

        return view('batches.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batch $batch)
    {
        $batch->load(['poProduction', 'partInternal', 'wipTrackings']);
        $poProductions = PoProduction::orderBy('po_number')->get();
        $partInternals = PartInternal::orderBy('part_number')->get();

        return view('batches.edit', compact('batch', 'poProductions', 'partInternals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|max:255|unique:batches,batch_number,' . $batch->id,
            'po_production_id' => 'required|exists:po_productions,id',
            'part_internal_id' => 'required|exists:part_internals,id',
            'quantity' => 'required|integer|min:1',
            'target_completed' => 'nullable|date',
            'part_no_customer' => 'nullable|string|max:255',
            'part_no_customer_source' => 'nullable|url|max:500',
            'drawing_number' => 'nullable|string|max:255',
            'drawing_number_source' => 'nullable|url|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Check if quantity changed - update all WIP tracking
            if ($batch->quantity != $validated['quantity']) {
                $batch->wipTrackings()->update([
                    'wip_qty' => $validated['quantity']
                ]);
            }

            $batch->update($validated);

            // Re-generate schedules jika qty atau start date berubah
            if ($request->has('regenerate_schedule')) {
                $batch->productionSchedules()->delete();
                $this->generateProductionSchedules($batch);
            }

            DB::commit();

            return redirect()
                ->route('batches.show', $batch->id)
                ->with('success', 'Batch updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating Batch: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update Batch: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        try {
            DB::beginTransaction();

            // Delete will cascade to WIP trackings (cascadeOnDelete in migration)
            $batch->delete();

            DB::commit();

            return redirect()
                ->route('batches.index')
                ->with('success', 'Batch and all related WIP tracking deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Batch: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to delete Batch: ' . $e->getMessage());
        }
    }

    /**
     * Export batches to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Batch::with(['poProduction', 'partInternal', 'wipTrackings']);

            // Apply same filters as index
            if ($request->filled('po_production_id')) {
                $query->where('po_production_id', $request->po_production_id);
            }

            if ($request->filled('part_internal_id')) {
                $query->where('part_internal_id', $request->part_internal_id);
            }

            $batches = $query->orderBy('created_at', 'desc')->get();

            $filename = 'batches_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($batches) {
                $file = fopen('php://output', 'w');

                // Header
                fputcsv($file, [
                    'Batch Number',
                    'PO Number',
                    'Part Number',
                    'Part Name',
                    'Quantity',
                    'Target Completed',
                    'Progress (%)',
                    'Created At'
                ]);

                // Data
                foreach ($batches as $batch) {
                    $totalOps = $batch->wipTrackings->count();
                    $completedOps = $batch->wipTrackings->where('status', 'completed')->count();
                    $percentage = $totalOps > 0 ? round(($completedOps / $totalOps) * 100) : 0;

                    fputcsv($file, [
                        $batch->batch_number,
                        $batch->poProduction->po_number,
                        $batch->partInternal->part_number,
                        $batch->partInternal->part_name,
                        $batch->quantity,
                        $batch->target_completed ?? '-',
                        $percentage . '%',
                        $batch->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            Log::error('Error exporting Batches: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to export Batches');
        }
    }

    public function exportPDF(Batch $batch)
    {
        $partInternal = PartInternal::with(['partOperations.division', 'partOperations.area', 'partOperations.process'])
            ->findOrFail($batch->part_internal_id);

        $pdf = Pdf::loadView('batches.jobcard', compact('partInternal', 'batch'));

        return $pdf->download('Master_Jobcard_' . $partInternal->part_number . '.pdf');
    }

    /**
     * Export single batch detail
     */
    public function exportSingle(Batch $batch)
    {
        try {
            $batch->load(['poProduction', 'partInternal', 'wipTrackings.partOperation.division']);

            $filename = 'batch_' . $batch->batch_number . '_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($batch) {
                $file = fopen('php://output', 'w');

                // Batch Info
                fputcsv($file, ['Batch Information']);
                fputcsv($file, ['Batch Number', $batch->batch_number]);
                fputcsv($file, ['PO Number', $batch->poProduction->po_number]);
                fputcsv($file, ['Part Number', $batch->partInternal->part_number]);
                fputcsv($file, ['Part Name', $batch->partInternal->part_name]);
                fputcsv($file, ['Quantity', $batch->quantity]);
                fputcsv($file, ['Target Completed', $batch->target_completed ?? '-']);
                fputcsv($file, []);

                // WIP Tracking
                fputcsv($file, ['WIP Tracking Details']);
                fputcsv($file, ['Route Order', 'Division', 'Step', 'Status', 'WIP Qty', 'Started', 'Finished']);

                foreach ($batch->wipTrackings->sortBy('partOperation.route_order') as $wip) {
                    fputcsv($file, [
                        $wip->partOperation->route_order,
                        $wip->partOperation->division ? $wip->partOperation->division->name : '-',
                        $wip->step,
                        $wip->status,
                        $wip->wip_qty,
                        $wip->started_at ?? '-',
                        $wip->finished_at ?? '-',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            Log::error('Error exporting Batch detail: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to export Batch detail');
        }
    }

    public function batchesTimeline(Request $request)
    {
        $query = Batch::with(['partInternal', 'productionSchedules']);

        // Filter by part
        if ($request->filled('part_id')) {
            $query->where('part_internal_id', $request->part_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'in_progress']);
        }

        $batches = $query->orderBy('created_at')->get();

        // PERBAIKAN: Cek kalau batches kosong atau gak ada schedule
        if ($batches->isEmpty()) {
            return view('batches.timeline', [
                'batches' => collect(),
                'minDate' => now(),
                'maxDate' => now()->addMonths(3),
            ]);
        }

        // Get all schedules from batches
        $allSchedules = [];
        foreach ($batches as $batch) {
            if ($batch->productionSchedules->isNotEmpty()) {
                $allSchedules = array_merge($allSchedules, $batch->productionSchedules->all());
            }
        }

        // PERBAIKAN: Cek kalau gak ada schedule sama sekali
        if (empty($allSchedules)) {
            // Ambil dari production_start_date batch
            $minDate = $batches->min('production_start_date');
            $maxDate = $batches->max('production_start_date')->addMonths(3);
        } else {
            $allSchedules = collect($allSchedules);
            $minDate = $allSchedules->min('plan_start_date');
            $maxDate = $allSchedules->max('plan_end_date');
        }

        // PERBAIKAN: Final safety check
        if (!$minDate || !$maxDate) {
            $minDate = now();
            $maxDate = now()->addMonths(3);
        }

        return view('batches.timeline', compact('batches', 'minDate', 'maxDate'));
    }
}
