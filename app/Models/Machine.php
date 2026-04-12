<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{

    use SoftDeletes;

    //
    protected $table = 'machines';

    protected $fillable = ['name','model','pic_id','description','status','shift_capability'];

    public function pic()
    {
        return $this->belongsTo(User::class,'pic_id');
    }

    public function machineSchedules()
    {
        return $this->hasMany(MachineSchedule::class);
    }

}
