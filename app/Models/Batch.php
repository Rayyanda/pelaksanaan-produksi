<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'batch_number',
        'po_production_id',
        'part_internal_id',
        'quantity',
        'target_completed',
        'part_no_customer',
        'part_no_customer_source',
        'drawing_number',
        'drawing_number_source',
        'status',
        'approval_manager',
        'approval_manager_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'target_completed' => 'date',
        'approval_manager_at' => 'datetime',
    ];

    /**
     * Get the approval manager that owns the batch.
     */
    public function approvalManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_manager');
    }

    /**
     * Get the PO Production that owns the batch.
     */
    public function poProduction(): BelongsTo
    {
        return $this->belongsTo(PoProduction::class);
    }

    /**
     * Get the Part Internal that owns the batch.
     */
    public function partInternal(): BelongsTo
    {
        return $this->belongsTo(PartInternal::class);
    }

    public function batchOperations(): HasMany
    {
        return $this->hasMany(BatchOperation::class);
    }

    /**
     * Get the WIP trackings for the batch.
     */
    public function wipTrackings(): HasMany
    {
        return $this->hasMany(WipTracking::class);
    }

    /**
     * Get the completion percentage of the batch.
     * Based on total operations, not just created WIP trackings.
     */
    public function getCompletionPercentageAttribute(): int
    {
        $totalOps = $this->partInternal->partOperations()->count();
        if ($totalOps === 0) return 0;

        $completed = $this->wipTrackings()->where('status', 'completed')->count();
        return round(($completed / $totalOps) * 100);
    }

    public function productionSchedules(): HasMany
    {
        return $this->hasMany(ProductionSchedule::class);
    }

    public function markInProgress()
    {
        if ($this->status === 'pending') {
            $this->update(['status' => 'in_progress']);
        }
    }


    /**
     * Check if batch is completed.
     * All operations must have completed WIP trackings.
     */
    public function isCompleted(): bool
    {
        $totalOps = $this->partInternal->partOperations()->count();
        if ($totalOps === 0) return false;

        $completed = $this->wipTrackings()->where('status', 'completed')->count();
        return $completed === $totalOps;
    }

    /**
     * Check if batch is in production.
     */
    public function isInProduction(): bool
    {
        return $this->wipTrackings()->where('status', 'in_progress')->exists();
    }

    /**
     * Get batch status.
     */
    public function getStatusAttribute(): string
    {
        if ($this->isCompleted()) {
            return 'completed';
        } elseif ($this->isInProduction()) {
            return 'in_production';
        }
        return 'pending';
    }

    /**
     * Get batch status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'success',
            'in_production' => 'primary',
            'pending' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Scope to filter batches by PO.
     */
    public function scopeForPo($query, $poProductionId)
    {
        return $query->where('po_production_id', $poProductionId);
    }

    /**
     * Scope to filter batches by Part.
     */
    public function scopeForPart($query, $partInternalId)
    {
        return $query->where('part_internal_id', $partInternalId);
    }

    /**
     * Scope to filter completed batches.
     */
    public function scopeCompleted($query)
    {
        return $query->whereHas('wipTrackings', function ($q) {
            $q->groupBy('batch_id')
                ->havingRaw('COUNT(*) = SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END)');
        });
    }

    /**
     * Scope to filter batches in production.
     */
    public function scopeInProduction($query)
    {
        return $query->whereHas('wipTrackings', function ($q) {
            $q->where('status', 'in_progress');
        });
    }

    /**
     * Scope to filter overdue batches.
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('target_completed')
            ->where('target_completed', '<', now())
            ->whereHas('wipTrackings', function ($q) {
                $q->where('status', '!=', 'completed');
            });
    }

    /**
     * Get days remaining until target completion.
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->target_completed) {
            return null;
        }

        return now()->diffInDays($this->target_completed, false);
    }

    /**
     * Check if batch is overdue.
     */
    public function isOverdue(): bool
    {
        if (!$this->target_completed || $this->isCompleted()) {
            return false;
        }

        return now()->isAfter($this->target_completed);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (empty($model->batch_number)) {

                $datePart = now()->format('dmY'); // tglblnthn
                $randomPart = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);

                $model->batch_number = $datePart . '-' . $randomPart;
            }
        });
    }
}
