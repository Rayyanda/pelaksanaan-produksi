<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WipTracking extends Model
{
    //
    protected $fillable = [
        'part_internal_id',
        'part_operation_id',
        'batch_id',
        'wip_qty',
        'step',
        'status',
        'started_at',
        'finished_at',
        'operation_notes',
    ];
    protected $casts = [
        'started_at' => 'date',
        'finished_at' => 'date',
    ];

    public function partInternal()
    {
        return $this->belongsTo(PartInternal::class, 'part_internal_id');
    }

    public function partOperation()
    {
        return $this->belongsTo(PartOperation::class, 'part_operation_id');
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function canStart(): bool
    {
        return $this->status === 'waiting';
    }

    /**
     * Check if WIP can be completed.
     */
    public function canComplete(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if WIP is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if WIP is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Get duration in days (if started and finished).
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->started_at || !$this->finished_at) {
            return null;
        }

        return $this->started_at->diffInDays($this->finished_at);
    }

    /**
     * Get the next operation in the sequence.
     */
    public function nextOperation()
    {
        if (!$this->batch_id) {
            return null;
        }

        return static::where('batch_id', $this->batch_id)
            ->whereHas('partOperation', function($q) {
                $q->where('route_order', '>', $this->partOperation->route_order);
            })
            ->orderBy('part_operation_id')
            ->first();
    }

    /**
     * Create WIP tracking for next operation in sequence.
     * Called automatically when current operation is completed.
     */
    public function createNextOperation()
    {
        // Get next operation in routing
        $currentRouteOrder = $this->partOperation->route_order;

        $nextOperation = PartOperation::where('part_internal_id', $this->part_internal_id)
            ->where('route_order', '>', $currentRouteOrder)
            ->orderBy('route_order')
            ->first();

        if (!$nextOperation) {
            // No next operation, this is the last one
            return null;
        }

        // Check if WIP already exists for next operation
        $existingWip = static::where('batch_id', $this->batch_id)
            ->where('part_operation_id', $nextOperation->id)
            ->first();

        if ($existingWip) {
            // Already exists, don't create duplicate
            return $existingWip;
        }

        // Create new WIP for next operation
        $newWip = static::create([
            'part_internal_id' => $this->part_internal_id,
            'part_operation_id' => $nextOperation->id,
            'batch_id' => $this->batch_id,
            'wip_qty' => $this->wip_qty, // Same quantity as current
            'step' => 'quality_check',
            'status' => 'waiting',
        ]);

        return $newWip;
    }

    /**
     * Get the previous operation in the sequence.
     */
    public function previousOperation()
    {
        if (!$this->batch_id) {
            return null;
        }

        return static::where('batch_id', $this->batch_id)
            ->whereHas('partOperation', function($q) {
                $q->where('route_order', '<', $this->partOperation->route_order);
            })
            ->orderBy('part_operation_id', 'desc')
            ->first();
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        // When starting, set started_at if not set
        static::updating(function ($wip) {
            if ($wip->isDirty('status') && $wip->status === 'in_progress' && !$wip->started_at) {
                $wip->started_at = now();
            }

            // When completing, set finished_at if not set
            if ($wip->isDirty('status') && $wip->status === 'completed' && !$wip->finished_at) {
                $wip->finished_at = now();
            }
        });
    }
}
