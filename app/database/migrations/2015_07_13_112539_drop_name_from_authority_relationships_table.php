<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropNameFromAuthorityRelationshipsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('authority_relationships', function($table)
		{
		    DB::statement('DELETE n1 FROM authority_relationships n1, authority_relationships n2 WHERE n1.id < n2.id AND n1.authority_id = n2.authority_id AND n1.realted_authority_id = n2.realted_authority_id');
		    $table->dropColumn('name');
		    $table->renameColumn('realted_authority_id', 'related_authority_id');
		    $table->unique(array('authority_id', 'related_authority_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('authority_relationships', function($table)
		{
		    $table->dropUnique(array('authority_id', 'related_authority_id'));
		    $table->renameColumn('related_authority_id', 'realted_authority_id');
		    $table->string('name');
		});
	}

}
