<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIspartofToItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('items', function($table)
		{
			$table->dropColumn('integrity');
			$table->dropColumn('integrity_work');
			$table->string('relationship_type')->default('samostatné dielo');
			$table->string('related_work')->nullable();
			$table->integer('related_work_order');
			$table->integer('related_work_total');
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
			$table->dropColumn('related_work_total');
			$table->dropColumn('related_work_order');
			$table->dropColumn('related_work');
			$table->dropColumn('relationship_type');
			$table->string('integrity');
			$table->string('integrity_work');
		});
	}

}
