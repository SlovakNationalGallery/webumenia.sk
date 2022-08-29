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
            'id' => $this->faker->unique()->lexify,
            'type' => 'person',
            'type_organization' => $this->faker->word,
            'name' => $this->faker->name,
            'sex' => $this->faker->word,
            'biography' => $this->faker->text,
            'birth_place' => $this->faker->word,
            'birth_date' => $this->faker->year,
            'death_place' => $this->faker->word,
            'death_date' => $this->faker->year,
            'birth_year' => $this->faker->year,
            'death_year' => $this->faker->year,
            'has_image' => $this->faker->boolean,
            'view_count' => $this->faker->randomNumber,
            'image_source_url' => $this->faker->url,
            'image_source_label' => $this->faker->word,
            'created_at' => $this->faker->date,
            'updated_at' => $this->faker->date,
        ];
    }
}
