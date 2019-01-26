<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveItemImagesImgUrlToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('img_url')->nullable();
        });

        DB::table('item_images')->where('order', 0)->chunk(100, function($images) {
            foreach ($images as $image) {
                DB::table('items')
                    ->where('id', $image->item_id)
                    ->update([
                        'img_url' => $image->img_url,
                    ]);
            }
        });

        Schema::table('item_images', function (Blueprint $table) {
            $table->dropColumn('img_url');
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
            $table->string('img_url')->nullable();
        });

        DB::table('items')->chunk(100, function($items) {
            foreach ($items as $item) {
                DB::table('item_images')
                    ->where('item_id', $item->id)
                    ->where('order', 0)
                    ->update([
                        'img_url' => $item->img_url,
                    ]);
            }
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('img_url');
        });
    }
}
