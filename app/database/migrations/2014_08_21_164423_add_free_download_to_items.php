<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFreeDownloadToItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('items', function($table)
		{
			$table->boolean('free_download')->default(0);
		});

		DB::unprepared( "UPDATE `items` SET free_download = 1 WHERE  `id` IN ('SVK:SNG.G_13592', 'SVK:SNG.G_13593', 'SVK:SNG.G_2005', 'SVK:SNG.G_2645', 'SVK:SNG.G_3996', 'SVK:SNG.G_5442', 'SVK:SNG.G_8136', 'SVK:SNG.G_8140', 'SVK:SNG.G_8141', 'SVK:SNG.G_8155', 'SVK:SNG.G_8156', 'SVK:SNG.G_8162', 'SVK:SNG.G_8170', 'SVK:SNG.G_8180', 'SVK:SNG.G_8184', 'SVK:SNG.G_8185', 'SVK:SNG.G_8193', 'SVK:SNG.G_8196', 'SVK:SNG.G_8198', 'SVK:SNG.G_8205', 'SVK:SNG.G_9276', 'SVK:SNG.G_9315', 'SVK:SNG.K_1053', 'SVK:SNG.K_11076', 'SVK:SNG.K_16147', 'SVK:SNG.K_16149', 'SVK:SNG.K_16150', 'SVK:SNG.K_16152', 'SVK:SNG.K_2375', 'SVK:SNG.K_476', 'SVK:SNG.K_477', 'SVK:SNG.O_1674', 'SVK:SNG.O_2449', 'SVK:SNG.O_2464', 'SVK:SNG.O_2832', 'SVK:SNG.O_399', 'SVK:SNG.O_471', 'SVK:SNG.O_4934', 'SVK:SNG.O_4974', 'SVK:SNG.O_4994', 'SVK:SNG.O_5301', 'SVK:SNG.O_988', 'SVK:SNG.UP-DK_2082', 'SVK:SNG.UP-DK_2083', 'SVK:SNG.UP-DK_2084', 'SVK:SNG.UP-DK_2099', 'SVK:SNG.UP-DK_2587', 'SVK:SNG.UP-DK_2598', 'SVK:SNG.UP-DK_2607', 'SVK:SNG.UP-DK_2625', 'SVK:SNG.UP-DK_2627', 'SVK:SNG.UP-DK_2629', 'SVK:SNG.UP-DK_2640', 'SVK:SNG.UP-DK_2645', 'SVK:SNG.UP-DK_2673', 'SVK:SNG.UP-DK_2678', 'SVK:SNG.UP-DK_3878', 'SVK:SNG.UP-DK_3904', 'SVK:SNG.UP-DK_3907', 'SVK:SNG.UP-DK_3908', 'SVK:SNG.UP-DK_3910', 'SVK:SNG.UP-DK_4229', 'SVK:SNG.UP-DK_4530', 'SVK:SNG.UP-DK_4561', 'SVK:SNG.UP-DK_4568');" );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('items', function($table)
		{
			$table->dropColumn('free_download');
		});
	}

}
