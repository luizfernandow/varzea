<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Championship;
use App\Models\Racer;
use App\Http\Resources\Championship as ChampionshipResource;
use App\Http\Resources\Ranking;

class RankingController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function championships()
    {
        $championships = Championship::orderBy('id', 'desc')->get();

        return ChampionshipResource::collection($championships);
    }

    public function byChampionship(Championship $championship)
    {
        $racers = Racer::getRankChampionship($championship->id);

        return Ranking::collection($racers)
            ->additional(['championship' => $championship])
            ->response();
    }
}
