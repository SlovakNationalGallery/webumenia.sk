<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFailedAttributesToSpiceHarvesterRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spice_harvester_records', function (Blueprint $table) {
            $table->timestamp('failed_at')->nullable();
            $table->string('error_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spice_harvester_records', function (Blueprint $table) {
            $table->dropColumn('failed_at', 'error_message');
        });
    }
}
