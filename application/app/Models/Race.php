<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Race extends Model
{
	use SoftDeletes;

	const TYPE_LAPS = 'laps';
	const TYPE_HOURS = 'hours';

    protected $fillable = ['name', 'type', 'laps', 'hours', 'group', 'date_start', 'time_start', 'locked', 'championship_id'];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    private static $positionPoint = [];

    public function lap()
    {
        return $this->hasMany('App\Models\Lap');
    }

    public function getDateStartAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getTimeStartAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function isTypeHours()
    {
        return $this->type == self::TYPE_HOURS;
    }

    public function getRank()
    {
        return $this->lap()
                ->select('racer_id', DB::raw('SUM(time) as time'), DB::raw('count(racer_id) as laps'))
                ->join('racers', 'laps.racer_id', '=', 'racers.id')
                ->whereRaw('racers.deleted_at is null')
                ->groupBy('racer_id')
                ->orderBy('laps', 'desc')
                ->orderBy('time', 'asc')
                ->get();
    }

    public function getRankGroup()
    {
        return $this->lap()
                ->select('group', DB::raw('SUM(time) as time'), DB::raw('count("group") as laps'))
                ->join('racers', 'laps.racer_id', '=', 'racers.id')
                ->join('racers_groups', 'laps.racer_id', '=', 'racers_groups.racer_id')
                ->whereRaw('racers.deleted_at is null')
                ->where('racers_groups.race_id', '=', $this->id)
                ->groupBy('group')
                ->orderBy('laps', 'desc')
                ->orderBy('time', 'asc')
                ->get();
    }

    public static function getBasePoint($positionIndex)
    {
        if (empty(self::$positionPoint)) {
            self::$positionPoint = DB::table('base_points')->get(); 
        }

        return isset(self::$positionPoint[$positionIndex]) ? self::$positionPoint[$positionIndex]->point : 1;
    }
}
