<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveImagesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $items = DB::table('items')->get();
        $table_images = DB::table('images');
        $now = new \DateTime();

        foreach ($items as $item) {
            $table_images->insert([
                'item_id' => $item->id,
                'img_url' => $item->img_url,
                'iipimg_url' => $item->iipimg_url,
                'order' => 0,
                'created_at' => $now,
            ]);
        }

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['img_url', 'iipimg_url']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('img_url')->nullable();
            $table->string('iipimg_url')->nullable();
        });

        $items = DB::table('items');

        $images = DB::table('images')
            ->where('order', 0) // get only primary images
            ->get();
        foreach ($images as $image) {
            $items
                ->where('id', $image->item_id)
                ->update([
                    'img_url' => $image->img_url,
                    'iipimg_url' => $image->iipimg_url,
                ]);
        }

        $items->truncate();
    }
}
