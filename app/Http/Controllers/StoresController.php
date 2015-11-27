<?php namespace App\Http\Controllers;

use App;
use App\Commands\UpdateStoresAreas;
use App\Http\Requests;
use Queue;
use Setting;
use App\Store;
use App\BeaconMajor;

class StoresController extends Controller
{

    public function updateStores()
    {
        if (Setting::get('stores_areas.updated_at') !== 'Updating') {
            Queue::push(new UpdateStoresAreas());
            Setting::set('stores_areas.updated_at', 'Updating');
            Setting::save();
//            $storeRepo = App::make('App\Repositories\StoreRepositoryInterface');
//            $stores = $storeRepo->getAllStores(true);
//            Utils::updateStoresAreas($stores);
            return "OK";
        } else {
            return "Updating";
        }
    }

    public function updateStoresStatus()
    {
        return Setting::get('stores_areas.updated_at');
    }

    public function getActiveStores() {
        $activeStores = BeaconMajor::lists('store_id');
        $stores = Store::query()->whereIn('id', $activeStores)->get()->filter(function ($store) {
            if ($store->latitude === null || $store->longitude === null)
                return false;
            else
                return true;
        });

        return $stores->values();
    }

}
