<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStartDateAndEndDateInAuthorityEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_events', function (Blueprint $table) {
            $table->smallInteger('start_date')->nullable()->change();
            $table->smallInteger('end_date')->nullable()->change();
        });
        DB::statement('UPDATE `authority_events` SET `start_date` = NULL WHERE `start_date` = 0;');
        DB::statement('UPDATE `authority_events` SET `end_date` = NULL WHERE `end_date` = 0;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authority_events', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
        });
    }

}
