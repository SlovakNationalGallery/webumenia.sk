<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedToSpiceHarvesterRecords extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('spice_harvester_records', function($table)
		{
		    // $table->boolean('is_deleted')->default(false);
		    $table->softDeletes();

		    // $table->leftJoin('items', 'spice_harvester_records.item_id', '=', 'items.id')->whereRaw('items.id IS NULL')->update(array('deleted_at' => date("Y-m-d H:i:s")));
		});

		//tie zaznamy, pre ktore uz neexistuju diela - nastavit ako zmazane
	    DB::unprepared( 'UPDATE spice_harvester_records r LEFT JOIN items i ON i.id = r.item_id SET r.deleted_at = NOW() WHERE i.id IS NULL;' );

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('spice_harvester_records', function($table)
		{
		    // $table->dropColumn('is_deleted');
		    $table->dropSoftDeletes();
		});		
	}

}
