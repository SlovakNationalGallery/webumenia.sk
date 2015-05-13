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
		DB::statement('ALTER TABLE `items` MODIFY `dating` VARCHAR(255) NULL;');
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
		DB::statement('ALTER TABLE `items` MODIFY `dating` VARCHAR(255) NOT NULL;');
	}

}
