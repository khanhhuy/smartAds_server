<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Category;

use App\ActiveCustomer;
use App\Facades\Mining;
use Carbon\Carbon;
use App\Facades\Connector;

class MiningController extends Controller {

	public function index(ActiveCustomer $customer)
	{
        $from = Carbon::now()->subMonths(config('mining.mining_range_months'))->toDateString();
        //return Mining::miningCustomer($customer, false);
		return Mining::miningAllCustomer($from);
	}

    public function getListCategories() {
        return view('select-category', ['tree' => self::getCategoryTree()]);
    }

    public function getCategoryTree(){

        $tree = DB::table('categories as t1')
        ->select('t1.name as lv1Name', 't1.id as lv1Id', 't1.is_suitable as lv1Suitable',
                't2.name as lv2Name', 't2.id as lv2Id', 't2.is_suitable as lv2Suitable',
                't3.name as lv3Name', 't3.id as lv3Id', 't3.is_suitable as lv3Suitable')
        ->leftJoin('categories as t2', 't1.id', '=', 't2.parent_id')
        ->leftJoin('categories as t3', 't2.id', '=', 't3.parent_id')
        ->whereNull('t1.parent_id')
        ->orderBy('t1.name', 'asc')
        ->orderBy('t2.name', 'asc')
        ->orderBy('t3.name', 'asc')
        ->get();

        $parsedTree = array();
        $index1 = $index2 = $index3 = 0;
        $lv1 = $tree[0]->lv1Id;
        $lv2 = $tree[0]->lv2Id;
        $lv3 = $tree[0]->lv3Id;

        foreach ($tree as $key => $category) {

            //order by Name
            if ($lv1 != $category->lv1Id) {
                $index1++;
                $index2 = $index3 = 0;
                $lv1 = $category->lv1Id;
                $lv2 = $category->lv2Id;
            }
            elseif ($lv2 != $category->lv2Id) {
                $index2++;
                $index3 = 0;
                $lv2 = $category->lv2Id;
            }

            $parsedTree[$index1]["name"] = $category->lv1Name;
            $parsedTree[$index1]["id"] = $category->lv1Id;
            $parsedTree[$index1]["is_suitable"] = $category->lv1Suitable;

            $parsedTree[$index1]["subcat"][$index2]["name"] = $category->lv2Name;
            $parsedTree[$index1]["subcat"][$index2]["id"] = $category->lv2Id;
            $parsedTree[$index1]["subcat"][$index2]["is_suitable"] = $category->lv2Suitable;

            if ($category->lv3Name != null) {
                $parsedTree[$index1]["subcat"][$index2]["subcat"][$index3]["name"] = $category->lv3Name;
                $parsedTree[$index1]["subcat"][$index2]["subcat"][$index3]["id"] = $category->lv3Id;
                $parsedTree[$index1]["subcat"][$index2]["subcat"][$index3]["is_suitable"] = $category->lv3Suitable;
            }
            else {
                $parsedTree[$index1]["subcat"][$index2]["subcat"]= [];
            }

            $index3++;
        }

        return $parsedTree;

    }

    public function selectCategory(Request $request){
        $inputs = $request->all();

        DB::table('categories')->update(['is_suitable' => 0]);
        foreach ($inputs as $key => $value) {
            if ($value == 'on') {
                $category = Category::find($key);
                if ($category != null) {
                    $category->is_suitable = 1;
                    $category->save();
                }
            }
        }

        return view('select-category', ['tree' => self::getCategoryTree()]);
    }
}
