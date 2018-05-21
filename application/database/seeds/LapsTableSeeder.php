<?php

use Illuminate\Database\Seeder;

class LapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // João
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 1,
	            'time' => '00:14:48'
	        ]);
        }

        // Gabriel
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 4,
	            'time' => '00:15:18'
	        ]);
        }

        // Maycon
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 3,
	            'time' => '00:15:36'
	        ]);
        }

        // Beto
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 2,
	            'time' => '00:16:36'
	        ]);
        }

        // Nero
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 7,
	            'time' => '00:16:39'
	        ]);
        }

        // Elias
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 6,
	            'time' => '00:17:21'
	        ]);
        }

        // Cássio
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 8,
	            'time' => '00:18:03'
	        ]);
        }

        // Márcio
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 15,
	            'time' => '00:18:11'
	        ]);
        }

        // Cascão
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 17,
	            'time' => '00:18:24'
	        ]);
        }

        // Oliver
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 10,
	            'time' => '00:19:12'
	        ]);
        }

        // Burin
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 19,
	            'time' => '00:19:30'
	        ]);
        }

        // Rural
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 20,
	            'time' => '00:20:17'
	        ]);
        }

        // Zé Sorveteiro
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 22,
	            'time' => '00:20:22'
	        ]);
        }

        // Osvaldo
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 9,
	            'time' => '00:20:44'
	        ]);
        }

        // Marcelo Kafer
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 13,
	            'time' => '00:20:56'
	        ]);
        }

        // Luiz Fernando
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 24,
	            'time' => '00:23:38'
	        ]);
        }

        // Tecão
        DB::table('laps')->insert([
            'race_id' => 1,
            'racer_id' => 25,
            'time' => '12:00:00'
        ]);

        // Wallan
        DB::table('laps')->insert([
            'race_id' => 1,
            'racer_id' => 26,
            'time' => '12:00:00'
        ]);

        // Léo
        DB::table('laps')->insert([
            'race_id' => 1,
            'racer_id' => 27,
            'time' => '12:00:00'
        ]);
    }
}
