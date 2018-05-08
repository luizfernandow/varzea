<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    protected $fillable = ['name', 'type', 'laps', 'hours', 'date_start', 'time_start'];
}
