<?php

use App\BeaconMajor;
use App\BeaconMinor;
use App\Store;
use App\WatchingList;
use Illuminate\Database\Seeder;

class BeaconSeeder extends Seeder {

	public function run()
	{
        DB::table('beacon_minors')->delete();
        DB::statement("ALTER TABLE `beacon_minors` AUTO_INCREMENT = 1");
        DB::statement("ALTER TABLE `beacon_majors` AUTO_INCREMENT = 1");

        $icyMinor=BeaconMinor::create(['minor'=>'1']);
        $mintMinor=BeaconMinor::create(['minor'=>'2']);
        $blueMinor=BeaconMinor::create(['minor'=>'3']);
        $m1=BeaconMajor::create(['major'=>'1']);
        $m1->store()->associate(Store::find('S_vn_tphcm_binhtan'))->save();
        BeaconMajor::create(['major'=>'2'])->store()->associate(Store::find('S_vn_tphcm_binhtrieu'))->save();
        BeaconMajor::create(['major'=>'3'])->store()->associate(Store::find('S_vn_dongnam_binhduong'))->save();
	}

}
