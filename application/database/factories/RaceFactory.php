<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Championship;
use App\Models\Race;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Race>
 */
final class RaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{name: string, type: string, laps: int, date_start: string, time_start: string, championship_id: \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Championship>}
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'type' => Race::TYPE_LAPS,
            'laps' => fake()->numberBetween(1,5),
            'date_start' => fake()->date('Y-m-d'),
            'time_start' => fake()->date('H:i'),
            'championship_id' => Championship::factory(),
        ];
    }
}
