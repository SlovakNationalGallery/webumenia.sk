<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeSlidesToFeaturedPieces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename table and model
        Schema::rename('slides', 'featured_pieces');
        DB::table('media')
            ->where('model_type', 'App\Slide')
            ->update(['model_type' => 'App\FeaturedPiece']);

        // Change subtitle -> excerpt (and expand it to TEXT)
        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->renameColumn('subtitle', 'excerpt');
        });
        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->text('excerpt')->change();
        });

        // Add new 'type' column
        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->text('type')->after('url');
        });
        DB::table('featured_pieces')->update(['type' => 'article']);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('featured_pieces', function (Blueprint $table) {
            $table->dropColumn('type');
        });

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
