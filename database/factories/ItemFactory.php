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
            'id' => $this->faker->unique()->lexify,
            'work_type' => $this->faker->word,
            'identifier' => $this->faker->word,
            'title' => $this->faker->word,
            'author' => $this->faker->name,
            'topic' => $this->faker->word,
            'place' => $this->faker->word,
            'date_earliest' => $this->faker->year,
            'date_latest' => $this->faker->year,
            'dating' => $this->faker->year,
            'medium' => $this->faker->word,
            'technique' => $this->faker->word,
            'gallery' => $this->faker->word,
            'description' => $this->faker->word,
            'work_level' => $this->faker->word,
            'subject' => $this->faker->word,
            'measurement' => $this->faker->word,
            'inscription' => $this->faker->word,
            'related_work_order' => $this->faker->randomNumber,
            'related_work_total' => $this->faker->randomNumber,
            'colors' => [
                $this->faker->hexColor => 1,
            ],
            'created_at' => $this->faker->date,
            'updated_at' => $this->faker->date,
        ];
    }
}
