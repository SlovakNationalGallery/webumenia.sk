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
            $table->string('title')->default('');
            $table->text('summary');
            $table->text('content');

            $table->unique(['article_id','locale']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        $default_locale = App::getLocale();

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

        Schema::table('articles', function($table) {
            $table->dropColumn([
                'title',
                'summary',
                'content',
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
        Schema::table('articles', function($table) {
            $table->string('title')->default('');
            $table->text('summary')->default('');
            $table->text('content')->default('');
        });

        $default_locale = App::getLocale();

        $article_translations = DB::table('article_translations')
                                    ->where('locale', '=', $default_locale)
                                    ->get();

        foreach ($article_translations as $article_translation) {
            DB::table('articles')
                ->where('id', $article_translation->article_id)
                ->update([
                    'title'      => $article_translation->title,
                    'summary'    => $article_translation->summary,
                    'content'    => $article_translation->content,
                ]);
        }

        Schema::dropIfExists('article_translations');
    }
}
