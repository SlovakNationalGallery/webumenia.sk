<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageSourceAttributesToAuthorities extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('authorities', function($table)
		{
			$table->string('image_source_url')->nullable();
			$table->string('image_source_label')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('authorities', function($table)
		{
			$table->dropColumn('image_source_url');
			$table->dropColumn('image_source_label');
		});
	}

}
