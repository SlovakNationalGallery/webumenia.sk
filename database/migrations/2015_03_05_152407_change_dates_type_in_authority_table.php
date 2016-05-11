<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDatesTypeInAuthorityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement('ALTER TABLE authorities MODIFY COLUMN birth_date VARCHAR(10)');
	    DB::statement('ALTER TABLE authorities MODIFY COLUMN death_date VARCHAR(10)');
	}

	public function down()
	{
		DB::statement('ALTER TABLE authorities MODIFY COLUMN birth_date DATE');
		DB::statement('ALTER TABLE authorities MODIFY COLUMN death_date DATE');
	}   
}
