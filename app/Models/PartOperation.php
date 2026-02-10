<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartOperation extends Model
{
    //
    protected $fillable = [
        'part_internal_id',
        'division_id',
        'area_id',
        'operation_data',
        'route_order',
    ];

    protected $casts = [
        'operation_date' => 'date',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function partInternal()
    {
        return $this->belongsTo(PartInternal::class, 'part_internal_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    // PartOperation.php
    public function wipTrackings()
    {
        return $this->hasMany(WipTracking::class, 'part_operation_id');
    }

    // Method helper
    public function getWipForBatch($batchId)
    {
        return $this->wipTrackings()
            ->where('batch_id', $batchId)
            ->first();
    }
}
