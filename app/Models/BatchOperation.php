<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchOperation extends Model
{
    //
    protected $fillable = [
        'batch_id',
        'part_operation_id',
        'qty_target',
        'qty_pass',
        'operation_date',
        'operator_id',
    ];

    protected $casts = [
        'operation_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function partOperation()
    {
        return $this->belongsTo(PartOperation::class, 'part_operation_id');
    }
}
