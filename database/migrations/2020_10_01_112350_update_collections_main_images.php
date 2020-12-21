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
		  DB::statement('UPDATE `slides` SET `image` =  CONCAT(id,"/",image,".jpg")');
		  DB::statement('UPDATE `collections` SET `main_image` =  CONCAT(id,".jpg") WHERE main_image IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		  DB::statement('UPDATE `slides` SET `image` =  REPLACE(REPLACE(image,CONCAT(id ,"/"),""),".jpg","")');
    }
}
