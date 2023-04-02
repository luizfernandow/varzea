<?php

namespace App\Http\Controllers;

use App\Http\Resources\Championship as ChampionshipResource;
use App\Http\Resources\Ranking;
use App\Models\Championship;
use App\Models\Racer;

final class RankingController extends Controller
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
        return Ranking::collection($championship->ranking()->with('racer')->get())
            ->additional(['championship' => $championship])
            ->response();
    }
}
