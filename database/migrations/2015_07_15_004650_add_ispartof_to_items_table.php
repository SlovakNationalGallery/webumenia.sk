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
		Schema::table('items', function($table) {
			$table->dropColumn('integrity');
		});

		Schema::table('items', function($table) {
			$table->dropColumn('integrity_work');
		});

		Schema::table('items', function($table) {
			$table->string('relationship_type')->default('samostatné dielo');
			$table->string('related_work')->nullable();
			$table->integer('related_work_order')->default(0);
			$table->integer('related_work_total')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('items', function($table) {
			$table->dropColumn('related_work_total');
		});

		Schema::table('items', function($table) {
			$table->dropColumn('related_work_order');
		});

		Schema::table('items', function($table) {
			$table->dropColumn('related_work');
		});

		Schema::table('items', function($table) {
			$table->dropColumn('relationship_type');
		});

		Schema::table('items', function($table) {
			$table->string('integrity')->default('');
			$table->string('integrity_work')->default('');
		});
	}

}
