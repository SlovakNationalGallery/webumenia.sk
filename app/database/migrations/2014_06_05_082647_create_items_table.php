<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// pre pripad vseobecnej tabulky s vyuzitim ulozenia metadat v elemts
		/*
		Schema::create('items', function(Blueprint $table)
		{
			$table->string('id')->unique();
			$table->boolean('public');
			$table->boolean('featured');
			$table->timestamps();
		});		
		*/

		// vsetko v tabulke
		Schema::create('items', function(Blueprint $table)
		{
			$table->string('id')->unique();
			$table->string('author');
			$table->string('title');
			$table->text('description');
			$table->string('work_type');
			$table->string('work_level');
			$table->string('topic');
			$table->string('subject');
			$table->string('measurement');
			$table->string('dating');
			$table->string('date_earliest')->nullable();
			$table->string('date_latest')->nullable();
			$table->string('medium');
			$table->string('technique');
			$table->string('inscription');
			$table->string('place')->nullable();
			$table->float('lat')->nullable();
			$table->float('lng')->nullable();
			$table->string('state_edition')->nullable();
			$table->string('integrity');
			$table->string('integrity_work');
			$table->string('gallery');
			$table->string('img_url')->nullable();
			$table->string('iipimg_url')->nullable();
			$table->string('item_type');
			$table->boolean('featured');
			$table->boolean('publish')->default(true);
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
		Schema::dropIfExists('items');
	}

}
