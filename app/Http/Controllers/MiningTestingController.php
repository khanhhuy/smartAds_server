<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\ActiveCustomer;
use App\Facades\Connector;

use Carbon\Carbon;

class MiningTestingController extends Controller {

	public function index(ActiveCustomer $customer)
	{
		//$itemlist = Connector::getItemIDsFromCategoryID('1115193_1071967_1149379');

		return self::miningAllCustomer($customer);
	}

    public function miningAllCustomer(ActiveCustomer $customer){

        $allCustomer = ActiveCustomer::all();
        $allWatchingList = array();

        foreach ($allCustomer as $index => $customer) {
            $transactions = Connector::getShoppingHistoryFromCustomer($customer);
            $watchingList = array();
            foreach ($transactions as $key => $eachTrans) {
                if(!in_array($eachTrans['item_id'], $watchingList)) {
                    $watchingList[] = $eachTrans['item_id'];
                }
            }
            $allWatchingList[$customer->id] = $watchingList;
        }

        //Todo: remove items not in approriate categories

        return $allWatchingList;
    }

}
