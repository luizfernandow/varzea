<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChampionshipController;

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
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
});

Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
    Route::post('update', [ProfileController::class, 'update']);
});


Route::prefix('ranking')->group(function () {
    Route::get('championships', [RankingController::class, 'championships']);
    Route::get('by-championship/{championship}', [RankingController::class, 'byChampionship']);
});

Route::prefix('races')->group(function () {
    Route::get('/', [RaceController::class, 'index']);
    Route::get('/{race}', [RaceController::class, 'show']);
});

Route::prefix('championships')->group(function () {
    Route::get('/', [ChampionshipController::class, 'index']);
});