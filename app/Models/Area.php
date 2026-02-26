<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //
    protected $table = 'areas';
    protected $fillable = [
        'division_id',
        'foreman_id',
        'name',
        'max_operator',
        'description',
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
