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


Route::get('/world-championships', 'HomeController@worldChampionships')->name('worldChampionships');
Route::get('/calendar', 'HomeController@calendar')->name('calendar');

Route::resource('races', 'RaceController');
Route::resource('racers', 'RacerController');

Route::get('/races/select-racers/{id}', 'RaceController@selectRacers')->name('selectRacers');
Route::get('/races/select-groups/{id}', 'RaceController@selectGroups')->name('selectGroups');
Route::post('/races/save-groups/{id}', 'RaceController@saveGroups')->name('saveGroups');
Route::post('/races/start-race/{id}', 'RaceController@startRace')->name('startRace');
Route::get('/races/start-race-groups/{id}', 'RaceController@startRaceGroups')->name('startRaceGroups');
Route::post('/races/save-laps/{id}', 'RaceController@saveLaps')->name('saveLaps');
Route::post('/races/save-laps-groups/{id}', 'RaceController@saveLapsGroups')->name('saveLapsGroups');

//store a push subscriber.
Route::post('/push','PushController@store');
//make a push notification.
Route::get('/push','PushController@push')->name('push');