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
			$table->string('organization');
			$table->string('contactPerson');
			$table->string('email');
			$table->string('kindOfPurpose');
			$table->string('purpose');
			$table->string('medium');
			$table->string('address');
			$table->string('phone');
			$table->string('ico');
			$table->string('dic');
			$table->boolean('no-dph');
			$table->integer('numOfCopies');
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
