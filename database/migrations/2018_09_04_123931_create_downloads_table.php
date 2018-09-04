<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_id')->nullable();
            $table->enum('type', ['private', 'publication', 'commercial'])->default('private');
            $table->string('company')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('purpose')->nullable();
            $table->text('note')->nullable();
            $table->string('publication_name')->nullable();
            $table->string('publication_author')->nullable();
            $table->string('publication_year')->nullable();
            $table->string('publication_print_run')->nullable();
            // $table->boolean('gdpr')->default(false);
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
        Schema::dropIfExists('downloads');
    }
}
