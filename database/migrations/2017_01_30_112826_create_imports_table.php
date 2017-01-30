<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->text('settings'); // json -> encoding, csv separator .... 
            $table->text('mapping'); // json -> columns mapping
            $table->boolean('publish')->default(true); // publish on webumenia? 
            $table->timestamps();
        });

        Schema::create('import_records', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('import_id');
            $table->integer('imported_items');
            $table->integer('skipped_items');
            $table->string('filename');
            $table->string('status');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('import_records');
        Schema::dropIfExists('imports');
    }
}
