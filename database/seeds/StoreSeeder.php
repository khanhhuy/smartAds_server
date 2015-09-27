<?php

use App\ActiveCustomer;
use App\Area;
use App\Beacon;
use App\BeaconMajor;
use App\BeaconMinor;
use App\Item;
use App\Store;
use App\WatchingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class StoreSeeder extends Seeder {

	public function run()
	{
        DB::table('stores')->delete();
        DB::table('ads_store')->delete();
        DB::table('areas')->delete();
        DB::table('ads_area')->delete();

        $storeRepo=App::make('App\Repositories\StoreRepositoryInterface');
        $stores = $storeRepo->getAllStores(true);
        foreach ($stores as $as){
            $this->insert($as,null);
        }

        Store::find('vn_tphcm_binhtan')->ads()->attach(3);
        Store::find('vn_dongnam_binhduong')->ads()->attach(5);
        Area::find('vn_tphcm')->ads()->attach([4,6]);
        Area::find('vn')->ads()->attach([5,7]);
	}

    private function insert($as, $parent)
    {
        if (array_key_exists('children', $as)) {
            Area::create(['id'=>$as['id'],'parent_id'=>$parent['id']]);
            foreach ($as['children'] as $child) {
                $this->insert($child,$as);
            }
        }
        else{
            Store::create(['id'=>$as['id'],'area_id'=>$parent['id']]);
        }
    }
}
