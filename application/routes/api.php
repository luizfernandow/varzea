<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');
});


Route::prefix('ranking')->group(function () {
    Route::get('championships', 'RankingController@championships');
    Route::get('by-championship/{championship}', 'RankingController@byChampionship');
});

Route::prefix('races')->group(function () {
    Route::get('/', 'RaceController@index');
    Route::get('/{race}', 'RaceController@show');
});
