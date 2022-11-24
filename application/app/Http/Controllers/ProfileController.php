<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $user->name = $request->name;
        $user->save();

        return response()->json('', 200);
    }
}
