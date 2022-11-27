<?php

namespace App\Http\Controllers;

use App\Http\Resources\Race as RaceResource;
use App\Models\Race;
use App\Models\Racer;
use App\Models\RacersGroup;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
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

    public function selectGroups(Race $race)
    {
        $racers = Racer::select('id', 'name')->orderBy('name')->get();
        $groups = RacersGroup::where('race_id', '=', $race->id)->get();
        $groups = $groups->mapWithKeys(function ($item) {
            return ["key_{$item['racer_id']}" => $item];
        })->toArray();

        return response()->json(['race' => $race, 'racersById' => $racers->pluck('name', 'id')->toArray(), 'racers' => $racers->toArray(), 'groups' => $groups], 200);
    }

    public function saveGroups(Request $request, $id)
    {
        $racers = $request->racers;
        $group = $request->group;
        $number = $request->number;
        RacersGroup::where('race_id', '=', $id)->delete();
        foreach ($racers as $racerId) {
            RacersGroup::updateOrCreate(
                ['race_id' => $id, 'racer_id' => $racerId],
                ['group' => $group[$racerId], 'number' => $number[$racerId]]
            );
        }
        return response()->json('', 200);
    }

    public function startRaceGroups(Race $race)
    {
        $groups = RacersGroup::where('race_id', '=', $race->id)->get();
        $racers = $groups->mapToGroups(function ($item) {
            $item = $item->toArray();
            $item['racer'] = Racer::where('id', '=', $item['racer_id'])->get()->first()->toArray();  
            $item['racer']['name'] = explode(' ',$item['racer']['name'])[0];
            return [$item['group'] => $item];
        })->toArray();
        
        return response()->json(['race' => $race, 'racers' => $racers], 200);
    }

    private function getData(Request $request)
    {
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
