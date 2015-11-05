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

}
