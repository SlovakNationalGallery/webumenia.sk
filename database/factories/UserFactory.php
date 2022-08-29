<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'username' => $this->faker->userName(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'role' => 'editor',
            'password' => bcrypt(Str::random(10)),
            'remember_token' => Str::random(10),
        ];
    }
}
