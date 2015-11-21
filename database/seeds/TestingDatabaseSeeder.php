<?php

use App\ActiveCustomer;
use App\Item;
use App\WatchingList;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TestingDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        $fake = Faker::create();
        DB::table('watching_lists')->delete();
        DB::table('active_customers')->delete();
        DB::table('items')->delete();
        DB::table('ads')->delete();
        DB::statement("ALTER TABLE `ads` AUTO_INCREMENT = 1");

        //fake customer
        $customer = ActiveCustomer::create([
            'id' => $fake->numberBetween(1, 1000000),
            'last_mining' => $fake->dateTime
        ]);
        Item::create(
            ['id' => 1]
        );
        $customer->watchingList()->attach([1]);

        $this->call('TestingStoreSeeder');
        $this->call('TestingBeaconSeeder');
        $this->call('TestingCategorySeeder');
    }

}
