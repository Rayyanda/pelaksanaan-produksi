<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Division extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($division) {
            if (empty($division->code)) {
                $division->code = 'DIV-' . strtoupper(substr(uniqid(), -6));
            }
            if (empty($division->slug)) {
                $division->slug = Str::slug($division->name);
            }
        });
    }

    public function operations()
    {
        return $this->hasManyThrough(PartOperation::class, Area::class, 'division_id', 'area_id', 'id', 'id');
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function activeWipTrackings()
    {
        return WipTracking::whereHas('partOperation.area.division', function ($q) {
            $q->where('division_id', $this->id);
        })->whereIn('status', ['waiting', 'in_progress']);
    }

    /**
     * Get completed WIP trackings for this division.
     */
    public function completedWipTrackings()
    {
        return WipTracking::whereHas('partOperation', function ($q) {
            $q->where('division_id', $this->id);
        })->where('status', 'completed');
    }

    /**
     * Get WIP count by status.
     */
    public function getWipCountByStatus($status)
    {
        return WipTracking::whereHas('partOperation', function ($q) {
            $q->where('division_id', $this->id);
        })->where('status', $status)->count();
    }

    /**
     * Get total WIP quantity for this division.
     */
    public function getTotalWipQty()
    {
        return WipTracking::whereHas('partOperation', function ($q) {
            $q->where('division_id', $this->id);
        })->whereIn('status', ['waiting', 'in_progress'])->sum('wip_qty');
    }

    /**
     * Get workload percentage (active WIPs vs capacity).
     * You can adjust the capacity value based on your needs.
     */
    public function getWorkloadPercentage($capacity = 100)
    {
        $activeCount = $this->activeWipTrackings()->count();
        return $capacity > 0 ? round(($activeCount / $capacity) * 100, 2) : 0;
    }

    public function partOperations()
    {
        return $this->hasMany(PartOperation::class);
    }

    public function wipTrackings()
    {
        return $this->hasManyThrough(
            WipTracking::class,
            PartOperation::class,
            'division_id',
            'part_operation_id'
        );
    }
}
