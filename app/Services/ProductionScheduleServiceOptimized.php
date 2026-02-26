<?php

namespace App\Services;

use App\Models\PartProcess;
use App\Models\ProductionCalendar;
use App\Models\ProductionSchedule;
use App\Models\Batch;
use App\Models\Area;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductionScheduleServiceOptimized
{
    private const WORKING_DAYS_PER_WEEK = 5;
    private const WORKING_HOURS_PER_DAY = 8;
    private const WORKING_MINUTES_PER_DAY = 480;
    private const WORK_START_HOUR = 8;
    private const WORK_END_HOUR = 16;

    // Cache untuk menghindari query berulang
    private $holidayCache = null;
    private $areaCache = null;
    private $existingSchedulesCache = null;
    private $occupiedOperatorsCache = [];

    /**
     * Generate production schedules untuk satu batch
     * OPTIMIZED VERSION dengan caching
     */
    public function generateScheduleForBatch(int $batchId, Carbon $requestedStartDate = null)
    {
        // Reset cache untuk batch baru
        $this->resetCache();

        $batch = Batch::with(['partInternal'])->findOrFail($batchId);

        if (!$batch->partInternal) {
            throw new \Exception('Batch tidak memiliki part internal');
        }

        // Pre-load semua data yang dibutuhkan (bulk loading)
        $this->preloadData($requestedStartDate);

        // Ambil part processes yang aktif
        $partProcesses = PartProcess::with(['area'])
            ->where('part_internal_id', $batch->part_internal_id)
            ->where('is_active', true)
            ->orderBy('process_order')
            ->get();

        if ($partProcesses->isEmpty()) {
            throw new \Exception('Tidak ada part process yang aktif untuk part ini');
        }

        $startDate = $requestedStartDate ?? now();
        $startDate = $this->getNextWorkingDay($startDate->copy());

        $schedules = [];
        $currentDateTime = $startDate->copy()->setTime(self::WORK_START_HOUR, 0);

        foreach ($partProcesses as $index => $process) {
            $scheduleData = $this->findAvailableSlotOptimized(
                $process,
                $currentDateTime,
                $batch->quantity ?? $batch->plan_qty,
                $batchId
            );

            // Simpan production schedule
            $schedule = ProductionSchedule::create([
                'batch_id' => $batchId,
                'process_name' => $process->area->name,
                'duration_weeks' => $scheduleData['duration_weeks'],
                'plan_start_date' => $scheduleData['plan_start_date']->format('Y-m-d'),
                'plan_end_date' => $scheduleData['plan_end_date']->format('Y-m-d'),
                'plan_qty' => $batch->quantity ?? $batch->plan_qty,
                'status' => 'planned',
                'notes' => $this->generateScheduleNotes($process, $scheduleData)
            ]);

            $schedules[] = array_merge(
                $schedule->toArray(),
                [
                    'process_order' => $process->process_order,
                    'area_name' => $process->area->name,
                    'start_time' => $scheduleData['start_time']->format('H:i'),
                    'end_time' => $scheduleData['end_time']->format('H:i'),
                ]
            );

            // Update cache untuk schedule yang baru dibuat
            $this->updateScheduleCache($schedule, $process);

            $currentDateTime = $scheduleData['end_time']->copy();

            if ($currentDateTime->hour >= self::WORK_END_HOUR) {
                $currentDateTime = $this->getNextWorkingDay($currentDateTime->addDay())
                    ->setTime(self::WORK_START_HOUR, 0);
            }
        }

        return $schedules;
    }

    /**
     * Pre-load semua data yang dibutuhkan untuk menghindari N+1 query
     */
    private function preloadData(Carbon $startDate = null)
    {
        $startDate = $startDate ?? now();
        $endDate = $startDate->copy()->addMonths(6); // Load 6 bulan ke depan

        // 1. Load semua holidays sekali saja
        $this->holidayCache = ProductionCalendar::whereBetween('start_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ])->orWhereBetween('end_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ])->get();

        // 2. Load semua areas dengan max_operator
        $this->areaCache = Area::all()->keyBy('id');

        // 3. Load existing schedules yang overlap dengan periode ini
        $this->existingSchedulesCache = DB::table('production_schedules as ps')
            ->join('batches as b', 'ps.batch_id', '=', 'b.id')
            ->join('part_processes as pp', function ($join) {
                $join->on('b.part_internal_id', '=', 'pp.part_internal_id')
                    ->where('pp.is_active', '=', true);
            })
            ->select(
                'ps.id',
                'ps.batch_id',
                'ps.plan_start_date',
                'ps.plan_end_date',
                'ps.status',
                'pp.area_id',
                'pp.operator_count'
            )
            ->where('ps.plan_end_date', '>=', $startDate->format('Y-m-d'))
            ->where('ps.status', '!=', 'completed')
            ->get()
            ->groupBy('area_id');
    }

    /**
     * OPTIMIZED: Cari slot dengan minimal query
     */
    private function findAvailableSlotOptimized(
        PartProcess $process,
        Carbon $preferredStart,
        int $planQty,
        int $currentBatchId
    ): array {
        $area = $this->areaCache[$process->area_id] ?? Area::find($process->area_id);

        // Hitung total durasi
        $totalMinutesNeeded = $this->calculateTotalDuration($process, $planQty);

        $currentDate = $this->getNextWorkingDay($preferredStart->copy());
        $currentTime = $currentDate->copy();

        if ($currentTime->hour < self::WORK_START_HOUR) {
            $currentTime->setTime(self::WORK_START_HOUR, 0);
        } elseif ($currentTime->hour >= self::WORK_END_HOUR) {
            $currentTime = $this->getNextWorkingDay($currentTime->addDay())
                ->setTime(self::WORK_START_HOUR, 0);
        }

        $startTime = null;
        $endTime = null;
        $minutesAllocated = 0;
        $allocationSlots = [];

        // OPTIMASI: Alokasi per hari, bukan per 30 menit!
        while ($minutesAllocated < $totalMinutesNeeded) {
            if (!$this->isWorkingDay($currentTime)) {
                $currentTime = $this->getNextWorkingDay($currentTime->addDay())
                    ->setTime(self::WORK_START_HOUR, 0);
                continue;
            }

            // Cek availability untuk SATU HARI penuh
            $availableOperators = $this->getAvailableOperatorsOptimized(
                $area,
                $currentTime,
                $currentBatchId
            );

            if ($availableOperators < $process->operator_count) {
                // Skip ke hari berikutnya
                $currentTime = $this->getNextWorkingDay($currentTime->addDay())
                    ->setTime(self::WORK_START_HOUR, 0);
                continue;
            }

            // Alokasi untuk hari ini
            $remainingMinutesToday = self::WORKING_MINUTES_PER_DAY;
            $minutesToAllocate = min(
                $totalMinutesNeeded - $minutesAllocated,
                $remainingMinutesToday
            );

            if ($startTime === null) {
                $startTime = $currentTime->copy();
            }

            $slotEnd = $currentTime->copy()
                ->setTime(self::WORK_START_HOUR, 0)
                ->addMinutes($minutesToAllocate);

            // Pastikan tidak melebihi jam kerja
            if ($slotEnd->hour > self::WORK_END_HOUR) {
                $slotEnd->setTime(self::WORK_END_HOUR, 0);
                $minutesToAllocate = $currentTime->copy()
                    ->setTime(self::WORK_START_HOUR, 0)
                    ->diffInMinutes($slotEnd);
            }

            $allocationSlots[] = [
                'date' => $currentTime->format('Y-m-d'),
                'start' => self::WORK_START_HOUR . ':00',
                'end' => $slotEnd->format('H:i'),
                'minutes' => $minutesToAllocate
            ];

            $minutesAllocated += $minutesToAllocate;
            $endTime = $slotEnd->copy();

            // Lanjut ke hari berikutnya
            $currentTime = $this->getNextWorkingDay($currentTime->addDay())
                ->setTime(self::WORK_START_HOUR, 0);
        }

        $workingDays = count($allocationSlots);
        $durationWeeks = ceil($workingDays / self::WORKING_DAYS_PER_WEEK);

        return [
            'plan_start_date' => $startTime,
            'plan_end_date' => $endTime,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_weeks' => $durationWeeks,
            'total_minutes' => $totalMinutesNeeded,
            'allocation_slots' => $allocationSlots
        ];
    }

    /**
     * OPTIMIZED: Cek operator availability dengan cache
     */
    private function getAvailableOperatorsOptimized(
        Area $area,
        Carbon $datetime,
        int $excludeBatchId = null
    ): int {
        $totalOperators = $area->max_operator ?? 0;

        if ($totalOperators === 0) {
            return 0;
        }

        $dateKey = $datetime->format('Y-m-d');
        $cacheKey = "area_{$area->id}_date_{$dateKey}";

        // Cek cache dulu
        if (isset($this->occupiedOperatorsCache[$cacheKey])) {
            return max(0, $totalOperators - $this->occupiedOperatorsCache[$cacheKey]);
        }

        // Hitung dari existing schedules yang sudah di-load
        $occupiedOperators = 0;

        if (isset($this->existingSchedulesCache[$area->id])) {
            foreach ($this->existingSchedulesCache[$area->id] as $schedule) {
                if ($schedule->batch_id == $excludeBatchId) {
                    continue;
                }

                // Cek apakah schedule overlap dengan date ini
                if (
                    $schedule->plan_start_date <= $dateKey &&
                    $schedule->plan_end_date >= $dateKey
                ) {
                    $occupiedOperators += $schedule->operator_count;
                }
            }
        }

        // Simpan ke cache
        $this->occupiedOperatorsCache[$cacheKey] = $occupiedOperators;

        return max(0, $totalOperators - $occupiedOperators);
    }

    /**
     * Update cache setelah schedule baru dibuat
     */
    private function updateScheduleCache($schedule, PartProcess $process)
    {
        $areaId = $process->area_id;

        if (!isset($this->existingSchedulesCache[$areaId])) {
            $this->existingSchedulesCache[$areaId] = collect();
        }

        $this->existingSchedulesCache[$areaId]->push((object)[
            'id' => $schedule->id,
            'batch_id' => $schedule->batch_id,
            'plan_start_date' => $schedule->plan_start_date,
            'plan_end_date' => $schedule->plan_end_date,
            'status' => $schedule->status,
            'area_id' => $areaId,
            'operator_count' => $process->operator_count
        ]);

        // Clear occupied cache untuk range tanggal ini
        $start = Carbon::parse($schedule->plan_start_date);
        $end = Carbon::parse($schedule->plan_end_date);

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $cacheKey = "area_{$areaId}_date_{$date->format('Y-m-d')}";
            unset($this->occupiedOperatorsCache[$cacheKey]);
        }
    }

    /**
     * Reset semua cache
     */
    private function resetCache()
    {
        $this->holidayCache = null;
        $this->areaCache = null;
        $this->existingSchedulesCache = null;
        $this->occupiedOperatorsCache = [];
    }

    /**
     * Cek apakah tanggal tertentu adalah hari kerja (dengan cache)
     */
    private function isWorkingDay(Carbon $date): bool
    {
        if ($date->dayOfWeek === Carbon::SATURDAY || $date->dayOfWeek === Carbon::SUNDAY) {
            return false;
        }

        return !$this->isHolidayOptimized($date);
    }

    /**
     * OPTIMIZED: Cek holiday dari cache
     */
    private function isHolidayOptimized(Carbon $date): bool
    {
        if ($this->holidayCache === null) {
            return false;
        }

        $dateStr = $date->format('Y-m-d');

        foreach ($this->holidayCache as $holiday) {
            if ($holiday->start_date <= $dateStr && $holiday->end_date >= $dateStr) {
                return true;
            }
        }

        return false;
    }

    /**
     * Hitung total durasi produksi
     */
    private function calculateTotalDuration(PartProcess $process, int $planQty): int
    {
        if ($process->capacity && $process->capacity > 0) {
            $cyclesNeeded = ceil($planQty / $process->capacity);
            return $cyclesNeeded * $process->duration;
        }

        return $process->duration * $planQty;
    }

    /**
     * Dapatkan hari kerja berikutnya
     */
    private function getNextWorkingDay(Carbon $date): Carbon
    {
        $nextDate = $date->copy();

        while (!$this->isWorkingDay($nextDate)) {
            $nextDate->addDay();
        }

        return $nextDate;
    }

    /**
     * Generate notes untuk schedule
     */
    private function generateScheduleNotes(PartProcess $process, array $scheduleData): string
    {
        $notes = [];

        $notes[] = "Process Order: {$process->process_order}";

        if ($process->equipment) {
            $notes[] = "Equipment: {$process->equipment}";
        }

        $notes[] = "Operators needed: {$process->operator_count}";

        if ($process->capacity) {
            $notes[] = "Capacity: {$process->capacity} units per {$process->duration} minutes";
        }

        $notes[] = "Total duration: {$scheduleData['total_minutes']} minutes";

        return implode(' | ', $notes);
    }

    /**
     * Generate schedules untuk multiple batches dengan priority
     */
    public function generateSchedulesForMultipleBatches(array $batchIds = null)
    {
        // Reset cache
        $this->resetCache();

        $query = Batch::with(['partInternal'])
            ->whereNotNull('target_completed')
            ->orderBy('target_completed', 'asc');

        if ($batchIds) {
            $query->whereIn('id', $batchIds);
        }

        $batches = $query->get();

        // Pre-load data untuk semua batches
        $earliestDate = now();
        $this->preloadData($earliestDate);

        $allSchedules = [];

        foreach ($batches as $batch) {
            try {
                $schedules = $this->generateScheduleForBatch($batch->id);
                $allSchedules[$batch->id] = [
                    'batch' => $batch,
                    'schedules' => $schedules,
                    'status' => 'success'
                ];
            } catch (\Exception $e) {
                $allSchedules[$batch->id] = [
                    'batch' => $batch,
                    'schedules' => [],
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ];
            }
        }

        return $allSchedules;
    }

    /**
     * Preview schedule (versi optimized)
     */
    public function previewSchedule(int $batchId, Carbon $requestedStartDate = null): array
    {
        $this->resetCache();

        $batch = Batch::with(['partInternal'])->findOrFail($batchId);

        $this->preloadData($requestedStartDate);

        $partProcesses = PartProcess::with(['area'])
            ->where('part_internal_id', $batch->part_internal_id)
            ->where('is_active', true)
            ->orderBy('process_order')
            ->get();

        if ($partProcesses->isEmpty()) {
            throw new \Exception('Tidak ada part process yang aktif untuk part ini');
        }

        $startDate = $requestedStartDate ?? now();
        $startDate = $this->getNextWorkingDay($startDate->copy());

        $preview = [];
        $currentDateTime = $startDate->copy()->setTime(self::WORK_START_HOUR, 0);

        foreach ($partProcesses as $process) {
            $scheduleData = $this->findAvailableSlotOptimized(
                $process,
                $currentDateTime,
                $batch->quantity ?? $batch->plan_qty,
                $batchId
            );

            $preview[] = [
                'process_order' => $process->process_order,
                'process_name' => $process->area->name,
                'area_id' => $process->area_id,
                'operator_needed' => $process->operator_count,
                'equipment' => $process->equipment,
                'plan_start_date' => $scheduleData['plan_start_date']->format('Y-m-d'),
                'plan_end_date' => $scheduleData['plan_end_date']->format('Y-m-d'),
                'start_time' => $scheduleData['start_time']->format('H:i'),
                'end_time' => $scheduleData['end_time']->format('H:i'),
                'duration_weeks' => $scheduleData['duration_weeks'],
                'total_minutes' => $scheduleData['total_minutes'],
                'allocation_slots' => $scheduleData['allocation_slots']
            ];

            $currentDateTime = $scheduleData['end_time']->copy();

            if ($currentDateTime->hour >= self::WORK_END_HOUR) {
                $currentDateTime = $this->getNextWorkingDay($currentDateTime->addDay())
                    ->setTime(self::WORK_START_HOUR, 0);
            }
        }

        return [
            'batch' => $batch,
            'total_processes' => count($preview),
            'earliest_start' => $preview[0]['plan_start_date'] ?? null,
            'latest_end' => end($preview)['plan_end_date'] ?? null,
            'processes' => $preview
        ];
    }

    /**
     * Get resource utilization report (optimized)
     */
    public function getResourceUtilization(Carbon $startDate, Carbon $endDate): array
    {
        $this->resetCache();
        $this->preloadData($startDate);

        $areas = $this->areaCache ?? Area::all();
        $utilization = [];

        foreach ($areas as $area) {
            $current = $startDate->copy();
            $dailyUtilization = [];

            while ($current->lte($endDate)) {
                if ($this->isWorkingDay($current)) {
                    $available = $area->max_operator ?? 0;
                    $used = $this->getAvailableOperatorsOptimized($area, $current);
                    $occupied = $available - $used;

                    $dailyUtilization[] = [
                        'date' => $current->format('Y-m-d'),
                        'day' => $current->format('l'),
                        'total_operators' => $available,
                        'occupied_operators' => $occupied,
                        'available_operators' => $used,
                        'utilization_percentage' => $available > 0 ? round(($occupied / $available) * 100, 2) : 0
                    ];
                }

                $current->addDay();
            }

            $utilization[$area->name] = [
                'area_id' => $area->id,
                'area_name' => $area->name,
                'max_operator' => $area->max_operator,
                'daily_utilization' => $dailyUtilization
            ];
        }

        return $utilization;
    }
}
