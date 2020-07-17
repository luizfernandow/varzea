<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', function(Request $request) {
    $email = $request->get('email');
    $password = $request->get('password');    

    if (Auth::attempt([
        'email' => $email,
        'password' => $password
    ])) {
        return response()->json('', 204 );
    } else {
        return response()->json([
            'error' => 'invalid_credentials'
        ], 403);
    }
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
