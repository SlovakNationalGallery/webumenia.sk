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
			$table->string('authorName');
			$table->string('title');
			$table->string('workType');
			$table->string('workLevel');
			$table->string('topic');
			$table->string('dating');
			$table->string('dateEarliest');
			$table->string('dateLatest');
			$table->string('medium');
			$table->string('technique');
			$table->string('inscription');
			$table->string('stateEdition');
			$table->string('integrity');
			$table->string('integrityWork');
			$table->string('itemType');
			$table->boolean('featured');
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
