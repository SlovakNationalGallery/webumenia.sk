<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Authority>
 */
class AuthorityFactory extends Factory
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
            'type' => 'person',
            'type_organization' => fake()->word,
            'name' => fake()->name,
            'sex' => fake()->word,
            'biography' => fake()->text,
            'birth_place' => fake()->word,
            'birth_date' => fake()->year,
            'death_place' => fake()->word,
            'death_date' => fake()->year,
            'birth_year' => fake()->year,
            'death_year' => fake()->year,
            'has_image' => fake()->boolean,
            'view_count' => fake()->randomNumber,
            'image_source_url' => fake()->url,
            'image_source_label' => fake()->word,
            'created_at' => fake()->date,
            'updated_at' => fake()->date,
        ];
    }
}
