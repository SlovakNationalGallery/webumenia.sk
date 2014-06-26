<?php 
class UsersTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        DB::table('users')->truncate();
        
        $now = date("Y-m-d H:i:s");

        $users = [
            [
                'username' => 'admin',
                'email' => 'lab@sng.sk',
                'password' => Hash::make('mednyanszky2411'),
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'username' => 'sng',
                'email' => 'info@sng.sk',
                'password' => Hash::make('esenge123'),
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table('users')->insert($users);

	}

}
