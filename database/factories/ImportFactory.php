<?php

namespace Database\Factories;

use App\Importers\MgImporter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Import>
 */
class ImportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->text(),
            'class_name' => MgImporter::class,
            'dir_path' => '.',
        ];
    }
}
