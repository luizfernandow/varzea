<?php

namespace App\Http\Controllers;

use App\Http\Resources\Race as RaceResource;
use App\Models\Race;

class RaceController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $races = Race::orderBy('date_start', 'desc')->get();

        return RaceResource::collection($races);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Race  $race
     * @return \Illuminate\Http\Response
     */
    public function show(Race $race)
    {
        return (new RaceResource($race->load(['lap', 'lap.racer'])))
                ->additional(['race' => $race])
                ->response();
    }
}
