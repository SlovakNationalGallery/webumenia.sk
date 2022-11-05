<?php

namespace Database\Factories;

use App\Importers\GmuRnlImporter;
use App\Importers\MgImporter;
use App\Importers\OglImporter;
use App\Importers\PnpImporter;
use App\Importers\PnpKarasekImporter;
use App\Importers\PnpTrienaleImporter;
use App\Importers\WebumeniaMgImporter;
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
            'class_name' => fake()->randomElement([
                GmuRnlImporter::class,
                MgImporter::class,
                OglImporter::class,
                PnpImporter::class,
                PnpKarasekImporter::class,
                PnpTrienaleImporter::class,
                WebumeniaMgImporter::class,
            ]),
        ];
    }
}
