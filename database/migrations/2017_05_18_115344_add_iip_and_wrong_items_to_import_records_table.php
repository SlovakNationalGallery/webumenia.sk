<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIipAndWrongItemsToImportRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_records', function($table)
        {
            $table->integer('imported_iip')->default(0);
            $table->integer('wrong_items')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_records', function($table)
        {
            $table->dropColumn('imported_iip');
            $table->dropColumn('wrong_items');            
        });
    }

}
