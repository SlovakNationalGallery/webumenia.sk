<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('authorities', function (Blueprint $table) {
            $table->json('roles')->nullable();
        });

        Schema::table('authority_translations', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority_translations', function (Blueprint $table) {
            $table->text('roles')->nullable();
        });

        Schema::table('authorities', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
};
