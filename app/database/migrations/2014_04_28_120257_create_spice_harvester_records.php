<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpiceHarvesterRecords extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /* Harvested records/items.
          id: primary key
          harvest_id: the corresponding set id in `oaipmh_harvester_harvests`
          item_id: the corresponding item id in `items`
          identifier: the OAI-PMH record identifier (unique identifier)
          datestamp: the OAI-PMH record datestamp
        */

		Schema::create('spice_harvester_records', function(Blueprint $table)
		{
				$table->increments('id');
				$table->integer('harvest_id');
				$table->string('item_id');
				$table->string('identifier');
				$table->dateTime('datestamp');
				$table->timestamps();

		});        
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('spice_harvester_records');
	}

}
