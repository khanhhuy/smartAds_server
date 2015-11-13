<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
//		DB::table('categories')->whereNotIn('id', DB::table('category_minor')->distinct()->lists('category_id'))->delete();
//
        $this->command->info('-------------Seeding-------------');
		$this->call('ActiveCustomerSeeder');
        $this->call('PortalUserSeeder');
		$this->call('AdsSeeder');
		$this->call('StoreSeeder');
        $this->call('BeaconSeeder');
        $this->call('CategorySeeder');
        $this->call('AdsFaker');
//		$this->call('TargetedRulesSeeder');
//		$this->call('StoreFaker');
	}
}
