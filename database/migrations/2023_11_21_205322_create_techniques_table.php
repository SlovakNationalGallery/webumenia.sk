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
        Schema::create('techniques', function (Blueprint $table) {
            $table->id();
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::create('technique_translations', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('technique_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['technique_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technique_translations');
        Schema::dropIfExists('techniques');
    }
};
