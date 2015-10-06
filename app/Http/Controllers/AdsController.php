<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\Ads;
use Illuminate\Support\Facades\Request;


class AdsController extends Controller
{

    public function show($ads)
    {
        return view('ads.show.' . $ads->id, compact('ads'));
    }

    public function index(Request $request)
    {
        $idOnly = $request->input('id_only');
        if ($idOnly != null && $idOnly == true) {
            return Ads::lists('id');
        } else {
            return Ads::all();
        }
    }

    public function receivedIndex(Request $request,ActiveCustomer $customer)
    {
        define('LIMIT_DEFAULT', 25);
        $limit = $request->input('limit', LIMIT_DEFAULT);
        if ($limit < 1) {
            $limit = LIMIT_DEFAULT;
        }
        $result = $customer->receivedAds()->latest('last_received')->take($limit)->get();

        $idOnly = Request::input('id_only');
        if ($idOnly != null && $idOnly == true) {
            return $result->lists('id');
        } else {
            return $result;
        }

    }

    public function manage()
    {
        return view('manager-master');
    }
}
