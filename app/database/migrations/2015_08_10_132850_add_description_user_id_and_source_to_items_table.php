<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionUserIdAndSourceToItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
public function up()
	{
		Schema::table('items', function($table)
		{
			$table->integer('description_user_id')->nullable();
			$table->string('description_source')->nullable();
			$table->string('description_source_link')->nullable();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('items', function($table)
		{
			$table->dropColumn('description_user_id');
			$table->dropColumn('description_source');
			$table->dropColumn('description_source_link');
		});
	}

}
