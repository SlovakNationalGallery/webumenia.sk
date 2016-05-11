<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    	

    	Schema::create('categories', function($table)
        {
			$table->increments('id');
			$table->string('name');
			$table->integer('order');
        });    	
		
		Schema::create('articles', function($table)
        {
			$table->increments('id');
			$table->integer('parent_id')->nullable();
			$table->integer('category_id')->nullable();
			$table->string('author');
			$table->string('slug')->unique();
			$table->string('title');
			$table->string('main_image')->nullable();
			$table->string('title_color')->nullable();
			$table->string('title_shadow')->nullable();
			$table->text('summary');
			$table->text('content');
			$table->integer('view_count')->default(0);
			$table->boolean('promote');
			$table->boolean('publish')->default(false);
			$table->dateTime('published_date')->nullable();
			$table->softDeletes();
			$table->timestamps();

			// autor(i)? kolekcie? / smart kolekcie?
			// rubrika.. ci viacero?
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('articles');
		Schema::drop('categories');
	}

}
