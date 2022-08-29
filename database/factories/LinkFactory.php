<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'label' => $this->faker->sentence,
            'linkable_type' => $this->faker->word,
            'linkable_id' => $this->faker->randomNumber,
        ];
    }
}
