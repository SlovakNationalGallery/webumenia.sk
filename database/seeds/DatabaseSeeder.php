<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Eloquent::unguard();
		$this->call('UsersTableSeeder');
		$this->call('HarvestsTableSeeder');
		// $this->call('CollectionsTableSeeder');
		$this->call('CategoriesTableSeeder');
		// $this->call('ArticlesTableSeeder');
		$this->call('RolesTableSeeder');
		// $this->call('SketchbooksTableSeeder');
		// $this->call('SlidesTableSeeder');
		$this->call('ImportRoleTableSeeder');
	}

}