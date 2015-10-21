<?php namespace App\Http\Controllers;

use App\BeaconMajor;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Store;
use Illuminate\Http\Request;
use Utils;

class MajorsController extends Controller {

    public function manage()
    {
        $stores=Store::lists('name','id');
        return view('majors.manage')->with(compact('stores'));
	}

    public function table(Request $request)
    {
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = BeaconMajor::count();
        $r['recordsFiltered'] = $r['recordsTotal'];
        $displayPromotions = BeaconMajor::skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'asc')->get();
        $r['data'] = $displayPromotions->map(function ($major) {
            $store=$major->store;
            return [
                $store->name,
                Utils::formatStoreAreas($store),
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
