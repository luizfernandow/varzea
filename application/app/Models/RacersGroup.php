<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacersGroup extends Model
{
    protected $fillable = ['race_id', 'racer_id', 'group', 'number'];

    public function racer()
    {
        return $this->belongsTo('App\Models\Racer');
    }

    public function race()
    {
        return $this->belongsTo('App\Models\Race');
    }
}
