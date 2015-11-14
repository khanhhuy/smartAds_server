<?php namespace App\Http\Controllers;

use App\BeaconMinor;
use App\Http\Requests;
use App\Http\Requests\MinorRequest;
use App\Repositories\CategoryRepositoryInterface;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Lang;
use Laracasts\Flash\Flash;

class MinorsController extends Controller
{
    private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function table(Request $request)
    {
        $MAJOR_COLUMNS = ['category', 'minor'];
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = BeaconMinor::count();
        $filtered = BeaconMinor::query();
        $joinedCat = false;

        //search
        $cols = $request->input("columns");
        for ($c = 1; $c < 3; $c++) {
            $val = $cols[$c]['search']['value'];
            if (!empty($val)) {
                $colName = $MAJOR_COLUMNS[$c - 1];
                $val = trim($val);
                switch ($colName) {
                    case 'category':
                        $filtered = $filtered->join('category_minor', 'beacon_minors.minor', '=', 'category_minor.beacon_minor')
                            ->join('categories', 'category_minor.category_id', '=', 'categories.id')
                            ->where('categories.name', 'LIKE', '%' . $val . '%');
                        $joinedCat = true;
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
                    if (!$joinedCat) {
                        $displays = $filtered->leftJoin('category_minor', 'beacon_minors.minor', '=', 'category_minor.beacon_minor')
                            ->leftJoin('categories', 'category_minor.category_id', '=', 'categories.id')
                            ->orderBy('categories.name', $order[0]['dir'])->select('beacon_minors.*')->distinct()->skip($request->input('start'))->take($request->input('length'))->with('categories')->get();
                    } else {
                        $displays = $filtered->orderBy('categories.name', $order[0]['dir'])->select('beacon_minors.*')->distinct()->skip($request->input('start'))->take($request->input('length'))->with('categories')->get();
                    }
                    break;
                default:
                    $displays = $filtered->skip($request->input('start'))->take($request->input('length'))->select('beacon_minors.*')->distinct()->with('categories')
                        ->orderBy($orderColumn, $order[0]['dir'])->get(); //skip: offset page, take: limit page
                    break;
            }
        } else {
            $displays = $filtered->skip($request->input('start'))->take($request->input('length'))
                ->orderBy('beacon_minors.updated_at', 'desc')->select('beacon_minors.*')->distinct()->with('categories')->get();
        }

        //transform
        $r['data'] = $displays->map(function ($minor) {
            $catsNames = $minor->categories->lists('name');
            sort($catsNames);
            return [
                $catsNames,
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

    public function store(MinorRequest $request)
    {
        $newMinor = $request->input('minor_id');
        $minor = BeaconMinor::create(['minor' => $newMinor]);
        $minor = BeaconMinor::find($newMinor);

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

    public function deleteMulti(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            abort('400');
        }
        BeaconMinor::destroy($ids);
    }

    public function show(BeaconMinor $minor)
    {
        $categories = $minor->categories()->lists('id');
        $v = array();
        $v['id'] = $minor->minor;
        $v['categories'] = $categories;
        return $v;
    }

    public function update(BeaconMinor $minor, MinorRequest $request)
    {
        $newMinor = $request->input('minor_id');
        $newCat = array();
        $categories = $request->except(['_token', 'minor_id']);
        foreach ($categories as $key => $value) {
            $newCat[] = $key;
        }
        if ($minor->minor == $newMinor) {
            $minor->categories()->detach();
            $minor->categories()->attach($newCat);
        } else {
            BeaconMinor::destroy($minor->minor);
            BeaconMinor::create(['minor' => $newMinor]);
            $currentMinor = BeaconMinor::find($newMinor);
            $currentMinor->categories()->attach($newCat);
        }

        Flash::success(Lang::get('flash.edit_success'));
        return view('partials.fixed-pos-message');

    }

    public function errors()
    {
        return view('errors.list');
    }

    // public preProcessCategories() {

    // }

}
