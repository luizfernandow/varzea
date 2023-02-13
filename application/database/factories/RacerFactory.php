<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Racer>
 */
final class RacerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{name: string}
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
        ];
    }
}
