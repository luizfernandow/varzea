<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Race;
use App\Racer;
use App\RacersGroup;
use Illuminate\Support\Facades\DB;

class RaceController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $races = Race::orderBy('date_start')->get();

        return view('race.index', ['races' => $races]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('race.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'date_start' => 'required|date_format:d/m/Y',
            'time_start' => 'required|date_format:H:i',            
            'laps' => 'required_without:type|integer|nullable',        
            'hours' => 'required_with:type|integer|nullable',        
            'group' => 'required_with:type|integer|nullable',
        ]);

        $data = $request->all();
        
        $data['date_start'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['date_start'])->format('Y-m-d');
        $data['type'] = isset($data['type']) ? Race::TYPE_HOURS : Race::TYPE_LAPS;
        
        Race::create($data);

        return redirect()->route('races.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $race = Race::find($id);
        $timeLaps = $race->lap->mapToGroups(function ($item, $key) {
            return [$item['racer_id'] => $item['time']];
        })->toArray();

        $bestLap = $race->lap->sortBy('time')->first();

        $groups = RacersGroup::where('race_id', '=', $id)->get();
        $racers = $groups->mapToGroups(function ($item) {
            $name = Racer::where('id', '=', $item['racer_id'])->get()->first()->name;  
            return [$item['group'] => $name];
        })->toArray();
    
        return view('race.show', ['race' => $race, 'timeLaps' => $timeLaps, 'bestLap' => $bestLap, 'racers' => $racers]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $race = Race::find($id);

        return view('race.edit', ['race' => $race]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'date_start' => 'required|date_format:d/m/Y',
            'time_start' => 'required|date_format:H:i',              
            'laps' => 'required_without:type|integer|nullable',        
            'hours' => 'required_with:type|integer|nullable',        
            'group' => 'required_with:type|integer|nullable',
        ]);

        $data = $request->all();
        $data['date_start'] = \Carbon\Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d');
        $data['type'] = isset($data['type']) ? Race::TYPE_HOURS : Race::TYPE_LAPS;

        $race = Race::find($id);
        $race->fill($data)->save();

        return redirect()->route('races.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Race::destroy($id);

        return redirect()->route('races.index');
    }

    public function selectRacers($id)
    {
        $racers = Racer::orderBy('name')->pluck('name', 'id')->toArray();

        return view('race.select-racers', ['racers' => $racers, 'id' => $id]);
    }

    public function selectGroups($id)
    {
        $racers = Racer::orderBy('name')->pluck('name', 'id')->toArray();
        $groups = RacersGroup::where('race_id', '=', $id)->get();
        $groups = $groups->mapWithKeys(function ($item) {
            return [$item['racer_id'] => $item];
        })->toArray();

        return view('race.select-groups', ['racers' => $racers, 'id' => $id, 'groups' => $groups]);
    }

    public function saveGroups(Request $request, $id)
    {
        $racers = $request->racers; 
        $group = $request->group;
        $number = $request->number;
        foreach ($racers as $racerId) {
            RacersGroup::updateOrCreate(['race_id' => $id, 'racer_id' => $racerId], 
                                        ['group' => $group[$racerId], 'number' => $number[$racerId]]);
        }
        return redirect()->route('races.index');
    }

    public function startRace(Request $request, $id)
    {
        $race = Race::find($id);
        $racers = Racer::orderBy('name')->find($request->racers)->pluck('name', 'id')->toArray();
        
        return view('race.start-race', ['race' => $race, 'racers' => $racers, 'id' => $id]);
    }

    public function startRaceGroups(Request $request, $id)
    {
        $race = Race::find($id);
        $groups = RacersGroup::where('race_id', '=', $id)->get();
        $racers = $groups->mapToGroups(function ($item) {
            $item = $item->toArray();
            $item['racer'] = Racer::where('id', '=', $item['racer_id'])->get()->first()->toArray();  
            return [$item['group'] => $item];
        })->toArray();
        
        return view('race.start-race-groups', ['race' => $race, 'racers' => $racers, 'id' => $id]);
    }

    public function saveLaps(Request $request, $id)
    {
        $race = Race::find($id);
        foreach ($request->except('_token') as $racerId => $lapsJson) {
            foreach (json_decode($lapsJson, true) as $lapTime) {
                DB::table('laps')->insert([
                    'race_id' => $id,
                    'racer_id' => $racerId,
                    'time' => gmdate("H:i:s", $lapTime)
                ]);
            }
        }
        $race->locked = true;
        $race->save();

        return redirect()->route('races.index');
    }

    public function saveLapsGroups(Request $request, $id)
    {
        $race = Race::find($id);
        foreach ($request->except('_token') as $groupId => $lapsJson) {
            $data = json_decode($lapsJson, true);
            $times = $data[0];
            $numberRacer = $data[1];
            foreach ($times as $key => $lapTime) {
                $rg = RacersGroup::where('race_id', '=', $id)
                                 ->where('group', '=', $groupId)
                                 ->where('number', '=', $numberRacer[$key])->get()->first();

                DB::table('laps')->insert([
                    'race_id' => $id,
                    'racer_id' => $rg->racer_id,
                    'time' => gmdate("H:i:s", $lapTime)
                ]);
            }
        }
        $race->locked = true;
        $race->save();

        return redirect()->route('races.index');
    }
}
