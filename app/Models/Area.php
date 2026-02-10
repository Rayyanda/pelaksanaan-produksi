<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //
    protected $fillable = [
        'division_id',
        'foreman_id',
        'name',
        'capacity',
        'operator_count',
        'duration',
        'is_active',
        'description',
        'process_order',
        'equipment',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function foreman()
    {
        return $this->belongsTo(User::class);
    }

    public function partOperations()
    {
        return $this->hasMany(PartOperation::class);
    }
}
