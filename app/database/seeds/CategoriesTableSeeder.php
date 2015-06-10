<?php 
class CategoriesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        DB::table('categories')->truncate();
        
        $now = date("Y-m-d H:i:s");

        $categories = [
            [
                'id' => 1,
                'name' => 'príbehy umenia',
                'order' => 1,
                // 'created_at' => $now,
                // 'updated_at' => $now
            ],
            [
                'id' => 2,
                'name' => 'dielo v detaile',
                'order' => 2,
                // 'created_at' => $now,
                // 'updated_at' => $now
            ],
            [
                'id' => 3,
                'name' => 'z webu umenia vyberá',
                'order' => 3,
                // 'created_at' => $now,
                // 'updated_at' => $now
            ],

        ];

        DB::table('categories')->insert($categories);

	}

}
