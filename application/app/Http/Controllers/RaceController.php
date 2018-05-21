<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Race;

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
        $races = Race::all();

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
            'laps' => 'required|integer',
        ]);

        $data = $request->all();
        $data['date_start'] = \Carbon\Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d');
        $data['type'] = Race::TYPE_LAPS;
        $data['hours'] = 0;

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

        return view('race.show', ['race' => $race]);
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
            'laps' => 'required|integer',
        ]);

        $data = $request->all();
        $data['date_start'] = \Carbon\Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d');
        $data['type'] = Race::TYPE_LAPS;
        $data['hours'] = 0;

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
}
