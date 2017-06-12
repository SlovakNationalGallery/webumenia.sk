<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_translations', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->string('item_id');
            $table->string('locale')->index();
            
            // translatable attributes
            $table->string('title');
            $table->string('description');
            $table->string('work_type');
            $table->string('work_level');
            $table->string('topic');
            $table->string('subject');
            $table->string('measurement');
            $table->string('dating');
            $table->string('medium');
            $table->string('technique');
            $table->string('inscription');
            $table->string('place');
            $table->string('state_edition');
            $table->string('gallery');
            $table->string('relationship_type');
            $table->string('related_work');
            $table->string('description_source');

            $table->unique(['item_id','locale']);
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        $default_locale = 'sk';

        DB::table('items')->orderBy('id')->chunk(100, function($items) use ($default_locale) {
            $item_translations = [];
            foreach ($items as $item) {
                $item_translations[] = [
                    'item_id'            => $item->id,
                    'locale'             => $default_locale,
                    'title'              => $item->title,
                    'description'        => $item->description,
                    'work_type'          => $item->work_type,
                    'work_level'         => $item->work_level,
                    'topic'              => $item->topic,
                    'subject'            => $item->subject,
                    'measurement'        => $item->measurement,
                    'dating'             => $item->dating,
                    'medium'             => $item->medium,
                    'technique'          => $item->technique,
                    'inscription'        => $item->inscription,
                    'place'              => $item->place,
                    'state_edition'      => $item->state_edition,
                    'gallery'            => $item->gallery,
                    'relationship_type'  => $item->relationship_type,
                    'related_work'       => $item->related_work,
                    'description_source' => $item->description_source,
                ];
            }

            DB::table('item_translations')->insert( $item_translations );
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_translations');
    }
}
