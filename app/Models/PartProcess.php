<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartProcess extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'part_processes';

    protected $fillable = [
        'part_internal_id',
        'area_id',
        'part_operation_id',
        'process_order',
        'capacity',
        'operator_count',
        'duration',
        'is_active',
        'equipment',
    ];

    protected $casts = [
        'part_internal_id' => 'integer',
        'area_id' => 'integer',
        'part_operation_id' => 'integer',
        'process_order' => 'integer',
        'capacity' => 'integer',
        'operator_count' => 'integer',
        'duration' => 'integer',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the part internal that owns this process.
     */
    public function partInternal()
    {
        return $this->belongsTo(PartInternal::class, 'part_internal_id');
    }

    /**
     * Get the part operation that owns this process.
     */
    public function partOperation()
    {
        return $this->belongsTo(PartOperation::class, 'part_operation_id');
    }

    /**
     * Get the area for this process.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
