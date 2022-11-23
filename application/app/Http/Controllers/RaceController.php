<?php

namespace App\Http\Controllers;

use App\Http\Resources\Race as RaceResource;
use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
    }

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

    public function store(Request $request)
    {
        $data = $this->getData($request);
        
        Race::create($data);

        return response()->json('', 201);
    }

    public function edit(Race $race)
    {
        return response()->json($race, 200);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getData($request);
        
        $race = Race::find($id);
        $race->fill($data)->save();

        return response()->json('', 200);
    }

    private function getData(Request $request) {
        $request->validate([
            'name' => 'required|max:255',
            'championship_id' => 'required',
            'date_start' => 'required|date_format:Y-m-d',
            'time_start' => 'required|date_format:H:i',              
            'laps' => 'required_without:type|integer|nullable',        
            'hours' => 'required_with:type|integer|nullable',        
            'group' => 'required_with:type|integer|nullable',
        ]);

        $data = $request->all();
        $data['type'] = isset($data['type']) ? Race::TYPE_HOURS : Race::TYPE_LAPS;

        return $data;
    }
}
