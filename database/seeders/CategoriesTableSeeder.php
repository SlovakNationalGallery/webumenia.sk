<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'príbehy umenia',
                'order' => 1,
            ],
            [
                'id' => 2,
                'name' => 'dielo v detaile',
                'order' => 2,
            ],
            [
                'id' => 3,
                'name' => 'z webu umenia vyberá',
                'order' => 3,
            ],
            [
                'id' => 4,
                'name' => 'výstava',
                'order' => 4,
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
