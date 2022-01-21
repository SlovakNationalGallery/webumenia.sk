<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutomaticallyMatchedToAuthorityItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_item', function (Blueprint $table) {
            $table->boolean('automatically_matched')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authority_item', function (Blueprint $table) {
            $table->dropColumn('automatically_matched');
        });
    }
}
