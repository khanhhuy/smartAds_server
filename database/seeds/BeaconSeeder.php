<?php

use App\ActiveCustomer;
use App\Beacon;
use App\BeaconMajor;
use App\BeaconMinor;
use App\Item;
use App\Store;
use App\WatchingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BeaconSeeder extends Seeder {

	public function run()
	{
//        DB::table('beacons')->delete();
        DB::table('beacon_minors')->delete();
        DB::table('beacon_majors')->delete();

        $icyMinor=BeaconMinor::create(['minor'=>'1']);
        $mintMinor=BeaconMinor::create(['minor'=>'2']);
        $blueMinor=BeaconMinor::create(['minor'=>'3']);
        $m1=BeaconMajor::create(['major'=>'1']);
        $m1->store()->associate(Store::find('vn_tphcm_binhtan'))->save();
        BeaconMajor::create(['major'=>'2'])->store()->associate(Store::find('vn_tphcm_binhtrieu'));
        BeaconMajor::create(['major'=>'3'])->store()->associate(Store::find('vn_dongnam_binhduong'));

        /*$icy=new Beacon(['major'=>'1','color' => 'icy marshmallow']);
        $icyMinor->beacons()->save($icy);

        $mintMinor->beacons()->save(new Beacon(['major'=>'2','color' => 'mint cocktail']));
        $blueMinor->beacons()->save(new Beacon(['major'=>'3','color' => 'blueberry pie']));*/
	}

}
