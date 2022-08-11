<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_translations', function (Blueprint $table) {
            $table->string('measurement', 512)->change();
        });

        Schema::table('spice_harvester_records', function (Blueprint $table) {
            $table->text('error_message')->change();
        });

        Schema::table('spice_harvester_harvests', function (Blueprint $table) {
            $table->text('status_messages')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spice_harvester_harvests', function (Blueprint $table) {
            $table->string('status_messages')->change();
        });

        Schema::table('spice_harvester_records', function (Blueprint $table) {
            $table->string('error_message')->change();
        });

        Schema::table('item_translations', function (Blueprint $table) {
            $table->string('measurement')->change();
        });
    }
};
