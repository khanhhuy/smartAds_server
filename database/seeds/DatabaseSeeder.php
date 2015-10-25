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
		$this->call('AdsSeeder');
		$this->call('StoreSeeder');
        $this->call('BeaconSeeder');
        $this->call('CategorySeeder');
		$this->call('AdsFaker');
		$this->call('TargetedRulesSeeder');
//		$this->call('StoreFaker');
	}
}
