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
            $table->text('bibliography')->nullable();
            $table->text('exhibitions')->nullable();
            $table->text('archive')->nullable();
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
            $table->dropColumn([
                'active_in',
                'bibliography',
                'exhibitions',
                'archive',
            ]);

        });
    }
}
