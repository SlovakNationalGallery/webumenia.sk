<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_records', function (Blueprint $table) {
            $table
                ->integer('imported_items')
                ->default(0)
                ->change();
            $table
                ->integer('skipped_items')
                ->default(0)
                ->change();
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_records', function (Blueprint $table) {
            $table->integer('user_id');
        });
    }
};
