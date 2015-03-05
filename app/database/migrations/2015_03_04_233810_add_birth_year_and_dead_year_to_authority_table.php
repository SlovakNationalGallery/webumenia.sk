<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBirthYearAndDeadYearToAuthorityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('authorities', function($table)
		{
			$table->integer('birth_year')->nullable();
			$table->integer('death_year')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('authorities', function($table)
		{
			$table->dropColumn('birth_year');
			$table->dropColumn('death_year');
		});
	}

}
