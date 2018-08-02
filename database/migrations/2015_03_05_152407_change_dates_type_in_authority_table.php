<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDatesTypeInAuthorityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('authorities', function (Blueprint $table) {
			$table->string('birth_date', 10)->change();
		});
		Schema::table('authorities', function (Blueprint $table) {
			$table->string('death_date', 10)->change();
		});
	}

	public function down()
	{
		Schema::table('authorities', function (Blueprint $table) {
			$table->date('birth_date')->change();
		});
		Schema::table('authorities', function (Blueprint $table) {
			$table->date('death_date')->change();
		});
	}   
}
