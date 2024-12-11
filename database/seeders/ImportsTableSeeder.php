<?php

namespace Database\Seeders;

use App\Import;
use App\Importers\UpmImporter;
use Illuminate\Database\Seeder;

class ImportsTableSeeder extends Seeder
{
    public function run()
    {
        Import::create([
            'name' => 'UPM',
            'dir_path' => 'UPM',
            'iip_dir_path' => 'UPM',
            'class_name' => UpmImporter::class,
            'disk' => 'import_iip',
        ]);
    }
}