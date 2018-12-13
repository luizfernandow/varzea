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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/offline', 'HomeController@offline')->name('offline');

Route::resource('races', 'RaceController');
Route::resource('racers', 'RacerController');

Route::get('/races/select-racers/{id}', 'RaceController@selectRacers')->name('selectRacers');
Route::get('/races/select-groups/{id}', 'RaceController@selectGroups')->name('selectGroups');
Route::post('/races/save-groups/{id}', 'RaceController@saveGroups')->name('saveGroups');
Route::post('/races/start-race/{id}', 'RaceController@startRace')->name('startRace');
Route::post('/races/save-laps/{id}', 'RaceController@saveLaps')->name('saveLaps');