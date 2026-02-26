<?php

namespace App\Services;

use App\Models\PartProcess;
use App\Models\ProductionCalendar;
use App\Models\ProductionSchedule;
use App\Models\Batch;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class ProductionScheduleService
{
    private const WORKING_HOURS_PER_DAY = 8;
    private const WORKING_MINUTES_PER_DAY = 480; // 8 * 60
    private const WORKING_DAYS_PER_WEEK = 5;

    // Cache holidays untuk menghindari query berulang
    private $holidaysCache = null;

    /**
     * Generate production schedules untuk satu batch
     * SIMPLE VERSION - hanya hitung durasi, skip libur
     */
    public function generateScheduleForBatch(int $batchId, Carbon $requestedStartDate = null)
    {
        $batch = Batch::with(['partInternal'])->findOrFail($batchId);

        if (!$batch->partInternal) {
            throw new \Exception('Batch tidak memiliki part internal');
        }

        // Load holidays sekali saja
        $this->loadHolidays();

        // Ambil part processes yang aktif, urut berdasarkan process_order
        $partProcesses = PartProcess::with(['area'])
            ->where('part_internal_id', $batch->part_internal_id)
            ->where('is_active', true)
            ->orderBy('process_order')
            ->get();

        if ($partProcesses->isEmpty()) {
            throw new \Exception('Tidak ada part process yang aktif untuk part ini');
        }

        // Tentukan start date
        $startDate = $requestedStartDate ?? now();
        $currentDate = $this->getNextWorkingDay($startDate->copy());

        $schedules = [];
        $planQty = $batch->quantity ?? $batch->plan_qty;

        foreach ($partProcesses as $process) {
            // Hitung durasi untuk process ini
            $scheduleData = $this->calculateSchedule(
                $process,
                $currentDate,
                $planQty
            );

            // Simpan ke database
            $schedule = ProductionSchedule::create([
                'batch_id' => $batchId,
                'process_name' => $process->area->name,
                'duration_weeks' => $scheduleData['duration_weeks'],
                'plan_start_date' => $scheduleData['plan_start_date']->format('Y-m-d'),
                'plan_end_date' => $scheduleData['plan_end_date']->format('Y-m-d'),
                'plan_qty' => $planQty,
                'status' => 'planned',
                'notes' => $this->generateNotes($process, $scheduleData)
            ]);

            $schedules[] = [
                'id' => $schedule->id,
                'batch_id' => $schedule->batch_id,
                'process_order' => $process->process_order,
                'process_name' => $schedule->process_name,
                'plan_start_date' => $schedule->plan_start_date,
                'plan_end_date' => $schedule->plan_end_date,
                'duration_weeks' => $schedule->duration_weeks,
                'plan_qty' => $schedule->plan_qty,
                'total_minutes' => $scheduleData['total_minutes'],
                'working_days' => $scheduleData['working_days']
            ];

            // Process berikutnya mulai setelah process ini selesai
            $currentDate = $scheduleData['plan_end_date']->copy()->addDay();
            $currentDate = $this->getNextWorkingDay($currentDate);
        }

        return $schedules;
    }

    /**
     * Hitung schedule untuk satu process
     */
    private function calculateSchedule(PartProcess $process, Carbon $startDate, int $planQty): array
    {
        // Hitung total durasi yang dibutuhkan (dalam menit)
        $totalMinutes = $this->calculateTotalDuration($process, $planQty);

        // Hitung berapa hari kerja yang dibutuhkan
        $workingDaysNeeded = ceil($totalMinutes / self::WORKING_MINUTES_PER_DAY);

        // Mulai dari hari kerja terdekat
        $planStartDate = $this->getNextWorkingDay($startDate->copy());

        // Hitung tanggal selesai dengan skip hari libur & weekend
        $planEndDate = $this->calculateEndDate($planStartDate, $workingDaysNeeded);

        // Hitung durasi dalam minggu
        $durationWeeks = ceil($workingDaysNeeded / self::WORKING_DAYS_PER_WEEK);

        return [
            'plan_start_date' => $planStartDate,
            'plan_end_date' => $planEndDate,
            'duration_weeks' => $durationWeeks,
            'total_minutes' => $totalMinutes,
            'working_days' => $workingDaysNeeded
        ];
    }

    /**
     * Hitung total durasi produksi dalam menit
     */
    private function calculateTotalDuration(PartProcess $process, int $planQty): int
    {
        // Jika ada capacity, gunakan untuk optimasi
        if ($process->capacity && $process->capacity > 0) {
            // capacity = unit yang bisa diproduksi per duration
            // Misal: capacity = 10 unit/60 menit
            $cyclesNeeded = ceil($planQty / $process->capacity);
            return $cyclesNeeded * $process->duration;
        }

        // Jika tidak ada capacity, hitung linear
        // duration per unit × quantity
        return $process->duration * $planQty;
    }

    /**
     * Hitung tanggal selesai dengan skip hari libur & weekend
     */
    private function calculateEndDate(Carbon $startDate, int $workingDaysNeeded): Carbon
    {
        $currentDate = $startDate->copy();
        $workingDaysCount = 0;

        // Loop sampai mencapai jumlah hari kerja yang dibutuhkan
        while ($workingDaysCount < $workingDaysNeeded) {
            if ($this->isWorkingDay($currentDate)) {
                $workingDaysCount++;

                // Jika sudah mencapai target, stop
                if ($workingDaysCount >= $workingDaysNeeded) {
                    break;
                }
            }

            $currentDate->addDay();
        }

        return $currentDate;
    }

    /**
     * Load holidays sekali saja untuk menghindari query berulang
     */
    private function loadHolidays()
    {
        if ($this->holidaysCache !== null) {
            return;
        }

        // Load holidays untuk 1 tahun ke depan
        $startDate = now()->subMonth();
        $endDate = now()->addYear();

        $this->holidaysCache = ProductionCalendar::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
        })->get();
    }

    /**
     * Cek apakah tanggal adalah hari kerja
     */
    private function isWorkingDay(Carbon $date): bool
    {
        // Cek weekend (Sabtu & Minggu)
        if ($date->dayOfWeek === Carbon::SATURDAY || $date->dayOfWeek === Carbon::SUNDAY) {
            return false;
        }

        // Cek apakah ada di production calendar (hari libur)
        return !$this->isHoliday($date);
    }

    /**
     * Cek apakah tanggal adalah hari libur
     */
    private function isHoliday(Carbon $date): bool
    {
        if ($this->holidaysCache === null) {
            $this->loadHolidays();
        }

        $dateStr = $date->format('Y-m-d');

        foreach ($this->holidaysCache as $holiday) {
            if ($holiday->start_date <= $dateStr && $holiday->end_date >= $dateStr) {
                return true;
            }
        }

        return false;
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
    private function generateNotes(PartProcess $process, array $scheduleData): string
    {
        $notes = [];

        $notes[] = "Process Order: {$process->process_order}";

        if ($process->equipment) {
            $notes[] = "Equipment: {$process->equipment}";
        }

        if ($process->operator_count) {
            $notes[] = "Operators: {$process->operator_count}";
        }

        if ($process->capacity) {
            $notes[] = "Capacity: {$process->capacity} units/{$process->duration} min";
        }

        $notes[] = "Duration: {$scheduleData['total_minutes']} min ({$scheduleData['working_days']} days)";

        return implode(' | ', $notes);
    }

    /**
     * Generate schedules untuk multiple batches dengan priority
     */
    public function generateSchedulesForMultipleBatches(array $batchIds = null)
    {
        // Reset cache
        $this->holidaysCache = null;

        // Ambil batches berdasarkan priority (target_completed terdekat)
        $query = Batch::with(['partInternal'])
            ->whereNotNull('target_completed')
            ->orderBy('target_completed', 'asc');

        if ($batchIds) {
            $query->whereIn('id', $batchIds);
        }

        $batches = $query->get();

        // Load holidays sekali untuk semua batches
        $this->loadHolidays();

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
     * Preview schedule tanpa save ke database
     */
    public function previewSchedule(int $batchId, Carbon $requestedStartDate = null): array
    {
        $batch = Batch::with(['partInternal'])->findOrFail($batchId);

        if (!$batch->partInternal) {
            throw new \Exception('Batch tidak memiliki part internal');
        }

        $this->loadHolidays();

        $partProcesses = PartProcess::with(['area'])
            ->where('part_internal_id', $batch->part_internal_id)
            ->where('is_active', true)
            ->orderBy('process_order')
            ->get();

        if ($partProcesses->isEmpty()) {
            throw new \Exception('Tidak ada part process yang aktif untuk part ini');
        }

        $startDate = $requestedStartDate ?? now();
        $currentDate = $this->getNextWorkingDay($startDate->copy());

        $preview = [];
        $planQty = $batch->quantity ?? $batch->plan_qty;

        foreach ($partProcesses as $process) {
            $scheduleData = $this->calculateSchedule($process, $currentDate, $planQty);

            $preview[] = [
                'process_order' => $process->process_order,
                'process_name' => $process->area->name,
                'area_id' => $process->area_id,
                'equipment' => $process->equipment,
                'operator_count' => $process->operator_count,
                'capacity' => $process->capacity,
                'duration_per_unit' => $process->duration,
                'plan_start_date' => $scheduleData['plan_start_date']->format('Y-m-d'),
                'plan_end_date' => $scheduleData['plan_end_date']->format('Y-m-d'),
                'duration_weeks' => $scheduleData['duration_weeks'],
                'total_minutes' => $scheduleData['total_minutes'],
                'working_days' => $scheduleData['working_days']
            ];

            $currentDate = $scheduleData['plan_end_date']->copy()->addDay();
            $currentDate = $this->getNextWorkingDay($currentDate);
        }

        return [
            'batch' => [
                'id' => $batch->id,
                'batch_number' => $batch->batch_number ?? "BATCH-{$batch->id}",
                'quantity' => $planQty,
                'target_completed' => $batch->target_completed
            ],
            'total_processes' => count($preview),
            'earliest_start' => $preview[0]['plan_start_date'] ?? null,
            'latest_end' => end($preview)['plan_end_date'] ?? null,
            'processes' => $preview
        ];
    }

    /**
     * Get detail breakdown per hari untuk satu batch (optional)
     */
    public function getDetailedBreakdown(int $batchId, Carbon $requestedStartDate = null): array
    {
        $batch = Batch::with(['partInternal'])->findOrFail($batchId);

        $this->loadHolidays();

        $partProcesses = PartProcess::with(['area'])
            ->where('part_internal_id', $batch->part_internal_id)
            ->where('is_active', true)
            ->orderBy('process_order')
            ->get();

        if ($partProcesses->isEmpty()) {
            throw new \Exception('Tidak ada part process yang aktif untuk part ini');
        }

        $startDate = $requestedStartDate ?? now();
        $currentDate = $this->getNextWorkingDay($startDate->copy());

        $breakdown = [];
        $planQty = $batch->quantity ?? $batch->plan_qty;

        foreach ($partProcesses as $process) {
            $scheduleData = $this->calculateSchedule($process, $currentDate, $planQty);

            // Generate detail per hari
            $period = CarbonPeriod::create(
                $scheduleData['plan_start_date'],
                $scheduleData['plan_end_date']
            );

            $dailyDetail = [];
            foreach ($period as $date) {
                if ($this->isWorkingDay($date)) {
                    $dailyDetail[] = [
                        'date' => $date->format('Y-m-d'),
                        'day_name' => $date->translatedFormat('l'),
                        'is_working_day' => true
                    ];
                } else {
                    $reason = 'Weekend';
                    if ($this->isHoliday($date)) {
                        $holiday = $this->holidaysCache->first(function ($h) use ($date) {
                            $dateStr = $date->format('Y-m-d');
                            return $h->start_date <= $dateStr && $h->end_date >= $dateStr;
                        });
                        $reason = $holiday ? $holiday->activity : 'Holiday';
                    }

                    $dailyDetail[] = [
                        'date' => $date->format('Y-m-d'),
                        'day_name' => $date->translatedFormat('l'),
                        'is_working_day' => false,
                        'reason' => $reason
                    ];
                }
            }

            $breakdown[] = [
                'process_order' => $process->process_order,
                'process_name' => $process->area->name,
                'plan_start_date' => $scheduleData['plan_start_date']->format('Y-m-d'),
                'plan_end_date' => $scheduleData['plan_end_date']->format('Y-m-d'),
                'total_minutes' => $scheduleData['total_minutes'],
                'working_days_needed' => $scheduleData['working_days'],
                'daily_detail' => $dailyDetail
            ];

            $currentDate = $scheduleData['plan_end_date']->copy()->addDay();
            $currentDate = $this->getNextWorkingDay($currentDate);
        }

        return [
            'batch' => $batch,
            'breakdown' => $breakdown
        ];
    }
}
