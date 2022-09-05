<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => fake()->unique()->lexify,
            'work_type' => fake()->word,
            'identifier' => fake()->word,
            'title' => fake()->word,
            'author' => fake()->name,
            'topic' => fake()->word,
            'place' => fake()->word,
            'date_earliest' => fake()->year,
            'date_latest' => fake()->year,
            'dating' => fake()->year,
            'medium' => fake()->word,
            'technique' => fake()->word,
            'gallery' => fake()->word,
            'description' => fake()->word,
            'work_level' => fake()->word,
            'subject' => fake()->word,
            'measurement' => fake()->word,
            'inscription' => fake()->word,
            'related_work_order' => fake()->randomNumber,
            'related_work_total' => fake()->randomNumber,
            'colors' => [
                fake()->hexColor => 1,
            ],
            'created_at' => fake()->date,
            'updated_at' => fake()->date,
        ];
    }
}
