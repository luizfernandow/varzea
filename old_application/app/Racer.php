<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Racer extends Model
{
	use SoftDeletes;

	protected $fillable = ['name', 'birthdate', 'weight'];


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

    public static function getRank($year)
    {
        $obj = new self();
        return $obj->select('racers.id', 'racers.name', DB::raw('SUM(CASE 
                WHEN base_points.point is not null  
                    THEN base_points.point
                WHEN position_races.id is not null 
                    THEN 1
                ELSE 0
            END) as points'))
                ->leftJoin('position_races', 'racers.id', '=', 'position_races.id')
                ->leftJoin('races', 'races.id', '=', 'position_races.race_id')
                ->leftJoin('base_points', 'base_points.position', '=', 'position_races.position')
                ->whereYear('races.date_start', '=', $year)
                ->groupBy('racers.id')
                ->groupBy('racers.name')
                ->orderBy('points', 'desc')
                ->get();
    }

    public static function getRankChampionship($championship_id)
    {
        $obj = new self();
        return $obj->select('racers.id', 'racers.name', DB::raw('SUM(CASE 
                WHEN base_points.point is not null  
                    THEN base_points.point
                WHEN position_races.id is not null 
                    THEN 1
                ELSE 0
            END) as points'))
                ->leftJoin('position_races', 'racers.id', '=', 'position_races.id')
                ->leftJoin('races', 'races.id', '=', 'position_races.race_id')
                ->leftJoin('base_points', 'base_points.position', '=', 'position_races.position')
                ->where('races.championship_id', '=', $championship_id)
                ->groupBy('racers.id')
                ->groupBy('racers.name')
                ->orderBy('points', 'desc')
                ->get();
    }
}
 