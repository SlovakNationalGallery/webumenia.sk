<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorityTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authority_translations', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('authority_id');
            $table->string('locale')->index();

            // translatable attributes
            $table->string('type_organization');
            $table->string('biography');
            $table->string('birth_place');
            $table->string('death_place');

            $table->unique(['authority_id','locale']);
            $table->foreign('authority_id')->references('id')->on('authorities')->onDelete('cascade');
        });

        $default_locale = 'sk';

        $authorities = DB::table('authorities')->get();
        $authority_translations = [];
        foreach ($authorities as $authority) {
            $authority_translations[] = [
                'authority_id' => $authority->id,
                'locale' => $default_locale,
                'type_organization' => $authority->type_organization,
                'biography' => $authority->biography,
                'birth_place' => $authority->birth_place,
                'death_place' => $authority->death_place,
            ];
        }

        DB::table('authority_translations')->insert($authority_translations);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authority_translations');
    }
}
