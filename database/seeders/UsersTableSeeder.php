<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = date("Y-m-d H:i:s");

        $users = [
            [
                'username' => 'admin',
                'email' => 'lab@sng.sk',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'username' => 'sng',
                'email' => 'info@sng.sk',
                'password' => Hash::make('sng'),
                'role' => 'editor',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'username' => 'press',
                'email' => '',
                'password' => Hash::make('press'),
                'role' => 'editor',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table('users')->insert($users);

	}

}
