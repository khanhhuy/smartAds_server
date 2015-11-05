<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\CategoryRepositoryInterface;
use App\BeaconMinor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laracasts\Flash\Flash;
use Lang;

class MinorsController extends Controller
{	
	private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
    }

   public function table(Request $request)
    {
        $MAJOR_COLUMNS = ['minor', 'category'];
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = BeaconMinor::count();
        $filtered = BeaconMinor::query();

        //order
        if ($request->has('order')) {
            $order = $request->input('order');
            $orderColumn = $MAJOR_COLUMNS[$order[0]['column'] - 1];
            switch ($orderColumn) {
                case 'store':
                    $displays = $filtered->join('stores', 'beacon_majors.store_id', '=', 'stores.id')->skip($request->input('start'))->take($request->input('length'))
                        ->orderBy('stores.name', $order[0]['dir'])->get();
                    break;
                case 'area':
                    $displays = $filtered->join('stores', 'beacon_majors.store_id', '=', 'stores.id')->skip($request->input('start'))->take($request->input('length'))
                        ->orderBy('stores.display_area', $order[0]['dir'])->get();
                    break;
                default:
                    $displays = $filtered->skip($request->input('start'))->take($request->input('length'))
                        ->orderBy($orderColumn, $order[0]['dir'])->get();
                    break;
            }
        } else {
            $displays = $filtered->skip($request->input('start'))->take($request->input('length'))
                ->orderBy('beacon_minors.updated_at', 'desc')->get();
        }

        //transform
        $r['data'] = $displays->map(function ($minor) {
            return [
                $minor->minor,
                $minor->categories->map(function ($category) {
                        return $category->name;
                    }),
            ];
        });

        return response()->json($r);
    }

    public function manage()
    {
    	$tree = $this->categoryRepo->getCategoryTree();
        return view('minors.manage', ['tree' => $tree]);
    }

    public function store(Request $request) {
        $inputs = $request->input();
        try {
            $minor = BeaconMinor::findOrFail($inputs['minor_id']);
        }
        catch (ModelNotFoundException $e) {
            $minor = BeaconMinor::create([$inputs['minor_id'], false]);
        }
        $minor->categories()->detach();
        $categories = $request->except(['_token', 'minor_id']);
        $newCat = array();
        foreach ($categories as $key => $value) {
            $newCat[] = $key;
        }
        $minor->categories()->attach($newCat);
        Flash::success(Lang::get('flash.add_success'));
        //return $minor->categories()->lists('category_id');
        return view('partials.fixed-pos-message');
    }

    public function deleteMulti() {

    }

    public function edit() {
        
    }

}
