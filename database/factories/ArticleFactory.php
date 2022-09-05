<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author' => fake()->name,
            'slug' => fake()->unique()->word,
            'title' => fake()->word,
            'summary' => fake()->sentence,
            'content' => fake()->sentence,
            'main_image' => fake()->word,
            'title_color' => fake()->hexColor,
            'title_shadow' => fake()->hexColor,
            'promote' => fake()->boolean,
            'publish' => true,
        ];
    }
}
