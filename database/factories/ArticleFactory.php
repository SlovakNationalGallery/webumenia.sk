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
            'author' => $this->faker->name,
            'slug' => $this->faker->unique()->word,
            'title' => $this->faker->word,
            'summary' => $this->faker->sentence,
            'content' => $this->faker->sentence,
            'main_image' => $this->faker->word,
            'title_color' => $this->faker->hexColor,
            'title_shadow' => $this->faker->hexColor,
            'promote' => $this->faker->boolean,
            'publish' => true,    
        ];
    }
}
