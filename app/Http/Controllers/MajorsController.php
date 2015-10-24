<?php namespace App\Http\Controllers;

use App\BeaconMajor;
use App\Http\Requests;
use App\Http\Requests\MajorRequest;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class MajorsController extends Controller
{

    public function manage()
    {
        return view('majors.manage');
    }

    public function table(Request $request)
    {
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = BeaconMajor::count();
        $r['recordsFiltered'] = $r['recordsTotal'];
        $displayPromotions = BeaconMajor::skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'desc')->get();
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

    public function deleteMulti(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return abort('400');
        }
        BeaconMajor::destroy($ids);
    }

    public function create()
    {
        return view('majors.partials.create');
    }

    public function store(MajorRequest $request)
    {
        BeaconMajor::create($request->only(['major', 'store_id']));
        Flash::success('Added Successfully');

        return redirect()->route('majors.create');
    }

    public function edit(BeaconMajor $major)
    {
        return view('majors.partials.edit')->with(compact('major'));
    }

    public function update(BeaconMajor $major, MajorRequest $request)
    {
        $newMajor = $request->input('major');
        if ($newMajor === $major->major) {
            $major->store_id = $request->input('store_id');
            $major->save();
        } else {
            $major->delete();
            BeaconMajor::create($request->only(['major', 'store_id']));
        }
        Flash::success('Updated Successfully');
        return redirect()->route('majors.create');
    }
}
