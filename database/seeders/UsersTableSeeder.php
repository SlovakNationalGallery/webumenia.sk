<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'lab@sng.sk',
                'password' => Hash::make('admin'),
                'can_administer' =>   true,
                'can_edit' => true,
                'can_publish' =>  true,
                'can_import' =>   true,
            ],
            [
                'username' => 'press',
                'email' => '',
                'password' => Hash::make('press'),
                'can_administer' => false,
                'can_edit' => true,
                'can_publish' => true,
                'can_import' => false,
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
    }
}
