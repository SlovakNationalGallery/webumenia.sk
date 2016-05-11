<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndAuthToSpiceHarvester extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('spice_harvester_harvests', function($table)
		{
			$table->enum('type', ['item', 'author'])->default('item');
			$table->string('username')->nullable();
			$table->string('password')->nullable();
		});

		Schema::table('spice_harvester_records', function($table)
		{
			$table->enum('type', ['item', 'author'])->default('item');
		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('spice_harvester_records', function($table)
		{
			$table->dropColumn('type');
		});

		Schema::table('spice_harvester_harvests', function($table)
		{
			$table->dropColumn('type');
			$table->dropColumn('username');
			$table->dropColumn('password');
		});


	}

}
