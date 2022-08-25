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
        $this->call(UsersTableSeeder::class);
        $this->call(HarvestsTableSeeder::class);
        // $this->call(CollectionsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        // $this->call(ArticlesTableSeeder::class);
        // $this->call(SketchbooksTableSeeder::class);
    }
}
