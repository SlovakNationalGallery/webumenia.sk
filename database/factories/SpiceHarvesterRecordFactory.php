<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\SpiceHarvesterRecord>
 */
class SpiceHarvesterRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => 'item',
            'identifier' => fake()->word,
            'item_id' => fake()->word,
            'datestamp' => fake()->date,
        ];
    }
}
