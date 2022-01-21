<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('password')->nullable(false);
        });

        DB::statement('
            UPDATE users AS u
                LEFT JOIN role_user AS ru ON ru.user_id = u.id
                LEFT JOIN roles AS r ON r.id = ru.role_id
            SET u.role = r.name
        ');

        // Limit existing roles to "editor" or "admin"
        DB::statement('UPDATE users SET role = "editor" WHERE role != "admin"');

        Schema::rename('permission_role', 'DELETE_ME_permission_role');
        Schema::rename('permissions', 'DELETE_ME_permissions');
        Schema::rename('role_user', 'DELETE_ME_role_user');
        Schema::rename('roles', 'DELETE_ME_roles');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('DELETE_ME_permission_role', 'permission_role');
        Schema::rename('DELETE_ME_permissions', 'permissions');
        Schema::rename('DELETE_ME_role_user', 'role_user');
        Schema::rename('DELETE_ME_roles', 'roles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}
