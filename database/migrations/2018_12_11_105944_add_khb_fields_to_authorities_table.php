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
            $table->string('active_in')->nullable();
            // $table->integer('studied_at_id')->nullable()->unsigned();
            // $table->integer('website_link_id')->nullable()->unsigned();
            // $table->integer('exhibition_id')->nullable()->unsigned();
            $table->text('bibliography')->nullable();
            $table->text('exhibitions')->nullable();
            $table->text('archive')->nullable();
            //
        });
        Schema::table('authorities', function (Blueprint $table) {
            // $table->foreign('studied_at_id')->references('id')->on('authority_events')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('website_link_id')->references('id')->on('links')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('exhibition_id')->references('id')->on('links')->onUpdate('cascade')->onDelete('cascade');
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
            // $table->dropForeign([
                // 'studied_at_id',
                // 'website_link_id',
                // 'exhibition_id'
            // ]);
            $table->dropColumn('active_in');
            // $table->dropColumn('studied_at_id');
            $table->dropColumn('bibliography');
            $table->dropColumn('exhibitions');
            $table->dropColumn('archive');

        });
    }
}
