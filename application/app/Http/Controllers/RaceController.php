<?php

namespace App\Http\Controllers;

use App\Http\Resources\Race as RaceResource;
use App\Models\Race;
use App\Models\Racer;
use App\Models\RacersGroup;
use App\Services\ChampionshipRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class RaceController extends Controller
{
    public function __construct(protected ChampionshipRanking $championshipRanking)
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'getLive', 'startRaceGroups']);
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
        $groups = $groups->mapWithKeys(fn ($item): array => ["key_{$item['racer_id']}" => $item])->toArray();

        return response()->json(['race' => $race, 'racersById' => $racers->pluck('name', 'id')->toArray(), 'racers' => $racers->toArray(), 'groups' => $groups], 200);
    }

    public function saveGroups(Request $request, $id)
    {
        $racers = $request->racers;
        $group = $request->group;
        $number = $request->number;
        $rfid = $request->rfid;
        RacersGroup::where('race_id', '=', $id)->delete();
        foreach ($racers as $racerId) {
            $key = "key_$racerId";
            RacersGroup::updateOrCreate(
                ['race_id' => $id, 'racer_id' => $racerId],
                ['group' => $group[$key], 'number' => $number[$key], 'rfid_code' => $rfid[$key]]
            );
        }
        return response()->json('', 200);
    }

    public function saveRacers(Request $request, $id)
    {
        $racers = $request->racers;
        $number = $request->number;
        $rfid = $request->rfid;
        RacersGroup::where('race_id', '=', $id)->delete();
        $index = 0;
        foreach ($racers as $racerId) {
            $key = "key_$racerId";
            RacersGroup::updateOrCreate(
                ['race_id' => $id, 'racer_id' => $racerId],
                ['group' => ++$index, 'number' => $number[$key], 'rfid_code' => $rfid[$key]]
            );
        }
        return response()->json('', 200);
    }

    public function startRaceGroups(Race $race)
    {
        $groups = RacersGroup::where('race_id', '=', $race->id)->get();
        $racers = $groups->mapToGroups(function ($item): array {
            $item = $item->toArray();
            $item['racer'] = Racer::where('id', '=', $item['racer_id'])->get()->first()->toArray();
            $item['racer']['name'] = explode(' ', (string) $item['racer']['name'])[0];
            return [$item['group'] => $item];
        })->toArray();

        return response()->json(['race' => $race, 'racers' => $racers], 200);
    }

    public function startRace(Race $race)
    {
        $groups = RacersGroup::where('race_id', '=', $race->id)->get();
        $racers = $groups->map(function ($item): array {
            $item = $item->toArray();
            $item['racer'] = Racer::where('id', '=', $item['racer_id'])->get()->first()->toArray();
            $item['racer']['name'] = explode(' ', (string) $item['racer']['name'])[0];
            return $item;
        })->toArray();

        return response()->json(['race' => $race, 'racers' => $racers], 200);
    }

    public function saveLapsGroups(Request $request, $id)
    {
        $race = Race::find($id);
        foreach ($request->except('_token') as $groupLaps) {
            for ($i = 0; $i < $groupLaps['lap']; $i++) {
                DB::table('laps')->insert([
                        'race_id' => $id,
                        'racer_id' => $groupLaps['lapsNumber'][$i],
                        'time' => gmdate("H:i:s", $groupLaps['laps'][$i])
                    ]);
            }
        }
        $race->locked = true;
        $race->save();

        $this->championshipRanking->generate($race->championship);

        return response()->json('', 200);
    }

    public function saveLaps(Request $request, $id)
    {
        $race = Race::find($id);
        foreach ($request->except('_token') as $idRacer => $racer) {
            for ($i = 0; $i < $racer['lap']; $i++) {
                DB::table('laps')->insert([
                        'race_id' => $id,
                        'racer_id' => $idRacer,
                        'time' => gmdate("H:i:s", $racer['laps'][$i])
                    ]);
            }
        }
        $race->locked = true;
        $race->save();

        $this->championshipRanking->generate($race->championship);

        return response()->json('', 200);
    }

    public function saveLive(Request $request, $id)
    {
        $race = Race::find($id);
        $race->live_data = $request->except('_token');
        $race->save();

        return response()->json('', 200);
    }

    public function getLive(Request $request, $id)
    {
        $race = Race::find($id);

        return response()->json($race->live_data, 200);
    }

    private function getData(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'championship_id' => 'required',
            'date_start' => 'required|date_format:Y-m-d',
            'time_start' => 'required|date_format:H:i',
            'laps' => 'required_if:type,false|integer|nullable',
            'hours' => 'required_if:type,true|integer|nullable',
            'group' => 'required_if:type,true|integer|nullable',
        ]);

        $data = $request->all();
        $data['type'] = isset($data['type']) && $data['type'] ? Race::TYPE_HOURS : Race::TYPE_LAPS;

        return $data;
    }
}
