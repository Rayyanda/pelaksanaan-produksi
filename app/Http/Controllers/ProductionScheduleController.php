<?php

namespace App\Http\Controllers;

use App\Services\ProductionScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\ProductionSchedule;
use App\Models\ProductionCalendar;

class ProductionScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ProductionScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Generate schedule untuk satu batch
     * POST /api/production-schedules/generate
     */
    public function generateForBatch(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'nullable|date'
        ]);

        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;

            $schedules = $this->scheduleService->generateScheduleForBatch(
                $request->batch_id,
                $startDate
            );

            return response()->json([
                'success' => true,
                'message' => 'Production schedules berhasil digenerate',
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Generate schedules untuk multiple batches dengan priority
     * POST /api/production-schedules/generate-multiple
     */
    public function generateForMultipleBatches(Request $request)
    {
        $request->validate([
            'batch_ids' => 'nullable|array',
            'batch_ids.*' => 'exists:batches,id'
        ]);

        try {
            $schedules = $this->scheduleService->generateSchedulesForMultipleBatches(
                $request->batch_ids
            );

            $summary = [
                'total_batches' => count($schedules),
                'success_count' => collect($schedules)->where('status', 'success')->count(),
                'failed_count' => collect($schedules)->where('status', 'failed')->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Production schedules berhasil digenerate untuk multiple batches',
                'summary' => $summary,
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Preview schedule sebelum disimpan (JSON untuk API)
     * GET /api/production-schedules/preview
     */
    public function preview(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'nullable|date'
        ]);

        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;

            $preview = $this->scheduleService->previewSchedule(
                $request->batch_id,
                $startDate
            );

            return response()->json([
                'success' => true,
                'data' => $preview
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show preview schedule di Blade view
     * GET /production-schedules/preview-view?batch_id=1&start_date=2024-03-01
     */
    public function previewView(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'nullable|date'
        ]);

        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;

            $preview = $this->scheduleService->previewSchedule(
                $request->batch_id,
                $startDate
            );

            return view('production-schedule-preview', [
                'preview' => $preview
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get resource utilization report
     * GET /api/production-schedules/resource-utilization
     */
    // public function resourceUtilization(Request $request)
    // {
    //     $request->validate([
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date'
    //     ]);

    //     try {
    //         $startDate = Carbon::parse($request->start_date);
    //         $endDate = Carbon::parse($request->end_date);

    //         $utilization = $this->scheduleService->getResourceUtilization($startDate, $endDate);

    //         return response()->json([
    //             'success' => true,
    //             'data' => $utilization
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 400);
    //     }
    // }

    /**
     * Show form untuk select batch (WEB)
     * atau Get all schedules (API)
     * GET /production-schedules
     */
    public function index(Request $request)
    {
        // Jika dari web browser, tampilkan list batches dengan schedules
        if (!$request->expectsJson()) {
            $batches = Batch::with(['partInternal', 'productionSchedules'])
                ->whereHas('productionSchedules') // Only batches yang sudah punya schedule
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            return view('production-schedules.index', [
                'batches' => $batches
            ]);
        }

        // Jika dari API, return JSON
        $query = ProductionSchedule::with(['batch.partInternal']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('plan_start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('plan_end_date', '<=', $request->end_date);
        }

        // Filter by batch
        if ($request->has('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $schedules = $query->orderBy('plan_start_date')->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Update actual production data
     * PUT /api/production-schedules/{id}/update-actual
     */
    public function updateActual(Request $request, $id)
    {
        $request->validate([
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
            'actual_qty' => 'nullable|integer|min:0',
            'status' => 'nullable|in:planned,in_progress,completed,delayed',
            'notes' => 'nullable|string'
        ]);

        try {
            $schedule = ProductionSchedule::findOrFail($id);

            $schedule->update($request->only([
                'actual_start_date',
                'actual_end_date',
                'actual_qty',
                'status',
                'notes'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Production schedule berhasil diupdate',
                'data' => $schedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete schedule
     * DELETE /api/production-schedules/{id}
     */
    public function destroy($id)
    {
        try {
            $schedule = ProductionSchedule::findOrFail($id);
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Production schedule berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get schedule by batch
     * GET /api/production-schedules/batch/{batchId}
     */
    public function getByBatch($batchId)
    {
        try {
            $schedules = ProductionSchedule::where('batch_id', $batchId)
                ->orderBy('plan_start_date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show schedule detail dengan daily breakdown dari database
     * GET /production-schedules/{batchId}/detail
     */
    public function showDetail($batchId)
    {
        try {
            $batch = Batch::with(['partInternal'])->findOrFail($batchId);

            // Ambil semua schedules untuk batch ini
            $schedules = ProductionSchedule::where('batch_id', $batchId)
                ->orderBy('plan_start_date')
                ->get();

            if ($schedules->isEmpty()) {
                return back()->with('error', 'No schedules found for this batch');
            }

            // Load holidays untuk cek hari libur
            $startDate = Carbon::parse($schedules->first()->plan_start_date)->subMonth();
            $endDate = Carbon::parse($schedules->last()->plan_end_date)->addMonth();

            $holidays = ProductionCalendar::where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })->get();

            // Generate daily breakdown untuk setiap schedule
            $schedules->transform(function ($schedule) use ($holidays) {
                $schedule->dailyBreakdown = $this->generateDailyBreakdown(
                    $schedule->plan_start_date,
                    $schedule->plan_end_date,
                    $holidays
                );

                $schedule->workingDays = collect($schedule->dailyBreakdown)
                    ->where('is_working_day', true)
                    ->count();

                return $schedule;
            });

            // Hitung summary
            $totalWorkingDays = $schedules->sum('workingDays');
            $totalDurationWeeks = $schedules->sum('duration_weeks');

            return view('production-schedules.show', [
                'batch' => $batch,
                'schedules' => $schedules,
                'totalWorkingDays' => $totalWorkingDays,
                'totalDurationWeeks' => $totalDurationWeeks
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Generate daily breakdown dari tanggal start sampai end
     */
    private function generateDailyBreakdown($startDate, $endDate, $holidays)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $breakdown = [];
        $workingDayCounter = 0;

        $current = $start->copy();
        while ($current->lte($end)) {
            $isWeekend = $current->dayOfWeek === Carbon::SATURDAY ||
                $current->dayOfWeek === Carbon::SUNDAY;

            $isHoliday = false;
            $holidayName = null;

            if (!$isWeekend) {
                foreach ($holidays as $holiday) {
                    $holidayStart = Carbon::parse($holiday->start_date);
                    $holidayEnd = Carbon::parse($holiday->end_date);

                    if ($current->between($holidayStart, $holidayEnd)) {
                        $isHoliday = true;
                        $holidayName = $holiday->activity;
                        break;
                    }
                }
            }

            $isWorking = !$isWeekend && !$isHoliday;

            if ($isWorking) {
                $workingDayCounter++;
            }

            $breakdown[] = [
                'date' => $current->format('Y-m-d'),
                'day_name' => $current->translatedFormat('l'),
                'day_name_short' => $current->format('D'),
                'is_working_day' => $isWorking,
                'is_weekend' => $isWeekend,
                'is_holiday' => $isHoliday,
                'holiday_name' => $holidayName,
                'working_day_number' => $isWorking ? $workingDayCounter : null
            ];

            $current->addDay();
        }

        return $breakdown;
    }

    /**
     * Reschedule a batch
     * POST /api/production-schedules/reschedule
     */
    public function reschedule(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'new_start_date' => 'required|date'
        ]);

        try {
            // Hapus schedule lama
            ProductionSchedule::where('batch_id', $request->batch_id)->delete();

            // Generate schedule baru
            $schedules = $this->scheduleService->generateScheduleForBatch(
                $request->batch_id,
                Carbon::parse($request->new_start_date)
            );

            return response()->json([
                'success' => true,
                'message' => 'Batch berhasil di-reschedule',
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateUrgent(Request $request, $id)
    {
        $request->validate([
            'is_urgent' => 'required|boolean'
        ]);

        try {
            $schedule = ProductionSchedule::findOrFail($id);
            $schedule->update([
                'is_urgent' => $request->is_urgent
            ]);

            return redirect()->back()->with('success', 'Schedule berhasil di-update');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
