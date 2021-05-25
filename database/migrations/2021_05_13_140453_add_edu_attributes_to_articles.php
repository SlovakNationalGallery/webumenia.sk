<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEduAttributesToArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->after('publish', function ($table) {
                $table->json('edu_media_types')->nullable();
                $table->json('edu_target_age_groups')->nullable();
                $table->boolean('edu_suitable_for_home')->default(0);
                $table->json('edu_keywords')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['edu_media_types', 'edu_target_age_groups', 'edu_suitable_for_home', 'edu_keywords']);
        });
    }
}
