<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDatingToNullableInItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('items', function (Blueprint $table) {
			$table->string('dating', 255)->nullable()->change();
		});
		DB::statement('UPDATE `items` SET `dating` = NULL WHERE `dating` = "";');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('UPDATE `items` SET `dating` = "" WHERE `dating` IS NULL;');
		Schema::table('items', function (Blueprint $table) {
			$table->string('dating', 255)->change();
		});
	}

}
