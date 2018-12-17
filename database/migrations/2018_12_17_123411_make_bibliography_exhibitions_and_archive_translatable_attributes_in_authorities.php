<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeBibliographyExhibitionsAndArchiveTranslatableAttributesInAuthorities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_translations', function (Blueprint $table) {
            $table->text('bibliography')->nullable();
            $table->text('exhibitions')->nullable();
            $table->text('archive')->nullable();
        });

        if (!DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            $default_locale = config('app.locale');

            $authority_data = DB::table('authorities')->get();

            foreach ($authority_data as $authority) {
                $result = DB::table('authority_translations')
                    ->where('authority_id', $authority->id)
                    ->where('locale', $default_locale)
                    ->update([
                        'bibliography' => $authority->bibliography,
                        'exhibitions' => $authority->exhibitions,
                        'archive' => $authority->archive,
                    ]);
            }
        }


        Schema::table('authorities', function (Blueprint $table) {
            $table->dropColumn([
                'bibliography',
                'exhibitions',
                'archive',
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

        Schema::table('authorities', function ($table) {
            $table->text('bibliography')->nullable();
            $table->text('exhibitions')->nullable();
            $table->text('archive')->nullable();
        });

        Schema::table('authority_translations', function (Blueprint $table) {
            $table->dropColumn([
                'bibliography',
                'exhibitions',
                'archive',
            ]);
        });

    }
}
