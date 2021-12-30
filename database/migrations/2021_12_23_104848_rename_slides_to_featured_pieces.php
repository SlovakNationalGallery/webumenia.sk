<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameSlidesToFeaturedPieces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('slides', 'featured_pieces');
        DB::table('media')
            ->where('model_type', 'App\Slide')
            ->update(['model_type' => 'App\FeaturedPiece']);

        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->renameColumn('subtitle', 'excerpt');
        });

        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->text('excerpt')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->string('excerpt')->change();
        });

        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->renameColumn('excerpt', 'subtitle');
        });

        DB::table('media')
            ->where('model_type', 'App\FeaturedPiece')
            ->update(['model_type' => 'App\Slide']);

        Schema::rename('featured_pieces', 'slides');
    }
}
