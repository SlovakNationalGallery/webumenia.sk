<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedRolesTableWithImportRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( \App\Role::where('name','=','import')->count() != 1 ) {
            \Artisan::call( 'db:seed', [
                   '--class' => 'ImportRoleTableSeeder',
                   '--force' => true ]
               );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
