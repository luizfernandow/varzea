<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChampionshipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\RacerController;
use App\Http\Controllers\RankingController;
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

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
});

Route::prefix('profile')->middleware('auth:sanctum')->group(function (): void {
    Route::post('update', [ProfileController::class, 'update']);
});


Route::prefix('ranking')->group(function (): void {
    Route::get('championships', [RankingController::class, 'championships']);
    Route::get('by-championship/{championship}', [RankingController::class, 'byChampionship']);
});

Route::prefix('races')->group(function (): void {
    Route::get('/', [RaceController::class, 'index']);
    Route::get('/{race}', [RaceController::class, 'show']);
    Route::post('/create', [RaceController::class, 'store']);
    Route::get('/{race}/edit', [RaceController::class, 'edit']);
    Route::put('/update/{id}', [RaceController::class, 'update']);

    Route::get('/select-groups/{race}', [RaceController::class, 'selectGroups']);
    Route::get('/start/{race}', [RaceController::class, 'startRace']);
    Route::get('/start-groups/{race}', [RaceController::class, 'startRaceGroups']);
    Route::post('/save-groups/{id}', [RaceController::class, 'saveGroups']);
    Route::post('/save-racers/{id}', [RaceController::class, 'saveRacers']);
    Route::post('/save-laps-groups/{id}', [RaceController::class, 'saveLapsGroups']);
    Route::post('/save-laps/{id}', [RaceController::class, 'saveLaps']);
});

Route::prefix('championships')->group(function (): void {
    Route::get('/', [ChampionshipController::class, 'index']);
    Route::get('/{id}', [ChampionshipController::class, 'edit']);
    Route::post('/create', [ChampionshipController::class, 'store']);
    Route::put('/update/{id}', [ChampionshipController::class, 'update']);
    Route::delete('/delete/{id}', [ChampionshipController::class, 'destroy']);
});

Route::prefix('racers')->group(function (): void {
    Route::get('/', [RacerController::class, 'index']);
    Route::get('/{id}', [RacerController::class, 'edit']);
    Route::post('/create', [RacerController::class, 'store']);
    Route::put('/update/{id}', [RacerController::class, 'update']);
    Route::delete('/delete/{id}', [RacerController::class, 'destroy']);
});
