<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'base_url' => 'http://www.webumenia.sk/oai-pmh/',
                'metadata_prefix' => 'oai_dc',
                'set_spec' => 'Europeana SNG',
                'set_name' => 'Europeana SNG',
                'type' => 'App\Harvest\Harvesters\ItemHarvester',
                'set_description' => 'Europeana set from SNG',
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
