<?php

use Illuminate\Database\Migrations\Migration;
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
        DB::table('items')
            ->chunkById(100, fn ($items) =>
                $items->each(fn ($item) =>
                    DB::table('item_frontends')->insert([
                        'frontend' => 'webumenia',
                        'item_id' => $item->id,
                    ])
                )
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
