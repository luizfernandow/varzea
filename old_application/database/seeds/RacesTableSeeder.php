<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('races')->insert([
            'name' => 'Rachão 1',
            'type' => 'laps',
            'laps' => 5,
            'date_start' => '2018-02-11',
            'time_start' => '10:00',
            'locked' => true
        ]);

    	DB::table('races')->insert([
            'name' => 'Rachão 2',
            'type' => 'laps',
            'laps' => 5,
            'date_start' => '2018-03-31',
            'time_start' => '10:00',
            'locked' => true
        ]);

    	DB::table('races')->insert([
            'name' => 'Rachão 3',
            'type' => 'laps',
            'laps' => 5,
            'date_start' => '2018-05-6',
            'time_start' => '10:00',
            'locked' => true
        ]);
    }
}