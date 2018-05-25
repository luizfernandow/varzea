<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function laps()
    {
        return $this->hasMany('App\Lap');
    }

    public function getDateStartAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getTimeStartAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getRank()
    {
        return $this->laps()
                ->select('racer_id', DB::raw('SUM(time) as time'), DB::raw('count(racer_id) as laps'))
                ->where('race_id', $this->id)
                ->groupBy('racer_id')
                ->orderBy('laps', 'desc')
                ->orderBy('time', 'asc')
                ->get();
    }
}
