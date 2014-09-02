<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('address');
			$table->string('email');
			$table->string('phone');
			$table->string('format');
			$table->text('note');
			$table->timestamps();
		});

		Schema::create('order_item', function(Blueprint $table)
		{
			$table->integer('order_id');
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
		Schema::dropIfExists('order_item');
		Schema::dropIfExists('orders');
	}
}
