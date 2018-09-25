<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_item', function(Blueprint $table)
        {
            $table->integer('download_id');
            $table->string('item_id');
        });

        Schema::table('downloads', function(Blueprint $table)
        {
            $table->dropColumn('item_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('downloads', function(Blueprint $table)
        {
            $table->string('item_id')->nullable();
        });

        Schema::dropIfExists('download_item');
    }
}
