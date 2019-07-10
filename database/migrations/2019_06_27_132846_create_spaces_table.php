<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaces', function (Blueprint $table) {
            $table->increments('id');
            $table->date('opened_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->integer('view_count')->default(0);
            $table->boolean('has_image')->default(false);
            $table->timestamps();
        });

        Schema::create('space_translations', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('space_id');
            $table->string('locale')->index();

            // translatable attributes
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('opened_place')->nullable();

            $table->mediumText('description')->nullable();

            $table->text('bibliography')->nullable();
            $table->text('exhibitions')->nullable();
            $table->text('archive')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('space_translations');
        Schema::drop('spaces');
    }
}
