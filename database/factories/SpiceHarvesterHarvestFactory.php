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
            'base_url' => $this->faker->url,
            'metadata_prefix' => $this->faker->word,
            'set_spec' => $this->faker->word,
            'set_name' => $this->faker->word,
            'set_description' => $this->faker->word,
            'status_messages' => $this->faker->sentence,
            'initiated' => $this->faker->date,
        ];
    }
}
