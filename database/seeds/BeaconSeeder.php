<?php

use App\ActiveCustomer;
use App\Beacon;
use App\BeaconMinor;
use App\Item;
use App\WatchingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BeaconSeeder extends Seeder {

	public function run()
	{
        DB::table('beacons')->delete();
        DB::table('beacon_minors')->delete();

        $icyMinor=BeaconMinor::create(['minor'=>'1']);
        $mintMinor=BeaconMinor::create(['minor'=>'2']);
        $blueMinor=BeaconMinor::create(['minor'=>'3']);
        $icy=new Beacon(['major'=>'1','color' => 'icy marshmallow']);
        $icyMinor->beacons()->save($icy);

        $mintMinor->beacons()->save(new Beacon(['major'=>'2','color' => 'mint cocktail']));
        $blueMinor->beacons()->save(new Beacon(['major'=>'3','color' => 'blueberry pie']));
	}

}
