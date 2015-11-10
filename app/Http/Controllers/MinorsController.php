<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\MinorRequest;
use App\Repositories\CategoryRepositoryInterface;
use App\BeaconMinor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laracasts\Flash\Flash;
use Lang;
use App\Utils\Utils;
use DB;

class MinorsController extends Controller
{	
	private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
    }

   public function table(Request $request)
    {
        $MAJOR_COLUMNS = ['category', 'minor'];
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = BeaconMinor::count();
        $filtered = BeaconMinor::query();

        //search
        $cols = $request->input("columns");
        for ($c = 1; $c < 3; $c++) {
            $val = $cols[$c]['search']['value'];
            if (!empty($val)) {
                $colName = $MAJOR_COLUMNS[$c - 1];
                $val = trim($val);
                switch ($colName) {
                    case 'category':
                        $selectCat = DB::table('category_minor')->lists('category_id');
                        $rCatId = $this->categoryRepo->searchCategoryByName($val, array_unique($selectCat));
                        if (!empty($rCatId)) {
                            $filtered = $filtered->join('category_minor', 'beacon_minors.minor', '=', 'category_minor.beacon_minor')
                                                    ->whereIn('category_id', $rCatId)->groupby('minor');
                        }
                        break;
                    case 'minor':
                        Utils::filterByFromToBased($filtered, $val, $colName);
                            break;
                    default:
                        break;
                }
            }
        }
        $r['recordsFiltered'] = $filtered->count();

        //order
        if ($request->has('order')) {
            $order = $request->input('order');
            $orderColumn = $MAJOR_COLUMNS[$order[0]['column'] - 1];
            switch ($orderColumn) {
                case 'category':
                    break;
                default:
                    $displays = $filtered->skip($request->input('start'))->take($request->input('length'))
                        ->orderBy($orderColumn, $order[0]['dir'])->get(); //skip: offset page, take: limit page
                    break;
            }
        } else {
            $displays = $filtered->skip($request->input('start'))->take($request->input('length'))
                ->orderBy('beacon_minors.updated_at', 'desc')->get();
        }

        //transform
        $r['data'] = $displays->map(function ($minor) {
            return [
                $minor->categories->map(function ($category) {
                        return $category->name;
                    }),
                $minor->minor
            ];
        });

        return response()->json($r);
    }

    public function manage()
    {
    	$tree = $this->categoryRepo->getCategoryTree();
        return view('minors.manage', ['tree' => $tree]);
    }

    public function store(MinorRequest $request) {
        $inputs = $request->input();
        try {
            $minor = BeaconMinor::findOrFail($inputs['minor_id']);
        }
        catch (ModelNotFoundException $e) {
            $minor_id = $inputs['minor_id'];
            BeaconMinor::create(['minor' => $minor_id]);
            $minor = BeaconMinor::find($minor_id);
        }
        
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

    public function deleteMulti(Request $request) {
        $ids = $request->input('ids');
        if (empty($ids)) {
            abort('400');
        }
        BeaconMinor::destroy($ids);
    }

    public function show(BeaconMinor $minor) {
        $categories = $minor->categories()->lists('id');
        $v = array();
        $v['id'] = $minor->minor;
        $v['categories'] = $categories;
        return $v;
    }

    public function update(BeaconMinor $minor, MinorRequest $request) {
        $newMinor = $request->input('minor_id');
        $newCat = array();
        $categories = $request->except(['_token', 'minor_id']);
        foreach ($categories as $key => $value) {
            $newCat[] = $key;
        }
        if ($minor->minor == $newMinor) {
            $minor->categories()->detach();
            $minor->categories()->attach($newCat);
        }
        else {
            BeaconMinor::destroy($minor->minor);
            BeaconMinor::create(['minor' => $newMinor]);
            $currentMinor = BeaconMinor::find($newMinor);
            $currentMinor->categories()->attach($newCat);
        }

        Flash::success(Lang::get('flash.edit_success'));
        return view('partials.fixed-pos-message');

    }

    public function errors() {
        return view('errors.list');
    }

    // public preProcessCategories() {
        
    // }

}
