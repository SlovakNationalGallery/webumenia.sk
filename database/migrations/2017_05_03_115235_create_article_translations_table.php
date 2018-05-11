<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_translations', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->string('locale')->index();

            // translatable attributes
            $table->string('title');
            $table->string('summary');
            $table->string('content');

            $table->unique(['article_id','locale']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        $default_locale = 'sk';

        $articles = DB::table('articles')->get();
        $article_translations = [];
        foreach ($articles as $article) {
            $article_translations[] = [
                'article_id' => $article->id,
                'locale'     => $default_locale,
                'title'      => $article->title,
                'summary'    => $article->summary,
                'content'    => $article->content,
            ];
        }

        DB::table('article_translations')->insert( $article_translations );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_translations');
    }
}
