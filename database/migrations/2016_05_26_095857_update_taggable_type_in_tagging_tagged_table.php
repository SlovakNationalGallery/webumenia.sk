<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTaggableTypeInTaggingTaggedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('UPDATE `tagging_tagged` SET `taggable_type` = "App\\\Item" WHERE `taggable_type` = "Item";');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('UPDATE `tagging_tagged` SET `taggable_type` = "Item" WHERE `taggable_type` = "App\\\Item";');
	}

}
