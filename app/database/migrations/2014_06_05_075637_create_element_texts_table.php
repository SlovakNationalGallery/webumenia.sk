<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementTextsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	Schema::create('element_texts', function(Blueprint $table)
		{
			$table->increments('id'); 
			$table->string('item_id'); // lebo ich format moze byt varchar - a aj bude
			$table->integer('element_id');
			$table->boolean('html');
			$table->string('text'); 
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('element_texts');
	}

}
