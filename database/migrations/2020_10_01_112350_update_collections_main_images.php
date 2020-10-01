<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollectionsMainImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		  DB::statement('UPDATE `slides` SET `image` =  CONCAT(id,"/",image)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		  DB::statement('UPDATE `slides` SET `image` =  REPLACE(image,CONCAT(id ,"/"),"")');
    }
}
