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
