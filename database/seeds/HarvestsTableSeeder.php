<?php

use Illuminate\Database\Seeder;

class HarvestsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('spice_harvester_harvests')->truncate();

        $now = date("Y-m-d H:i:s");

        $hasrvests = [
            [
                'base_url' => 'http://stary.webumenia.sk/oai-pmh/authority/',
                'metadata_prefix' => 'ulan',
                'type' => 'author',
                'status' => 'queued',
                'status_messages' => '',
                'initiated' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];


        DB::table('spice_harvester_harvests')->insert($hasrvests);

    }

}