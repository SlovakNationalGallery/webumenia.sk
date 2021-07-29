<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeInSpiceHarvesterHarvestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spice_harvester_harvests', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
        });

        DB::statement('UPDATE spice_harvester_harvests SET type = "App\\\Harvest\\\Harvesters\\\ItemHarvester" WHERE type = "item"');
        DB::statement('UPDATE spice_harvester_harvests SET type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester" WHERE type = "author"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('UPDATE spice_harvester_harvests SET type = "item" WHERE type = "App\\\Harvest\\\Harvesters\\\ItemHarvester"');
        DB::statement('UPDATE spice_harvester_harvests SET type = "author" WHERE type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester"');
        DB::statement('ALTER TABLE spice_harvester_harvests MODIFY COLUMN type enum("item", "author") NOT NULL DEFAULT "item"');
    }
}
