<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Race extends Model
{
	use SoftDeletes;

	const TYPE_LAPS = 'laps';
	const TYPE_HOURS = 'hours';

    protected $fillable = ['name', 'type', 'laps', 'hours', 'date_start', 'time_start'];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getDateStartAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getTimeStartAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
}
