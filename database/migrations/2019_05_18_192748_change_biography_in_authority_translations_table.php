<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBiographyInAuthorityTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_translations', function (Blueprint $table) {
            // $table->mediumText('biography')->change();
            // https://github.com/laravel/framework/issues/21847#issuecomment-454728171
            $table->string('biography', 65536)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authority_translations', function (Blueprint $table) {
            $table->text('biography')->change();
        });
    }
}
