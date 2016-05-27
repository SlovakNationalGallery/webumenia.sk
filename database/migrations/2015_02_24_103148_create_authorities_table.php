<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthoritiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('authorities', function ($table) {
            $table->string('id')->unique();
            $table->string('type');
            $table->string('type_organization')->nullable();
            $table->string('name');
            $table->string('sex');
            $table->text('biography');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('death_place')->nullable();
            $table->date('death_date')->nullable();
            $table->timestamps();
        });

        Schema::create('nationalities', function ($table) {
            $table->string('id')->unique;
            $table->string('code');
            $table->timestamps();
        });

        Schema::create('authority_nationality', function ($table) {
            $table->string('authority_id');
            $table->string('nationality_id');
            $table->boolean('prefered');
        });

        Schema::create('authority_roles', function ($table) {
            $table->increments('id');
            $table->string('authority_id');
            $table->boolean('prefered');
            $table->string('role');
        });

        Schema::create('authority_names', function ($table) {
            $table->increments('id');
            $table->string('authority_id');
            $table->boolean('prefered');
            $table->string('name');
        });

        Schema::create('authority_events', function ($table) {
            $table->increments('id');
            $table->string('authority_id');
            $table->boolean('prefered');
            $table->string('event');
            $table->string('place');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });

        Schema::create('authority_relationships', function ($table) {
            $table->increments('id');
            $table->string('authority_id');
            $table->string('realted_authority_id')->nullable();
            $table->string('name');
            $table->string('type');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('authority_relationships');
		Schema::dropIfExists('authority_events');
		Schema::dropIfExists('authority_names');
		Schema::dropIfExists('authority_roles');
		Schema::dropIfExists('authority_nationality');
		Schema::dropIfExists('nationalities');
		Schema::dropIfExists('authorities');
	}

}
