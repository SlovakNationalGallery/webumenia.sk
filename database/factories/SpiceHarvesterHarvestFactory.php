<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\SpiceHarvesterHarvest>
 */
class SpiceHarvesterHarvestFactory extends Factory
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
            'base_url' => fake()->url,
            'metadata_prefix' => fake()->word,
            'set_spec' => fake()->word,
            'set_name' => fake()->word,
            'set_description' => fake()->word,
            'status_messages' => fake()->sentence,
            'initiated' => fake()->date,
        ];
    }
}
