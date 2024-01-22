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
        Schema::create('item_frontends', function (Blueprint $table) {
            $table->string('frontend');
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->timestamps();
            $table->primary(['item_id', 'frontend']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_frontends');
    }
};
