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
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ContextAdsService
{
    protected $categoryRepo, $storeRepo, $customerRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo, StoreRepositoryInterface $storeRepo, CustomerRepositoryInterface $customerRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->storeRepo = $storeRepo;
        $this->customerRepo = $customerRepo;
    }

    public function getContextAds(ActiveCustomer $customer, BeaconMajor $major, BeaconMinor $minor)
    {
        $result = $this->getSmartPromotions($customer, $major, $minor);
        $result['targetedAds'] = $this->getTargetedAds($customer, $major, $minor);

        return $result;
    }

    public function getTargetedAds($customer, $major, $minor)
    {   
        $allTargeted = Ads::available()->targeted()->where('is_whole_system', true)->get();
        $store = $major->store;
        $storeAds = $store->ads()->get();
        $area = $store->area;
        $areaAds = $area->getAllAds($customer);
        $regionTargetedAds = $allTargeted->merge($storeAds)->merge($areaAds);

        $customerInfo = $this->customerRepo->getCustomerInfo($customer->id);
        
        $targetedAds = array();

        foreach ($regionTargetedAds as $ads) {
            $rule = $ads->targetedRule()->get();
            if ($rule->isEmpty())
                continue;
            $rule = $rule[0];
            if (($customerInfo['gender'] != $rule->gender) && ($rule->gender != 2))
                continue;
            $age = Carbon::now()->diffInYears(Carbon::createFromFormat('Y-m-d', $customerInfo['birth']));
            $toAge = ($rule->to_age == 0) ? $rule->to_age + $age + 1 : $rule->to_age; //to age = 0 exception
            if (($age < $rule->from_age) || ($age > $toAge))
                continue;

            $toMember = ($rule->to_family_members == 0) ? $customerInfo['family_members'] + 1 : $rule->to_family_members;
            if (($customerInfo['family_members'] < $rule->from_family_members)
                || ($customerInfo['family_members'] > $toMember)
            )
                continue;
            
            if ($rule->jobs_desc !== null) {
                if ($customerInfo['jobs_id'] === null)
                    continue;
                $jobs = explode(',', $rule->jobs_desc);
                if (!in_array($customerInfo['jobs_id'], $jobs))
                    continue;
            }

            $targetedAds[] = $ads;
        }

        return Collection::make($targetedAds);
    }

    public function getSmartPromotions($customer, $major, $minor)
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
            $minors = DB::table('category_minor')->whereIn('category_id', $cats)->distinct()->lists('beacon_minor');
            $a['minors'] = $minors;
        }

        $result['entrancePromotions'] = $entranceAds->values();
        $result['aislePromotions'] = $aisleAds->values();
        return $result;
    }

}