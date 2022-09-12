<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spice_harvester_records', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
        });

        DB::statement('UPDATE spice_harvester_records SET type = "App\\\Harvest\\\Harvesters\\\ItemHarvester" WHERE type = "item"');
        DB::statement('UPDATE spice_harvester_records SET type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester" WHERE type = "author"');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('UPDATE spice_harvester_records SET type = "item" WHERE type = "App\\\Harvest\\\Harvesters\\\ItemHarvester"');
        DB::statement('UPDATE spice_harvester_records SET type = "author" WHERE type = "App\\\Harvest\\\Harvesters\\\AuthorityHarvester"');
        DB::statement('ALTER TABLE spice_harvester_records MODIFY COLUMN type enum("item", "author") NOT NULL DEFAULT "item"');
    }
};
