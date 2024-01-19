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
        DB::table('frontends')->insert([
            'name' => 'webumenia.sk',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $frontendId = DB::table('frontends')
            ->where('name', 'webumenia.sk')
            ->first()->id;

        DB::table('items')
            ->chunkById(100, fn ($items) =>
                $items->each(fn ($item) =>
                    DB::table('frontend_item')->insert([
                        'frontend_id' => $frontendId,
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
        DB::table('frontends')->where('name', 'webumenia.sk')->delete();
    }
};
