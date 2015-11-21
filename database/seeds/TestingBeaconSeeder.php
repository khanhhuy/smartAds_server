<?php

use App\BeaconMajor;
use App\BeaconMinor;
use App\Store;
use App\WatchingList;
use Illuminate\Database\Seeder;

class TestingBeaconSeeder extends Seeder
{

    public function run()
    {
        DB::table('beacon_minors')->delete();

        DB::statement("ALTER TABLE `beacon_majors` AUTO_INCREMENT = 1");

        BeaconMinor::create(['minor' => '1']);
        $m1 = BeaconMajor::create(['major' => '1']);
        $m1->store()->associate(Store::find('S_vn_tphcm_binhtan'))->save();
    }

}
