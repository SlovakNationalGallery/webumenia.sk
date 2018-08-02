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
            $table->string('type_organization')->nullable();
            $table->text('biography')->default('');
            $table->string('birth_place')->nullable();
            $table->string('death_place')->nullable();

            $table->unique(['authority_id','locale']);
            $table->foreign('authority_id')->references('id')->on('authorities')->onDelete('cascade');
        });

        $default_locale = App::getLocale();

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

        Schema::table('authorities', function($table) {
            $table->dropColumn([
                'type_organization',
                'biography',
                'birth_place',
                'death_place',
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
        Schema::table('authorities', function($table) {
            $table->string('type_organization')->nullable();
            $table->text('biography')->default('');
            $table->string('birth_place')->nullable();
            $table->string('death_place')->nullable();
        });

        $default_locale = App::getLocale();

        $authority_translations = DB::table('authority_translations')
                                    ->where('locale', '=', $default_locale)
                                    ->get();

        foreach ($authority_translations as $authority_translation) {
            DB::table('authorities')
                ->where('id', $authority_translation->authority_id)
                ->update([
                    'type_organization'      => $authority_translation->type_organization,
                    'biography'    => $authority_translation->biography,
                    'birth_place'    => $authority_translation->birth_place,
                    'death_place'    => $authority_translation->death_place,
                ]);
        }

        Schema::dropIfExists('authority_translations');
    }
}
