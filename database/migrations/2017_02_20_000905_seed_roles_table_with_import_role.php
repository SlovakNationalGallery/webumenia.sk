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
        if (!DB::table('roles')->where('name', 'import')->exists()) {
            $now = new \DateTime();

            DB::table('roles')->insert([
                'name' => 'import',
                'description' => 'User can launch import',
                'created_at' => $now,
                'updated_at' => $now
            ]);
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
