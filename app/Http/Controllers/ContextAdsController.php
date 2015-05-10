<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\BeaconMinor;
use App\Facades\ContextAds;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ContextAdsController extends Controller
{

    public function index(ActiveCustomer $customer, BeaconMinor $minor)
    {
        return ContextAds::getContextAds($customer,$minor);
    }

}
