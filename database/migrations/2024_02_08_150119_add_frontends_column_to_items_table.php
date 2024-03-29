<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE items ADD COLUMN frontends JSON DEFAULT (JSON_ARRAY('webumenia'))");
        DB::statement('CREATE INDEX items_frontends_index ON items ( (CAST(frontends AS CHAR(32) ARRAY)) )');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('items_frontends_index');
            $table->dropColumn('frontends');
        });
    }
};
