<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ProductionSchedule extends Model
{
    protected $fillable = [
        'batch_id',
        'process_name',
        'duration_weeks',
        'plan_start_date',
        'plan_end_date',
        'plan_qty',
        'actual_start_date',
        'actual_end_date',
        'actual_qty',
        'status',
        'notes',
    ];

    protected $casts = [
        'plan_start_date' => 'date',
        'plan_end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    // Helper untuk cek delay
    public function isDelayed(): bool
    {
        if (!$this->actual_end_date || !$this->plan_end_date) {
            return false;
        }

        return $this->actual_end_date->gt($this->plan_end_date);
    }

    // Helper untuk hitung delay dalam hari
    public function getDelayDays(): int
    {
        if (!$this->isDelayed()) {
            return 0;
        }

        return $this->actual_end_date->diffInDays($this->plan_end_date);
    }

    // Helper untuk nama proses yang lebih readable
    public function getProcessLabel(): string
    {
        $labels = [
            'waxing' => 'Waxing (Injection + Assembly)',
            'mould_room' => 'Mould Room',
            'heat_treatment' => 'Heat Treatment (Dewaxing)',
            'melting' => 'Melting',
            'cut_off' => 'Cut Off',
            'finishing' => 'Finishing',
            'machining' => 'Machining',
            'quality_control' => 'Quality Control',
        ];

        return $labels[$this->process_name] ?? ucwords(str_replace('_', ' ', $this->process_name));
    }
}
