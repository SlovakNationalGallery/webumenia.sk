<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_id');
            $table->string('title')->nullable();
            $table->string('img_url')->nullable();
            $table->string('iipimg_url')->nullable();
            $table->integer('order')->unsigned();
            $table->timestamps();

            $table->unique(['item_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
