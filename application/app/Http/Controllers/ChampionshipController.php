<?php

namespace App\Http\Controllers;

use App\Http\Resources\Championship as ChampionshipResource;
use App\Models\Championship;
use Illuminate\Http\Request;

final class ChampionshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $data = $request->all();

        Championship::create($data);

        return response()->json('', 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $championship = Championship::find($id);

        return response()->json($championship, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $data = $request->all();

        $championship = Championship::find($id);
        $championship->fill($data)->save();

        return response()->json('', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Championship::destroy($id);

        return response()->json('', 200);
    }
}
