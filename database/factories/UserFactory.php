<?php

namespace Database\Factories;

use App\Enums\FrontendEnum;
use App\Services\Frontend;
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
            'username' => fake()->userName(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'password' => bcrypt(Str::random(10)),
            'remember_token' => Str::random(10),
            'frontend' => FrontendEnum::WEBUMENIA->value,
        ];
    }
}
