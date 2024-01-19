<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('item_frontends')
            ->insertUsing(
                ['frontend', 'item_id'],
                DB::table('items')->selectRaw('"webumenia", id')
            );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('item_frontends')->where('frontend', 'webumenia')->delete();
    }
};
