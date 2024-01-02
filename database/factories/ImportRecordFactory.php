<?php

namespace Database\Factories;

use App\ImportRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportRecordFactory extends Factory
{
    protected $model = ImportRecord::class;

    public function definition(): array
    {
        return [
            'filename' => fake()->filePath,
        ];
    }
}
