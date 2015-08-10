<?php 
class RolesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        // DB::table('roles')->truncate();

        $admin = new Role;
        $admin->name = 'admin';
        $admin->save();
        
        $editor = new Role;
        $editor->name = 'editor';
        $editor->save();
        
        $users = User::all();

        foreach ($users as $user) {
            $user->roles()->attach( $admin->id );    
        }

        $user = new User;
        $user->username = 'ludikovaz';
        $user->email = 'zuzana.ludikova@sng.sk';
        $user->name = 'Zuzana Ludiková';
        $user->password = Hash::make("moyzes15");
        $user->save();

        $user->roles()->attach( $editor->id );



        
        // $now = date("Y-m-d H:i:s");

        // $users = [
        //     [
        //         'username' => 'admin',
        //         'email' => 'lab@sng.sk',
        //         'password' => Hash::make('admin'),
        //         'created_at' => $now,
        //         'updated_at' => $now
        //     ],
        //     [
        //         'username' => 'sng',
        //         'email' => 'info@sng.sk',
        //         'password' => Hash::make('sng'),
        //         'created_at' => $now,
        //         'updated_at' => $now
        //     ],
        //     [
        //         'username' => 'press',
        //         'email' => '',
        //         'password' => Hash::make('press'),
        //         'created_at' => $now,
        //         'updated_at' => $now
        //     ]
        // ];

        // DB::table('users')->insert($users);

	}

}
