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
        $now = new \DateTime();

        DB::table('items')->orderBy('id')->chunk(100, function($items) use ($now) {
            foreach ($items as $item) {
                DB::table('images')->insert([
                    'item_id' => $item->id,
                    'img_url' => $item->img_url,
                    'iipimg_url' => $item->iipimg_url,
                    'order' => 0,
                    'created_at' => $now,
                ]);
            }
        });

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

        DB::table('images')->where('order', 0)->orderBy('id')->chunk(100, function($images) {
            foreach ($images as $image) {
                DB::table('items')->where('id', $image->item_id)
                    ->update([
                        'img_url' => $image->img_url,
                        'iipimg_url' => $image->iipimg_url,
                    ]);
            }
        });

        DB::table('images')->truncate();
    }
}
