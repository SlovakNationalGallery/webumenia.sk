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
            $table->string('name');
            $table->string('type');
            $table->string('text');

            $table->unique(['collection_id','locale']);
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
        });

        $default_locale = 'sk';

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_translations');
    }
}
