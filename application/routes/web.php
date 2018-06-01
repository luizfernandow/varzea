<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('races', 'RaceController');
Route::resource('racers', 'RacerController');

Route::get('/races/select-racers/{id}', 'RaceController@selectRacers')->name('selectRacers');
Route::post('/races/start-race/{id}', 'RaceController@startRace')->name('startRace');