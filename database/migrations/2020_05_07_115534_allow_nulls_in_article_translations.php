<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullsInArticleTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_translations', function (Blueprint $table) {
            //
            $table->string('title')->nullable(true)->change();
            $table->text('summary')->nullable(true)->change();
            $table->text('content')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_translations', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->text('summary')->nullable(false)->change();
            $table->text('content')->nullable(false)->change();
        });
    }
}
