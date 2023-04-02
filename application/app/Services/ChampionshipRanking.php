<?php

namespace App\Services;

use App\Models\Championship;

final class ChampionshipRanking
{
    private const BASE_POINTS = [
        1 => 300,
        2 => 250,
        3 => 200,
        4 => 180,
        5 => 160,
        6 => 140,
        7 => 130,
        8 => 120,
        9 => 110,
        10 => 100,
        11 => 90,
        12 => 80,
        13 => 70,
        14 => 60,
        15 => 50,
        16 => 40,
        17 => 30,
        18 => 20,
        19 => 10
    ];

    public static function getPoints($position): int 
    {
        return self::BASE_POINTS[$position] ?? 1;
    }

    public function generate(Championship $championship): void
    {
        $racersPoint = [];
        foreach ($championship->races as $race) {
            foreach ($race->raceStandings as $racer) {
                if (!isset($racersPoint[$racer->id])) {
                    $racersPoint[$racer->id] = 0;
                }
                $racersPoint[$racer->id] += self::getPoints($racer->position);
            }
        }
        $collection = collect($racersPoint)->map(function (int $points, int $racer_id) {
            return ['racer_id' => $racer_id, 'points' => $points];
        });

        $championship->ranking()->delete();
        $championship->ranking()->createMany($collection);
    }
}
