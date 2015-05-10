<?php

use App\ActiveCustomer;
use App\Ads;
use App\Beacon;
use App\BeaconMinor;
use App\Item;
use App\WatchingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdsSeeder extends Seeder {

	public function run()
	{
        DB::table('ads_item')->delete();
        DB::table('ads_category')->delete();
        DB::table('ads')->delete();


        Ads::create(['id'=>1,'title'=>'Bột giặt Tide Downy 4.5kg Giảm giá 20%'])->items()->attach(1);
        Ads::create(['id'=>2,'title'=>'Bột giặt ARIEL DOWNY 4.1kg Giảm giá 20%'])->items()->attach(5);
        Ads::create(['id'=>3,'title'=>'Kem đánh răng COLGATE the mát bạc hà 150g Giảm giá 19%'])->items()->attach(2);
        Ads::create(['id'=>4,'title'=>'Nước xả DOWNY nắng mai túi 1.8L Giảm giá 18%'])->items()->attach(3);
        Ads::create(['id'=>5,'title'=>'Nước ngọt Pepsi 1.5L Giảm giá 12%'])->items()->attach(4);
        Ads::create(['id'=>6,'title'=>'Nước giặt Omo Matic Font Load 2.7kg Giảm giá 10%'])->items()->attach(7);
        Ads::create(['id'=>7,'title'=>'Nước xả DOWNY 1 lần xả túi 1.6L Giảm giá 18%'])->items()->attach(6);
	}

}
