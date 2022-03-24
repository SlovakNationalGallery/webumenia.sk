<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shuffled_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table
                ->foreign('item_id')
                ->references('id')
                ->on('items');
            $table->json('crop');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shuffled_items');
    }
};
