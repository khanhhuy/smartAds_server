<?php namespace App\Http\Controllers;

use App\BeaconMajor;
use App\Http\Requests;
use App\Store;
use Illuminate\Http\Request;

class MajorsController extends Controller
{

    public function manage()
    {
        $allStores = Store::leftJoin('beacon_majors', 'stores.id', '=', 'beacon_majors.store_id')->whereNull('major')->get();
        $stores = [];
        foreach ($allStores as $s) {
            $stores[$s->id] = $s->name . " <br/>(" . $s->display_area . ')';
        }
        return view('majors.manage')->with(compact('stores'));
    }

    public function table(Request $request)
    {
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = BeaconMajor::count();
        $r['recordsFiltered'] = $r['recordsTotal'];
        $displayPromotions = BeaconMajor::skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'asc')->get();
        $r['data'] = $displayPromotions->map(function ($major) {
            $store = $major->store;
            return [
                $store->name,
                $store->display_area,
                $major->major,
                $major->updated_at->format('m-d-Y'),
            ];
        });

        return response()->json($r);
    }

    public function deleteMulti()
    {

    }
}
