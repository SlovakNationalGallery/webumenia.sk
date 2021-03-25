<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        Eloquent::unguard();
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
