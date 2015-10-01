<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ActiveCustomer;
use App\Facades\Mining;
use Carbon\Carbon;

use Illuminate\Http\Request;

class AccountController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	public function update(ActiveCustomer $customer) {

		$from = Carbon::now()->subMonths(config('mining.mining_range_months'))->toDateString();
		$lastMiningDate = Carbon::createFromFormat('Y-m-d H:i:s', $customer->last_mining);

		if (Carbon::now()->subDays(config('mining.mining_range_lastMiningDays'))
					->gt($lastMiningDate)) {
			Mining::miningCustomer($customer, false, $from);
		}
		//update after 18h
		else {
			//TODO:set up event update after 18h
			Mining::miningCustomer($customer, true, $lastMiningDate->toDateString());
		}

		$customer->last_mining = Carbon::now();

		return $customer->watchingList()->get();
	}

}
