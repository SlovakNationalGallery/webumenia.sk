<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class HarvestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
                'initiated' => Date::now(),
            ],
        ];

        DB::table('spice_harvester_harvests')->insert($hasrvests);
    }
}
