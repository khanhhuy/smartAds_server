<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 1:14 PM
 */

namespace App\Services;


use App\ActiveCustomer;
use App\Ads;
use App\BeaconMajor;
use App\BeaconMinor;
use App\Facades\Connector;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ContextAdsService
{
    protected $categoryRepo, $storeRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo, StoreRepositoryInterface $storeRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->storeRepo = $storeRepo;
    }

    public function getContextAds(ActiveCustomer $customer, BeaconMajor $major, BeaconMinor $minor)
    {
        $watchingList = $customer->watchingList->lists('id');
        $wholeSystemAds = Ads::forCustomer($customer)->where('is_whole_system', true)->get();
        $store = $major->store;
        $storeAds = $store->ads()->forCustomer($customer)->get();
        $area = $store->area;
        $areaAds = $area->getApplyingAdsForCustomer($customer);
        $allAds = $wholeSystemAds->merge($storeAds)->merge($areaAds);
        $contextAds = $allAds->filter(function ($ads) use ($watchingList) {
            $intersect = $ads->items()->whereIn('id', $watchingList)->get();
            if (!$intersect->isEmpty()) {
                return true;
            }
            return false;
        });
        $minEntranceDiscountValue = $customer->getMinEntranceDiscountValue();
        $minEntranceDiscountRate = $customer->getMinEntranceDiscountRate();
        $entranceAds = $contextAds->filter(function ($ads) use ($minEntranceDiscountValue, $minEntranceDiscountRate) {
            return $ads->discount_value >= $minEntranceDiscountValue || $ads->discount_rate >= $minEntranceDiscountRate;
        });
        $aisleAds = $contextAds->diff($entranceAds);
        foreach ($aisleAds as $a) {
            $items = $a->items;
            $cats = $this->categoryRepo->getAllCategoryNodesOfItems($items);
            //$minors=BeaconMinor::join('category_minor','beacon_minors.minor','=','category_minor.beacon_minor')->whereIn('category_id',$cats)->get();
            $minors = DB::table('category_minor')->whereIn('category_id', $cats)->lists('beacon_minor');
            $a['minors'] = $minors;
        }

        $result['entranceAds'] = $entranceAds->values();
        $result['aisleAds'] = $aisleAds->values();


        return $result;
    }

    /*public function getContextAds(ActiveCustomer $customer, BeaconMinor $minor)
    {
        $watchingList = $customer->watchingList->lists('id');
        $nearbyCategories = $minor->categories;
        $nearbyItemIDs = $this->categoryRepo->getItemIDsFromCategories($nearbyCategories);

        $nearbyWatchingItemIDs = array_values(array_intersect($watchingList, $nearbyItemIDs));

        $contextAds1 = Ads::all()->filter(function ($ads) use ($nearbyWatchingItemIDs) {
            if ($ads->items != null) {
                $intersect = array_intersect($ads->items->lists('id'), $nearbyWatchingItemIDs);
                if (!empty($intersect)) {
                    return true;
                }
            }
            return false;
        });
        $contextAds2 = Ads::all()->filter(function ($ads) use ($nearbyWatchingItemIDs, $contextAds1) {
            $categories = $ads->categories;
            if (!$contextAds1->contains($ads) && $categories != null) {
                foreach ($categories as $category) {
                    $itemIDs = Connector::getItemIDsFromCategory($category);
                    $intersect = array_intersect($itemIDs, $nearbyWatchingItemIDs);
                    if (!empty($intersect)) {
                        return true;
                    }
                }
            }
            return false;
        });
        $contextAds = $contextAds1->merge($contextAds2);

        return $contextAds;
    }*/
}