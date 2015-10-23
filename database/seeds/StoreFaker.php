<?php

use App\Ads;
use App\BeaconMajor;
use App\Item;
use App\Store;
use App\WatchingList;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StoreFaker extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $i) {
            $id='S_' . $faker->userName;
            Store::create([
                'id' =>$id,
                'area_id' => $faker->randomElement(Store::distinct()->lists('area_id')),
                'name' => $faker->name,
                'address' =>  $faker->address
            ]);
            BeaconMajor::create([
                'store_id'=>$id
            ]);
        }

        $all = Store::all();
        foreach ($all as $s) {
            $s->display_area=Utils::formatStoreAreas($s);
            $s->save();
        }
    }

}
