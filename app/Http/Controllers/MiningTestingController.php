<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use App\ActiveCustomer;
use App\Facades\Mining;
use Carbon\Carbon;

class MiningTestingController extends Controller {

	public function index(ActiveCustomer $customer)
	{
        $from = Carbon::now()->subMonths(config('mining.mining_range_months'))->toDateString();
        //return Mining::miningCustomer($customer, false);
		return Mining::miningAllCustomer($from);
	}

    public function getCategoryTree(){

        $tree = DB::table('categories as t1')
        ->select('t1.name as lv1Name', 't1.id as lv1Id',
                't2.name as lv2Name', 't2.id as lv2Id',
                't3.name as lv3Name', 't3.id as lv3Id')
        ->leftJoin('categories as t2', 't1.id', '=', 't2.parent_id')
        ->leftJoin('categories as t3', 't2.id', '=', 't3.parent_id')
        ->whereNull('t1.parent_id')
        ->orderBy('t1.name', 'asc')
        ->orderBy('t2.name', 'asc')
        ->orderBy('t3.name', 'asc')
        ->get();

        $parsedTree = array();

        foreach ($tree as $key => $category) {

            //order by Name
            $parsedTree[$category->lv1Name]["Id"] = $category->lv1Id;
            $parsedTree[$category->lv1Name]
                                        [$category->lv2Name]
                                        ["Id"] = $category->lv2Id;
            $parsedTree[$category->lv1Name]
                                        [$category->lv2Name]
                                        [$category->lv3Name]
                                        ["Id"] = $category->lv3Id;

            //order by ID
            // $parsedTree[$category->lv1Id]["name"] = $category->lv1Name;
            // $parsedTree[$category->lv1Id]
            //                             [$category->lv2Id]
            //                             ["name"] = $category->lv2Name;
            // $parsedTree[$category->lv1Id]
            //                             [$category->lv2Id]
            //                             [$category->lv3Id]
            //                             ["name"] = $category->lv3Name;
        }

        return $parsedTree;
    }
}
