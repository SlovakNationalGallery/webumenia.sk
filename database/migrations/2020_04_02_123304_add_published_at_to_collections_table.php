<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishedAtToCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dateTime('published_at')->after('created_at')->nullable();
        });
        DB::statement('UPDATE collections SET published_at = created_at WHERE publish = 1');
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('publish');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('publish');
        });
        DB::statement('UPDATE collections SET publish = 1 WHERE published_at IS NOT NULL');
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
}
