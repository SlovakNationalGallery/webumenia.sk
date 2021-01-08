<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('publish');
            $table->string('alert_class');
            $table->string('updated_by');
            $table->timestamps();
        });

        Schema::create('notice_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('notice_id')->unsigned();
            $table->string('locale')->index();
            $table->text('content');
        
            $table->unique(['notice_id', 'locale']);
            $table->foreign('notice_id')->references('id')->on('notices')->onDelete('cascade');
        });

        DB::statement(
            'INSERT INTO notices (publish, alert_class, created_at, updated_at) VALUES (false, "warning", NOW(), NOW())'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notice_translations');
        Schema::dropIfExists('notices');
    }
}
