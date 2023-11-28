<?php

namespace Database\Factories;

use App\SpatieMedia as Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Media::class;

    public function definition()
    {
        return [
            'disk' => 'media',
            'name' => fake()->word,
            'file_name' => fake()->word,
            'size' => fake()->randomNumber,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ];
    }
}
