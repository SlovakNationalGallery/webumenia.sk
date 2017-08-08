<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;

class ImportRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $import = new Role();
        $import->name = 'import';
        $import->description  = 'User can launch import';
        $import->save();
    }
}
