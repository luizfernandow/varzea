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
    Route::post('login', (new AuthController())->login(...));
    Route::post('logout', (new AuthController())->logout(...));
    Route::get('user', (new AuthController())->user(...));
});

Route::prefix('profile')->middleware('auth:sanctum')->group(function (): void {
    Route::post('update', (new ProfileController())->update(...));
});


Route::prefix('ranking')->group(function (): void {
    Route::get('championships', (new RankingController())->championships(...));
    Route::get('by-championship/{championship}', (new RankingController())->byChampionship(...));
});

Route::prefix('races')->group(function (): void {
    Route::get('/', (new RaceController())->index(...));
    Route::get('/{race}', (new RaceController())->show(...));
    Route::post('/create', (new RaceController())->store(...));
    Route::get('/{race}/edit', (new RaceController())->edit(...));
    Route::put('/update/{id}', (new RaceController())->update(...));

    Route::get('/select-groups/{race}', [RaceController::class, 'selectGroups']);
    Route::get('/start/{race}', [RaceController::class, 'startRace']);
    Route::get('/start-groups/{race}', [RaceController::class, 'startRaceGroups']);
    Route::post('/save-groups/{id}', [RaceController::class, 'saveGroups']);
    Route::post('/save-racers/{id}', [RaceController::class, 'saveRacers']);
    Route::post('/save-laps-groups/{id}', [RaceController::class, 'saveLapsGroups']);
    Route::post('/save-laps/{id}', [RaceController::class, 'saveLaps']);
});

Route::prefix('championships')->group(function (): void {
    Route::get('/', (new ChampionshipController())->index(...));
    Route::get('/{id}', (new ChampionshipController())->edit(...));
    Route::post('/create', (new ChampionshipController())->store(...));
    Route::put('/update/{id}', (new ChampionshipController())->update(...));
    Route::delete('/delete/{id}', (new ChampionshipController())->destroy(...));
});

Route::prefix('racers')->group(function (): void {
    Route::get('/', (new RacerController())->index(...));
    Route::get('/{id}', (new RacerController())->edit(...));
    Route::post('/create', (new RacerController())->store(...));
    Route::put('/update/{id}', (new RacerController())->update(...));
    Route::delete('/delete/{id}', (new RacerController())->destroy(...));
});
