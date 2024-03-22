<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spice_harvester_harvests', function (Blueprint $table) {
            $table->string('set_spec')->nullable()->change();
            $table->string('set_name')->nullable()->change();
            $table->string('set_description')->nullable()->change();
            $table->text('status_messages')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spice_harvester_harvests', function (Blueprint $table) {
            $table->string('set_spec')->nullable(false)->change();
            $table->string('set_name')->nullable(false)->change();
            $table->string('set_description')->nullable(false)->change();
            $table->text('status_messages')->nullable(false)->change();
        });
    }
};
