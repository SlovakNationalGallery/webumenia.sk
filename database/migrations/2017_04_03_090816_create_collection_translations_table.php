<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_translations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('collection_id')->unsigned();
            $table->string('locale')->index();

            //translatable attributes
            $table->string('name')->default('');
            $table->string('type')->default('');
            $table->text('text')->default('');

            $table->unique(['collection_id','locale']);
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
        });

        $default_locale = App::getLocale();

        $collections = DB::table('collections')->get();
        $collection_translations = [];
        foreach ($collections as $collection) {
            $collection_translations[] =
                [
                    'collection_id' => $collection->id,
                    'locale' => $default_locale,
                    'name' => $collection->name,
                    'type' => $collection->type,
                    'text' => $collection->text,
                ];
        }

        DB::table('collection_translations')->insert( $collection_translations );

        Schema::table('collections', function($table) {
            $table->dropColumn([
                'name',
                'type',
                'text',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function($table) {
            $table->string('name')->default('');
            $table->string('type')->default('');
            $table->text('text')->default('');
        });

        $default_locale = App::getLocale();

        $collection_translations = DB::table('collection_translations')
                                    ->where('locale', '=', $default_locale)
                                    ->get();

        foreach ($collection_translations as $collection_translation) {
            DB::table('collections')
                ->where('id', $collection_translation->collection_id)
                ->update([
                    'name'    => $collection_translation->name,
                    'type'    => $collection_translation->type,
                    'text'    => $collection_translation->text,
                ]);
        }

        Schema::dropIfExists('collection_translations');
    }
}
