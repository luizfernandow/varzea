<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

final class Race extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const TYPE_LAPS = 'laps';
    public const TYPE_HOURS = 'hours';

    protected $fillable = ['name', 'type', 'laps', 'hours', 'group', 'date_start', 'time_start', 'locked', 'championship_id'];

    protected $casts = [
        'deleted_at' => 'datetime',
        'live_data' => 'json'
    ];

    private static $positionPoint = [];

    public function championship(): BelongsTo
    {
        return $this->belongsTo(Championship::class);
    }

    public function lap(): HasMany
    {
        return $this->hasMany(Lap::class);
    }

    public function racersGroup(): HasMany
    {
        return $this->hasMany(RacersGroup::class);
    }

    public function raceStandings(): HasMany
    {
        return $this->hasMany(RaceStandings::class);
    }

    public function getDateStartAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getTimeStartAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function isTypeHours(): bool
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
}
