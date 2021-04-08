<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEnforcerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('DELETE_ME_permission_role');
        Schema::dropIfExists('DELETE_ME_permissions');
        Schema::dropIfExists('DELETE_ME_role_user');
        Schema::dropIfExists('DELETE_ME_roles');
    }

    public function down()
    {
        Schema::create('DELETE_ME_roles', function(Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('DELETE_ME_role_user', function(Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('DELETE_ME_permissions', function(Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('DELETE_ME_permission_role', function(Blueprint $table) {
            $table->increments('id');
        });
    }
}
