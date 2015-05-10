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
		Model::unguard();
        $this->command->info('-------------Seeding-------------');
		$this->call('ActiveCustomerSeeder');
        $this->call('BeaconSeeder');
        $this->call('CategorySeeder');
        $this->call('AdsSeeder');
	}

}
