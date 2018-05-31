<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $seconds = Carbon::createFromFormat('H:i:s', '01:12:37')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 1,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        $seconds = Carbon::createFromFormat('H:i:s', '01:15:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 1,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:16:57')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 4,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:13:39')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 3,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:21:53')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 2,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        $seconds = Carbon::createFromFormat('H:i:s', '01:18:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 2,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:23:41')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 7,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Elias
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 1,
	            'racer_id' => 5,
	            'time' => '00:17:21'
	        ]);
        }

        $seconds = Carbon::createFromFormat('H:i:s', '01:25:43')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 5,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        $seconds = Carbon::createFromFormat('H:i:s', '01:19:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 5,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:20:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 8,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:21:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 10,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:48:13')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 9,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        $seconds = Carbon::createFromFormat('H:i:s', '01:23:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 9,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        $seconds = Carbon::createFromFormat('H:i:s', '01:35:32')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 13,
	            'time' => gmdate("H:i:s", $seconds/5)
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

        DB::table('laps')->insert([
            'race_id' => 2,
            'racer_id' => 24,
            'time' => '12:00:00'
        ]);

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

        //Lucas Juliano
        $seconds = Carbon::createFromFormat('H:i:s', '01:15:57')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 11,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Lucas bonde bike
        $seconds = Carbon::createFromFormat('H:i:s', '01:20:08')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 6,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        $seconds = Carbon::createFromFormat('H:i:s', '01:16:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 6,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Duzinho
        $seconds = Carbon::createFromFormat('H:i:s', '01:21:10')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 14,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Ivo
        $seconds = Carbon::createFromFormat('H:i:s', '01:34:11')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 18,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Du bola
        $seconds = Carbon::createFromFormat('H:i:s', '01:39:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 21,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Weber
        $seconds = Carbon::createFromFormat('H:i:s', '01:44:02')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 2,
	            'racer_id' => 23,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Pedro
        $seconds = Carbon::createFromFormat('H:i:s', '01:17:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 12,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }

        // Gabi
        $seconds = Carbon::createFromFormat('H:i:s', '01:22:00')->secondsSinceMidnight();
        for ($i=0; $i < 5 ; $i++) { 
	        DB::table('laps')->insert([
	            'race_id' => 3,
	            'racer_id' => 16,
	            'time' => gmdate("H:i:s", $seconds/5)
	        ]);
        }
    }
}
