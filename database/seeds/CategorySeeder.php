<?php

use App\ActiveCustomer;
use App\Beacon;
use App\BeaconMinor;
use App\Category;
use App\Item;
use App\WatchingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CategorySeeder extends Seeder {

	public function run()
	{
        DB::table('category_minor')->delete();
        DB::table('categories')->delete();

        $catFabricSofteners=Category::create(['id' => '1115193_1071967_1149392']);
        $catLaundryDetergents=Category::create(['id' => '1115193_1071967_1149379']);
        $catToothpaste=Category::create(['id' => '1085666_1007221_1023020']);
        $catSoftDrinks=Category::create(['id' => '976759_976782_1001680']);

        $minor1=BeaconMinor::find(1);
        $minor2=BeaconMinor::find(2);
        $minor3=BeaconMinor::find(3);


        $minor1->categories()->attach($catFabricSofteners);
        $minor2->categories()->attach($catLaundryDetergents);
        $minor3->categories()->attach([$catSoftDrinks->id, $catToothpaste->id]);
	}

}
