<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $admin = new Role();
        $admin->name = 'admin';
        $admin->save();

        $editor = new Role();
        $editor->name = 'editor';
        $editor->save();

        $users = User::all();

        foreach ($users as $user) {
            $user->roles()->attach($admin->id);
        }
    }
}
