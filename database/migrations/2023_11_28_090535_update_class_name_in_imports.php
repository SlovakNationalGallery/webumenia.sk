<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('imports')
            ->where('class_name', 'App\\Importers\\WebumeniaMgImporter')
            ->update(['class_name' => 'App\\Importers\\MgImporter']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('imports')
            ->where('class_name', 'App\\Importers\\MgImporter')
            ->update(['class_name' => 'App\\Importers\\WebumeniaMgImporter']);
    }
};
