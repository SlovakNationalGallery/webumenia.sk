<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('media', 'spatie_media');

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::create('medium_translations', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('medium_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['medium_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medium_translations');
        Schema::dropIfExists('media');
        Schema::rename('spatie_media', 'media');
    }
};
