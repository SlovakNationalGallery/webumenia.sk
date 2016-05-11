<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collections', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('type');
			$table->text('text');
			$table->integer('order');
			$table->timestamps();
		});
		
		Schema::create('collection_item', function(Blueprint $table)
		{
			$table->integer('collection_id');
			$table->string('item_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('collection_item');
		Schema::dropIfExists('collections');
	}

}
