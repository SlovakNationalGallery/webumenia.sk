<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->word,
            'type' => fake()->word,
            'text' => fake()->sentence,
        ];
    }

    public function published()
    {
        return $this->state(['published_at' => $this->faker->dateTime]);
    }

    public function featured()
    {
        return $this->state(['featured' => true]);
    }
}
