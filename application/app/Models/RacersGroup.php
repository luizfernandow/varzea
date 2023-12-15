<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class RacersGroup extends Model
{
    protected $fillable = ['race_id', 'racer_id', 'group', 'number', 'rfid_code'];

    public function racer()
    {
        return $this->belongsTo(\App\Models\Racer::class);
    }

    public function race()
    {
        return $this->belongsTo(\App\Models\Race::class);
    }
}
