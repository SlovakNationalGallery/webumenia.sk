<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageColorPublishToCollectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('collections', function($table)
		{
			$table->string('main_image')->nullable();
			$table->string('title_color')->nullable();
			$table->string('title_shadow')->nullable();
			$table->boolean('publish')->default(false);
			$table->integer('view_count')->default(0);
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('collections', function($table) {
			$table->dropColumn('main_image');
		});

		Schema::table('collections', function($table) {
			$table->dropColumn('title_color');
		});

		Schema::table('collections', function($table) {
			$table->dropColumn('title_shadow');
		});

		Schema::table('collections', function($table) {
			$table->dropColumn('publish');
		});

		Schema::table('collections', function($table) {
			$table->dropColumn('view_count');
		});
	}

}
