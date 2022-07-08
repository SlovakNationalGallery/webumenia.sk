<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->call('HarvestsTableSeeder');
        // $this->call('CollectionsTableSeeder');
        $this->call('CategoriesTableSeeder');
        // $this->call('ArticlesTableSeeder');
        // $this->call('SketchbooksTableSeeder');
    }
}
