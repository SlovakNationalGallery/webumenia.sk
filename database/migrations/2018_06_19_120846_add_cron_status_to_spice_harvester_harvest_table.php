<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCronStatusToSpiceHarvesterHarvestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spice_harvester_harvests', function (Blueprint $table) {
            $table->enum('cron_status', array('manual','daily','weekly'))->default('manual');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spice_harvester_harvests', function (Blueprint $table) {
            $table->dropColumn('cron_status');
            //
        });
    }
}
