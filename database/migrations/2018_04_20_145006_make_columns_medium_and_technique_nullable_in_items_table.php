<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeColumnsMediumAndTechniqueNullableInItemsTable extends Migration
{

    public function up()
    {

        if (Schema::hasColumns('items', ['medium', 'technique'])) {
           Schema::table('items', function (Blueprint $table) {
               $table->string('medium')->nullable()->change();
               $table->string('technique')->nullable()->change();
           });
        }

        if (Schema::hasColumns('item_translations', ['medium', 'technique'])) {
           Schema::table('item_translations', function (Blueprint $table) {
               $table->string('medium')->nullable()->change();
               $table->string('technique')->nullable()->change();
           });
        }

    }

    public function down()
    {
        if (Schema::hasColumns('items', ['medium', 'technique'])) {
           Schema::table('items', function (Blueprint $table) {
                $table->string('medium')->nullable(false)->change();
                $table->string('technique')->nullable(false)->change();
           });
        }

        if (Schema::hasColumns('item_translations', ['medium', 'technique'])) {
            DB::table('item_translations')->whereNull('medium')->update(['medium' => '']);
            DB::table('item_translations')->whereNull('technique')->update(['technique' => '']);

            Schema::table('item_translations', function (Blueprint $table) {
                $table->string('medium')->nullable(false)->change();
                $table->string('technique')->nullable(false)->change();
            });
        }

    }
}
