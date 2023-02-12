<?php

namespace App\Http\Controllers;

use App\Http\Resources\Racer as RacerResource;
use App\Models\Racer;
use Illuminate\Http\Request;

final class RacerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $racers = Racer::orderBy('name', 'asc')->get();

        return RacerResource::collection($racers);
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

        Racer::create($data);

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
        $racer = Racer::find($id);

        return response()->json($racer, 200);
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

        $racer = Racer::find($id);
        $racer->fill($data)->save();

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
        Racer::destroy($id);

        return response()->json('', 200);
    }
}
