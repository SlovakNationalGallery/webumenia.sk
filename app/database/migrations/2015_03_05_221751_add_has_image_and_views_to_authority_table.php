<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasImageAndViewsToAuthorityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('authorities', function($table)
		{
			$table->boolean('has_image')->default(false);
			$table->integer('view_count')->default(0);
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
			$table->dropColumn('view_count');
			$table->dropColumn('has_image');
		});
	}

}
