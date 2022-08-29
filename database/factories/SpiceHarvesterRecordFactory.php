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
            'identifier' => $this->faker->word,
            'item_id' => $this->faker->word,
            'datestamp' => $this->faker->date,
        ];
    }
}
