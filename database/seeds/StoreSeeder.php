<?php

use App\Area;
use App\Store;
use App\Utils\Utils;
use App\WatchingList;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{

    public function run()
    {
        DB::table('beacon_majors')->delete();
        DB::table('stores')->delete();
        DB::table('ads_store')->delete();
        DB::table('ads_area')->delete();
        DB::table('areas')->delete();


        $storeRepo = App::make('App\Repositories\StoreRepositoryInterface');
        $stores = $storeRepo->getAllStores(true);
        Utils::updateStoresAreas($stores, false);

        Store::find('S_vn_tphcm_binhtan')->ads()->attach(3);
        Store::find('S_vn_dongnam_binhduong')->ads()->attach(5);
        Area::find('A_vn_tphcm')->ads()->attach([4, 6]);
        Area::find('A_vn')->ads()->attach([5, 7]);
    }

}