<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleToAuthorityItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('authority_item', function($table)
		{
			$table->string('name')->default('');
			$table->string('role')->default('autor/author');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('authority_item', function($table) {
			$table->dropColumn('name');
		});

		Schema::table('authority_item', function($table) {
			$table->dropColumn('role');
		});
	}

}
