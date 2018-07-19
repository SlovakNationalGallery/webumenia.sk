<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeColumnsMediumAndTechniqueNullableInItemsTable extends Migration
{

    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('medium')->nullable()->change();
            $table->string('technique')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('medium')->nullable(false)->change();
            $table->string('technique')->nullable(false)->change();
        });
    }
}
