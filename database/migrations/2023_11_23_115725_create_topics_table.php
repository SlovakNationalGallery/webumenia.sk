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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('topic_translations', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('topic_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['topic_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_translations');
        Schema::dropIfExists('topics');
    }
};
