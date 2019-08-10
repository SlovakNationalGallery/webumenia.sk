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
		Schema::table('items', function (Blueprint $table) {
			$table->integer('date_earliest')->change();
		});
		Schema::table('items', function (Blueprint $table) {
			$table->integer('date_latest')->change();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('items', function (Blueprint $table) {
			$table->string('date_earliest', 4)->change();
		});
		Schema::table('items', function (Blueprint $table) {
			$table->string('date_latest', 4)->change();
		});
	}

}
