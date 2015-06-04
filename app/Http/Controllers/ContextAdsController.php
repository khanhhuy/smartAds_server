<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\BeaconMinor;
use App\Facades\ContextAds;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ContextAdsController extends Controller
{

    public function index(ActiveCustomer $customer, BeaconMinor $minor)
    {
        $contextAds = ContextAds::getContextAds($customer, $minor);
        $currentReceivedAds = $customer->receivedAds;
        $contextAdsReceived = $contextAds->intersect($currentReceivedAds);
        if (!$contextAdsReceived->isEmpty()) {
            $customer->receivedAds()->updateExistingPivot($contextAdsReceived->lists('id'), ['last_received' => Carbon::now()]);
        }
        $customer->receivedAds()->attach($contextAds->diff($currentReceivedAds)->lists('id'));


        return $contextAds;
    }

}
