<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\AuthorityRelationship>
 */
class AuthorityRelationshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'authority_id' => $this->faker->randomNumber,
            'related_authority_id' => $this->faker->randomNumber,
            'type' => $this->faker->word,
        ];
    }
}
