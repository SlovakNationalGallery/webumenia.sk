<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement('ALTER TABLE items MODIFY COLUMN date_earliest INT( 255 )');
	    DB::statement('ALTER TABLE items MODIFY COLUMN date_latest INT( 255 )');

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    DB::statement('ALTER TABLE items MODIFY COLUMN date_earliest VARCHAR(4)');
	    DB::statement('ALTER TABLE items MODIFY COLUMN date_latest VARCHAR(4)');
	}

}
