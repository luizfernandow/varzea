<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lap extends Model
{
    protected $fillable = ['race_id', 'racer_id', 'time'];

    public function racer()
    {
        return $this->belongsTo('App\Models\Racer');
    }

    public function race()
    {
        return $this->belongsTo('App\Models\Race');
    }
}
