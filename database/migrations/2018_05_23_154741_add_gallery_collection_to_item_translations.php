<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGalleryCollectionToItemTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_translations', function(Blueprint $table)
        {
            $table->string('gallery_collection')->nullable();
        });

        Schema::table('items', function(Blueprint $table)
        {
            $table->dropColumn('gallery_collection');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_translations', function(Blueprint $table)
        {
            $table->dropColumn('gallery_collection');
        });

        Schema::table('items', function(Blueprint $table)
        {
            $table->string('gallery_collection')->nullable();
        });
    }
}
