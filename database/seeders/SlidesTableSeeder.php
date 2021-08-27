<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SlidesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        DB::table('slides')->truncate();
        
        $now = date("Y-m-d H:i:s");

        $slides = [
            [
                'title' => 'Grafické kabinety',
                'subtitle' => 'v Mirbachovom paláci',
                'url' => URL::to('/kolekcia/51'),
                'publish' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'Zima na Slovensku',
                'subtitle' => NULL,
                'url' => URL::to('/kolekcia/50'),
                'image' => 'zima',
                'publish' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'Skicáre',
                'subtitle' => 'Uchovávanie sveta',
                'url' => URL::to('/kolekcia/47'),
                'publish' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'Nizozemská maľba',
                'subtitle' => 'zbierka nizozemských, holandských a flámskych malieb z obdobia rokov 1500 – 1800',
                'url' => URL::to('/kolekcia/56'),
                'publish' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table('slides')->insert($slides);

	}

}
