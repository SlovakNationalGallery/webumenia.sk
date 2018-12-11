<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKhbFieldsToAuthoritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authorities', function (Blueprint $table) {
            $table->string('active_in');
            $table->integer('studied_at_id')->unsigned();            
            $table->integer('website_link_id')->unsigned();
            $table->integer('exhibition_id')->unsigned();            
            $table->string('bibliography');
            //
        });
        Schema::table('authorities', function (Blueprint $table) {
            $table->foreign('studied_at_id')->references('id')->on('authority_events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('website_link_id')->references('id')->on('links')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('exhibition_id')->references('id')->on('links')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authorities', function (Blueprint $table) {
            $table->dropForeign([
                'studied_at_id',
                'website_link_id',
                'exhibition_id'
            ]);
        });
        Schema::dropIfExists('active_in');
        Schema::dropIfExists('bibliography');
    }
}
