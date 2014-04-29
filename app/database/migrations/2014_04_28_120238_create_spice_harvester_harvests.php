<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpiceHarvesterHarvests extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		/* Harvests/collections:
          id: primary key
          collection_id: the corresponding collection id in `collections`
          base_url: the OAI-PMH base URL
          metadata_prefix: the OAI-PMH metadata prefix used for this harvest
          set_id: the OAI-PMH set spec (unique identifier)
          set_name: the OAI-PMH set name
          set_description: the Dublin Core description of the set, if any
          status: the current harvest status for this set: starting, in progress, completed, error, deleted
          status_messages: any messages sent from the harvester, usually during an error status
          initiated: the datetime the harvest initiated
          completed: the datetime the harvest completed
        */		
		Schema::create('spice_harvester_harvests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('collection_id')->nullable();
			$table->string('base_url');
			$table->string('metadata_prefix');
			$table->string('set_spec');
			$table->string('set_name');
			$table->string('set_description');
			$table->string('status');
			$table->string('status_messages');
			$table->dateTime('initiated');
			$table->dateTime('completed');
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
		Schema::drop('spice_harvester_harvests');
	}

}
