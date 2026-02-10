<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionCalendar extends Model
{
    //
    protected $fillable = [
        'start_date',
        'end_date',
        'activity',
        'day_type',
        'working_hours',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'working_hours' => 'integer',
    ];
}
