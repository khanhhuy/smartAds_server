<?php

use App\Area;
use App\Store;
use App\WatchingList;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{

    public function run()
    {
        DB::table('stores')->delete();
        DB::table('ads_store')->delete();
        DB::table('areas')->delete();
        DB::table('ads_area')->delete();

        $storeRepo = App::make('App\Repositories\StoreRepositoryInterface');
        $stores = $storeRepo->getAllStores(true);
        foreach ($stores as $as) {
            $this->insert($as, null);
        }

        $all = Store::all();
        foreach ($all as $s) {
            $s->display_area=Utils::formatStoreAreas($s);
            $s->save();
        }

        Store::find('S_vn_tphcm_binhtan')->ads()->attach(3);
        Store::find('S_vn_dongnam_binhduong')->ads()->attach(5);
        Area::find('A_vn_tphcm')->ads()->attach([4, 6]);
        Area::find('A_vn')->ads()->attach([5, 7]);
    }

    private function insert($as, $parentID)
    {
        if (array_key_exists('children', $as)) {
            Area::create(['id' => 'A_' . $as['id'], 'parent_id' => $parentID, 'name' => $as['name']]);
            foreach ($as['children'] as $child) {
                $this->insert($child, 'A_' . $as['id']);
            }
        } else {
            Store::create(['id' => 'S_' . $as['id'], 'area_id' => $parentID, 'name' => $as['name'], 'address' => $as['address']]);
        }
    }
}
