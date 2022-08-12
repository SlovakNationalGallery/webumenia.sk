<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table
                ->string('identifier')
                ->nullable()
                ->default(null)
                ->change();
        });

        DB::statement("UPDATE items SET identifier = NULL WHERE identifier = ''");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE items SET identifier = '' WHERE identifier IS NULL");

        Schema::table('items', function (Blueprint $table) {
            $table
                ->string('identifier')
                ->nullable(false)
                ->default('')
                ->change();
        });
    }
};
