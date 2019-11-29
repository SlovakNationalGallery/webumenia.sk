<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeIntoItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_images', function (Blueprint $table) {
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
        });

        \App\ItemImage::hasIIP()->chunk(200, function ($item_images) {
            foreach ($item_images as $item_image) {
                $item_image->setSize();
                $item_image->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_images', function (Blueprint $table) {
            $table->dropColumn([
                'width',
                'height',
            ]);
        });
    }
}
