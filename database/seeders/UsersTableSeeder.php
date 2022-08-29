<?php

namespace Database\Seeders;

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
                'role' => 'admin',
            ],
            [
                'username' => 'sng',
                'email' => 'info@sng.sk',
                'password' => Hash::make('sng'),
                'role' => 'editor',
            ],
            [
                'username' => 'press',
                'email' => '',
                'password' => Hash::make('press'),
                'role' => 'editor',
            ],
        ];

        DB::table('users')->insert($users);
    }
}
