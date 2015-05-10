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
use App\BeaconMinor;
use App\Facades\Connector;
use App\Repositories\CategoryInterface;

class ContextAdsService {
    protected $categoryRepo;
    public function __construct(CategoryInterface $categoryRepo)
    {
        $this->categoryRepo=$categoryRepo;
    }

    public function getContextAds(ActiveCustomer $customer, BeaconMinor $minor)
    {
        $watchingList = $customer->watchingList->lists('id');
        $nearbyCategories = $minor->categories;
        $nearbyItemIDs = $this->categoryRepo->getItemIDsFromCategories($nearbyCategories);

        $nearbyWatchingItemIDs = array_values(array_intersect($watchingList, $nearbyItemIDs));

        //$adsItems=DB::table('ads_item')->distinct()->lists('item_id');
        //$result=array_values(array_intersect($result1,$adsItems));
        //$contextAds=Ads::join('ads_item','ads.id','=','ads_item.ads_id')->whereIn('item_id',$result1)->select('id','title')->distinct()->get()->all();
        $contextAds1 = Ads::all()->filter(function ($ads) use ($nearbyWatchingItemIDs) {
            if ($ads->items != null && !empty(array_intersect($ads->items->lists('id'), $nearbyWatchingItemIDs))) {
                return true;
            }
            return false;
        });
        $contextAds2 = Ads::all()->filter(function($ads) use ($nearbyWatchingItemIDs,$contextAds1) {
            $categories=$ads->categories;
            if (!$contextAds1->contains($ads)&&$categories!=null){
                foreach ($categories as $category){
                    $itemIDs=Connector::getItemIDsFromCategory($category);
                    if (!empty(array_intersect($itemIDs,$nearbyWatchingItemIDs))){
                        return true;
                    }
                }
            }
            return false;
        });
        $contextAds=array_merge($contextAds1->all(),$contextAds2->all());

        return $contextAds;
    }
}