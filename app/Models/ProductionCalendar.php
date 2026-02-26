<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionCalendar extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'start_date',
        'end_date',
        'activity',
        'day_type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
