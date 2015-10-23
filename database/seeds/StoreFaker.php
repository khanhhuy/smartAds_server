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

        foreach (range(1, 100) as $index) {
            $store=Store::create([
                'id' => 'S_' . $faker->idNumber,
                'area_id' => $faker->randomElement(Store::distinct()->lists('area_id')),
                'name' => $faker->name,
                'address' =>  $faker->address
            ]);
            $major=BeaconMajor::create([
                'store_id'=>$store->id
            ]);
        }
    }

}
