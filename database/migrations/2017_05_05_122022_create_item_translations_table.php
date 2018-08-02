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
            $table->mediumText('description');
            $table->string('work_type');
            $table->string('work_level');
            $table->string('topic');
            $table->string('subject');
            $table->string('measurement');
            $table->string('dating');
            $table->string('medium');
            $table->string('technique');
            $table->string('inscription');
            $table->string('place')->nullable();
            $table->string('state_edition')->nullable();
            $table->string('gallery');
            $table->string('relationship_type')->nullable();
            $table->string('related_work')->nullable();
            $table->string('description_source');

            $table->unique(['item_id','locale']);
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        $default_locale = App::getLocale();

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

        Schema::table('items', function($table) {
            $table->dropColumn([
                'title',
                'description',
                'work_type',
                'work_level',
                'topic',
                'subject',
                'measurement',
                'dating',
                'medium',
                'technique',
                'inscription',
                'place',
                'state_edition',
                'gallery',
                'relationship_type',
                'related_work',
                'description_source',
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
        Schema::table('items', function($table) {
            $table->string('title');
            $table->mediumText('description');
            $table->string('work_type');
            $table->string('work_level');
            $table->string('topic');
            $table->string('subject');
            $table->string('measurement');
            $table->string('dating');
            $table->string('medium');
            $table->string('technique');
            $table->string('inscription');
            $table->string('place')->nullable();
            $table->string('state_edition')->nullable();
            $table->string('gallery');
            $table->string('relationship_type')->nullable();
            $table->string('related_work')->nullable();
            $table->string('description_source');
        });

        $default_locale = App::getLocale();

        $item_translations = DB::table('item_translations')
                                    ->where('locale', '=', $default_locale)
                                    ->get();

        foreach ($item_translations as $item_translation) {
            DB::table('items')
                ->where('id', $item_translation->item_id)
                ->update([
                    'title'             => $item_translation->title,
                    'description'       => $item_translation->description,
                    'work_type'         => $item_translation->work_type,
                    'work_level'        => $item_translation->work_level,
                    'topic'             => $item_translation->topic,
                    'subject'           => $item_translation->subject,
                    'measurement'       => $item_translation->measurement,
                    'dating'            => $item_translation->dating,
                    'medium'            => $item_translation->medium,
                    'technique'         => $item_translation->technique,
                    'inscription'       => $item_translation->inscription,
                    'place'             => $item_translation->place,
                    'state_edition'     => $item_translation->state_edition,
                    'gallery'           => $item_translation->gallery,
                    'relationship_type' => $item_translation->relationship_type,
                    'related_work'      => $item_translation->related_work,
                    'description_source'=> $item_translation->description_source,
                ]);
        }

        Schema::dropIfExists('item_translations');
    }
}
