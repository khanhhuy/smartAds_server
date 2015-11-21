<?php

use App\Store;
use App\Utils\Utils;
use App\WatchingList;
use Illuminate\Database\Seeder;

class TestingStoreSeeder extends Seeder
{

    public function run()
    {
        DB::table('beacon_majors')->delete();

        $forceRefresh = false;
        if (!$forceRefresh && !Store::all()->isEmpty()) {
            return;
        }

        DB::table('stores')->delete();
        DB::table('ads_store')->delete();
        DB::table('ads_area')->delete();
        DB::table('areas')->delete();


        $storeRepo = App::make('App\Repositories\StoreRepositoryInterface');
        $stores = $storeRepo->getAllStores(true);
        Utils::updateStoresAreas($stores, false);

    }

}