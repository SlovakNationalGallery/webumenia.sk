<?php

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // DB::table('roles')->truncate();

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
