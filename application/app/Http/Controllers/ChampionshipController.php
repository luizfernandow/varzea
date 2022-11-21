<?php

namespace App\Http\Controllers;

use App\Http\Resources\Championship as ChampionshipResource;
use App\Models\Championship;

class ChampionshipController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $championships = Championship::orderBy('id', 'desc')->get();

        return ChampionshipResource::collection($championships);
    }
}
