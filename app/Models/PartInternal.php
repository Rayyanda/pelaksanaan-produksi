<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartInternal extends Model
{
    //
    protected $fillable = [
        'part_number','nointernal','part_name','matl_spec','matl_req','comp_per_mould',
        'target_qty_waxing','target_qty_mould_room','target_qty_melting',
        'target_qty_heat_treatment','target_qty_cut_off','target_qty_finishing',
        'target_qty_machining','target_qty_quality_control','deoxidation'
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class, 'part_internal_id');
    }

    public function partOperations()
    {
        return $this->hasMany(PartOperation::class, 'part_internal_id');
    }

    // Helper untuk get process capacities
    public function getProcessCapacities(): array
    {
        return [
            'waxing' => $this->target_qty_waxing,
            'mould_room' => $this->target_qty_mould_room,
            'heat_treatment' => $this->target_qty_heat_treatment,
            'melting' => $this->target_qty_melting,
            'cut_off' => $this->target_qty_cut_off,
            'finishing' => $this->target_qty_finishing,
            'machining' => $this->target_qty_machining,
            'quality_control' => $this->target_qty_quality_control,
        ];
    }

}
