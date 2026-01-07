<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoProduction extends Model
{
    //
    protected $fillable = [
        'po_number',
        'po_snapshot',
        'quantity',
        'due_date',
        'po_source',
    ];

    protected $casts = [
        'po_snapshot' => 'array',
        'due_date' => 'date',
    ];

}
