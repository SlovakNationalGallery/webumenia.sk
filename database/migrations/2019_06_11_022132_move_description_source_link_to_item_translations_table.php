<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveDescriptionSourceLinkToItemTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_translations', function (Blueprint $table) {
            $table->string('description_source_link')->nullable();
        });

        DB::table('items')->orderBy('id')->chunk(100, function($items) {
            foreach ($items as $item) {
                DB::table('item_translations')
                    ->where('item_id', $item->id)
                    ->where('locale', config('app.locale'))
                    ->update(['description_source_link' => $item->description_source_link]);
            }
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('description_source_link');
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
            $table->string('description_source_link')->nullable();
        });

        DB::table('item_translations')
            ->where('locale', config('app.locale'))
            ->orderBy('id')
            ->chunk(100, function($item_translations) {
                foreach ($item_translations as $item_translation) {
                    DB::table('items')
                        ->where('id', $item_translation->item_id)
                        ->update(['description_source_link' => $item_translation->description_source_link]);
                }
            });

        Schema::table('item_translations', function (Blueprint $table) {
            $table->dropColumn('description_source_link');
        });
    }
}
