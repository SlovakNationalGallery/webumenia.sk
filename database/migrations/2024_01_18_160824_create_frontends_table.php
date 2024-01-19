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
        Schema::create('frontends', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('frontend_item', function (Blueprint $table) {
            $table
                ->foreignId('frontend_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->primary(['frontend_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frontend_item');
        Schema::dropIfExists('frontends');
    }
};
