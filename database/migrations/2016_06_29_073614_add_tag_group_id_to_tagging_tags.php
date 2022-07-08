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
        Schema::table('tagging_tags', function ($table) {
            // Needed for switch from v2 to v4
            // Taken from https://github.com/rtconner/laravel-tagging/blob/bcce5300c01e5706b01f3dd3d7c920b46eb63c06/migrations/2014_01_07_073615_create_tags_table.php#L16
            $table
                ->integer('tag_group_id')
                ->unsigned()
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagging_tags', function ($table) {
            $table->dropColumn('tag_group_id');
        });
    }
};
