<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'ALTER TABLE spice_harvester_records MODIFY COLUMN type VARCHAR(255) COLLATE utf8_slovak_ci NOT NULL'
        );
        DB::statement(
            'UPDATE spice_harvester_records r ' .
                'JOIN spice_harvester_harvests h ' .
                'ON r.harvest_id = h.id ' .
                'SET r.type = "App\\\Models\\\Item" ' .
                'WHERE h.type in ("App\\\Harvest\\\Harvesters\\\ItemHarvester", "App\\\Harvest\\\Harvesters\\\GmuhkItemHarvester")'
        );
        DB::statement(
            'UPDATE spice_harvester_records r ' .
                'JOIN spice_harvester_harvests h ' .
                'ON r.harvest_id = h.id ' .
                'SET r.type = "App\\\Models\\\Item" ' .
                'WHERE h.type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester"'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(
            'UPDATE spice_harvester_records r ' .
                'JOIN spice_harvester_harvests h ' .
                'ON r.harvest_id = h.id ' .
                'SET r.type = "item" ' .
                'WHERE h.type in ("App\\\Harvest\\\Harvesters\\\ItemHarvester", "App\\\Harvest\\\Harvesters\\\GmuhkItemHarvester")'
        );
        DB::statement(
            'UPDATE spice_harvester_records r ' .
                'JOIN spice_harvester_harvests h ' .
                'ON r.harvest_id = h.id ' .
                'SET r.type = "authority" ' .
                'WHERE h.type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester"'
        );
        DB::statement(
            'ALTER TABLE spice_harvester_records MODIFY COLUMN type ENUM("authority", "item") COLLATE utf8_slovak_ci NOT NULL DEFAULT "item"'
        );
    }
};
