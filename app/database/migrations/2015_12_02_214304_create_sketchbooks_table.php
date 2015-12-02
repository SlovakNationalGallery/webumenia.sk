<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSketchbooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sketchbooks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('item_id');
			$table->string('title');
			$table->string('file')->nullable();
			$table->integer('order');
			$table->boolean('publish')->default(0);
			$table->timestamp('generated_at')->nullable();
			$table->timestamps();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sketchbooks');
	}
}
