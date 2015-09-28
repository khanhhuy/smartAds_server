<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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


}
