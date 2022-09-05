<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'ALTER TABLE spice_harvester_records ALTER type DROP DEFAULT'
        );
        DB::statement(
            'UPDATE spice_harvester_records r ' .
                'JOIN spice_harvester_harvests h ' .
                'ON r.harvest_id = h.id ' .
                'SET r.type = "App\\\Models\\\Item" ' .
                'WHERE h.type in ("App\\\Harvest\\\Harvesters\\\ItemHarvester", "App\\\Harvest\\\Harvesters\\\GmuhkItemHarvester") ' .
                'OR r.type = "App\\\Harvest\\\Harvesters\\\ItemHarvester"'
        );
        DB::statement(
            'UPDATE spice_harvester_records r ' .
                'JOIN spice_harvester_harvests h ' .
                'ON r.harvest_id = h.id ' .
                'SET r.type = "App\\\Models\\\Authority" ' .
                'WHERE h.type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester"' .
                'OR r.type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester"'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
