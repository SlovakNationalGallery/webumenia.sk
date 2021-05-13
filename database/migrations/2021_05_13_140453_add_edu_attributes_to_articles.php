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
                $table->json('edu_media_types');
                $table->json('edu_target_age_groups');
                $table->boolean('edu_suitable_for_home');
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
            $table->dropColumn(['edu_media_types', 'edu_target_age_groups', 'edu_suitable_for_home']);
        });
    }
}
