<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/MachineSchedule.php
class MachineSchedule extends Model
{
    protected $fillable = [
        'machine_id', 'wip_tracking_id', 'assigned_by',
        'status', 'actual_start', 'actual_end', 'notes',
        'shift_start', 'shift_count', 'scheduled_date',
    ];

    protected $casts = [
        'actual_start' => 'datetime',
        'actual_end'   => 'datetime',
    ];

    public function machine()     { return $this->belongsTo(Machine::class); }
    public function wipTracking() { return $this->belongsTo(WipTracking::class); }
    public function assignedBy()  { return $this->belongsTo(User::class, 'assigned_by'); }
}
