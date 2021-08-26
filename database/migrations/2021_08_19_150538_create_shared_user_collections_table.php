<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedUserCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_user_collections', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            $table->string('name');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->json('items');
            $table->string('update_token');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_user_collections');
    }
}
