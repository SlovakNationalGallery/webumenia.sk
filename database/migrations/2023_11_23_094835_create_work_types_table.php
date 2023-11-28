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
        Schema::create('work_types', function (Blueprint $table) {
            $table->id();
            $table->nestedSet();
            $table->timestamps();
        });

        Schema::create('work_type_translations', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('work_type_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['work_type_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_type_translations');
        Schema::dropIfExists('work_types');
    }
};
